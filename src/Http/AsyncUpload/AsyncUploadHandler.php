<?php

namespace TallStackUi\Http\AsyncUpload;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use TallStackUi\Components\Form\Upload\Async\Component;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadCompleted;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadFailed;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadStarted;

class AsyncUploadHandler
{
    private const OPTIONS = [
        'disk' => 'string',
        'directory' => 'string',
        'rules' => 'array',
        'store' => 'callable',
        'authorize' => 'callable',
        'max_size' => 'integer',
        'tmp_disk' => 'string',
    ];

    /**
     * @param  array<string, mixed>  $options
     *
     * @throws AsyncUploadException
     */
    public function __construct(private readonly array $options = [])
    {
        $this->validate();
    }

    /** @throws AsyncUploadException */
    public function handle(Request $request): JsonResponse|Response
    {
        $request = AsyncUploadRequest::createFrom($request);
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        if ($rejection = $this->guard($request)) {
            return $rejection;
        }

        $tmp = $this->tmp();
        $staging = $this->staging($request);

        // Atomic: only the request that actually creates the directory gets
        // true back, so the event fires once no matter which chunk index
        // happens to arrive first.
        if (@mkdir($tmp->path($staging), 0755, true)) {
            AsyncUploadStarted::dispatch(
                (string) $request->input('uuid'),
                (string) $request->input('real_name'),
                (string) $request->input('mime'),
                (int) $request->input('total_size'),
                (int) $request->input('total_chunks'),
            );
        }

        $tmp->putFileAs($staging, $request->file('chunk'), $request->input('chunk_index').'.part');

        if (count($tmp->files($staging)) < (int) $request->input('total_chunks')) {
            return response()->noContent();
        }

        // Directory rename is atomic on POSIX: concurrent requests that also
        // see a complete set lose the race and stop here.
        $sealed = $staging.'.sealed';

        if (! @rename($tmp->path($staging), $tmp->path($sealed))) {
            return response()->noContent();
        }

        return $this->finalize($request, $sealed);
    }

    protected function assemble(AsyncUploadRequest $request, string $sealed): ?string
    {
        $tmp = $this->tmp();
        $assembled = $tmp->path($sealed).'/assembled';
        $handle = fopen($assembled, 'wb');

        // By index, never by directory listing order.
        for ($index = 0; $index < (int) $request->input('total_chunks'); $index++) {
            $part = $tmp->path($sealed.'/'.$index.'.part');

            if (! is_file($part)) {
                fclose($handle);

                return null;
            }

            $stream = fopen($part, 'rb');
            stream_copy_to_stream($stream, $handle);
            fclose($stream);
        }

        fclose($handle);

        return filesize($assembled) === (int) $request->input('total_size') ? $assembled : null;
    }

    protected function config(?string $key = null): mixed
    {
        return __ts_get_component_configuration(Component::class, $key);
    }

    protected function disk(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($this->name());

        return $disk;
    }

    protected function extension(string $name): string
    {
        $extension = Str::of(pathinfo($name, PATHINFO_EXTENSION))
            ->lower()
            ->replaceMatches('/[^a-z0-9]/', '')
            ->limit(10, '');

        return $extension->isEmpty() ? '' : '.'.$extension;
    }

    /** @param array<string, array<int, string>> $errors */
    protected function fail(AsyncUploadRequest $request, string $reason, array $errors, int $status): JsonResponse
    {
        AsyncUploadFailed::dispatch(
            $reason,
            (string) $request->input('uuid'),
            (string) $request->input('real_name'),
            $errors,
        );

        return response()->json([
            'message' => array_values($errors)[0][0] ?? trans('ts-ui::messages.upload_async.errors.generic'),
            'errors' => $errors,
        ], $status);
    }

    /** @throws AsyncUploadException */
    protected function finalize(AsyncUploadRequest $request, string $sealed): JsonResponse
    {
        $tmp = $this->tmp();
        $assembled = $this->assemble($request, $sealed);

        if ($assembled === null) {
            $tmp->deleteDirectory($sealed);

            return $this->fail($request, 'integrity', [
                'chunk' => [trans('ts-ui::messages.upload_async.errors.integrity')],
            ], 422);
        }

        $file = new SymfonyFile($assembled);

        // Rules run against the assembled bytes, so mime and size are checked
        // on what actually landed rather than on what the client claimed.
        if ($rules = ($this->options['rules'] ?? null)) {
            $validator = Validator::make(['file' => $file], $rules);

            if ($validator->fails()) {
                $tmp->deleteDirectory($sealed);

                return $this->fail($request, 'rules', $validator->errors()->toArray(), 422);
            }
        }

        $disk = $this->disk();
        $path = $this->persist($request, $file);

        $tmp->deleteDirectory($sealed);

        $response = new AsyncUploadResponse(
            id: (string) $request->input('uuid'),
            path: $path,
            realName: (string) $request->input('real_name'),
            size: (int) $disk->size($path),
            mime: $disk->mimeType($path) ?: (string) $request->input('mime'),
            url: rescue(fn () => $disk->url($path), null, false),
        );

        AsyncUploadCompleted::dispatch($response, $this->name(), (string) $request->input('uuid'));

        return response()->json($response->toArray());
    }

    protected function guard(AsyncUploadRequest $request): ?JsonResponse
    {
        $authorize = $this->options['authorize'] ?? null;

        if ($authorize !== null && ! $authorize($request)) {
            return $this->fail($request, 'unauthorized', [
                'chunk' => [trans('ts-ui::messages.upload_async.errors.unauthorized')],
            ], 403);
        }

        // The client-side max_size is UX only; this is the check a raw
        // request cannot skip.
        $max = $this->options['max_size'] ?? $this->config('max_size');

        if ($max !== null && (int) $request->input('total_size') > $max * 1024 * 1024) {
            return $this->fail($request, 'size', [
                'chunk' => [trans('ts-ui::messages.upload_async.errors.size', ['max' => $max])],
            ], 422);
        }

        return null;
    }

    protected function name(): string
    {
        return $this->options['disk'] ?? $this->config('disk');
    }

    /** @throws AsyncUploadException */
    protected function persist(AsyncUploadRequest $request, SymfonyFile $file): string
    {
        if ($store = ($this->options['store'] ?? null)) {
            return (string) $store($file, $request);
        }

        // No global fallback on purpose: guessing a destination is worse
        // than refusing to pick one.
        if (! isset($this->options['directory'])) {
            throw new AsyncUploadException('Provide a [directory] option or a [store] callback to persist the uploaded file.');
        }

        $directory = trim((string) $this->options['directory'], '/');
        $name = Str::uuid()->toString().$this->extension((string) $request->input('real_name'));

        $this->disk()->putFileAs($directory, $file, $name);

        return $directory.'/'.$name;
    }

    protected function staging(AsyncUploadRequest $request): string
    {
        return trim((string) $this->config('tmp_directory'), '/').'/'.$request->input('uuid');
    }

    protected function tmp(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($this->options['tmp_disk'] ?? $this->config('tmp_disk'));

        return $disk;
    }

    /** @throws AsyncUploadException */
    private function validate(): void
    {
        if ($unknown = array_diff(array_keys($this->options), array_keys(self::OPTIONS))) {
            throw new AsyncUploadException(sprintf(
                'Unknown async upload option(s) [%s]. Available: [%s].',
                implode(', ', $unknown),
                implode(', ', array_keys(self::OPTIONS)),
            ));
        }

        foreach ($this->options as $key => $value) {
            if ($value === null) {
                continue;
            }

            $expected = self::OPTIONS[$key];

            $valid = match ($expected) {
                'callable' => is_callable($value),
                'array' => is_array($value),
                'integer' => is_int($value),
                default => is_string($value) && trim($value) !== '',
            };

            if (! $valid) {
                throw new AsyncUploadException("The async upload option [{$key}] must be of type [{$expected}].");
            }
        }
    }
}

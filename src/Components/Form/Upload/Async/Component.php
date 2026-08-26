<?php

namespace TallStackUi\Components\Form\Upload\Async;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\UploadAsyncRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.upload.async')]
#[PassThroughRuntime(UploadAsyncRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public string $route,
        public ?string $method = null,
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $title = null,
        public ?string $description = null,
        public ComponentSlot|string|null $tip = null,
        public ?bool $multiple = false,
        public ?bool $preview = true,
        public ?bool $manual = false,
        public ?bool $disabled = false,
        public ?bool $readonly = false,
        public ?int $limit = null,
        public ?int $maxSize = null,
        public ?string $accept = null,
        public array|Collection|null $files = null,
        public ?string $height = null,
        public ?int $columns = null,
        public ?int $chunkSize = null,
        public ?int $concurrency = null,
        public ?int $retries = null,
        public ?array $headers = null,
        public ComponentSlot|string|null $footer = null,
        public string|bool|null $error = null,
        public bool|string|null $editor = null,
        public ?string $aspect = null,
    ) {
        $this->method ??= 'POST';
        $this->title ??= trans('ts-ui::messages.upload_async.title');
        $this->description ??= trans('ts-ui::messages.upload_async.description');
        $this->height ??= 'min-h-48';
        $this->columns = max(1, min(6, $this->columns ?? 6));
        $this->files = $this->files instanceof Collection ? $this->files->all() : ($this->files ?? []);
        $this->error ??= trans('ts-ui::messages.upload_async.errors.generic');

        $this->route = rescue(fn () => route($this->route), $this->route, false);
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.upload.async');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex flex-col gap-2',
            'dropzone' => [
                'base' => 'group relative flex w-full cursor-pointer flex-col justify-center gap-3 overflow-hidden rounded-lg border-1 border-dashed border-gray-200 bg-gray-50 p-3 transition dark:border-dark-700 dark:bg-dark-800',
                'dragging' => 'border-primary-500 bg-primary-50 scale-[1.01] dark:bg-primary-900/20',
                'locked' => 'opacity-60 cursor-not-allowed',
                'input' => 'pointer-events-none absolute inset-0 h-full w-full opacity-0 outline-hidden',
                'placeholder' => 'pointer-events-none flex w-full items-center justify-center',
                'placeholder-full' => 'flex-col gap-1 px-6 text-center',
                'placeholder-compact' => 'gap-2',
                'icon' => 'text-primary-500 dark:text-primary-400',
                'icon-full' => 'h-10 w-10',
                'icon-compact' => 'h-5 w-5',
                'icon-bouncing' => 'animate-bounce',
                'title' => 'text-gray-700 dark:text-dark-200',
                'title-full' => 'text-md font-semibold',
                'title-compact' => 'text-sm font-medium',
                'description' => 'text-gray-500 dark:text-dark-400',
                'description-full' => 'text-sm',
                'description-compact' => 'text-xs',
                'tip' => 'mt-1 text-xs text-gray-400 dark:text-dark-500',
            ],
            'grid' => [
                'frame' => 'relative z-10',
                'wrapper' => 'custom-scrollbar grid max-h-96 gap-2 overflow-y-auto',
                'fade' => 'pointer-events-none absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-gray-50 to-transparent dark:from-dark-700',
                'multiple' => 'w-full',
                'single' => 'w-32',
                'single-cols' => 'grid-cols-1',
                'cols' => [
                    '1' => 'grid-cols-1',
                    '2' => 'grid-cols-1 sm:grid-cols-2',
                    '3' => 'grid-cols-2 sm:grid-cols-3',
                    '4' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4',
                    '5' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5',
                    '6' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6',
                ],
            ],
            'tile' => [
                'wrapper' => 'group/tile relative aspect-square overflow-hidden rounded-md bg-gray-100 dark:bg-dark-700',
                'image' => 'h-full w-full object-cover',
                'image-clickable' => 'cursor-pointer',
                'document' => 'flex h-full w-full flex-col items-center justify-center gap-1',
                'document-icon' => 'h-12 w-12 text-gray-400 dark:text-dark-300',
                'extension' => 'rounded bg-white/90 px-1.5 py-0.5 text-[10px] font-semibold uppercase text-gray-700 dark:bg-dark-800/90 dark:text-dark-200',
                'name-overlay' => 'absolute inset-x-0 top-0 block truncate bg-gradient-to-b from-black/60 to-transparent px-2 py-1 text-xs text-white',
                'size-overlay' => 'absolute inset-x-0 bottom-0 block truncate bg-gradient-to-t from-black/60 to-transparent px-2 py-1 text-xs text-white',
                'remove' => 'absolute bottom-1 right-1 z-10 cursor-pointer rounded-full bg-white/90 p-1 opacity-0 transition hover:bg-red-50 group-hover/tile:opacity-100 max-md:opacity-100 dark:bg-dark-800/90',
                'remove-icon' => 'h-3.5 w-3.5 text-red-500',
                'progress' => 'absolute bottom-0 left-0 z-10 h-1 bg-primary-500 transition-all',
                'success-mark' => 'absolute right-1 top-1 z-10 rounded-full bg-green-500 p-1 text-white',
                'error-ring' => 'pointer-events-none absolute inset-0 z-10 rounded-md ring-2 ring-inset ring-red-500',
                'error-badge' => 'absolute right-1 top-1 z-10 rounded-full bg-red-500 p-1 text-white',
                'error-msg' => 'absolute inset-x-0 bottom-0 z-10 truncate bg-red-500 px-2 py-1 text-center text-xs font-medium text-white',
            ],
            'lightbox' => [
                'backdrop' => 'fixed inset-0 z-50 flex items-center justify-center bg-gray-500/80 dark:bg-dark-600/75',
                'positioner' => 'relative m-5 max-h-[90vh] max-w-3xl',
                'wrapper' => 'overflow-hidden rounded shadow-lg',
                'image' => 'block h-auto max-h-[90vh] w-auto max-w-full object-contain',
                'caption' => 'absolute inset-x-0 bottom-0 truncate bg-gradient-to-t from-black/70 to-transparent px-4 pb-3 pt-20 text-center text-sm text-white',
                'close' => 'absolute -right-5 -top-10 h-10 w-10 cursor-pointer',
                'close-icon' => 'dark:text-dark-300 h-5 w-5 text-white',
            ],
            'footer' => [
                'wrapper' => 'mt-3 flex items-center justify-between border-t border-gray-200 pt-3 dark:border-dark-700',
                'summary' => 'text-xs text-gray-500 dark:text-dark-400',
                'actions' => 'flex items-center gap-2',
            ],
            'error' => [
                'wrapper' => 'mt-2 flex w-full items-center justify-center',
                'message' => 'text-sm font-semibold text-red-500',
            ],
        ]);
    }
}

<?php

namespace TallStackUi\Components\Form\Upload;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Miscellaneous\UploadComponentFileAdapter;
use TallStackUi\Support\Runtime\Components\UploadRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('form.upload')]
#[PassThroughRuntime(UploadRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ComponentSlot|string|null $tip = null,
        public ?bool $multiple = false,
        public ?bool $preview = true,
        public ?bool $delete = false,
        public ?bool $static = false,
        public ?string $placeholder = null,
        public string $deleteMethod = 'deleteUpload',
        public string|bool|null $error = null,
        public ?ComponentSlot $footer = null,
        public ?bool $overflow = null,
        public ?bool $closeAfterUpload = null,
    ) {
        $this->placeholder ??= trans('ts-ui::messages.upload.placeholder');
        $this->error ??= trans('ts-ui::messages.upload.error');
    }

    /** @throws Exception */
    final public function adapter(array|TemporaryUploadedFile $files): array
    {
        return app(UploadComponentFileAdapter::class, [
            'static' => $this->static,
            'files' => $files,
        ])();
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.upload');
    }

    public function customization(): array
    {
        return Arr::dot([
            'input' => [
                'caret' => 'caret-transparent',
            ],
            'slot' => [
                'spacing' => 'ml-1 mr-2',
            ],
            'icon' => 'dark:text-dark-400 h-5 w-5 text-gray-500',
            'placeholder' => [
                'input' => 'absolute inset-0 z-50 h-full w-full cursor-pointer opacity-0 outline-hidden',
                'wrapper' => 'dark:border-dark-500 dark:bg-dark-600 relative flex h-20 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition',
                'wrapper-dragging' => 'bg-primary-100',
                'title' => 'text-md dark:text-dark-300 font-bold text-gray-600',
                'tip' => 'mx-4 mt-2 text-center text-sm text-gray-500 dark:text-gray-400',
                'icon' => [
                    'class' => 'dark:text-dark-300 h-6 w-6 text-gray-600',
                    'wrapper' => 'inline-flex items-center justify-center space-x-2',
                ],
            ],
            'staging' => [
                'wrapper' => 'flex-col w-full items-center justify-center',
                'with-footer' => 'mb-2',
            ],
            'item' => [
                'wrapper' => 'soft-scrollbar my-4 max-h-64 w-full overflow-auto px-2',
                'ul' => 'dark:divide-dark-700 divide-y divide-gray-100',
                'li' => 'flex justify-between gap-x-6',
                'li-multiple' => 'py-2',
                'content' => 'min-w-0 gap-x-4',
                'actions' => 'flex-col items-end',
                'title' => 'dark:text-dark-300 truncate text-sm font-semibold leading-6 text-gray-900',
                'description' => 'min-w-0',
                'size' => 'dark:text-dark-300 mt-1 text-xs leading-5 text-gray-500',
                'image' => 'h-12 w-12 flex-none rounded-full bg-gray-50',
                'image-clickable' => 'cursor-pointer',
                'document' => 'text-primary-500 dark:text-dark-300 h-5 w-5 shrink-0',
                'delete' => 'h-4 w-4 shrink-0 text-red-500',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                'class' => 'w-full p-3',
            ],
            'upload' => [
                'wrapper' => 'mt-2 flex h-1 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'progress' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full bg-green-600 text-center text-xs text-white transition duration-500',
            ],
            'preview' => [
                'backdrop' => 'fixed left-0 top-0 z-50 flex h-full w-full items-center justify-center bg-gray-500/80',
                'wrapper' => 'relative m-5 max-w-3xl rounded shadow-lg',
                'image' => 'h-auto max-w-full',
                'button' => [
                    'base' => 'absolute -right-5 -top-10 h-10 w-10 cursor-pointer',
                    'icon' => 'dark:text-dark-300 h-5 w-5 text-white',
                ],
            ],
            'error' => [
                'wrapper' => 'mt-2 flex w-full items-center justify-center',
                'message' => 'font-semibold text-red-500',
            ],
            'static' => [
                'empty' => [
                    'wrapper' => 'text-center',
                    'icon' => 'mx-auto h-10 w-10 text-gray-400 dark:text-dark-200',
                    'title' => 'text-primary-500 dark:text-dark-300 text-lg font-semibold',
                    'description' => 'dark:text-dark-400 mt-1 text-sm text-gray-700',
                ],
            ],
            'invalid' => 'mt-1 block text-sm font-medium text-red-500',
        ]);
    }
}

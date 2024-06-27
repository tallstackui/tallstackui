<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Components\UploadComponentFileAdapter;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.upload')]
class Upload extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
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
    ) {
        $this->placeholder ??= __('tallstack-ui::messages.upload.placeholder');
        $this->error ??= __('tallstack-ui::messages.upload.error');
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
        return view('tallstack-ui::components.form.upload');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'icon' => 'dark:text-dark-400 h-5 w-5 text-gray-500',
            'placeholder' => [
                'input' => 'absolute inset-0 z-50 h-full w-full cursor-pointer opacity-0 outline-none',
                'wrapper' => 'dark:border-dark-500 dark:bg-dark-600 relative flex h-20 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition',
                'title' => 'text-md dark:text-dark-300 font-bold text-gray-600',
                'tip' => 'mx-4 mt-2 text-center text-sm text-gray-500 dark:text-gray-400',
                'icon.class' => 'dark:text-dark-300 h-6 w-6 text-gray-600',
            ],
            'upload' => [
                'wrapper' => 'mt-2 flex h-1 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'progress' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full bg-green-600 text-center text-xs text-white transition duration-500',
            ],
            'item' => [
                'wrapper' => 'soft-scrollbar my-4 max-h-64 w-full overflow-auto px-2',
                'ul' => 'dark:divide-dark-700 divide-y divide-gray-100',
                'li' => 'flex justify-between gap-x-6',
                'title' => 'dark:text-dark-300 truncate text-sm font-semibold leading-6 text-gray-900',
                'size' => 'dark:text-dark-300 mt-1 text-xs leading-5 text-gray-500',
                'image' => 'h-12 w-12 flex-none rounded-full bg-gray-50',
                'document' => 'text-primary-500 dark:text-dark-300 h-5 w-5 flex-shrink-0',
                'delete' => 'h-4 w-4 flex-shrink-0 text-red-500',
            ],
            'preview' => [
                'backdrop' => 'fixed left-0 top-0 z-50 flex h-full w-full items-center justify-center bg-gray-500 bg-opacity-75',
                'wrapper' => 'relative m-5 max-w-3xl rounded shadow-lg',
                'image' => 'h-auto max-w-full',
                'button' => [
                    'wrapper' => 'absolute -right-5 -top-10 h-10 w-10',
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
        ]);
    }
}

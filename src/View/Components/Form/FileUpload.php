<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.fileupload')]
class FileUpload extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $multiple = false,
        public ?bool $preview = true,
        public ?bool $delete = false,
        public string $deleteMethod = 'deleteUpload',
        public string|bool|null $error = null,
        public ComponentSlot|string|null $tip = null,
        public ?ComponentSlot $footer = null,
    ) {
        $this->error ??= __('tallstack-ui::messages.fileupload.error');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.fileupload');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'box' => [
                'wrapper' => [
                    'first' => 'dark:border-dark-600 absolute top-full z-50 mt-2 w-full overflow-hidden rounded-md border border-gray-200 shadow-lg',
                    'second' => 'shadow-xs dark:bg-dark-800 rounded-md bg-white p-3',
                ],
            ],
            'placeholder' => [
                'wrapper' => 'dark:border-dark-500 dark:bg-dark-700 dark:hover:bg-dark-600 flex h-32 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition hover:bg-gray-100',
                'title' => 'text-md font-bold text-gray-600 dark:text-dark-300',
                'tip' => 'mx-4 mt-2 text-center text-sm text-gray-500 dark:text-gray-400',
                'icon' => 'h-6 w-6 text-gray-600 dark:text-dark-300',
            ],
            'upload' => [
                'wrapper' => 'mt-2 flex h-1 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'progress' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full bg-green-600 text-center text-xs text-white transition duration-500',
            ],
            'item' => [
                'wrapper' => 'soft-scrollbar max-h-64 w-full overflow-auto px-2',
                'ul' => 'dark:divide-dark-700 divide-y divide-gray-100',
                'li' => 'flex justify-between gap-x-6 py-4',
                'title' => 'dark:text-dark-300 truncate text-sm font-semibold leading-6 text-gray-900',
                'size' => 'dark:text-dark-300 mt-1 text-xs leading-5 text-gray-500',
                'image' => 'h-12 w-12 flex-none rounded-full bg-gray-50',
                'document' => 'h-5 w-5 flex-shrink-0 text-primary-500 dark:text-dark-300',
                'delete' => 'h-4 w-4 flex-shrink-0 text-red-500',
            ],
            'preview' => [
                'backdrop' => 'absolute inset-0 z-40 bg-gray-500 opacity-75 dark:bg-gray-900 backdrop-blur-md',
                'wrapper' => [
                    'first' => 'fixed inset-0 transform transition-all',
                    'second' => 'fixed left-1/2 top-1/2 z-50 -translate-x-1/2 -translate-y-1/2 transform rounded-lg transition-all',
                ],
                'button' => [
                    'wrapper' => 'absolute -top-10 -right-5 h-10 w-10',
                    'icon' => 'h-5 w-5 text-white dark:text-dark-300',
                ],
            ],
            'error' => [
                'wrapper' => 'mt-2 flex w-full items-center justify-center',
                'file' => 'text-xs text-red-500',
                'message' => 'font-semibold text-red-500',
            ],
        ]);
    }
}

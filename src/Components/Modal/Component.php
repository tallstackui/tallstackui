<?php

namespace TallStackUi\Components\Modal;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\ModalRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('modal')]
#[PassThroughRuntime(ModalRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool|string|null $blur = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public ?string $entangle = 'modal',
        public ?bool $center = null,
        public ?bool $overflow = null,
        public ?bool $scrollable = null,
    ) {
        $this->entangle = is_string($this->wire) ? $this->wire : (is_bool($this->wire) ? 'modal' : $this->entangle);
    }

    public function blade(): View
    {
        return view('ts-ui::components.modal.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
                'second' => 'fixed inset-0 z-50 w-screen overflow-y-auto',
                'third' => 'mx-auto flex min-h-full w-full transform justify-center sm:p-4',
                'third-centered-padding' => 'p-4',
                'fourth' => 'dark:bg-dark-700 relative flex w-full transform flex-col rounded-t-xl sm:rounded-xl bg-white text-left shadow-xl transition-all',
                'fourth-centered-rounded' => 'rounded-xl',
                'scrollable' => 'max-h-[80vh] flex flex-col overflow-hidden',
            ],
            'scrollbar' => [
                'thin' => 'soft-scrollbar',
                'thick' => 'custom-scrollbar',
            ],
            'positions' => [
                'top' => 'items-end sm:items-start',
                'center' => 'items-center',
            ],
            'blur' => [
                'sm' => 'backdrop-blur-sm',
                'md' => 'backdrop-blur-md',
                'lg' => 'backdrop-blur-lg',
                'xl' => 'backdrop-blur-xl',
            ],
            'title' => [
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b border-b-gray-100 px-4 py-2.5',
                'text' => 'text-md text-secondary-600 dark:text-dark-300 whitespace-normal font-medium',
                'close' => 'text-secondary-300 h-5 w-5 cursor-pointer',
            ],
            'body' => 'dark:text-dark-300 grow rounded-b-xl py-5 text-gray-700 px-4',
            'body.scrollable' => 'overflow-y-auto',
            'footer' => 'dark:text-dark-300 dark:border-t-dark-600 flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 p-4 text-gray-700',
            'footer.scrollable' => 'sticky bottom-0 z-10 bg-white dark:bg-dark-700',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            __ts_validation_exception($this, 'The [wire] property cannot be an empty string');
        }

        $configuration = __ts_get_component_configuration(self::class);
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];

        if (! in_array($this->size ?? $configuration['size'] ?? '2xl', $sizes)) {
            __ts_validation_exception($this, 'The [size] must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str($this->zIndex ?? $configuration['z-index'] ?? 'z-50')->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }
    }
}

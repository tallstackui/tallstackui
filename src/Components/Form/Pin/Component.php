<?php

namespace TallStackUi\Components\Form\Pin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\PinRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.pin')]
#[PassThroughRuntime(PinRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?int $length = null,
        public ?string $prefix = null,
        public ?bool $clear = null,
        public ?bool $invalidate = null,
        public ?bool $numbers = null,
        public ?bool $letters = null,
        public ?bool $smart = null,
        #[SkipDebug]
        public ?string $mask = null,
    ) {
        // This pattern is part of the AlpineJS mask plugin
        $this->mask = $this->numbers ? '9' : ($this->letters ? 'a' : null);
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.pin');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'mt-1 flex items-center',
            'input' => [
                'size' => [
                    'prefix' => 'w-[60px]',
                    'base' => 'w-[38px]',
                ],
                'base' => 'mr-2 block rounded-md text-center text-lg font-medium ring-1 disabled:pointer-events-none disabled:opacity-50',
                'color' => [
                    'base' => 'dark:border-dark-600 dark:text-dark-300 focus:ring-primary-600 focus-within:focus:ring-primary-600 border-gray-300 text-gray-600 ring-transparent focus-within:ring-2 focus:border-0 focus:ring-2',
                    'background' => 'dark:bg-dark-800 bg-white',
                    'error' => 'border-0 border-red-500 text-red-600 ring-red-300 placeholder:text-red-600 focus-within:ring-red-500 focus-within:placeholder:text-red-600 focus:ring-2 focus:ring-red-500 focus-within:focus:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500',
                ],
            ],
            'button' => 'h-6 w-6 text-red-500',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! $this->length) {
            __ts_validation_exception($this, 'The [length] is mandatory and should be set.');
        }

        if ($this->prefix && strlen($this->prefix) > 3) {
            __ts_validation_exception($this, 'The [prefix] must be 3 characters or less.');
        }
    }
}

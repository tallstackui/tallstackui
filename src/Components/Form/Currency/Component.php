<?php

namespace TallStackUi\Components\Form\Currency;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\CurrencyRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.currency')]
#[PassThroughRuntime(CurrencyRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        public ?string $locale = 'en-US',
        public ?int $decimals = 2,
        public ?int $precision = 4,
        public bool|string|null $symbol = null,
        public bool|string|null $currency = null,
        public ?bool $mutate = null,
        #[SkipDebug]
        public ?array $symbols = [],
    ) {
        $this->symbols = trans('ts-ui::messages.currency');
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.currency');
    }

    public function customization(): array
    {
        return Arr::dot([
            'input' => [
                'appearance' => 'appearance-number-none',
            ],
            'symbol' => [
                'spacing' => 'ml-2',
            ],
            'currency' => [
                'spacing' => 'mr-2',
            ],
            'clearable' => [
                'wrapper' => 'cursor-pointer absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'padding' => [
                    'with-currency' => 'right-9',
                    'without-currency' => 'right-2',
                ],
                'size' => 'h-5 w-5',
                'color' => 'hover:text-red-500',
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->precision < $this->decimals) {
            __ts_validation_exception($this, 'The [precision] must be greater than or equal to [decimals].');
        }
    }
}

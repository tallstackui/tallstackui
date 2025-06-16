<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\CurrencyRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('form.currency')]
#[PassThroughRuntime(CurrencyRuntime::class)]
class Currency extends TallStackUiComponent implements Personalization
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
        $this->symbols = trans('tallstack-ui::messages.currency');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.currency');
    }

    public function personalization(): array
    {
        return Arr::dot([
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
}

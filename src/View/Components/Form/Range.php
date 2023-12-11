<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Traits\InteractWithProviders;

#[SoftPersonalization('form.range')]
class Range extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $size = null,
        public ?string $color = 'primary',
    ) {
        $this->id ??= uniqid();
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'relative rounded-md',
                'base' => 'dark:bg-dark-700 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 transition',
                'sizes' => [
                    'sm' => 'h-1 [&::-webkit-slider-thumb]:h-3 [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                    'md' => 'h-2 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                    'lg' => 'h-3 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'disabled' => 'disabled:opacity-50 disabled:cursor-not-allowed',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.range');
    }
}

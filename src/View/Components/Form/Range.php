<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

#[SoftPersonalization('form.range')]
class Range extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $id = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $size = null,
        public ?string $color = 'primary',
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $min = 0,
        public ?int $max = 10,
        public ?int $delay = 2,
        public array $customWrapper = [],
    ) {
        $this->id ??= uniqid();
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
        $this->customWrapper = ['wrapper' => 'relative mt-1 rounded-md'];

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'base' => 'w-full rounded-lg appearance-none bg-gray-200 dark:bg-dark-800 cursor-pointer',
                'sizes' => [
                    'sm' => 'h-1 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-3 [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:rounded-full',
                    'md' => 'h-2 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:rounded-full',
                    'lg' => 'h-3 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:rounded-full',
                ],
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.range');
    }
}

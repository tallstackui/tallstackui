<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;
use TallStackUi\Support\Personalizations\Traits\InteractWithProviders;
use TallStackUi\Support\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('tooltip')]
class Tooltip extends Component implements Personalization
{
    use InteractWithProviders;
    use InteractWithValidations;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = 'question-mark-circle',
        public string $color = 'primary',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $size = null,
        public ?string $position = 'top',
        public ?string $style = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->xs ? 'xs' : 'sm'));
        $this->style = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon'));

        $this->validate();
        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex',
            'sizes' => [
                'xs' => 'h-4 w-4',
                'sm' => 'h-5 w-5',
                'md' => 'h-6 w-6',
                'lg' => 'h-7 w-7',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tooltip');
    }
}

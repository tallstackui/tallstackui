<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Circle extends Component implements Personalize
{
    use InteractWithProviders;

    private array $buttonSizeClasses;

    private array $fontSizeClasses;

    private array $iconSizeClasses;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?bool $solid = null,
        public ?bool $outline = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?string $style = null,
        public ?string $size = 'lg',
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';

        $this->buttonSizeClasses = [
            'sm' => 'w-6 h-6',
            'md' => 'w-9 h-9',
            'lg' => 'w-12 h-12',
        ];

        $this->iconSizeClasses = [
            'sm' => 'w-3 h-3',
            'md' => 'w-4 h-4',
            'lg' => 'w-8 h-8',
        ];

        $this->fontSizeClasses = [
            'sm' => 'text-xs',
            'md' => 'text-base',
            'lg' => 'text-2xl',
        ];

        $this->colors();
    }

    public function personalization(): array
    {
        return [
            'wrapper' => $this->buttonSizeClasses[$this->size].' outline-none inline-flex justify-center items-center group transition ease-in duration-150 font-semibold focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed rounded-full',
            'icon.size' => $this->iconSizeClasses[$this->size],
            'span' => $this->fontSizeClasses[$this->size],
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.button.circle');
    }
}

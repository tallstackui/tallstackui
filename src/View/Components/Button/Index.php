<?php

namespace TasteUi\View\Components\Button;

use Closure;
use Illuminate\View\Component;

class Index extends Component
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?string $href = null,
        private ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }

    public function render(): Closure
    {
        return function (array $data) {
            return view('taste-ui::components.buttons.index', $this->merge($data))->render();
        };
    }

    protected function merge(array $data): array
    {
        $data['color'] = $this->colors();

        return $data;
    }

    private function colors(): string
    {
        return [
            'outline' => [
                'primary' => 'ring-primary-500 text-primary-500 border border-primary-500 hover:bg-primary-50 hover:ring-primary-50',
                'secondary' => 'ring-secondary-500 text-secondary-500 border border-secondary-500 hover:bg-secondary-50 hover:ring-secondary-50',
                'green' => 'ring-green-500 text-green-500 border border-green-500 hover:bg-green-50 hover:ring-green-50',
                'red' => 'ring-red-500 text-red-500 border border-red-500 hover:bg-red-50 hover:ring-red-50',
                'yellow' => 'ring-yellow-500 text-yellow-500 border border-yellow-500 hover:bg-yellow-50 hover:ring-yellow-50',
                'blue' => 'ring-blue-500 text-blue-500 border border-blue-500 hover:bg-blue-50 hover:ring-blue-50',
            ],
            'solid' => [
                'primary' => 'ring-primary-500 text-white bg-primary-500 hover:bg-primary-600 hover:ring-primary-600',
                'secondary' => 'ring-secondary-500 text-white bg-secondary-500 hover:bg-secondary-600 hover:ring-secondary-600',
                'green' => 'ring-green-500 text-white bg-green-500 hover:bg-green-600 hover:ring-green-600',
                'red' => 'ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600',
                'yellow' => 'ring-yellow-500 text-white bg-yellow-500 hover:bg-yellow-600 hover:ring-yellow-600',
                'blue' => 'ring-blue-500 text-white bg-blue-500 hover:bg-blue-600 hover:ring-blue-600',
            ],
        ][$this->style][$this->color];
    }
}

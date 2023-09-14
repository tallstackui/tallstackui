<?php

namespace TasteUi\View\Components\Avatar;

use Closure;
use Illuminate\View\Component;

class Index extends Component
{
    public function __construct(
        public ?string $label,
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $square = null,
    ) {
        //
    }

    public function render(): Closure
    {
        return function (array $data) {
            return view('taste-ui::components.avatar', $this->merge($data))->render();
        };
    }

    protected function merge(array $data): array
    {
        $data['color'] = $this->colors();

        return $data;
    }

    private function colors(): string
    {
        //TODO: it will continue here?
        if (str_contains($this->label, 'http')) {
            return '';
        }

        return [
            'primary' => 'border border-primary-500 bg-primary-500',
            'secondary' => 'border border-secondary-500 bg-secondary-500',
            'green' => 'border border-green-500 bg-green-500',
            'red' => 'border border-red-500 bg-red-500',
            'yellow' => 'border border-yellow-500 bg-yellow-500',
            'blue' => 'border border-blue-500 bg-blue-500',
        ][$this->color];
    }
}

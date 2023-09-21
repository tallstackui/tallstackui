<?php

namespace TasteUi\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Alert extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public string $color = 'primary',
        public ?string $class = null,
        public bool $closeable = false,
    ) {
        //
    }

    public function render(): Closure
    {
        return function (array $data) {
            return view('taste-ui::components.alert', $this->merge($data))->render();
        };
    }

    protected function merge(array $data): array
    {
        $data['colors'] = [
            'base' => $this->base(),
            'title' => $this->title(),
            'text' => $this->text(),
            'icon' => $this->icon(),
        ];

        $data['attributes'] = $this->class($data['attributes']);

        return $data;
    }

    private function class(ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        return $attributes->class($this->class);
    }

    private function base(): string
    {
        return [
            'primary' => 'rounded-md p-4 bg-primary-50',
            'secondary' => 'rounded-md p-4 bg-secondary-50',
            'green' => 'rounded-md p-4 bg-green-50',
            'red' => 'rounded-md p-4 bg-red-50',
            'yellow' => 'rounded-md p-4 bg-yellow-50',
            'blue' => 'rounded-md p-4 bg-blue-50',
        ][$this->color];
    }

    private function title(): string
    {
        return [
            'primary' => 'text-lg font-semibold text-primary-800',
            'secondary' => 'text-lg font-semibold text-secondary-800',
            'green' => 'text-lg font-semibold text-green-800',
            'red' => 'text-lg font-semibold text-red-800',
            'yellow' => 'text-lg font-semibold text-yellow-800',
            'blue' => 'text-lg font-semibold text-blue-800',
        ][$this->color];
    }

    private function text(): string
    {
        return [
            'primary' => 'mt-2 text-sm text-primary-800',
            'secondary' => 'mt-2 text-sm text-secondary-800',
            'green' => 'mt-2 text-sm text-green-800',
            'red' => 'mt-2 text-sm text-red-800',
            'yellow' => 'mt-2 text-sm text-yellow-800',
            'blue' => 'mt-2 text-sm text-blue-800',
        ][$this->color];
    }

    private function icon(): string
    {
        return [
            'primary' => 'w-5 h-5 text-primary-600',
            'secondary' => 'w-5 h-5 text-secondary-600',
            'green' => 'w-5 h-5 text-green-600',
            'red' => 'w-5 h-5 text-red-600',
            'yellow' => 'w-5 h-5 text-yellow-600',
            'blue' => 'w-5 h-5 text-blue-600',
        ][$this->color];
    }
}

<?php

namespace TasteUi\View\Components\Avatar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;

class Index extends Component
{
    public function __construct(
        public string $label,
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public bool $square = false,
        public bool $modelable = false,
    ) {
        $this->size ??= $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        // TODO: refactor this to concentrate in a single place
        if (! in_array($this->size, ['sm', 'md', 'lg'])) {
            throw new InvalidArgumentException('Invalid size. Allowed values are: sm, md, lg.');
        }
    }

    public function render(): View
    {
        return view('taste-ui::components.avatar');
    }

    public function getBaseClass(): string
    {
        $color = $this->colors();

        return Arr::toCssClasses([
            'inline-flex shrink-0 items-center justify-center overflow-hidden bg-gray-500 text-xl',
            'w-8 h-8' => $this->size === 'sm',
            'w-12 h-12' => $this->size === 'md',
            'w-14 h-14' => $this->size === 'lg',
            'rounded-full' => ! $this->square,
            $color,
        ]);
    }

    public function getBaseSpanClass(): string
    {
        return 'font-semibold text-white';
    }

    public function getBaseImageClass(): string
    {
        return Arr::toCssClasses([
            'shrink-0 object-cover object-center text-xl',
            'w-8 h-8' => $this->size === 'sm',
            'w-12 h-12' => $this->size === 'md',
            'w-14 h-14' => $this->size === 'lg',
            'rounded-full' => ! $this->square,
        ]);
    }

    private function colors(): ?string
    {
        if ($this->modelable) {
            return null;
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

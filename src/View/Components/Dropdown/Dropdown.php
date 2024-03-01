<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('dropdown')]
class Dropdown extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        #[SkipDebug]
        public ?string $header = null,
        #[SkipDebug]
        public ?string $action = null,
        public ?string $position = 'bottom-end',
        public ?bool $static = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.dropdown.dropdown');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-start',
                'second' => 'relative inline-block text-left',
                'third' => 'dark:bg-dark-700 absolute z-10 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'slot' => 'overflow-hidden rounded-md',
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }

    final public function transitions(): string
    {
        $side = str_contains((string) $this->position, 'right') || str_contains((string) $this->position, 'left');
        $orientation = str_contains((string) $this->position, 'bottom') || str_contains((string) $this->position, 'right');

        $content = <<<'HTML'
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 {%start%}"
             x-transition:enter-end="opacity-100 {%end%}"
             x-transition:leave="transition duration-100 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        HTML;

        $content = str_replace('{%start%}', $side ? ($orientation ? '-translate-x-2' : 'translate-x-2') : ($orientation ? '-translate-y-2' : 'translate-y-2'), $content);
        $content = str_replace('{%end%}', $side ? 'translate-x-0' : 'translate-y-0', $content);

        return trim((string) str($content)->squish());
    }

    /** @throws InvalidSelectedPositionException */
    protected function validate(): void
    {
        InvalidSelectedPositionException::validate(static::class, $this->position);
    }
}

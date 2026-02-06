<?php

namespace TallStackUi\Components\Dropdown\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Traits\DropdownSharedTransitions;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Exceptions\InvalidSelectedPositionException;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dropdown')]
class Component extends TallStackUiComponent implements Customization
{
    use DropdownSharedTransitions;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'bottom-end',
        public ?bool $static = false,
        #[SkipDebug]
        public ?string $header = null,
        #[SkipDebug]
        public ?string $action = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.dropdown.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-start',
                'second' => 'relative inline-block text-left',
            ],
            'header.wrapper' => 'm-2',
            'slot.wrapper' => 'overflow-hidden rounded-md',
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                'class' => 'w-56',
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }

    /** @throws InvalidSelectedPositionException */
    protected function validate(): void
    {
        InvalidSelectedPositionException::validate(static::class, $this->position);
    }
}

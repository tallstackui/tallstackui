<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Dropdown\Traits\SharedTransitions;
use TallStackUi\View\Components\Floating;

#[SoftCustomization('dropdown')]
class Dropdown extends TallStackUiComponent implements Customization
{
    use SharedTransitions;

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
        return view('tallstack-ui::components.dropdown.dropdown');
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

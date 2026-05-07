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
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $width = null,
        #[SkipDebug]
        public ?string $header = null,
        #[SkipDebug]
        public ?string $action = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
        $this->width ??= $this->size;
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
                'widths' => [
                    'xxs' => "data-[tsui-dropdown-width='xxs']:w-32",
                    'xs' => "data-[tsui-dropdown-width='xs']:w-40",
                    'sm' => "data-[tsui-dropdown-width='sm']:w-48",
                    'md' => "data-[tsui-dropdown-width='md']:w-56",
                    'lg' => "data-[tsui-dropdown-width='lg']:w-64",
                    'xl' => "data-[tsui-dropdown-width='xl']:w-72",
                    '2xl' => "data-[tsui-dropdown-width='2xl']:w-80",
                ],
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
                'icon-rotated' => 'rotate-180',
            ],
        ]);
    }

    /** @throws InvalidSelectedPositionException */
    protected function validate(): void
    {
        InvalidSelectedPositionException::validate(static::class, $this->position);

        $allowed = ['xxs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl'];

        if (! in_array($this->width, $allowed, true)) {
            __ts_validation_exception($this, 'The [width] must be one of: '.implode(', ', $allowed).'.');
        }
    }
}

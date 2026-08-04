<?php

namespace TallStackUi\Components\Wrapper\Radio;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('wrapper.radio')]
class Component extends TallStackUiComponent implements Customization
{
    /** Container key holding the properties whose error is already on the page. */
    private const CLAIMED = 'ts-ui::wrapper.radio.claimed';

    public function __construct(
        public ?string $property = null,
        public string|ComponentSlot|null $label = null,
        public ?string $id = null,
        public ?string $position = 'left',
        public ?string $alignment = 'middle',
        public ?bool $invalidate = null,
        public ?bool $error = false,
    ) {
        $this->invalidate ??= config('ts-ui.invalidate_global') ?? false;
    }

    public function blade(): View
    {
        return view('ts-ui::components.wrapper.radio');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-center',
                'second' => [
                    'start' => 'flex items-start',
                    'middle' => 'flex items-center',
                ],
            ],
            'label' => [
                'wrapper' => 'relative inline-flex cursor-pointer items-start',
                'text' => 'dark:text-dark-400 cursor-pointer items-center text-sm font-medium text-gray-700',
                'error' => 'text-red-600 dark:text-red-500',
                'spacing' => [
                    'left' => 'mr-2',
                    'right' => 'ml-2',
                ],
            ],
        ]);
    }

    /**
     * The label arrives here as a prop rather than a slot, so its attributes are
     * already readable — which is not true of the component that owns the slot,
     * where the body is only captured after the props are snapshotted.
     */
    protected function setup(): void
    {
        $this->claim();

        if (! $this->label instanceof ComponentSlot) {
            return;
        }

        if ($this->label->attributes->has('left')) {
            $this->position = 'left';
        }

        if ($this->label->attributes->has('start')) {
            $this->alignment = 'start';
        }
    }

    /**
     * Options bound to the same property are one wrapper each, and each would
     * print the same validation message under its own option. The first to
     * render claims it for the request; the rest stay quiet.
     */
    private function claim(): void
    {
        $property = $this->property;

        if (blank($property) || $this->invalidate === true) {
            return;
        }

        $resolved = app()->bound(self::CLAIMED) ? app(self::CLAIMED) : [];

        $claimed = array_filter(is_array($resolved) ? $resolved : [], 'is_string');

        if (in_array($property, $claimed, true)) {
            $this->invalidate = true;

            return;
        }

        app()->instance(self::CLAIMED, [...$claimed, $property]);
    }
}

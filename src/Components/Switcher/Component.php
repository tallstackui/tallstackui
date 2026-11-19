<?php

namespace TallStackUi\Components\Switcher;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\SwitcherColors;
use TallStackUi\Support\Runtime\Components\SwitcherRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('switcher')]
#[ColorsThroughOf(SwitcherColors::class)]
#[PassThroughRuntime(SwitcherRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $on = null,
        public ComponentSlot|string|null $off = null,
        public ?string $iconWhenOn = null,
        public ?string $iconWhenOff = null,
        public ?string $colorWhenOn = null,
        public ?string $colorWhenOff = null,
        public ?string $position = 'left',
        public ?bool $rotate = false,
        public ?bool $flip = false,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $effect = null,
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->position = $this->position === 'right' ? 'right' : 'left';

        $this->effect = $this->rotate ? 'rotate' : ($this->flip ? 'flip' : 'fade');
    }

    public function blade(): View
    {
        return view('ts-ui::components.switcher.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'relative inline-grid cursor-pointer items-center justify-items-start rounded-sm select-none has-focus-visible:outline-2 has-focus-visible:outline-offset-2 has-focus-visible:outline-primary-500',
                'flip' => 'perspective-near',
                'locked' => 'cursor-not-allowed! opacity-60',
            ],
            'input' => 'peer sr-only',
            'content' => [
                'base' => 'col-start-1 row-start-1 inline-flex items-center gap-x-1.5',
                'reverse' => 'flex-row-reverse',
                'transition' => 'transition-all duration-300 ease-out',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-base',
                    'lg' => 'text-lg',
                ],
            ],
            'on' => [
                'fade' => 'invisible opacity-0 peer-checked:visible peer-checked:opacity-100',
                'rotate' => 'invisible -rotate-45 opacity-0 peer-checked:visible peer-checked:rotate-0 peer-checked:opacity-100',
                'flip' => 'invisible rotate-y-180 backface-hidden peer-checked:visible peer-checked:rotate-y-0',
            ],
            'off' => [
                'fade' => 'peer-checked:invisible peer-checked:opacity-0',
                'rotate' => 'peer-checked:invisible peer-checked:rotate-45 peer-checked:opacity-0',
                'flip' => 'backface-hidden peer-checked:invisible peer-checked:-rotate-y-180',
            ],
            'icon' => [
                'sizes' => [
                    'xs' => 'h-4 w-4',
                    'sm' => 'h-5 w-5',
                    'md' => 'h-6 w-6',
                    'lg' => 'h-7 w-7',
                ],
            ],
        ]);
    }

    protected function validate(array $data): void
    {
        if ($this->rotate && $this->flip) {
            __ts_validation_exception($this, 'The [rotate] and [flip] cannot be used together.');
        }

        foreach (['on' => $this->iconWhenOn, 'off' => $this->iconWhenOff] as $state => $icon) {
            if (blank($data[$state] ?? null) && blank($icon)) {
                __ts_validation_exception($this, "The [$state] state requires a text, a slot or an icon.");
            }
        }
    }
}

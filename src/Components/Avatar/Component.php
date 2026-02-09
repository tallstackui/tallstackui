<?php

namespace TallStackUi\Components\Avatar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\AvatarColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('avatar')]
#[ColorsThroughOf(AvatarColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?Model $model = null,
        public ?string $text = null,
        public ?string $color = 'primary',
        public ?string $image = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public bool $square = false,
        public ?string $property = 'name',
        public ?string $background = '0D8ABC',
        public ?bool $borderless = false,
        public ?array $options = [],
        public bool|Closure $presence = false,
        public ?string $presenceColor = 'green',
        public ?string $presencePosition = 'right-top',
        public bool|Closure $pulse = false,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->presence = value($this->presence);
        $this->pulse = value($this->pulse);
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.avatar.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'inline-flex shrink-0 items-center justify-center overflow-hidden',
                'sizes' => [
                    'xs' => 'w-6 h-6 text-xs',
                    'sm' => 'w-8 h-8 text-sm',
                    'md' => 'w-12 h-12 text-md',
                    'lg' => 'w-14 h-14 text-lg',
                ],
            ],
            'content' => [
                'image' => [
                    'class' => 'shrink-0 object-cover object-center text-xl',
                    'sizes' => [
                        'xs' => 'w-6 h-6 text-xs',
                        'sm' => 'w-8 h-8 text-sm',
                        'md' => 'w-12 h-12 text-md',
                        'lg' => 'w-14 h-14 text-lg',
                    ],
                ],
                'text' => [
                    'class' => 'font-semibold',
                    'colors' => [
                        'colorful' => 'text-white',
                        'white' => 'text-black',
                    ],
                ],
            ],
            'border' => [
                'base' => 'border-2',
                'radius' => 'rounded-full',
            ],
            'presence' => [
                'base' => 'relative inline-flex',
                'wrapper' => 'absolute flex',
                'dot' => 'rounded-full ring-2 ring-white dark:ring-dark-700',
                'ping' => 'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                'sizes' => [
                    'xs' => 'h-1.5 w-1.5',
                    'sm' => 'h-2 w-2',
                    'md' => 'h-3 w-3',
                    'lg' => 'h-3.5 w-3.5',
                ],
                'positions' => [
                    'right-top' => 'top-0 right-0',
                    'right-bottom' => 'bottom-0 right-0',
                    'left-top' => 'top-0 left-0',
                    'left-bottom' => 'bottom-0 left-0',
                ],
            ],
        ]);
    }

    final public function modelable(): string
    {
        $params = Arr::query([
            'name' => $this->model->getAttribute($this->property),
            'background' => $this->background,
            'color' => $this->color,
            ...$this->options,
        ]);

        return "https://ui-avatars.com/api?{$params}";
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if ($this->presence && ! in_array($this->presencePosition, ['right-top', 'right-bottom', 'left-top', 'left-bottom'])) {
            __ts_validation_exception($this, 'The [presence-position] must be one of: right-top, right-bottom, left-top, left-bottom.');
        }

        if (! $this->model && ! $this->text) {
            return;
        }

        if ($this->text) {
            return;
        }

        $model = $this->model::class;
        $property = $this->model->getAttribute($this->property);

        if (blank($property)) {
            __ts_validation_exception($this, "The property [{$this->property}] does not exists or is blank at the model [$model]");
        }
    }
}

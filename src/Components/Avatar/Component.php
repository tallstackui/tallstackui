<?php

namespace TallStackUi\Components\Avatar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\AvatarColors;
use TallStackUi\Support\Runtime\Components\AvatarRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('avatar')]
#[ColorsThroughOf(AvatarColors::class)]
#[PassThroughRuntime(AvatarRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public const DIMENSIONS = [
        'xs' => 24,
        'sm' => 32,
        'md' => 48,
        'lg' => 56,
        'xl' => 64,
        '2xl' => 80,
        '3xl' => 96,
        '4xl' => 112,
        '5xl' => 128,
        '6xl' => 144,
        '7xl' => 160,
    ];

    public const GRAVATAR_DEFAULTS = ['404', 'mp', 'identicon', 'monsterid', 'wavatar', 'retro', 'robohash', 'blank'];

    public const GRAVATAR_RATINGS = ['g', 'pg', 'r', 'x'];

    public const SIZES = ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

    public function __construct(
        public ?Model $model = null,
        public ?string $text = null,
        public ?string $color = 'primary',
        public ?string $image = null,
        public bool|string|null $gravatar = null,
        public ?string $gravatarDefault = null,
        public ?string $gravatarRating = null,
        public bool $square = false,
        public ?string $property = 'name',
        public ?string $background = '0D8ABC',
        public ?bool $borderless = null,
        public ?array $options = [],
        public bool|Closure $presence = false,
        public ?string $presenceColor = 'green',
        public ?string $presencePosition = 'right-top',
        public bool|Closure $pulse = false,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->borderless ??= __ts_get_component_configuration(self::class, 'borderless') ?? false;

        $this->presence = value($this->presence);
        $this->pulse = value($this->pulse);
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
                    'md' => 'w-12 h-12 text-base',
                    'lg' => 'w-14 h-14 text-lg',
                    'xl' => 'w-16 h-16 text-xl',
                    '2xl' => 'w-20 h-20 text-2xl',
                    '3xl' => 'w-24 h-24 text-3xl',
                    '4xl' => 'w-28 h-28 text-4xl',
                    '5xl' => 'w-32 h-32 text-5xl',
                    '6xl' => 'w-36 h-36 text-6xl',
                    '7xl' => 'w-40 h-40 text-7xl',
                ],
            ],
            'content' => [
                'image' => [
                    'class' => 'shrink-0 object-cover object-center text-xl',
                    'sizes' => [
                        'xs' => 'w-6 h-6 text-xs',
                        'sm' => 'w-8 h-8 text-sm',
                        'md' => 'w-12 h-12 text-base',
                        'lg' => 'w-14 h-14 text-lg',
                        'xl' => 'w-16 h-16 text-xl',
                        '2xl' => 'w-20 h-20 text-2xl',
                        '3xl' => 'w-24 h-24 text-3xl',
                        '4xl' => 'w-28 h-28 text-4xl',
                        '5xl' => 'w-32 h-32 text-5xl',
                        '6xl' => 'w-36 h-36 text-6xl',
                        '7xl' => 'w-40 h-40 text-7xl',
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
                'base' => 'relative inline-flex w-fit',
                'wrapper' => 'absolute flex',
                'dot' => 'rounded-full ring-2 ring-white dark:ring-dark-700',
                'ping' => 'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                'sizes' => [
                    'xs' => 'h-1.5 w-1.5',
                    'sm' => 'h-2 w-2',
                    'md' => 'h-3 w-3',
                    'lg' => 'h-3.5 w-3.5',
                    'xl' => 'h-4 w-4',
                    '2xl' => 'h-5 w-5',
                    '3xl' => 'h-6 w-6',
                    '4xl' => 'h-7 w-7',
                    '5xl' => 'h-8 w-8',
                    '6xl' => 'h-9 w-9',
                    '7xl' => 'h-10 w-10',
                ],
                'positions' => [
                    'right-top' => 'top-0 right-0',
                    'right-bottom' => 'bottom-0 right-0',
                    'left-top' => 'top-0 left-0',
                    'left-bottom' => 'bottom-0 left-0',
                ],
                'offsets' => [
                    'right-top' => 'translate-x-[14.6%] -translate-y-[14.6%]',
                    'right-bottom' => 'translate-x-[14.6%] translate-y-[14.6%]',
                    'left-top' => '-translate-x-[14.6%] -translate-y-[14.6%]',
                    'left-bottom' => '-translate-x-[14.6%] translate-y-[14.6%]',
                ],
            ],
        ]);
    }

    final public function gravatarable(): ?string
    {
        $email = $this->email();

        if (blank($email)) {
            return null;
        }

        $params = Arr::query([
            's' => $this->dimension(),
            'd' => $this->fallback(),
            'r' => $this->gravatarRating,
        ]);

        return 'https://gravatar.com/avatar/'.hash('sha256', mb_strtolower(trim($email)))."?{$params}";
    }

    final public function modelable(?string $name = null): string
    {
        $params = Arr::query([
            'name' => $name ?? $this->model->getAttribute($this->property),
            'background' => $this->background,
            'color' => $this->color,
            'size' => $this->dimension(),
            ...$this->options,
        ]);

        return "https://ui-avatars.com/api?{$params}";
    }

    final public function source(): ?string
    {
        return $this->image ?? $this->gravatarable() ?? ($this->model ? $this->modelable() : null);
    }

    /** @throws InvalidArgumentException */
    protected function validate(array $data): void
    {
        /** @var ComponentAttributeBag $attributes */
        $attributes = $data['attributes'];

        $sizes = array_values(array_filter(self::SIZES, fn (string $size): bool => (bool) $attributes->get($size, false)));

        if (count($sizes) > 1) {
            __ts_validation_exception($this, 'Only one size can be used at a time, but ['.implode(', ', $sizes).'] were given');
        }

        $this->size = $sizes[0] ?? $this->size ?? __ts_get_component_configuration(self::class, 'size') ?? 'md';

        if (! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of: '.implode(', ', self::SIZES));
        }

        // Dropped by hand: [2xl] cannot be a prop because $2xl is invalid PHP.
        $attributes->setAttributes(Arr::except($attributes->getAttributes(), self::SIZES));

        if ($this->presence && ! in_array($this->presencePosition, ['right-top', 'right-bottom', 'left-top', 'left-bottom'])) {
            __ts_validation_exception($this, 'The [presence-position] must be one of: right-top, right-bottom, left-top, left-bottom.');
        }

        $gravatar = __ts_get_component_configuration(self::class, 'gravatar');

        $this->gravatarDefault ??= data_get($gravatar, 'default') ?? 'mp';
        $this->gravatarRating ??= data_get($gravatar, 'rating') ?? 'g';

        if (! in_array($this->gravatarDefault, self::GRAVATAR_DEFAULTS, true)) {
            __ts_validation_exception($this, 'The [gravatar-default] must be one of: '.implode(', ', self::GRAVATAR_DEFAULTS));
        }

        if (! in_array($this->gravatarRating, self::GRAVATAR_RATINGS, true)) {
            __ts_validation_exception($this, 'The [gravatar-rating] must be one of: '.implode(', ', self::GRAVATAR_RATINGS));
        }

        if ($this->gravatar) {
            if (blank($this->email())) {
                __ts_validation_exception($this, 'The [gravatar] requires an email, either inline or through a model carrying it.');
            }

            return;
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

    private function dimension(): int
    {
        return self::DIMENSIONS[$this->size ?? 'md'] * 2;
    }

    private function email(): ?string
    {
        if (is_string($this->gravatar)) {
            return str_contains($this->gravatar, '@')
                ? $this->gravatar
                : $this->model?->getAttribute($this->gravatar);
        }

        return $this->gravatar === true ? $this->model?->getAttribute('email') : null;
    }

    private function fallback(): string
    {
        $name = $this->text ?? $this->model?->getAttribute($this->property);

        return filled($name) ? $this->modelable($name) : $this->gravatarDefault;
    }
}

<?php

namespace TallStackUi\Components\Icon;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\IconColors;
use TallStackUi\Support\Concerns\BuildRawIcon;
use TallStackUi\Support\Runtime\Components\IconRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('icon')]
#[ColorsThroughOf(IconColors::class)]
#[PassThroughRuntime(IconRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use BuildRawIcon;

    public const COLORS = [
        'black', 'primary', 'secondary', 'slate', 'gray', 'zinc', 'neutral', 'stone',
        'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan',
        'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose', 'mauve',
        'olive', 'mist', 'taupe',
    ];

    public const SIZES = ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        #[SkipDebug]
        public ?string $type = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
        #[SkipDebug]
        public bool $internal = true,
        #[SkipDebug]
        public ?string $color = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.icon.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'sizes' => [
                'xs' => 'h-3 w-3',
                'sm' => 'h-4 w-4',
                'md' => 'h-5 w-5',
                'lg' => 'h-6 w-6',
                'xl' => 'h-7 w-7',
                '2xl' => 'h-8 w-8',
                '3xl' => 'h-10 w-10',
                '4xl' => 'h-12 w-12',
                '5xl' => 'h-14 w-14',
                '6xl' => 'h-16 w-16',
                '7xl' => 'h-20 w-20',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(array $data): void
    {
        /** @var ComponentAttributeBag $attributes */
        $attributes = $data['attributes'];

        $sizes = $this->shorthands($attributes, self::SIZES);
        $colors = $this->shorthands($attributes, self::COLORS);

        if (count($sizes) > 1) {
            __ts_validation_exception($this, 'Only one size can be used at a time, but ['.implode(', ', $sizes).'] were given');
        }

        if (count($colors) > 1) {
            __ts_validation_exception($this, 'Only one color can be used at a time, but ['.implode(', ', $colors).'] were given');
        }

        $handmade = $attributes->has('class');

        $this->size = $handmade ? null : ($sizes[0] ?? __ts_get_component_configuration(self::class, 'size') ?? 'md');
        $this->color = $handmade || $this->error ? null : ($colors[0] ?? null);

        if ($this->size !== null && ! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of ['.implode(', ', self::SIZES).']');
        }

        // x-dynamic-component builds a template out of the attribute names,
        // so keeping [2xl] would compile to the invalid variable $2xl.
        $attributes->setAttributes(Arr::except($attributes->getAttributes(), [...self::SIZES, ...self::COLORS]));
    }

    private function shorthands(ComponentAttributeBag $attributes, array $allowed): array
    {
        return array_values(array_filter($allowed, fn (string $key): bool => (bool) $attributes->get($key, false)));
    }
}

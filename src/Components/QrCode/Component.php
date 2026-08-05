<?php

namespace TallStackUi\Components\QrCode;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View as Views;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Icon\Component as Icon;
use TallStackUi\Components\Traits\SkeletonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\QrCodeColors;
use TallStackUi\Support\QrCode\Encoder;
use TallStackUi\Support\Runtime\Components\QrCodeRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('qrCode')]
#[ColorsThroughOf(QrCodeColors::class)]
#[PassThroughRuntime(QrCodeRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SkeletonSetup;

    public const FORMATS = ['png', 'svg'];

    /** An icon name is lower case and hyphenated; anything else reads as a caption. */
    public const ICON = '/^[a-z0-9]+(-[a-z0-9]+)*$/';

    public const SIZES = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    public const WATERMARK = 8;

    public function __construct(
        public ?string $link = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?string $watermark = null,
        public bool|int|null $skeleton = null,
        public ?bool $copy = null,
        public bool|string|null $download = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view($this->skeletonized() ? 'ts-ui::components.qr-code.skeleton' : 'ts-ui::components.qr-code.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex flex-col items-center gap-2',
            'code' => 'shrink-0',
            'sizes' => [
                'xs' => 'size-24',
                'sm' => 'size-32',
                'md' => 'size-40',
                'lg' => 'size-48',
                'xl' => 'size-56',
                '2xl' => 'size-64',
            ],
            'watermark' => [
                'icon' => 'size-auto',
                'family' => 'ui-sans-serif, system-ui, sans-serif',
                'weight' => '700',
            ],
            'actions' => [
                'wrapper' => 'flex items-center gap-1',
                'button' => 'dark:text-dark-400 dark:hover:text-dark-200 cursor-pointer rounded-md p-1 text-gray-500 transition hover:text-gray-700',
                'icon' => 'size-5',
            ],
            'skeleton' => [
                ...$this->blocks(),
                'block' => 'dark:fill-dark-600 fill-gray-200',
            ],
        ]);
    }

    /** Only an icon whose view exists counts as one; the rest is drawn as text. */
    public function iconic(): bool
    {
        if ($this->watermark === null || preg_match(self::ICON, $this->watermark) !== 1) {
            return false;
        }

        $configuration = __ts_get_component_configuration(Icon::class);
        $type = $configuration['type'] ?? 'heroicons';

        if (in_array($type, ['hero', 'heroicons'], true)) {
            return Views::exists('ts-ui::components.icon.heroicons.'.($configuration['style'] ?? 'solid').'.'.$this->watermark);
        }

        // A local set is plain Blade components, so the same check applies once
        // the configured path is turned back into a view name. A package set is
        // resolved by its own factory, which this cannot see into.
        if (str_contains($type, '/blade-')) {
            return true;
        }

        return Views::exists('components.'.str_replace(['views/components/', '/'], ['', '.'], $type).'.'.$this->watermark);
    }

    /**
     * The highest level is what pays for the modules a watermark removes, so
     * it is not offered as a choice: asking for a watermark asks for it.
     */
    public function level(): string
    {
        return $this->watermark !== null ? 'H' : 'M';
    }

    protected function validate(): void
    {
        $this->guard();

        if ($this->size !== null && ! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of: '.implode(', ', self::SIZES).'.');
        }

        if (is_string($this->download) && ! in_array($this->download, self::FORMATS, true)) {
            __ts_validation_exception($this, 'The [download] must be one of: '.implode(', ', self::FORMATS).'.');
        }

        // Only the caption has to fit the strip. An icon is drawn from its
        // view, so its name is as long as the set made it.
        if ($this->watermark !== null && ! $this->iconic() && mb_strlen($this->watermark) > self::WATERMARK) {
            __ts_validation_exception($this, 'The [watermark] must not be longer than '.self::WATERMARK.' characters as text.');
        }

        // The link is the content, which is what a placeholder stands in for.
        if ($this->skeletonized()) {
            return;
        }

        if (blank($this->link)) {
            __ts_validation_exception($this, 'The [link] is required.');
        }

        if (filter_var($this->link, FILTER_VALIDATE_URL) === false) {
            __ts_validation_exception($this, 'The [link] must be a valid URL.');
        }

        // Checked here so the failure names the property, instead of surfacing
        // as the encoder's own exception from inside the runtime.
        if (strlen($this->link) > ($limit = Encoder::limit($this->level()))) {
            __ts_validation_exception($this, 'The [link] must not be longer than '.$limit.' characters.');
        }
    }
}

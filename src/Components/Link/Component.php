<?php

namespace TallStackUi\Components\Link;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\LinkColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('link')]
#[ColorsThroughOf(LinkColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?string $href = null,
        public ?string $color = 'primary',
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public array|Collection|null $query = null,
        public ?string $fragment = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $blank = null,
        public ?bool $bold = null,
        public ?bool $underline = null,
        public ?bool $italic = null,
        public ?bool $colorless = null,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $formatted = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : ($this->xs ? 'xs' : 'md'));

        $this->formatted = $this->href;

        if ($this->query) {
            // We just transform to collect to avoid the need
            // to check if $this->query is an instance of Collection
            $this->formatted .= '?';
            $this->formatted .= Arr::query(collect($this->query)->toArray());
        }

        if ($this->fragment) {
            $this->fragment = str_replace('#', '', $this->fragment);

            $this->formatted .= "#{$this->fragment}";
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.link.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'bold' => 'font-bold',
            'underline' => 'underline',
            'italic' => 'italic',
            'icon' => [
                'base' => 'flex items-center gap-x-1',
                'size' => 'h-4 w-4',
            ],
            'sizes' => [
                'xs' => 'text-xs',
                'sm' => 'text-sm',
                'md' => 'text-md',
                'lg' => 'text-lg',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        // The href is only mandatory when fragment
        // is not set. With that, we allow the usage
        // of link component for anchor links.
        if (! $this->fragment && ! $this->href) {
            __ts_validation_exception($this, 'The [href] attribute is required when no [fragment] is provided.');
        }
    }
}

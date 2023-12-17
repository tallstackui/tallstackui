<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('link')]
class Link extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $href = null,
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public array|Collection|null $query = null,
        public ?string $fragment = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $blank = null,
        public ?bool $bold = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $formatted = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : 'md');

        $this->formatted = $this->href;

        if ($this->query) {
            // We just transform to collect to avoid the need
            // to check if $this->query is instance of Collection
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
        return view('tallstack-ui::components.link');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'sizes' => [
                'sm' => 'text-sm',
                'md' => 'text-md',
                'lg' => 'text-lg',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (! $this->href) {
            throw new InvalidArgumentException('The link [href] attribute is required.');
        }
    }
}

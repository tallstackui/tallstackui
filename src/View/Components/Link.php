<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;

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
        public ?string $size = null,
    ) {
        //
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

    protected function prepare(): void
    {
        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : 'md');

        if ($this->query) {
            // We just transform to collect to avoid the need
            // to check if $this->query is instance of Collection
            $query = collect($this->query)->toArray();

            $prepare = Arr::query($query);

            $this->href .= "?{$prepare}";
        }

        if ($this->fragment) {
            $this->fragment = str_replace('#', '', $this->fragment);

            $this->href .= "#{$this->fragment}";
        }
    }

    protected function validate(): void
    {
        if (! $this->href) {
            throw new InvalidArgumentException('The link [href] attribute is required.');
        }
    }
}

<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('navbar')]
class NavBar extends Component implements Personalization
{
    public function __construct(
        public string|array|Collection|null $text = null,
        public ?bool $close = false,
        public ?bool $animated = false,
        public ?int $seconds = 1,
        public ?string $left = null,
        public ?string $color = 'primary',
        public string|null|Carbon $until = null,
        public bool $show = true,
        public bool $wire = false,
        public ?bool $sm = false,
        public ?bool $md = true,
        public ?bool $lg = false,
        public ?string $size = null,
    ) {
        if ($this->text instanceof Collection) {
            $this->text = $this->text->values()
                ->toArray();
        }

        if (is_array($this->text)) {
            $this->text = $this->text[array_rand($this->text)];
        }

        if (!is_null($this->until)) {
            $until = null;

            try {
                $until = Carbon::parse($this->until);
            } catch (InvalidArgumentException) {
                //
            }

            throw_if(!$until, new InvalidArgumentException('The [until] attribute must be a Carbon instance or a date string.'));

            if (today()->greaterThan($until)) {
                $this->show = false;
            }
        }

        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : 'md');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.navbar');
    }
}

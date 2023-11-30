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
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('banner')]
class Banner extends Component implements Personalization
{
    use InteractWithProviders;
    use InteractWithValidations;

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
        public ?string $size = 'sm',
    ) {
        $this->colors();
        $this->validate();

        $this->setup();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative flex flex-row items-center justify-between px-6 py-2',
            'sizes' => [
                'sm' => 'py-2',
                'md' => 'py-3',
                'lg' => 'py-4',
            ],
            'slot.left' => 'absolute left-0 ml-4 text-sm font-medium',
            'text' => 'flex-grow text-center text-sm font-medium',
            'close' => 'h-4 w-4 cursor-pointer',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.banner');
    }

    private function setup(): void
    {
        if ($this->wire) {
            return;
        }

        if ($this->text instanceof Collection) {
            $this->text = $this->text->values()
                ->toArray();
        }

        if (is_array($this->text)) {
            $this->text = $this->text[array_rand($this->text)];
        }

        if (! is_null($this->until)) {
            $until = null;

            try {
                $until = Carbon::parse($this->until);
            } catch (InvalidArgumentException) {
                //
            }

            throw_if(! $until, new InvalidArgumentException('The [until] attribute must be a Carbon instance or a date string.'));

            if (today()->greaterThan($until)) {
                $this->show = false;
            }
        }
    }
}

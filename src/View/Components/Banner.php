<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
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
        public string|null|array $color = 'primary',
        public ?bool $close = false,
        public ?bool $animated = false,
        public ?int $enter = 3,
        public ?int $leave = null,
        public ?string $left = null,
        public string|null|Carbon $until = null,
        public ?bool $wire = false,
        public ?bool $light = false,
        public ?bool $show = true,
        public ?string $size = 'sm',
        public ?string $style = 'solid',
        public ?string $effect = null,
    ) {
        $this->style = $this->light ? 'light' : $this->style;

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
            'icon' => 'w-5 h-5 text-white',
            'close' => 'h-4 w-4 cursor-pointer ml-4',
            'progress' => [
                'wrapper' => 'dark:bg-dark-600 relative h-1 w-full rounded-full bg-neutral-100',
                'bar' => 'bg-primary-500 dark:bg-dark-400 absolute h-full w-24 duration-300 ease-linear',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.banner');
    }

    private function setup(): void
    {
        // If the banner is wire, we don't need to set up anything else
        // Because the banner will be displayed through the Livewire events
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

        if (is_null($this->until)) {
            return;
        }

        if (today()->lessThanOrEqualTo(Carbon::parse($this->until))) {
            return;
        }

        $this->show = false;
    }
}

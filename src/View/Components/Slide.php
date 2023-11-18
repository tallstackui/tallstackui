<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('slide')]
class Slide extends Component implements Personalization
{
    use InteractWithProviders;
    use InteractWithValidations;

    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public ?bool $blur = null,
        public ?bool $left = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public string $entangle = 'slide',
    ) {
        $this->validate();
        $this->configurations();

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'slide');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.slide');
    }
}

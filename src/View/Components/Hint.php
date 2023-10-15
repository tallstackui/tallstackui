<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Hint extends Component implements Personalize
{
    public function __construct(
        public ?string $computed = null,
        public bool $error = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return ['text' => 'mt-2 text-sm text-secondary-500'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.hint');
    }
}

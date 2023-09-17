<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Errors extends Component
{
    public function __construct(
        public ?string $title = null,
        public string|array|null $only = null,
        public ?string $color = 'red',
    ) {
        $this->title ??= __('taste-ui::messages.errors.title');
    }

    public function render(): View
    {
        return view('taste-ui::components.errors');
    }

    public function messages(ViewErrorBag $errors): array
    {
        $messages = $errors->getMessages();

        if (blank($this->only)) {
            return $messages;
        }

        $this->only = is_array($this->only) ? $this->only : [$this->only];

        return array_filter($messages, fn ($name) => in_array($name, $this->only), ARRAY_FILTER_USE_KEY);
    }
}

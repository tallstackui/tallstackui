<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Errors extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?string $title = null,
        public string|array|null $only = null,
        public ?string $color = 'red',
        public bool $pulse = false,
    ) {
        $this->title ??= __('tallstack-ui::messages.errors.title');

        $this->colors();
    }

    public function count(ViewErrorBag $errors): int
    {
        return count($this->messages($errors));
    }

    public function messages(ViewErrorBag $errors): array
    {
        $messages = $errors->getMessages();

        if (blank($this->only)) {
            return $messages;
        }

        $this->only = is_array($this->only) ? $this->only : [$this->only];

        return array_filter($messages, fn (string $name) => in_array($name, $this->only), ARRAY_FILTER_USE_KEY);
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'p-4 w-full',
                'second' => 'rounded-lg p-4',
            ],
            'title' => [
                'wrapper' => 'flex items-center border-b pb-3',
                'text' => 'text-sm font-semibold',
            ],
            'body' => [
                'wrapper' => 'mt-2 ml-5 pl-1',
                'list' => 'list-disc text-sm space-y-1',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.errors');
    }
}

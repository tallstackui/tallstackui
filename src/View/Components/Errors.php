<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use InvalidArgumentException;
use TallStackUi\Foundation\Colors\ColorSource;
use TallStackUi\Foundation\Colors\ErrorsColors;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;

#[SoftPersonalization('errors')]
#[ColorSource(ErrorsColors::class)]
class Errors extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $title = null,
        public string|array|null $only = null,
        public ?string $icon = 'x-circle',
        public ?string $color = 'red',
        public bool $close = false,
    ) {
        $this->title ??= __('tallstack-ui::messages.errors.title');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.errors');
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
            'wrapper' => 'rounded-lg p-4 shadow',
            'title' => [
                'wrapper' => 'flex items-center justify-between border-b pb-3',
                'text' => 'text-md inline-flex items-center gap-1 font-bold',
            ],
            'body' => [
                'wrapper' => 'ml-5 mt-2 pl-1',
                'list' => 'text-md list-disc space-y-1',
            ],
            'close' => 'w-5 h-5',
        ]);
    }

    public function validate(): void
    {
        if (blank($this->title)) {
            throw new InvalidArgumentException('The errors [title] cannot be empty');
        }

    }
}

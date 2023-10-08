<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;

class Errors extends Component implements Customizable
{
    public function __construct(
        public ?string $title = null,
        public string|array|null $only = null,
        public ?string $color = 'red',
        public bool $pulse = false,
    ) {
        $this->title ??= __('tallstack-ui::messages.errors.title');
    }

    public function render(): View
    {
        return view('tallstack-ui::components.errors');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        $text = TallStackUi::colors()
            ->when($this->color === 'black', fn (Color $color) => $color->set('text', 'white'))
            ->unless($this->color === 'black', fn (Color $color) => $color->set('text', $this->color, 800))
            ->get();

        return Arr::dot([
            'base' => [
                'wrapper' => [
                    'first' => Arr::toCssClasses([
                        'p-4 w-full',
                        'animate-pulse' => $this->pulse,
                    ]),
                    'second' => Arr::toCssClasses([
                        'rounded-lg p-4',
                        TallStackUi::colors()
                            ->when($this->color === 'white', fn (Color $color) => $color->set('bg', 'gray', 50))
                            ->unless($this->color === 'white', fn (Color $color) => $color->set('bg', $this->color, $this->color === 'black' ? null : 50))
                            ->get(),
                    ]),
                ],
            ],
            'title' => [
                'base' => Arr::toCssClasses([
                    'text-sm font-semibold',
                    $text,
                ]),
                'wrapper' => Arr::toCssClasses([
                    'flex items-center border-b pb-3',
                    $text,
                    TallStackUi::colors()
                        ->set('border', $this->color, 200)
                        ->get(),
                ]),
            ],
            'body' => [
                'list' => Arr::toCssClasses([
                    'list-disc text-sm space-y-1',
                    $text,
                ]),
                'wrapper' => 'mt-2 ml-5 pl-1',
            ],
        ]);
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
}

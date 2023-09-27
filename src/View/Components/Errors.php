<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;

class Errors extends Component implements Customizable
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

    public function customization(bool $error = false): array
    {
        return [
            'body' => $this->tasteUiBodyClasses(),
            ...$this->tasteUiMainClasses(),
            ...$this->tasteUiTitleClasses(),
        ];
    }

    public function tasteUiMainClasses(): array
    {
        return Arr::dot([
            'base' => 'p-4 w-full',
            'wrapper' => Arr::toCssClasses([
                'rounded-lg p-4',
                TasteUi::colors()
                    ->set('bg', $this->color, 50)
                    ->get(),
            ]),
        ], 'main.');
    }

    public function tasteUiTitleClasses(): array
    {
        return Arr::dot([
            'base' => Arr::toCssClasses([
                'text-sm font-semibold',
                TasteUi::colors()
                    ->set('text', $this->color, 800)
                    ->get(),
            ]),
            'wrapper' => Arr::toCssClasses([
                'flex items-center border-b-2 pb-3',
                TasteUi::colors()
                    ->set('text', $this->color, 800)
                    ->get(),
                TasteUi::colors()
                    ->set('border', $this->color, 200)
                    ->get(),
            ]),
        ], 'title.');
    }

    public function customTitleWrapperClasses(): string
    {
        return Arr::toCssClasses([
            'flex items-center border-b-2 pb-3',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
            TasteUi::colors()
                ->set('border', $this->color, 200)
                ->get(),
        ]);
    }

    public function tasteUiBodyClasses(): string
    {
        return Arr::toCssClasses([
            'list-disc text-sm space-y-1',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
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

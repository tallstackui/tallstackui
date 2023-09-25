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

    public function customize(bool $error = false): array
    {
        return [
            'main' => [
                'base' => $this->customMainBaseClasses(),
                'wrapper' => $this->customMainWrapperClasses(),
            ],
            'title' => [
                'base' => $this->customTitleBaseClasses(),
                'wrapper' => $this->customTitleWrapperClasses(),
            ],
            'body' => $this->customBodyClasses(),
        ];
    }

    public function customMainBaseClasses(): string
    {
        return 'p-4 w-full';
    }

    public function customMainWrapperClasses(): string
    {
        return Arr::toCssClasses([
            'rounded-lg p-4',
            TasteUi::colors()
                ->set('bg', $this->color, 50)
                ->get(),
        ]);
    }

    public function customTitleBaseClasses(): string
    {
        return Arr::toCssClasses([
            'text-sm font-semibold',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
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

    public function customBodyClasses(): string
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

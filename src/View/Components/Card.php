<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Card extends Component implements Customizable
{
    public function __construct(
        public ?string $header = null,
        public ?string $footer = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.card');
    }

    public function customization(bool $error = false): array
    {
        return [
            'wrapper' => $this->customWrapperClasses(),
            'title' => $this->customTitleClasses(),
            'footer' => $this->customFooterClasses(),
        ];
    }

    public function customWrapperClasses(): array
    {
        return [
            'first' => 'flex justify-center gap-4',
            'second' => 'flex w-full flex-col rounded-lg bg-white shadow-md',
        ];
    }

    public function customTitleClasses(): array
    {
        return [
            'wrapper' => Arr::toCssClasses([
                'flex items-center justify-between',
                'border-b px-4 py-2.5' => $this->header !== null,
            ]),
            'text' => 'font-medium text-md text-secondary-700',
        ];
    }

    public function customFooterClasses(): array
    {
        return [
            'wrapper' => 'rounded-lg rounded-t-none border-t px-4 py-4 bg-secondary-50 text-secondary-700 sm:px-6',
            'text' => 'flex items-center justify-end gap-2',
        ];
    }
}

<?php

namespace TallStackUi\Components\Errors;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\ErrorsColors;
use TallStackUi\Support\Runtime\Components\ErrorsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('errors')]
#[ColorsThroughOf(ErrorsColors::class)]
#[PassThroughRuntime(ErrorsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $title = null,
        public string|array|null $only = null,
        public ?string $icon = 'x-circle',
        public ?string $color = 'red',
        public ?bool $close = false,
        public ?bool $withoutTitle = false,
        public ?bool $listNumeric = false,
        public ?bool $paddingless = false,
        public ?bool $shadowless = false,
        public ?bool $bordered = false,
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->title ??= trans('ts-ui::messages.errors.title');
    }

    public function blade(): View
    {
        return view('ts-ui::components.errors.main');
    }

    public function count(ViewErrorBag $errors): int
    {
        return count($this->messages($errors));
    }

    public function customization(): array
    {
        return Arr::dot([
            'outer' => 'w-full',
            'wrapper' => 'rounded-lg p-4 shadow',
            'shadowless' => 'shadow-none!',
            'bordered' => 'border',
            'paddingless' => [
                'wrapper' => 'px-0!',
                'title' => 'px-4',
                'body' => 'px-4!',
                'footer' => 'px-4',
            ],
            'title' => [
                'wrapper' => 'flex items-center',
                'divider' => 'border-b pb-3',
                'text' => 'text-md inline-flex items-center gap-1 font-bold',
                'icon' => 'w-5 h-5',
            ],
            'body' => [
                'wrapper' => 'ml-5 mt-2 pl-1',
                'list' => 'text-md space-y-1',
            ],
            'close' => 'w-5 h-5',
            'slots' => [
                'footer' => [
                    'wrapper' => 'mt-2',
                    'base' => 'flex gap-2',
                    'start' => 'justify-start',
                    'center' => 'justify-center',
                    'end' => 'justify-end',
                    'between' => 'justify-between',
                ],
            ],
        ]);
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

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if ($this->withoutTitle || filled($this->title)) {
            return;
        }

        __ts_validation_exception($this, 'The [title] cannot be empty');
    }
}

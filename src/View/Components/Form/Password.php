<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.password')]
class Password extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array|null $rules = null,
        public ?bool $invalidate = null
    ) {
        $this->setup();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.password');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'error' => $this->error(),
        ]);
    }

    protected function setup(): void
    {
        $this->rules = collect($this->rules);

        $this->rules = $this->rules->reduce(function (Collection $carry, string $value) {
            if (str_contains($value, 'min')) {
                $carry->put('min', (explode(':', $value)[1] ?? '8'));
            }

            if (str_contains($value, 'numbers')) {
                $carry->put('numbers', true);
            }

            if (str_contains($value, 'symbols')) {
                $carry->put('symbols', (explode(':', $value)[1] ?? '!@#$%^&*()_+-='));
            }

            if (str_contains($value, 'mixed')) {
                $carry->put('mixed', true);
            }

            return $carry;
        }, collect());
    }
}

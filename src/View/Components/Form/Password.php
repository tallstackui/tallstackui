<?php

namespace TallStackUi\View\Components\Form;

use Exception;
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
        public ?bool $generator = false,
        public ?bool $invalidate = null
    ) {
        $this->setup();
    }

    /**
     * Set the default rules values for `min` and `symbols`.
     *
     * @return string[]
     */
    public static function defaults(): array
    {
        // This is not a "final" method because it
        // can be overridden in the child class.
        return ['min' => '8', 'symbols' => '!@#$%^&*()_+-='];
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
                'wrapper' => 'flex items-center',
                'class' => 'dark:text-dark-400 h-5 w-5 cursor-pointer text-gray-500',
            ],
            'rules' => [
                'wrapper' => 'dark:bg-dark-700 dark:border-dark-600 absolute z-50 rounded-lg border border-gray-300 bg-white p-4',
                'title' => 'text-lg font-semibold text-red-500 dark:text-dark-300',
                'block' => 'mt-2 flex flex-col',
                'items' => [
                    'base' => 'inline-flex items-center gap-1 text-gray-700 text-md dark:text-dark-300',
                    'icons' => [
                        'error' => 'h-5 w-5 text-red-500',
                        'success' => 'h-5 w-5 text-green-500',
                    ],
                ],
            ],
            'error' => $this->error(),
        ]);
    }

    protected function setup(): void
    {
        $this->rules = collect($this->rules)->reduce(function (Collection $carry, string $value) {
            if (str_contains($value, 'min')) {
                $carry->put('min', (explode(':', $value)[1] ?? self::defaults()['min']));
            }

            if (str_contains($value, 'numbers')) {
                $carry->put('numbers', true);
            }

            if (str_contains($value, 'symbols')) {
                $carry->put('symbols', (explode(':', $value)[1] ?? self::defaults()['symbols']));
            }

            if (str_contains($value, 'mixed')) {
                $carry->put('mixed', true);
            }

            return $carry;
        }, collect());
    }

    /** @throws Exception */
    protected function validate(): void
    {
        if ($this->generator && (! $this->rules || $this->rules->isEmpty())) {
            throw new Exception('The password [generator] requires the [rules] of the password.');
        }
    }
}

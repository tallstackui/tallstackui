<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Traits\MergeAttributes;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.password')]
class Password extends BaseComponent implements Personalization
{
    use MergeAttributes;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array|null $rules = null,
        public ?bool $mixedCase = false,
        public ?bool $generator = false,
        public ?bool $invalidate = null
    ) {
        $this->rules = collect($this->rules)->reduce(function (Collection $carry, string $value) {
            $defaults = self::defaults();

            if (str_contains($value, 'min')) {
                $carry->put('min', (explode(':', $value)[1] ?? $defaults['min']));
            }

            if (str_contains($value, 'numbers')) {
                $carry->put('numbers', true);
            }

            if (str_contains($value, 'symbols')) {
                $carry->put('symbols', (explode(':', $value)[1] ?? $defaults['symbols']));
            }

            if (str_contains($value, 'mixed')) {
                $carry->put('mixed', true);
            }

            return $carry;
        }, collect());
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

    final public function icons(): array
    {
        return ['x-circle' => TallStackUi::icon('x-circle'), 'check-circle' => TallStackUi::icon('check-circle')];
    }

    public function personalization(): array
    {
        return Arr::dot([
            'icon' => [
                'wrapper' => 'flex items-center',
                'class' => 'h-5 w-5 cursor-pointer',
            ],
            'rules' => [
                'title' => 'text-md font-semibold text-red-500 dark:text-dark-300',
                'block' => 'mt-2 flex flex-col',
                'items' => [
                    'base' => 'inline-flex items-center gap-1 text-gray-700 text-sm dark:text-dark-300',
                    'icons' => [
                        'error' => 'h-5 w-5 text-red-500',
                        'success' => 'h-5 w-5 text-green-500',
                    ],
                ],
            ],
        ]);
    }

    /** @throws Exception */
    protected function validate(): void
    {
        if ($this->generator && (! $this->rules || $this->rules->isEmpty())) {
            throw new Exception('The password [generator] requires the [rules] of the password.');
        }
    }
}

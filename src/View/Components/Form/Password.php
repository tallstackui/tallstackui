<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\PasswordRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Floating;

#[SoftPersonalization('form.password')]
#[PassThroughRuntime(PasswordRuntime::class)]
class Password extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public Collection|array|bool|null $rules = null,
        public ?bool $mixedCase = false,
        public ?bool $generator = null,
        public ?bool $invalidate = null,
        public ?bool $typingOnly = null,
        #[SkipDebug]
        public ?bool $simple = null,
    ) {
        $default = config('tallstackui.settings.form.password.rules');

        $this->simple = $this->rules === null && $this->generator === null;

        $this->rules = collect(is_bool($this->rules) || is_null($this->rules) ? $default : $this->rules)
            ->mapWithKeys(function (string $value, ?string $key = null) use ($default): array {
                // When $this->rules is bool/null, we interact with default values.
                if (is_bool($this->rules) || is_null($this->rules)) {
                    return match ($key) {
                        'min' => ['min' => $value],
                        'numbers' => ['numbers' => (bool) $value],
                        'mixed' => ['mixed' => (bool) $value],
                        'symbols' => ['symbols' => $value],
                        default => [],
                    };
                }

                $rescued = rescue(fn () => explode(':', $value)[1], report: false);

                return match (true) {
                    str_contains($value, 'min') => ['min' => $rescued ?? data_get($default, 'min', 8)],
                    str_contains($value, 'numbers') => ['numbers' => true],
                    str_contains($value, 'mixed') => ['mixed' => true],
                    str_contains($value, 'symbols') => ['symbols' => $rescued ?? data_get($default, 'symbols', '!@#$%^&*()_+-=')],
                    default => [$key => $value],
                };
            });
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.password');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'icon' => [
                'wrapper' => 'flex items-center',
                'class' => 'h-5 w-5 cursor-pointer',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->personalization())->get('wrapper'),
                'class' => 'w-full p-3',
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
        if ($this->generator && $this->rules?->isEmpty()) {
            __ts_validation_exception($this, 'The [generator] requires the [rules] of the password.');
        }
    }
}

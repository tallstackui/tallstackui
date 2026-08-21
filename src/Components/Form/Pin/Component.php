<?php

namespace TallStackUi\Components\Form\Pin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\PinRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.pin')]
#[PassThroughRuntime(PinRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?int $length = null,
        public ?string $prefix = null,
        public ?bool $clear = null,
        public ?bool $invalidate = null,
        public ?bool $numbers = null,
        public ?bool $letters = null,
        public ?bool $smart = null,
        public ?bool $password = null,
        public bool|string|null $separator = null,
        public int|string|array|null $split = null,
        public ?bool $group = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.pin');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'mt-1 flex items-center',
            'input' => [
                'size' => [
                    'prefix' => 'w-[60px]',
                    'base' => 'w-[38px]',
                ],
                'base' => 'block text-center text-lg font-medium ring-1',
                'spacing' => 'mr-2',
                'rounding' => 'rounded-md',
                'group' => [
                    'base' => 'relative focus:z-10',
                    'first' => 'rounded-l-md',
                    'last' => 'rounded-r-md',
                    'joined' => '-ml-px',
                ],
                'locked' => 'cursor-not-allowed! opacity-50',
                'color' => [
                    'base' => 'dark:border-dark-600/50 dark:text-dark-300 focus:ring-primary-600 focus-within:focus:ring-primary-600 border-gray-200 text-gray-600 ring-transparent focus-within:ring-2 focus:border-0 focus:ring-2',
                    'background' => 'dark:bg-dark-800 bg-white',
                    'error' => 'border-0 border-red-500 text-red-600 ring-red-300 placeholder:text-red-600 focus-within:ring-red-500 focus-within:placeholder:text-red-600 focus:ring-2 focus:ring-red-500 focus-within:focus:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500',
                ],
            ],
            'separator' => 'mr-2 select-none text-lg font-medium text-gray-400 dark:text-dark-400',
            'button' => 'h-6 w-6 text-red-500',
        ]);
    }

    /**
     * The box indexes followed by a separator. Without `split` the
     * boxes are cut in the middle, so `123-456` needs no extra prop.
     */
    public function splits(): array
    {
        if (! $this->separator || $this->length < 2) {
            return [];
        }

        if ($this->split === null) {
            return [(int) ceil($this->length / 2)];
        }

        $positions = is_string($this->split) ? explode(',', $this->split) : Arr::wrap($this->split);

        return collect($positions)
            ->map(fn (mixed $position): int => (int) trim((string) $position))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! $this->length) {
            __ts_validation_exception($this, 'The [length] is mandatory and should be set.');
        }

        if ($this->prefix && strlen($this->prefix) > 3) {
            __ts_validation_exception($this, 'The [prefix] must be 3 characters or less.');
        }

        if ($this->numbers && $this->letters) {
            __ts_validation_exception($this, 'The [numbers] and [letters] cannot be used together.');
        }

        if (is_string($this->separator) && mb_strlen($this->separator) > 3) {
            __ts_validation_exception($this, 'The [separator] must be 3 characters or less.');
        }

        if ($this->split !== null && ! $this->separator) {
            __ts_validation_exception($this, 'The [split] requires the [separator] to be set.');
        }

        foreach ($this->splits() as $position) {
            if ($position < 1 || $position >= $this->length) {
                __ts_validation_exception($this, 'The [split] positions must be between 1 and the [length] minus one.');
            }
        }
    }
}

<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\DateRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Floating;

#[SoftPersonalization('form.date')]
#[PassThroughRuntime(DateRuntime::class)]
class Date extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?bool $invalidate = null,
        public ?bool $range = false,
        public ?bool $multiple = false,
        public ?string $format = 'YYYY-MM-DD',
        public string|null|Carbon $minDate = null,
        public string|null|Carbon $maxDate = null,
        public ?int $minYear = null,
        public ?int $maxYear = null,
        public ?bool $helpers = null,
        public ?bool $monthYearOnly = false,
        public array|Collection $disable = [],
        public int|string $start = 0,
        public int|string|null $only = null,
        public ?bool $weekdays = false,
        public ?bool $weekends = false,
    ) {
        $this->disable = collect($this->disable)
            ->flatten()
            ->unique()
            ->map(function (string|Carbon $value) {
                if (! $value instanceof Carbon) {
                    return $value;
                }

                return $value->format('Y-m-d');
            })
            ->values();

        $this->start = (int) $this->start;
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.date');
    }

    final public function dates(): array
    {
        return [
            'date' => [
                'min' => $this->minDate ? Carbon::parse($this->minDate)->format('Y-m-d') : null,
                'max' => $this->maxDate ? Carbon::parse($this->maxDate)->format('Y-m-d') : null,
            ],
            'year' => [
                'min' => $this->minYear,
                'max' => $this->maxYear,
            ],
        ];
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'helpers' => 'custom-scrollbar flex items-center justify-between space-x-2 overflow-auto pb-2',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->personalization())->get('wrapper'),
                'class' => 'p-3 w-[17rem]',
            ],
            'box' => [
                'picker' => [
                    'button' => 'text-gray-900 focus:ring-dark-200 flex items-center justify-between rounded-lg px-2 py-1 mb-6 text-sm font-semibold focus:outline-hidden focus:ring-2 dark:text-white',
                    'wrapper' => [
                        'first' => 'dark:bg-dark-700 absolute left-0 top-0 flex h-full w-full select-none rounded-lg bg-white p-3',
                        'second' => 'flex w-full flex-wrap',
                        'third' => 'flex h-12 w-full items-center justify-between px-1',
                    ],
                    'label' => 'text-gray-900 dark:bg-dark-700 hover:bg-dark-100 dark:hover:bg-dark-600 focus:ring-dark-200 flex cursor-pointer items-center justify-between rounded-lg bg-white px-2 py-1 text-sm font-semibold focus:outline-hidden focus:ring-0 dark:text-white',
                    'range' => 'text-gray-400 dark:text-dark-400 font-medium hover:bg-dark-100 dark:hover:bg-dark-600 text-gray-600 dark:text-dark-400 disabled:text-gray-400 dark:disabled:text-dark-500 flex h-6 w-1/4 cursor-pointer select-none items-center justify-center rounded-md p-1 text-center font-normal disabled:cursor-not-allowed',
                    'separator' => 'mx-1',
                ],
            ],
            'label' => [
                'days' => 'text-gray-400 dark:text-dark-400 select-none text-center text-xs font-medium',
                'month' => 'text-gray-800 dark:text-dark-100 cursor-pointer select-none text-lg font-bold',
                'year' => 'text-gray-600 dark:text-dark-400 ml-1 cursor-pointer select-none text-lg font-normal',
            ],
            'button' => [
                'blank' => 'border border-transparent p-1 text-center text-sm',
                'day' => 'focus:shadow-outline disabled:text-gray-400 dark:disabled:text-dark-500 dark:active:bg-primary-500 ring-primary-500 active:bg-primary-600 flex h-7 w-7 items-center justify-center rounded-full text-center text-sm leading-none outline-hidden transition-all duration-200 ease-in-out hover:shadow-sm active:text-white disabled:cursor-not-allowed cursor-pointer',
                'select' => 'text-gray-600 dark:text-dark-400 hover:bg-dark-200 dark:hover:bg-dark-600',
                'today' => 'text-primary-500 dark:text-dark-300 !font-bold',
                'selected' => 'bg-primary-500 !text-white hover:bg-opacity-75',
                'helpers' => 'text-gray-500 dark:text-dark-300 bg-dark-200 dark:bg-dark-600 hover:bg-dark-300 dark:hover:bg-dark-500 select-none whitespace-nowrap rounded-md px-2 py-1 text-sm font-medium',
                'navigate' => 'focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600 inline-flex cursor-pointer rounded-full p-1 transition duration-100 ease-in-out focus:outline-hidden',
            ],
            'icon' => [
                'wrapper' => 'flex items-center gap-1.5',
                'size' => 'h-5 w-5',
                'clear' => 'hover:text-red-500',
                'input' => 'text-secondary-500 dark:text-dark-400 flex cursor-pointer items-center gap-2',
                'navigate' => 'text-gray-600 dark:text-dark-300 h-5 w-5',
            ],
            'range' => 'bg-dark-200 dark:bg-dark-600',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $min = null;
        $max = null;

        try {
            $min = Carbon::parse($this->minDate);
            $max = Carbon::parse($this->maxDate);
        } catch (Exception) {
            //
        }

        if (blank($min)) {
            __ts_validation_exception($this, 'The [min-date] attribute must be a Carbon instance or a valid date string.');
        }

        if (blank($max)) {
            __ts_validation_exception($this, 'The [max-date] attribute must be a Carbon instance or a valid date string.');
        }

        // We should only apply this logic if $this->maxDate is defined,
        // because, when parsing a null date, the date returned is the
        // current one, causing the comparison to always result in true
        // since $min can be greater than the $max (set incorrectly).
        if (($this->minDate && $this->maxDate) && $min->greaterThan($max)) {
            __ts_validation_exception($this, 'The [min-date] must be less than or equal to [max-date].');
        }

        if (($this->minYear && $this->maxYear) && $this->maxYear < $this->minYear) {
            __ts_validation_exception($this, 'The year [min-year] must be less than or equal to [max-year].');
        }

        if ($this->start > 6) {
            __ts_validation_exception($this, 'The [start] attribute must be between 0 and 6.');
        }

        if ($this->only && $this->only > 6) {
            __ts_validation_exception($this, 'The [only] attribute must be between 0 and 6.');
        }
    }
}

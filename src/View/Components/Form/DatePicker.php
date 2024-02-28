<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Traits\SanitizePropertyValue;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.datepicker')]
class DatePicker extends BaseComponent implements Personalization
{
    use SanitizePropertyValue;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?bool $invalidate = null,
        public ?bool $range = false,
        public ?bool $multiple = false,
        public ?string $format = 'YYYY-MM-DD',
        public string|null|Carbon $minDate = null,
        public string|null|Carbon $maxDate = null,
        public ?int $minYear = null,
        public ?int $maxYear = null,
        public bool|array|null $helpers = null,
        public array|Collection $disable = [],
    ) {
        $this->helpers = $this->helpers === true ? ['yesterday', 'today', 'tomorrow'] : [];
        $this->disable = collect($this->disable);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.datepicker');
    }

    final public function dates(): array
    {
        return [
            'date' => [
                'min' => $this->minDate,
                'max' => $this->maxDate,
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
                'helpers' => 'custom-scrollbar mt-4 flex items-center justify-between space-x-2 overflow-auto pb-2',
            ],
            'box' => [
                'wrapper' => [
                    'first' => 'dark:bg-dark-700 border-dark-200 dark:border-dark-600 absolute z-10 w-[17rem] max-w-lg rounded-lg border bg-white p-4 antialiased shadow',
                    'second' => 'mb-4 flex items-center justify-between',
                ],
                'picker' => [
                    'button' => 'text-gray-900 focus:ring-dark-200 flex items-center justify-between rounded-lg px-2 py-1 text-sm font-semibold focus:outline-none focus:ring-2 dark:text-white',
                    'wrapper' => [
                        'first' => 'dark:bg-dark-700 absolute left-0 top-0 flex h-full w-full select-none rounded-lg bg-white p-3',
                        'second' => 'flex w-full flex-wrap',
                        'third' => 'mb-2 flex h-12 w-full items-center justify-between px-1',
                    ],
                    'label' => 'text-gray-900 dark:bg-dark-700 hover:bg-dark-100 dark:hover:bg-dark-600 focus:ring-dark-200 flex cursor-pointer items-center justify-between rounded-lg bg-white px-2 py-1 text-sm font-semibold focus:outline-none focus:ring-2 dark:text-white',
                    'range' => 'text-gray-400 dark:text-dark-400 font-medium hover:bg-dark-100 dark:hover:bg-dark-600 text-gray-600 dark:text-dark-400 disabled:text-gray-400 dark:disabled:text-dark-500 flex h-6 w-1/4 cursor-pointer select-none items-center justify-center rounded-md p-1 text-center font-normal disabled:cursor-not-allowed',
                ],
            ],
            'label' => [
                'days' => 'text-gray-400 dark:text-dark-400 select-none text-center text-xs font-medium',
                'month' => 'text-gray-800 dark:text-dark-100 cursor-pointer select-none text-lg font-bold',
                'year' => 'text-gray-600 dark:text-dark-400 ml-1 cursor-pointer select-none text-lg font-normal',
            ],
            'button' => [
                'blank' => 'border border-transparent p-1 text-center text-sm',
                'day' => 'focus:shadow-outline disabled:text-gray-400 dark:disabled:text-dark-500 dark:active:bg-primary-500 ring-primary-500 active:bg-primary-600 flex h-7 w-7 items-center justify-center rounded-full text-center text-sm leading-none outline-none transition-all duration-200 ease-in-out hover:shadow-sm active:text-white disabled:cursor-not-allowed',
                'select' => 'text-gray-600 dark:text-dark-400 hover:bg-dark-200 dark:hover:bg-dark-600',
                'today' => 'text-primary-500 dark:text-dark-300 !font-bold',
                'selected' => 'bg-primary-500 !text-white hover:bg-opacity-75',
                'helpers' => 'text-gray-500 dark:text-dark-300 bg-dark-200 dark:bg-dark-600 hover:bg-dark-300 dark:hover:bg-dark-500 select-none whitespace-nowrap rounded-md px-2 py-1 text-sm font-medium',
                'navigate' => 'focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600 inline-flex cursor-pointer rounded-full p-1 transition duration-100 ease-in-out focus:outline-none',
            ],
            'icon' => [
                'size' => 'h-5 w-5',
                'clear' => 'hover:text-red-500',
                'input' => 'text-secondary-500 dark:text-dark-400 flex cursor-pointer items-center gap-2',
                'navigate' => 'text-gray-600 dark:text-dark-300 h-5 w-5',
            ],
            'range' => 'bg-dark-200 dark:bg-dark-600',
        ]);
    }

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
            throw new InvalidArgumentException('The datepicker [min-date] attribute must be a Carbon instance or a valid date string.');
        }

        if (blank($max)) {
            throw new InvalidArgumentException('The datepicker [max-date] attribute must be a Carbon instance or a valid date string.');
        }

        // We should only apply this logic if $this->maxDate is defined,
        // because when parsing a null date, the date returned is the
        // current one, causing the comparison to always result in true
        // since $min can be greater than the $max (set incorrectly).
        if ($this->maxDate && $min->greaterThan($max)) {
            throw new InvalidArgumentException('The datepicker [min-date] must be less than or equal to [max-date].');
        }
    }
}

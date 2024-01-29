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
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.datepicker')]
class DatePicker extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

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
        public bool|array|Collection|null $helpers = null,
        public array|Collection $disable = [],
        public ?array $placeholders = null,
        public ?int $delay = 2,
    ) {
        $this->helpers = $this->helpers === true ?
            collect(['yesterday', 'today', 'tomorrow', 'last7days', 'last15days', 'last30days']) :
            ($helpers !== null ? collect($this->helpers) : collect([]));

        if ($this->range === true && ! blank($this->helpers)) {
            $this->helpers = $this->helpers->diff(['tomorrow', 'today', 'yesterday']);
        } else {
            $this->helpers = $this->helpers->diff(['last7days', 'last15days', 'last30days']);
        }

        $this->messages();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.datepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'cursor-pointer truncate',
                'class' => [...$this->input()],
            ],
            'wrapper' => [
                'helpers' => 'flex items-center justify-between mt-4 custom-scrollbar overflow-auto pb-2 space-x-2',
            ],
            'box' => [
                'wrapper' => 'absolute z-10 max-w-lg p-4 antialiased bg-white dark:bg-dark-700 border rounded-lg shadow w-[17rem] border-dark-200 dark:border-dark-600',
                'picker' => [
                    'button' => 'text-sm rounded-lg flex items-center justify-between text-dark-900 dark:text-white font-semibold py-1 px-2 focus:outline-none focus:ring-2 focus:ring-dark-200 cursor-pointer',
                    'wrapper' => [
                        'first' => 'absolute top-0 left-0 flex w-full h-full p-3 bg-white dark:bg-dark-700 rounded-lg select-none',
                        'second' => 'flex flex-wrap w-full',
                        'third' => 'w-full flex items-center justify-between mb-2 px-1 h-12',
                    ],
                    'label' => 'text-sm rounded-lg flex items-center justify-between text-dark-900 dark:text-white bg-white dark:bg-dark-700 font-semibold py-1 px-2 hover:bg-dark-100 dark:hover:bg-dark-600 focus:outline-none focus:ring-2 focus:ring-dark-200 cursor-pointer',
                    'range' => 'flex items-center justify-center select-none w-1/4 p-1 text-center cursor-pointer hover:bg-dark-100 dark:hover:bg-dark-600 font-normal rounded-md text-dark-600 dark:text-dark-400 h-6',
                ],
            ],
            'label' => [
                'days' => 'select-none text-xs font-medium text-center text-dark-400 dark:text-dark-400',
                'month' => 'select-none text-lg font-bold text-dark-800 dark:text-dark-100',
                'year' => 'select-none ml-1 text-lg font-normal text-dark-600 dark:text-dark-400',
            ],
            'button' => [
                'day' => 'flex items-center justify-center text-sm leading-none text-center rounded-full h-7 w-7 focus:shadow-outline active:text-white disabled:text-dark-400 dark:disabled:text-dark-500 disabled:cursor-not-allowed dark:active:bg-primary-500 ring-primary-500 active:bg-primary-600 outline-none transition-all duration-200 ease-in-out hover:shadow-sm',
                'select' => 'text-dark-600 dark:text-dark-400 hover:bg-dark-200 dark:hover:bg-dark-600',
                'today' => 'text-primary-500 dark:text-dark-300 !font-bold',
                'selected' => 'bg-primary-500 !text-white hover:bg-opacity-75',
                'helpers' => 'px-2 py-1 select-none text-sm whitespace-nowrap font-medium text-dark-500 dark:text-dark-300 bg-dark-200 dark:bg-dark-600 rounded-md hover:bg-dark-300 dark:hover:bg-dark-500',
                'navigate' => 'inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600',
            ],
            'icon' => [
                'input' => 'cursor-pointer text-secondary-500 dark:text-dark-400 flex items-center gap-2',
                'navigate' => 'w-5 h-5 text-dark-600 dark:text-dark-300',
            ],
            'range' => 'bg-dark-200 dark:bg-dark-600',
            'error' => $this->error(),
        ]);
    }

    protected function messages(): void
    {
        $this->placeholders['days'] = [
            __('tallstack-ui::messages.datepicker.days.sun'),
            __('tallstack-ui::messages.datepicker.days.mon'),
            __('tallstack-ui::messages.datepicker.days.tue'),
            __('tallstack-ui::messages.datepicker.days.wed'),
            __('tallstack-ui::messages.datepicker.days.thu'),
            __('tallstack-ui::messages.datepicker.days.fri'),
            __('tallstack-ui::messages.datepicker.days.sat'),
        ];

        $this->placeholders['months'] = [
            __('tallstack-ui::messages.datepicker.months.january'),
            __('tallstack-ui::messages.datepicker.months.february'),
            __('tallstack-ui::messages.datepicker.months.march'),
            __('tallstack-ui::messages.datepicker.months.april'),
            __('tallstack-ui::messages.datepicker.months.may'),
            __('tallstack-ui::messages.datepicker.months.june'),
            __('tallstack-ui::messages.datepicker.months.july'),
            __('tallstack-ui::messages.datepicker.months.august'),
            __('tallstack-ui::messages.datepicker.months.september '),
            __('tallstack-ui::messages.datepicker.months.october'),
            __('tallstack-ui::messages.datepicker.months.november'),
            __('tallstack-ui::messages.datepicker.months.december'),
        ];
    }

    protected function validate(): void
    {
        $minDate = null;
        $maxDate = null;

        try {
            $minDate = Carbon::parse($this->minDate);
            $maxDate = Carbon::parse($this->maxDate);
        } catch (Exception) {
            //
        }

        if (blank($maxDate) || blank($maxDate)) {
            throw new InvalidArgumentException('The DatePicker [min-date|max-date] attribute must be a Carbon instance or a valid date string.');
        }

        if ($minDate->greaterThan($maxDate)) {
            throw new InvalidArgumentException('The DatePicker [min-date] must be less than or equal to [max-date].');
        }

        $helper = collect(['today', 'yesterday', 'tomorrow', 'last7days', 'last15days', 'last30days']);

        if (! $this->helpers->diff($helper)->isEmpty()) {
            throw new InvalidArgumentException('The DatePicker [helpers] only allows the values: '.$helper->implode(', '));
        }
    }
}

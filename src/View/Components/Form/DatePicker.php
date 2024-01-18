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
        public ?bool $time = false,
        public ?string $format = null,
        public string|null|Carbon $min = null,
        public string|null|Carbon $max = null,
        public bool|array|Collection|null $helpers = null,
        public array|Collection|null $disabledDates = null,
    ) {
        $this->helpers = $this->helpers === true ? collect(['yesterday', 'today', 'tomorrow']) : collect($this->helpers);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.datepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => ['class' => [...$this->input()]],
            'wrapper' => [
                'helpers' => 'flex items-center justify-between mt-4 soft-scrollbar overflow-auto pb-2 space-x-2',
            ],
            'label' => [
                'days' => 'text-xs font-medium text-center text-gray-800 dark:text-dark-300',
                'month' => 'text-lg font-bold text-gray-800 dark:text-dark-100',
                'year' => 'ml-1 text-lg font-normal text-gray-600 dark:text-dark-400',
            ],
            'button' => [
                'day' => 'flex items-center justify-center text-sm leading-none text-center rounded-full h-7 w-7 focus:shadow-outline active:text-white disabled:text-gray-400 disabled:cursor-not-allowed dark:active:bg-primary-500 ring-primary-500 focus:bg-primary-600 dark:focus:ring-offset-dark-300 dark:focus:ring-primary-600 outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-offset-white',
                'select' => 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-600',
                'today' => 'text-primary-500 font-bold',
                'selected' => 'bg-primary-500 text-white hover:bg-opacity-75',
                'helpers' => 'px-2 py-1 text-sm whitespace-nowrap font-medium text-gray-500 dark:text-gray-300 bg-gray-200 dark:bg-dark-600 rounded-md hover:bg-gray-300 dark:hover:bg-dark-500',
                'navigate' => 'inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-dark-100 dark:hover:bg-dark-600',
            ],
            'icon' => [
                'navigate' => 'w-5 h-5 text-gray-600 dark:text-dark-300',
            ],
            'range' => 'bg-gray-200 dark:bg-dark-600',
            'colon' => 'text-lg mx-1 font-bold text-gray-600 dark:text-dark-200',
            'error' => $this->error(),
        ]);
    }

    protected function validate(): void
    {
        $min = null;
        $max = null;

        try {
            $min = Carbon::parse($this->min);
            $max = Carbon::parse($this->max);
        } catch (Exception) {
            //
        }

        if (blank($min) || blank($max)) {
            throw new InvalidArgumentException('The DatePicker [min|max] attribute must be a Carbon instance or a valid date string.');
        }
    }
}

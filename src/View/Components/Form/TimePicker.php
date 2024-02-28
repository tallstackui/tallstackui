<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.timepicker')]
class TimePicker extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?bool $helper = null,
        public ?string $format = '12',
        public ?string $stepHour = '1',
        public ?string $stepMinute = '1',
        public ?ComponentSlot $footer = null,
    ) {
        $this->format = $this->format === '12' ? '12' : '24';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.timepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:border-dark-600 absolute top-full z-50 mt-2 w-[18rem] overflow-hidden rounded-md border border-gray-200 shadow-lg',
                'second' => 'shadow-xs soft-scrollbar dark:bg-dark-700 overflow-auto rounded-md bg-white p-4',
                'third' => 'flex select-none items-center justify-center gap-1',
            ],
            'icon' => 'h-5 w-5',
            'time' => 'text-primary-600 dark:text-dark-300 dark:border-dark-700 w-20 rounded-full p-2 text-center text-4xl font-medium transition',
            'separator' => 'dark:text-dark-400 h-14 text-5xl text-gray-300',
            'format' => [
                'wrapper' => 'divide-primary-200 dark:divide-dark-500 m-2 flex h-14 flex-col justify-between divide-y',
                'input' => 'peer hidden',
                'size' => 'w-12',
                'color' => 'peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:text-primary-600 dark:peer-checked:text-dark-100 peer-checked:dark:bg-dark-700 peer-checked:dark:border-dark-500 dark:border-dark-600 dark:hover:text-dark-300 inline-flex w-full cursor-pointer items-center justify-between border border-gray-300 bg-white p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-600 peer-checked:font-bold dark:bg-dark-700 dark:text-dark-400 dark:hover:bg-dark-700',
                'am' => [
                    'label' => 'rounded-t-lg border-b-0',
                    'title' => 'w-full text-center text-sm',
                ],
                'pm' => [
                    'label' => 'rounded-b-lg border-t-0',
                    'title' => 'w-full text-center text-sm font-medium',
                ],
            ],
            'range' => [
                'base' => 'dark:bg-dark-600 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 dark:[&::-webkit-slider-thumb]:bg-dark-400 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                'light' => 'bg-primary-50',
                'dark' => 'dark:bg-dark-600',
            ],
            'helper' => [
                'wrapper' => 'mt-2 flex flex-col space-y-6 outline-none',
                'button' => 'w-full uppercase',
            ],
        ]);
    }
}

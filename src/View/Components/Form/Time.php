<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\TimeRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Floating;

#[SoftPersonalization('form.time')]
#[PassThroughRuntime(TimeRuntime::class)]
class Time extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?bool $invalidate = null,
        public ?bool $helper = null,
        public ?int $minHour = null,
        public ?int $maxHour = null,
        public ?int $minMinute = null,
        public ?int $maxMinute = null,
        public ?string $format = '12',
        public ?string $stepHour = '1',
        public ?string $stepMinute = '1',
        #[SkipDebug]
        public ?ComponentSlot $footer = null,
    ) {
        $this->format = $this->format === '12' ? '12' : '24';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.time');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex select-none items-center justify-center gap-1',
            'icon' => [
                'size' => 'h-5 w-5',
                'clear' => 'hover:text-red-500',
                'wrapper' => 'flex items-center gap-1.5',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->personalization())->get('wrapper'),
                'class' => 'p-3 w-[18rem]',
            ],
            'time' => 'text-primary-600 dark:text-dark-300 dark:border-dark-700 w-20 rounded-full p-2 text-center text-4xl font-medium transition',
            'separator' => 'dark:text-dark-400 h-14 text-5xl text-gray-300',
            'interval' => [
                'wrapper' => 'flex justify-center items-center',
                'text' => 'text-xl text-primary-400 dark:text-dark-400 font-bold',
                'buttons' => [
                    'wrapper' => 'mt-4 flex items-center justify-center divide-x divide-primary-600',
                    'am' => 'flex h-4 w-full items-center justify-center rounded-l-lg px-4 text-xs text-white transition bg-primary-500 py-2.5 focus:ring-primary-600 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600 dark:bg-primary-700 dark:hover:bg-primary-600 dark:hover:ring-primary-600',
                    'pm' => 'flex h-4 w-full items-center justify-center rounded-r-lg px-4 text-xs text-white transition bg-primary-500 py-2.5 focus:ring-primary-600 focus:ring-2 focus:ring-offset-2 dark:bg-primary-700 dark:hover:bg-primary-600 dark:hover:ring-primary-600 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600',
                ],
            ],
            'range' => [
                'base' => 'dark:bg-dark-600 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 dark:[&::-webkit-slider-thumb]:bg-dark-400 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                'light' => 'bg-primary-50',
                'dark' => 'dark:bg-dark-600',
            ],
            'helper' => [
                'wrapper' => 'mt-2 flex flex-col space-y-6 outline-hidden',
                'button' => 'w-full uppercase',
            ],
        ]);
    }

    final public function times(): array
    {
        return [
            'hour' => [
                'min' => $this->minHour,
                'max' => $this->maxHour,
            ],
            'minute' => [
                'min' => $this->minMinute,
                'max' => $this->maxMinute,
            ],
        ];
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (array_filter([$this->minHour, $this->maxHour, $this->minMinute, $this->maxMinute]) === []) {
            return;
        }

        if ($this->minHour && $this->maxHour) {
            if ($this->minHour < 0 || $this->minHour > 23) {
                __ts_validation_exception($this, 'The [min-hour] must be between 0 and 23.');
            }

            if ($this->maxHour < 0 || $this->maxHour > 23) {
                __ts_validation_exception($this, 'The [max-hour] must be between 0 and 23.');
            }

            if ($this->minHour > $this->maxHour) {
                __ts_validation_exception($this, 'The [min-hour] must be less than or equal to the time [max-hour].');
            }
        }

        if ($this->minMinute && $this->maxMinute) {
            if ($this->minMinute < 0 || $this->minMinute > 59) {
                __ts_validation_exception($this, 'The [min-minute] must be between 0 and 59.');
            }

            if ($this->maxMinute < 0 || $this->maxMinute > 59) {
                __ts_validation_exception($this, 'The [max-minute] must be between 0 and 59.');
            }

            if ($this->minMinute > $this->maxMinute) {
                __ts_validation_exception($this, 'The [min-minute] must be less than or equal to the time [max-minute].');
            }
        }
    }
}

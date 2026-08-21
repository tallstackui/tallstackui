<?php

namespace TallStackUi\Components\Step\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\SkeletonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\StepRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('step')]
#[PassThroughRuntime(StepRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SkeletonSetup;

    public const HELPERS = ['default', 'minimal', 'compact'];

    public function __construct(
        public ?int $selected = null,
        public ?bool $panels = false,
        public ?bool $circles = false,
        public ?bool $simple = false,
        public bool|string|null $helpers = false,
        public ?bool $navigate = false,
        public ?bool $navigatePrevious = false,
        public ?string $variation = null,
        public bool|int|null $skeleton = null,
        #[SkipDebug]
        public ComponentSlot|string|null $finish = null,
        #[SkipDebug]
        public ComponentSlot|string|null $previous = null,
        #[SkipDebug]
        public ComponentSlot|string|null $next = null,
    ) {
        $this->variation = $this->panels ? 'panels' : ($this->circles ? 'circles' : 'simple');
    }

    public function blade(): View
    {
        return view($this->skeletonized() ? 'ts-ui::components.step.skeleton' : 'ts-ui::components.step.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'panels' => 'md:flex overflow-auto soft-scrollbar',
                'simple' => 'space-y-2 md:flex md:gap-8 md:space-y-0 overflow-auto soft-scrollbar pb-2',
                'circles' => 'relative flex flex-col md:flex-row',
            ],
            'circles' => [
                'li' => 'group flex flex-1 gap-x-2 transition-colors md:block md:shrink md:basis-0',
                'wrapper' => 'min-w-8 min-h-8 text-md flex flex-col items-center align-middle md:inline-flex md:w-full md:flex-row md:flex-wrap',
                'check' => 'h-5 w-5 text-white',
                'circle' => [
                    'wrapper' => 'w-8 h-8 flex shrink-0 items-center justify-center rounded-full font-bold',
                    'inactive' => 'border-2 border-gray-300 text-gray-500 dark:text-dark-300 dark:border-dark-700',
                    'current' => 'border-2 border-primary-500 text-primary-500',
                    'border' => 'border-2 border-green-600',
                    'active' => 'bg-green-600 text-white',
                ],
                'highlighter' => [
                    'wrapper' => 'h-2.5 w-2.5 rounded-full transition-colors',
                    'current' => ' bg-primary-500',
                    'active' => 'bg-green-600',
                ],
                'divider' => [
                    'wrapper' => 'h-full w-0.5 transition-colors group-last:hidden md:mt-0 md:h-0.5 md:w-full md:flex-1',
                    'inactive' => 'bg-gray-200 dark:bg-dark-700',
                    'active' => 'bg-green-600',
                ],
                'text' => [
                    'wrapper' => 'grow pb-2 transition-colors md:mt-3 md:grow-0',
                    'title' => 'block text-base font-medium transition-colors text-gray-600 dark:text-dark-300',
                    'description' => 'text-sm font-medium text-gray-500 transition-colors dark:text-dark-400',
                ],
            ],
            'simple' => [
                'li' => 'transition-all md:flex-1',
                'bar' => [
                    'wrapper' => 'flex flex-col py-2 pl-4 md:border-l-0 md:border-t-4 md:pb-0 md:pl-0 md:pt-4',
                    'inactive' => 'group border-l-4 border-gray-200 dark:border-dark-700',
                    'current' => 'border-l-4 border-primary-500',
                    'active' => 'border-l-4 border-green-600',
                ],
                'text' => [
                    'title' => [
                        'wrapper' => 'whitespace-nowrap text-base font-bold',
                        'inactive' => 'text-gray-600 dark:text-dark-300',
                        'current' => 'text-primary-500',
                        'active' => 'text-green-600',
                    ],
                    'description' => 'whitespace-nowrap text-sm font-medium text-gray-500 dark:text-dark-400',
                ],
            ],
            'panels' => [
                'text-layout' => 'flex-col',
                'li' => 'relative md:flex md:flex-1 border-b last:border-b-0 border-gray-200 dark:border-dark-600 md:border-0',
                'wrapper' => 'group flex w-full items-center',
                'check' => 'h-5 w-5 text-white',
                'item' => 'flex items-center px-6 py-4 text-sm font-medium',
                'circle' => [
                    'wrapper' => 'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                    'inactive' => 'border-2 border-gray-300 dark:border-dark-700',
                    'current' => 'bg-primary-500 dark:border-primary-500',
                    'active' => 'bg-green-600 dark:border-green-600',
                ],
                'divider' => [
                    'wrapper' => 'absolute right-0 top-0 hidden h-full w-5 md:block',
                    'svg' => 'h-full w-full text-gray-200 dark:text-dark-700',
                ],
                'text' => [
                    'number' => [
                        'active' => 'text-gray-500 dark:text-dark-300',
                        'inactive' => 'text-white',
                    ],
                    'title' => [
                        'wrapper' => 'ml-4 whitespace-nowrap text-base font-bold',
                        'inactive' => 'text-gray-600 dark:text-dark-300',
                        'active' => 'text-green-600',
                    ],
                    'description' => 'ml-4 whitespace-nowrap text-xs font-medium text-gray-500 dark:text-dark-400',
                ],
            ],
            'panels-shape' => 'mb-2 rounded-md border border-gray-200 dark:border-dark-700',
            'content' => 'my-2',
            'helpers.wrapper' => 'flex justify-between',
            'skeleton' => [
                ...$this->blocks(),
                'circle' => 'size-8 rounded-full',
                'panel-circle' => 'size-10 rounded-full',
                'title' => 'h-4 w-24',
                'description' => 'h-3 w-32',
                'content' => 'h-24 w-full',
                'helper' => 'h-10 w-24',
            ],
        ]);
    }

    public function helper(): string
    {
        return str_contains($this->helpers, '::') || str_contains($this->helpers, '.')
            ? $this->helpers
            : 'ts-ui::components.step.helpers.'.$this->helpers;
    }

    protected function setup(): void
    {
        if ($this->helpers === true) {
            $configured = __ts_get_component_configuration(self::class, 'helpers');

            $this->helpers = is_string($configured) ? $configured : 'default';
        }
    }

    protected function validate(): void
    {
        $this->guard();

        if (is_string($this->helpers) && ! str_contains($this->helpers, '::') && ! str_contains($this->helpers, '.') && ! in_array($this->helpers, self::HELPERS, true)) {
            __ts_validation_exception($this, 'The [helpers] must be one of ['.implode(', ', self::HELPERS).'] or a view path.');
        }
    }
}

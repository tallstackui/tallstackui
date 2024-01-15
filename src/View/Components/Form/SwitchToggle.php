<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\SetupRadioCheckboxToggle;

#[SoftPersonalization('form.switch')]
class SwitchToggle extends BaseComponent implements Personalization
{
    use SetupRadioCheckboxToggle;

    public function __construct(
        public string|null|ComponentSlot $label = null,
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        #[SkipDebug]
        public ?string $size = null,
        public ?string $position = 'right',
        public ?string $color = 'primary',
        public ?array $icons = null,
        public ?bool $invalidate = null,
        public ?bool $thematic = null,
    ) {
        $this->setup();
        $this->icons = $this->thematic ? ['sun', 'moon'] : ($this->icons ?: ['x-mark', 'check']);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.switch');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative flex items-center justify-end',
            'input' => [
                'class' => 'peer absolute inset-y-0 left-0.5 translate-x-0 my-0.5 transform cursor-pointer appearance-none rounded-full border-0 bg-white shadow transition duration-200 ease-in-out checked:bg-none checked:text-white focus:outline-none focus:ring-0 focus:ring-offset-0 z-20',
                'sizes' => [
                    'xs' => 'h-2 w-2 checked:translate-x-3',
                    'sm' => 'h-3 w-3 checked:translate-x-4',
                    'md' => 'h-4 w-4 checked:translate-x-5',
                    'lg' => 'h-5 w-5 checked:translate-x-5',
                ],
            ],
            'background' => [
                'class' => 'bg-secondary-200 dark:bg-dark-800 block cursor-pointer rounded-full transition duration-100 ease-in-out group-focus:ring-2 group-focus:ring-offset-2 peer-focus:ring-2 peer-focus:ring-offset-2',
                'sizes' => [
                    'xs' => 'h-3 w-6',
                    'sm' => 'h-4 w-8',
                    'md' => 'h-5 w-10',
                    'lg' => 'h-6 w-11',
                ],
            ],
            'icon' => [
                'wrapper' => 'absolute m-0.5 mr-1',
                'sizes' => [
                    'xs' => 'h-2 w-2',
                    'sm' => 'h-3 w-3',
                    'md' => 'h-4 w-4',
                    'lg' => 'h-4 w-4',
                ],
                'color' => [
                    'on' => 'text-white',
                    'off' => 'text-dark-500',
                ],
            ],
            'error' => 'bg-red-600 group-focus:ring-red-600 peer-checked:bg-red-600 peer-focus:ring-red-600',
        ]);
    }

    protected function validate(): void
    {
        $positions = ['right', 'left'];

        if (! in_array($this->position, $positions)) {
            throw new InvalidArgumentException('The switch label [position] must be one of the following: ['.implode(', ', $positions).']');
        }

        if (count($this->icons) !== 2) {
            throw new InvalidArgumentException('The switch [icons] must have two positions');
        }
    }
}

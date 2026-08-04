<?php

namespace TallStackUi\Components\Form\Tag;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\TagRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.tag')]
#[PassThroughRuntime(TagRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;

    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $prefix = null,
        public ?int $limit = null,
        public ?int $lazy = null,
        public ?bool $invalidate = null,
        public Collection|array|null $options = null,
        #[SkipDebug]
        public ?array $placeholders = null,
        #[SkipDebug]
        public ComponentSlot|string|null $after = null,
    ) {
        $this->options = collect($this->options)->map('strval')->unique()->values()->all();

        $this->placeholders = array_merge(trans('ts-ui::messages.tag'), $this->placeholders ?? []);
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.tag');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex flex-wrap items-center gap-1 border-0 px-2 py-1.5 pr-4',
            'label' => [
                'base' => 'inline-flex h-6 items-center rounded-lg bg-gray-100 px-1 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200 space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600',
                'icon' => 'h-4 w-4 cursor-pointer text-red-500',
            ],
            'input' => [
                'base' => 'flex grow items-center border-0 border-transparent py-0 px-1 text-gray-600 outline-hidden focus:outline-hidden focus:ring-0 bg-transparent!',
                ...collect($this->input())->except('base')->toArray(),
            ],
            'button' => [
                'wrapper' => 'text-secondary-500 dark:text-dark-400 absolute inset-y-0 right-2 flex cursor-pointer items-center',
                'icon' => 'h-5 w-5 hover:text-red-500',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                // z-40! keeps the panel under the Dialog and Modal overlays, which also sit at z-50.
                'class' => 'overflow-auto z-40!',
            ],
            'box' => [
                'wrapper' => 'custom-scrollbar max-h-60 w-full overflow-auto text-base focus:outline-hidden sm:text-sm',
                'item' => 'dark:text-dark-300 dark:hover:bg-dark-500 relative cursor-pointer select-none truncate px-3 py-2 text-gray-700 hover:bg-gray-100',
                'highlighted' => 'bg-gray-100 dark:bg-dark-500',
                'empty' => 'block w-full px-3 py-2 text-sm text-gray-600 dark:text-dark-300',
                'after' => 'dark:border-dark-600 border-t border-gray-200',
            ],
            'error' => $this->error(),
        ]);
    }

    /** @throws Exception */
    protected function validate(): void
    {
        if ($this->lazy !== null && $this->lazy < 1) {
            __ts_validation_exception($this, 'The [lazy] must be greater than zero.');
        }

        if (! $this->prefix) {
            return;
        }

        if (strlen($this->prefix) > 1) {
            __ts_validation_exception($this, 'The [prefix] must be a single character.');
        }
    }
}

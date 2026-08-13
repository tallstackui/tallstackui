<?php

namespace TallStackUi\Components\Swap;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\SwapRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('swap')]
#[PassThroughRuntime(SwapRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $select = null,
        public ?bool $block = false,
        public ?bool $preview = null,
        public ?bool $vertical = null,
        public ?bool $loop = null,
        public ?string $tooltip = null,
        public ?bool $invalidate = null,
        #[SkipDebug]
        public Collection|array $options = [],
    ) {
        $this->preview ??= (bool) __ts_get_component_configuration(self::class, 'preview');

        $this->vertical ??= (bool) __ts_get_component_configuration(self::class, 'vertical');

        $this->loop ??= (bool) (__ts_get_component_configuration(self::class, 'loop') ?? true);
    }

    public function blade(): View
    {
        return view('ts-ui::components.swap.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative',
            'input' => [
                'base' => 'inline-flex items-center gap-x-1 rounded-md p-1.5 text-sm ring-1 focus-within:ring-2',
                'color' => 'dark:ring-dark-600/50 dark:text-dark-300 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 text-gray-600 ring-gray-200',
                'background' => 'dark:bg-dark-800 bg-white',
                'error' => $this->error(),
                'block' => 'flex w-full',
                'locked' => 'dark:bg-dark-900 cursor-not-allowed bg-gray-100 opacity-60',
            ],
            'button' => [
                'base' => 'dark:text-dark-400 dark:hover:text-dark-200 flex shrink-0 cursor-pointer items-center justify-center text-gray-400 transition-colors hover:text-gray-600 focus:outline-hidden disabled:cursor-default disabled:opacity-40 disabled:hover:text-gray-400 dark:disabled:hover:text-dark-400',
                'icon' => 'h-4 w-4',
            ],
            'viewport' => [
                'base' => 'relative h-6 overflow-hidden rounded select-none',
                'draggable' => 'cursor-grab active:cursor-grabbing',
                'mask' => '[mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]',
                'touch' => [
                    'horizontal' => 'touch-pan-y',
                    'vertical' => 'touch-pan-x',
                ],
                'width' => [
                    'base' => 'w-28',
                    'block' => 'w-full flex-1',
                    'preview' => 'w-56',
                ],
            ],
            'track' => [
                'base' => 'flex h-full will-change-transform',
                'transition' => 'transition-transform duration-300 ease-out',
                'vertical' => 'flex-col',
            ],
            'item' => [
                'base' => 'flex h-full shrink-0 items-center justify-center truncate leading-6',
                'fade' => [
                    'active' => 'scale-100 opacity-100',
                    'inactive' => 'scale-90 opacity-60',
                ],
                'sizes' => [
                    'base' => 'w-full',
                    'preview' => 'w-1/3',
                ],
                'transition' => 'transition-[opacity,transform] duration-300 ease-out',
            ],
        ]);
    }

    protected function setup(): void
    {
        $this->options = $this->options instanceof Collection
            ? $this->options->values()->toArray()
            : array_values($this->options);

        [$label, $value] = $this->keys();

        $this->options = collect($this->options)->map(function (mixed $option) use ($label, $value): array {
            if (! is_array($option)) {
                return ['label' => (string) $option, 'value' => $option];
            }

            if (! array_key_exists($label, $option)) {
                __ts_validation_exception($this, "The key [$label] is missing in the options array.");
            }

            if (! array_key_exists($value, $option)) {
                __ts_validation_exception($this, "The key [$value] is missing in the options array.");
            }

            return ['label' => (string) $option[$label], 'value' => $option[$value]];
        })->toArray();
    }

    protected function validate(): void
    {
        if ($this->preview && $this->vertical) {
            __ts_validation_exception($this, 'The [preview] and [vertical] cannot be used together.');
        }
    }

    private function keys(): array
    {
        $keys = ['label' => 'label', 'value' => 'value'];

        $this->select ??= __ts_get_component_configuration(self::class, 'select');

        foreach (explode('|', (string) $this->select) as $part) {
            $segments = explode(':', $part, 2);

            if (count($segments) === 2 && array_key_exists($segments[0], $keys)) {
                $keys[$segments[0]] = $segments[1];
            }
        }

        return [$keys['label'], $keys['value']];
    }
}

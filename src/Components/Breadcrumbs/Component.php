<?php

namespace TallStackUi\Components\Breadcrumbs;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry;
use TallStackUi\TallStackUiComponent;
use Throwable;

#[SoftCustomization('breadcrumbs')]
class Component extends TallStackUiComponent implements Customization
{
    /** @throws CircularDependencyException|BindingResolutionException */
    public function __construct(
        public array|Collection|null $items = null,
        public ?string $separator = '/',
        public ?string $separatorClass = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $lg = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        if ($this->items instanceof Collection) {
            $this->items = $this->items->toArray();
        }

        if ($this->items === null) {
            $this->items = app(BreadcrumbRegistry::class)->resolve();
        }

        $this->items = $this->links($this->items);
        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : ($this->xs ? 'xs' : 'md'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.breadcrumbs.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center',
            'list' => [
                'class' => 'flex items-center',
                'sizes' => [
                    'xs' => 'gap-0.5',
                    'sm' => 'gap-0.5',
                    'md' => 'gap-1',
                    'lg' => 'gap-1.5',
                ],
            ],
            'separator' => [
                'wrapper' => 'flex items-center',
                'text' => [
                    'class' => 'text-gray-400 dark:text-dark-400 select-none',
                    'sizes' => [
                        'xs' => 'text-xs mx-0.5',
                        'sm' => 'text-xs mx-0.5',
                        'md' => 'text-sm mx-1',
                        'lg' => 'text-base mx-1',
                    ],
                ],
                'icon' => [
                    'class' => 'text-gray-400 dark:text-dark-400 shrink-0',
                    'sizes' => [
                        'xs' => 'w-3 h-3 mx-0.5',
                        'sm' => 'w-3.5 h-3.5 mx-0.5',
                        'md' => 'w-4 h-4 mx-0.5',
                        'lg' => 'w-5 h-5 mx-1',
                    ],
                ],
            ],
            'item' => [
                'wrapper' => 'flex items-center',
                'link' => [
                    'class' => 'inline-flex items-center text-gray-500 dark:text-dark-300 transition-colors hover:text-gray-700 dark:hover:text-dark-100',
                    'sizes' => [
                        'xs' => 'text-xs',
                        'sm' => 'text-xs',
                        'md' => 'text-sm',
                        'lg' => 'text-base',
                    ],
                ],
                'current' => [
                    'class' => 'inline-flex items-center font-medium text-gray-700 dark:text-dark-200',
                    'sizes' => [
                        'xs' => 'text-xs',
                        'sm' => 'text-xs',
                        'md' => 'text-sm',
                        'lg' => 'text-base',
                    ],
                ],
                'icon' => [
                    'class' => 'shrink-0',
                    'sizes' => [
                        'xs' => 'w-3 h-3 mr-0.5',
                        'sm' => 'w-3.5 h-3.5 mr-0.5',
                        'md' => 'w-4 h-4 mr-1',
                        'lg' => 'w-5 h-5 mr-1.5',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Resolve named routes to URLs in breadcrumb items.
     */
    private function links(array $items): array
    {
        return array_map(function (array $item): array {
            if (isset($item['link']) && ! str_starts_with($item['link'], '/') && ! str_starts_with($item['link'], 'http')) {
                try {
                    $item['link'] = route($item['link']);
                } catch (Throwable) {
                    //
                }
            }

            return $item;
        }, $items);
    }
}

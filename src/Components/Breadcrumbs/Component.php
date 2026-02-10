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

#[SoftCustomization('breadcrumbs')]
class Component extends TallStackUiComponent implements Customization
{
    /** @throws CircularDependencyException|BindingResolutionException */
    public function __construct(
        public array|Collection|null $items = null,
        public ?string $separator = '/',
        public ?string $separatorClass = null,
        #[SkipDebug]
        public bool $separatorIsIcon = false,
    ) {
        if ($this->items instanceof Collection) {
            $this->items = $this->items->toArray();
        }

        if ($this->items === null) {
            $this->items = app(BreadcrumbRegistry::class)->resolve();
        }

        $this->separatorIsIcon = str_contains($this->separator, '-');
    }

    public function blade(): View
    {
        return view('ts-ui::components.breadcrumbs.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center',
            'list' => 'flex items-center gap-1',
            'separator' => [
                'wrapper' => 'flex items-center',
                'text' => 'text-sm text-gray-400 dark:text-dark-400 mx-1 select-none',
                'icon' => 'w-4 h-4 text-gray-400 dark:text-dark-400 mx-0.5 shrink-0',
            ],
            'item' => [
                'wrapper' => 'flex items-center',
                'link' => 'text-sm text-gray-500 dark:text-dark-300 transition-colors hover:text-gray-700 dark:hover:text-dark-100',
                'current' => 'text-sm font-medium text-gray-700 dark:text-dark-200',
                'icon' => 'w-4 h-4 mr-1 shrink-0',
            ],
        ]);
    }
}

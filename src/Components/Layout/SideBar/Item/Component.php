<?php

namespace TallStackUi\Components\Layout\SideBar\Item;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('sideBar.item')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?string $route = null,
        public ?string $href = null,
        public ?string $match = null,
        public ComponentSlot|string|null $icon = null,
        public ComponentSlot|string|null $badge = null,
        public ?string $badgeColor = 'red',
        public ?bool $current = null,
        public ?bool $opened = null,
        public Closure|bool $visible = true,
    ) {
        $this->visible = value($this->visible);
    }

    public function blade(): View
    {
        return view('ts-ui::components.layout.sidebar.item');
    }

    public function customization(): array
    {
        return Arr::dot([
            'group' => [
                'button' => 'text-primary-500 hover:bg-primary-50/50 dark:hover:bg-dark-600/50 flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold transition-all dark:text-white cursor-pointer',
                'icon' => [
                    'base' => 'text-primary-500 h-6 w-6 shrink-0 dark:text-white',
                    'collapse' => [
                        'base' => 'text-primary-500 ml-auto h-4 w-4 shrink-0 transition-all dark:text-white',
                        'rotate' => 'text-primary-500 rotate-180 dark:text-white',
                    ],
                ],
                'group' => 'px-2 pl-5',
                'text' => 'whitespace-nowrap overflow-hidden transition-all duration-150',
                'text.visible' => 'opacity-100 max-w-48',
                'text.hidden' => 'opacity-0 max-w-0',
                'badge' => 'overflow-hidden transition-all duration-150',
                'badge.visible' => 'opacity-100 scale-100',
                'badge.hidden' => 'opacity-0 scale-0',
            ],
            'item' => [
                'wrapper' => [
                    'base' => 'py-0.5',
                    'border' => 'border-outline border-l border-primary-200 dark:border-dark-500 pl-2',
                ],
                'state' => [
                    'base' => 'group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold transition-all',
                    'current' => 'text-primary-500 bg-primary-50 dark:bg-dark-600 dark:text-white',
                    'normal' => 'text-primary-500 hover:bg-primary-50 dark:hover:bg-dark-600 dark:text-white',
                    'collapsed' => 'justify-center text-center',
                ],
                'icon' => 'text-primary-500 h-6 w-6 shrink-0 transition-all dark:text-white',
                'text' => 'whitespace-nowrap overflow-hidden transition-all duration-150',
                'text.visible' => 'opacity-100 max-w-48',
                'text.hidden' => 'opacity-0 max-w-0',
                'badge' => 'ml-auto overflow-hidden transition-all duration-150',
                'badge.visible' => 'opacity-100 scale-100',
                'badge.hidden' => 'opacity-0 scale-0',
            ],
        ]);
    }

    final public function matches(): bool
    {
        if ($this->route) {
            $str = str($this->route);

            // If start with / and does not contain the app.url,
            // then we assume it is a basic url: /dashboard
            if ($str->startsWith('/') && ! $str->contains(config('app.url'))) {
                return url($this->route) === url(request()->url());
            }

            $route = Route::getCurrentRoute();

            // If contains the app.url, then we assume it is a
            // route created in the route helper: route('dashboard')
            return $this->route === route(
                $route->getName(),
                // This is necessary to correctly resolve routes of a view type
                $route->getActionMethod() === "\Illuminate\Routing\ViewController" ? [] : $route->parameters()
            );
        }

        return $this->match && request()->routeIs($this->match);
    }
}

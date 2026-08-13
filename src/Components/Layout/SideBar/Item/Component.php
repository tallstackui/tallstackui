<?php

namespace TallStackUi\Components\Layout\SideBar\Item;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
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
                'button' => 'text-primary-500 hover:bg-primary-50/50 dark:hover:bg-dark-600/50 flex w-full items-center rounded-md p-2 text-left text-sm font-semibold transition-all dark:text-white cursor-pointer',
                'button.gap' => 'gap-x-3',
                'button.collapsed' => 'relative justify-center',
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
                'badge.visible' => 'max-w-24 opacity-100',
                'badge.hidden' => 'max-w-0 opacity-0',
                'dot' => 'absolute top-1.5 left-1/2 translate-x-1.5',
                'flyout' => [
                    'wrapper' => Arr::toCssClasses([collect(app(Floating::class)->customization())->get('wrapper'), 'w-60', 'overflow-hidden']),
                    'scroll' => 'max-h-[min(24rem,calc(100dvh-2rem))] overflow-y-auto px-2 pb-2',
                    'scrollbar.thin' => 'soft-scrollbar',
                    'scrollbar.thick' => 'custom-scrollbar',
                    'header' => 'dark:bg-dark-800 text-gray-500 dark:text-dark-300 sticky top-0 -mx-2 bg-white px-2 pt-2 pb-1 text-xs font-semibold tracking-wide uppercase',
                    'items' => 'flex flex-col gap-y-0.5',
                ],
            ],
            'item' => [
                'wrapper' => [
                    'base' => 'py-0.5',
                    'border' => 'border-outline border-l border-primary-200 dark:border-dark-700 pl-2',
                ],
                'state' => [
                    'base' => 'group flex items-center rounded-md p-2 text-sm font-semibold transition-all',
                    'gap' => 'gap-x-3',
                    'current' => 'text-primary-500 bg-primary-50 dark:bg-dark-700 dark:text-white',
                    'normal' => 'text-primary-500 hover:bg-primary-50 dark:hover:bg-dark-700 dark:text-white',
                    'collapsed' => 'relative justify-center text-center',
                ],
                'icon' => 'text-primary-500 h-6 w-6 shrink-0 transition-all dark:text-white',
                'text' => 'whitespace-nowrap overflow-hidden transition-all duration-150',
                'text.visible' => 'opacity-100 max-w-48',
                'text.hidden' => 'opacity-0 max-w-0',
                'badge' => 'overflow-hidden transition-all duration-150',
                'badge.visible' => 'ml-auto max-w-24 opacity-100',
                'badge.hidden' => 'max-w-0 opacity-0',
                'dot' => 'absolute top-1.5 left-1/2 translate-x-1.5',
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

            // Error views have no current route, and a route declared without ->name()
            // has no name to feed back into the route() helper. Both would throw and
            // take the whole page down, so an item simply cannot match either one.
            if (blank($name = $route?->getName())) {
                return false;
            }

            // If contains the app.url, then we assume it is a
            // route created in the route helper: route('dashboard')
            return $this->route === route(
                $name,
                // This is necessary to correctly resolve routes of a view type
                $route->getActionMethod() === "\Illuminate\Routing\ViewController" ? [] : $route->parameters()
            );
        }

        return $this->match && request()->routeIs($this->match);
    }
}

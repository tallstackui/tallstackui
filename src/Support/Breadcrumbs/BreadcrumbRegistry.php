<?php

namespace TallStackUi\Support\Breadcrumbs;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as PageRoute;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Throwable;

class BreadcrumbRegistry
{
    /**
     * The registered breadcrumb definitions keyed by route name.
     *
     * @var array<string, Closure>
     */
    protected array $definitions = [];

    /**
     * Register a breadcrumb definition for a named route.
     */
    public function for(string $route, Closure $callback): self
    {
        $this->definitions[$route] = $callback;

        return $this;
    }

    /**
     * Check whether a breadcrumb definition exists for the given route name.
     */
    public function has(string $route): bool
    {
        return isset($this->definitions[$route]);
    }

    /**
     * Resolve the full breadcrumb items array for a given route (or the current route).
     *
     * Resolution walks the parent chain recursively: if a trail declares
     * a parent via ->parent('route.name'), the parent's items are resolved
     * first and prepended, producing the complete hierarchical trail.
     *
     * Route model bindings (e.g., User $user) are automatically injected
     * into callbacks via app()->call(), so type-hinted parameters resolve
     * from the current request's route parameters.
     *
     * @return array<int, array{label: string, link?: string, icon?: string, tooltip?: string}>
     */
    public function resolve(?string $route = null): array
    {
        return $this->trail($route, $this->current());
    }

    /**
     * The route of the page being rendered. A Livewire update runs on its
     * own route, so the page one is matched again from the URL the
     * component was first rendered at.
     */
    private function current(): ?PageRoute
    {
        if (! Livewire::isLivewireRequest()) {
            return Route::current();
        }

        try {
            $route = Route::getRoutes()->match(Request::create(Livewire::originalUrl(), Livewire::originalMethod()));

            Route::substituteBindings($route);
            Route::substituteImplicitBindings($route);

            return $route;
        } catch (Throwable) {
            return Route::current();
        }
    }

    /**
     * @return array<int, array{label: string, link?: string, icon?: string, tooltip?: string}>
     */
    private function trail(?string $route, ?PageRoute $current): array
    {
        $route ??= $current?->getName();

        if (! $route || ! isset($this->definitions[$route])) {
            return [];
        }

        $trail = new BreadcrumbTrail;

        $parameters = $current ? $current->parameters() : [];

        app()->call($this->definitions[$route], array_merge(['trail' => $trail], $parameters));

        $items = [];

        if ($parent = $trail->parentRoute()) {
            $items = $this->trail($parent, $current);
        }

        return array_merge($items, $trail->items());
    }
}

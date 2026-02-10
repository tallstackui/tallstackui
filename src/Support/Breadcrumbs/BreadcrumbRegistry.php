<?php

namespace TallStackUi\Support\Breadcrumbs;

use Closure;
use Illuminate\Support\Facades\Route;

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
        $route ??= Route::currentRouteName();

        if (! $route || ! isset($this->definitions[$route])) {
            return [];
        }

        $trail = new BreadcrumbTrail;

        $current = Route::current();
        $parameters = $current ? $current->parameters() : [];

        app()->call($this->definitions[$route], array_merge(['trail' => $trail], $parameters));

        $items = [];

        if ($parent = $trail->parentRoute()) {
            $items = $this->resolve($parent);
        }

        return array_merge($items, $trail->items());
    }
}

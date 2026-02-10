<?php

namespace TallStackUi\Support\Breadcrumbs;

class BreadcrumbTrail
{
    /**
     * The accumulated breadcrumb items for this trail.
     *
     * @var array<int, array{label: string, link?: string, icon?: string, tooltip?: string}>
     */
    protected array $items = [];

    /**
     * The parent route name whose items are prepended during resolution.
     */
    protected ?string $parent = null;

    /**
     * Add a breadcrumb item to the trail.
     */
    public function add(string $label, ?string $link = null, ?string $icon = null, ?string $tooltip = null): self
    {
        $this->items[] = array_filter([
            'label' => $label,
            'link' => $link,
            'icon' => $icon,
            'tooltip' => $tooltip,
        ], fn (mixed $value) => $value !== null);

        return $this;
    }

    /**
     * Get the breadcrumb items accumulated in this trail.
     *
     * @return array<int, array{label: string, link?: string, icon?: string, tooltip?: string}>
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Set a parent route whose breadcrumb items will be prepended before this trail's items during resolution.
     */
    public function parent(string $route): self
    {
        $this->parent = $route;

        return $this;
    }

    /**
     * Get the parent route name, if one was set.
     */
    public function parentRoute(): ?string
    {
        return $this->parent;
    }
}

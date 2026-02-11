# Layout Component Improvements

Summary of changes made to the Layout component system.

## 1. Collapse Button Moved to Header

The sidebar collapse toggle button has been moved from inside the sidebar to the header component. This prevents conflicts with the sidebar `brand` slot and provides a cleaner UX.

The collapse button appears automatically in the header when the sidebar has the `collapsible` attribute. No additional configuration is needed on the header.

### How It Works

The sidebar sets `$store['tsui.side-bar'].collapsible = true` on the Alpine store, and the header reads this reactively via `x-show`. Communication happens entirely through the Alpine store — no PHP props or `@aware` chains needed.

### Default Icon

The collapse button now uses the `bars-4` icon (same as the mobile hamburger) for visual consistency. This replaces the previous `chevron-left`/`chevron-right` icons.

### Soft Customization

The collapse button customization keys are now on the **Header** component (previously on SideBar):

```php
TallStackUi::personalize()
    ->layout('header')
    ->block('collapse.class', 'hidden md:block cursor-pointer')
    ->block('collapse.icon', 'bars-4')
    ->block('collapse.icon.size', 'h-6 w-6 text-gray-500 dark:text-white');
```

The following SideBar customization keys have been **removed**:
- `desktop.collapse.wrapper`
- `desktop.collapse.buttons.expanded.icon`
- `desktop.collapse.buttons.expanded.class`
- `desktop.collapse.buttons.collapsed.icon`
- `desktop.collapse.buttons.collapsed.class`

## 2. Sidebar Footer Slot

A new `footer` slot has been added to the sidebar component. It renders at the bottom of the sidebar, separated from the menu items by a border.

The footer is always visible — it does not scroll with the items. The items area scrolls independently while the footer stays pinned at the bottom.

When the sidebar is collapsed, the footer content is clipped via `overflow-hidden` to prevent text from overflowing outside the narrow sidebar.

### Usage

```blade
<x-side-bar collapsible>
    <x-side-bar.item text="Home" icon="home" route="/" />
    <x-side-bar.item text="Settings" icon="cog-6-tooth" route="/settings" />

    <x-slot:footer>
        <div class="flex items-center gap-2">
            <x-avatar sm />
            <span>John Doe</span>
        </div>
    </x-slot:footer>
</x-side-bar>
```

### Soft Customization

```php
TallStackUi::personalize()
    ->sideBar()
    ->block('desktop.footer', 'shrink-0 overflow-hidden border-t border-gray-200 dark:border-dark-600 px-2 py-4')
    ->block('mobile.footer', 'shrink-0 border-t border-gray-200 dark:border-dark-600 px-2 py-4');
```

## 3. Smooth Collapse Animation

The sidebar collapse/expand animation has been improved. Previously, the sidebar width snapped instantly while text faded separately, causing a visible flash of partially-visible text.

### Changes

- **Sidebar width**: Added `transition-[width] duration-300` to the desktop sidebar wrapper for smooth width animation
- **Main content padding**: Added `transition-[padding] duration-300` to the layout wrapper so content padding animates in sync
- **Item/group text**: Replaced Alpine `x-show`/`x-transition` with CSS class-based transitions (`opacity` + `max-width`) at `duration-150`
- **Separator text**: Same CSS transition pattern applied to separator labels
- **Group chevron**: The expand/collapse chevron icon on group items is now hidden when the sidebar is collapsed
- **Group sub-items**: Nested items (inside groups) are hidden when the sidebar is collapsed, preventing empty space with only the vertical border line visible

The text transitions at 150ms (faster) while the sidebar width transitions at 300ms. This means text disappears before the sidebar shrinks, and appears as the sidebar expands — eliminating the flash.

### New Customization Keys (SideBar Item)

```php
TallStackUi::personalize()
    ->sideBar('item')
    ->block('item.text.visible', 'opacity-100 max-w-48')
    ->block('item.text.hidden', 'opacity-0 max-w-0')
    ->block('group.text.visible', 'opacity-100 max-w-48')
    ->block('group.text.hidden', 'opacity-0 max-w-0');
```

### New Customization Keys (SideBar Separator)

```php
TallStackUi::personalize()
    ->sideBar('separator')
    ->block('simple.base.visible', 'opacity-100 max-w-48')
    ->block('simple.base.hidden', 'opacity-0 max-w-0');
```

## 4. Sidebar Scroll Structure

The sidebar layout has been restructured so that only the items area scrolls, while the brand and footer remain fixed.

### Structure

```
sidebar container (flex col, no overflow)
  brand (shrink-0, fixed at top)
  items wrapper (flex-1, min-h-0, overflow-y-auto)  ← only this scrolls
  footer (shrink-0, fixed at bottom)
```

The `min-h-0` on the items wrapper is essential — without it, the flex item won't shrink below its content size and `overflow-y-auto` won't activate.

Horizontal padding (`px-2`) is applied to the items list (`<ul>`) rather than the sidebar container. This ensures:
- The scrollbar sits flush against the sidebar border
- The footer `border-t` spans edge-to-edge
- Items still have horizontal padding

### New Customization Keys (SideBar)

```php
TallStackUi::personalize()
    ->sideBar()
    ->block('desktop.wrapper.items', 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5')
    ->block('mobile.wrapper.items', 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5');
```

## Full Layout Example

```blade
<body>
    <x-layout>
        <x-slot:header>
            <x-layout.header>
                <x-slot:left>
                    <span class="text-lg font-bold">MyApp</span>
                </x-slot:left>
                <x-slot:right>
                    <x-dropdown text="Hello, {{ auth()->user()->name }}!">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown.items text="Logout"
                                onclick="event.preventDefault(); this.closest('form').submit();" />
                        </form>
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-side-bar collapsible>
                <x-slot:brand>
                    <div class="flex h-16 items-center px-4">
                        <img src="/logo.svg" alt="Logo" class="h-8" />
                    </div>
                </x-slot:brand>

                <x-side-bar.separator text="Navigation" />
                <x-side-bar.item text="Dashboard" icon="home" route="/" />
                <x-side-bar.item text="Documents" icon="document-text" route="/documents" />

                <x-side-bar.separator text="Management" />
                <x-side-bar.item text="Settings" icon="cog-6-tooth">
                    <x-side-bar.item text="Profile" route="/settings/profile" />
                    <x-side-bar.item text="Account" route="/settings/account" />
                    <x-side-bar.item text="Billing" route="/settings/billing" />
                </x-side-bar.item>
                <x-side-bar.item text="Users" icon="users" route="/users" />

                <x-slot:footer>
                    <div class="flex items-center gap-3 px-2">
                        <x-avatar sm />
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                </x-slot:footer>
            </x-side-bar>
        </x-slot:menu>

        <div class="p-4">
            <h1 class="text-2xl font-bold">Page Content</h1>
            <p>Your main content goes here.</p>
        </div>
    </x-layout>
</body>
```

## Files Modified

| File | Change |
|------|--------|
| `src/Components/Layout/SideBar/Main/collapse.js` | Added `collapsible` property to Alpine store |
| `src/Components/Layout/SideBar/Main/Component.php` | Added `$footer` slot, footer/items customization keys, `transition-[width]` on desktop wrapper, removed collapse keys, restructured scroll layout |
| `src/Components/Layout/SideBar/Item/Component.php` | Added `text.visible`/`text.hidden` customization keys for items and groups |
| `src/Components/Layout/SideBar/Separator/Component.php` | Added `base.visible`/`base.hidden` customization keys, transition classes |
| `src/Components/Layout/Header/Component.php` | Added `collapse.*` customization keys |
| `src/Components/Layout/Main/Component.php` | Added `transition-[padding] duration-300` to wrapper padding classes |
| `src/resources/views/components/layout/sidebar/main.blade.php` | Removed collapse button, added `x-init` for store flag, restructured to separate scroll area from footer, added footer slots |
| `src/resources/views/components/layout/sidebar/item.blade.php` | Replaced `x-show`/`x-transition` with CSS class-based transitions, hide group sub-items when collapsed |
| `src/resources/views/components/layout/sidebar/separator.blade.php` | Replaced `x-text` truncation with CSS transitions |
| `src/resources/views/components/layout/header.blade.php` | Added collapse toggle button via Alpine store |

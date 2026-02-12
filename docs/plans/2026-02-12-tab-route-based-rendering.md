# Tab Route-Based Rendering Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add `when`, `navigate`, and `navigateHover` props to `<x-tab.items>` so tab content only renders when a URL matches, and clicking navigates to the URL.

**Architecture:** Each `<x-tab.items>` optionally receives a `when` URL. Server-side, the Runtime compares it against `request()->url()` — only matching tabs render slot content. Client-side, the `when` URL and navigation mode are pushed into Alpine's `tabs` array, and click/keypress/select handlers navigate instead of client-side switching.

**Tech Stack:** PHP 8.1, Blade, Alpine.js, Livewire, PestPHP

---

### Task 1: Add props to Items Component

**Files:**
- Modify: `src/Components/Tab/Items/Component.php`

**Step 1: Add `when`, `navigate`, `navigateHover` props**

```php
public function __construct(
    public ?string $tab = null,
    public ?string $title = null,
    public ?string $when = null,
    public ?bool $navigate = null,
    public ?bool $navigateHover = null,
    #[SkipDebug]
    public ComponentSlot|string|null $left = null,
    #[SkipDebug]
    public ComponentSlot|string|null $right = null,
) {
    //
}
```

**Step 2: Commit**

```bash
git add src/Components/Tab/Items/Component.php
git commit -m "feat(tab): add when, navigate, navigateHover props to Items"
```

---

### Task 2: Add URL matching to TabItemsRuntime

**Files:**
- Modify: `src/Support/Runtime/Components/TabItemsRuntime.php`

**Step 1: Write failing feature test**

Add to `src/Components/Tab/FeatureTest.php`:

```php
it('can render with when matching current url', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" when="'.$url.'">Matched Content</x-tab.items><x-tab.items tab="B" when="https://not-matching.test/other">Hidden Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('Matched Content')
        ->not->toContain('Hidden Content');
});
```

**Step 2: Run test to verify it fails**

Run: `./vendor/bin/pest --filter="can_render_with_when_matching_current_url" --group=Feature`
Expected: FAIL — both contents render because `when` logic doesn't exist yet.

**Step 3: Update Runtime with URL matching and navigation props**

```php
<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class TabItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var ComponentSlot|string|null $right */
        $right = $this->data('right');

        /** @var ComponentSlot|string|null $left */
        $left = $this->data('left');

        /** @var string|null $when */
        $when = $this->data('when');

        return [
            'content' => [
                'right' => is_string($right) ? $right : ($right?->toHtml() ?? null),
                'left' => is_string($left) ? $left : ($left?->toHtml() ?? null),
            ],
            'shouldRender' => ! $when || rtrim(request()->url(), '/') === rtrim($when, '/'),
        ];
    }
}
```

**Step 4: Update items.blade.php — conditional slot rendering + Alpine data**

```blade
<div x-show="selected === @js($tab)" role="tabpanel" x-init="tabs.push({ tab: @js($tab), title: @js($title), right: @js($content['right']), left: @js($content['left']), when: @js($when), navigate: @js((bool) $navigate), navigateHover: @js((bool) $navigateHover) }); @if($shouldRender && $when) selected = @js($tab); @endif" aria-labelledby="{{ $tab }}">
    @if ($shouldRender)
        {{ $slot }}
    @endif
</div>
```

**Step 5: Run test to verify it passes**

Run: `./vendor/bin/pest --filter="can_render_with_when_matching_current_url" --group=Feature`
Expected: PASS

**Step 6: Commit**

```bash
git add src/Support/Runtime/Components/TabItemsRuntime.php src/resources/views/components/tab/items.blade.php
git commit -m "feat(tab): add URL matching in Runtime and conditional slot rendering"
```

---

### Task 3: Add navigation to main.blade.php

**Files:**
- Modify: `src/resources/views/components/tab/main.blade.php`

**Step 1: Update click handler on `<li>` tab headers**

Replace the current `x-on:click` and `x-on:keypress.enter` on the `<li>`:

```blade
x-on:click="if (item.when) { if (item.navigate || item.navigateHover) { Livewire.navigate(item.when); } else { window.location.href = item.when; } } else { selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}})); }"
x-on:keypress.enter="if (item.when) { if (item.navigate || item.navigateHover) { Livewire.navigate(item.when); } else { window.location.href = item.when; } } else { selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}})); }"
```

**Step 2: Add hover prefetch for navigateHover**

Add to the `<li>` element:

```blade
x-on:mouseenter="if (item.when && item.navigateHover && !item._prefetched) { let link = document.createElement('link'); link.rel = 'prefetch'; link.href = item.when; document.head.appendChild(link); item._prefetched = true; }"
```

**Step 3: Update mobile select change handler**

Replace the current `x-on:change` on the `<select>`:

```blade
x-on:change="let t = tabs.find(i => i.tab === selected); if (t && t.when) { if (t.navigate || t.navigateHover) { Livewire.navigate(t.when); } else { window.location.href = t.when; } } else { $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: selected}})); }"
```

**Step 4: Commit**

```bash
git add src/resources/views/components/tab/main.blade.php
git commit -m "feat(tab): add route-based navigation in click, keypress, and select handlers"
```

---

### Task 4: Write remaining feature tests

**Files:**
- Modify: `src/Components/Tab/FeatureTest.php`

**Step 1: Add tests**

```php
it('does not render slot when url does not match', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A" when="https://not-matching.test/other">
            Should Not Render
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->not->toContain('Should Not Render');
});

it('renders slot when no when attribute is set', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A">
            Always Renders
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('Always Renders');
});

it('passes navigate to alpine data', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" :when="\''. $url .'\'" navigate>Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('navigate: true');
});

it('passes navigateHover to alpine data', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" :when="\''. $url .'\'" navigate-hover>Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('navigateHover: true');
});

it('auto selects tab when url matches', function () {
    $url = request()->url();

    $component = '<x-tab selected="B"><x-tab.items tab="A" when="'.$url.'">Content A</x-tab.items><x-tab.items tab="B">Content B</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain("selected = 'A'");
});
```

**Step 2: Run all feature tests**

Run: `./vendor/bin/pest --filter="Tab" --group=Feature`
Expected: All pass

**Step 3: Commit**

```bash
git add src/Components/Tab/FeatureTest.php
git commit -m "test(tab): add feature tests for route-based rendering"
```

---

### Task 5: Write browser tests

**Files:**
- Modify: `src/Components/Tab/BrowserTest.php`

**Step 1: Add browser test for `when` rendering**

```php
#[Test]
public function can_render_only_matching_tab_content_with_when(): void
{
    Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            $currentUrl = request()->url();

            return <<<HTML
            <div>
                <x-tab>
                    <x-tab.items tab="current" title="Current" when="{$currentUrl}">
                        Matched Tab Content
                    </x-tab.items>
                    <x-tab.items tab="other" title="Other" when="https://not-matching.test/other">
                        Other Tab Content
                    </x-tab.items>
                </x-tab>
            </div>
            HTML;
        }
    })
        ->waitForLivewireToLoad()
        ->assertSee('Current')
        ->assertSee('Other')
        ->assertSee('Matched Tab Content')
        ->assertDontSee('Other Tab Content');
}
```

**Step 2: Add browser test for `when` with `navigate`**

```php
#[Test]
public function can_navigate_to_url_when_clicking_tab_with_when_and_navigate(): void
{
    Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            $currentUrl = request()->url();

            return <<<HTML
            <div>
                <x-tab>
                    <x-tab.items tab="current" title="Current" when="{$currentUrl}" navigate>
                        Current Content
                    </x-tab.items>
                    <x-tab.items tab="about" title="About" when="/about" navigate>
                        About Content
                    </x-tab.items>
                </x-tab>
            </div>
            HTML;
        }
    })
        ->waitForLivewireToLoad()
        ->assertSee('Current Content')
        ->assertSee('About')
        ->assertDontSee('About Content');
}
```

**Step 3: Add browser test for auto-selection via URL match**

```php
#[Test]
public function can_auto_select_tab_based_on_url_match(): void
{
    Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            $currentUrl = request()->url();

            return <<<HTML
            <div>
                <x-tab selected="other">
                    <x-tab.items tab="other" title="Other" when="https://not-matching.test/other">
                        Other Content
                    </x-tab.items>
                    <x-tab.items tab="current" title="Current" when="{$currentUrl}">
                        Current Content
                    </x-tab.items>
                </x-tab>
            </div>
            HTML;
        }
    })
        ->waitForLivewireToLoad()
        ->waitForText('Current Content')
        ->assertSee('Current Content')
        ->assertDontSee('Other Content');
}
```

**Step 4: Run browser tests**

Run: `./vendor/bin/pest --filter="can_render_only_matching_tab_content_with_when" --group=Browser`
Run: `./vendor/bin/pest --filter="can_navigate_to_url" --group=Browser`
Run: `./vendor/bin/pest --filter="can_auto_select_tab_based" --group=Browser`

**Step 5: Commit**

```bash
git add src/Components/Tab/BrowserTest.php
git commit -m "test(tab): add browser tests for route-based rendering"
```

---

### Task 6: Update documentation

**Files:**
- Modify: `.ai/components/tab/items.md`
- Modify: `.ai/components/tab/main.md`

**Step 1: Update items.md**

Add to the Attributes table:

```markdown
| when            | string\|null       | null    | URL that determines when this tab's content renders (compared to current URL) |
| navigate        | bool\|null         | null    | Use Livewire SPA navigation (`Livewire.navigate()`) when clicking this tab    |
| navigate-hover  | bool\|null         | null    | Same as `navigate` but prefetches the URL on hover                            |
```

Add a new "Route-Based Rendering" section with usage examples.

**Step 2: Update main.md**

Add a "Route-Based Tabs" section explaining the feature and showing a complete example.

**Step 3: Commit**

```bash
git add .ai/components/tab/
git commit -m "docs(tab): document route-based rendering feature"
```

---

### Task 7: Build assets and final verification

**Step 1: Run npm build**

Run: `npm run build`

**Step 2: Run all Tab tests**

Run: `./vendor/bin/pest --filter="Tab" --group=Feature`
Run: `./vendor/bin/pest --filter="can_render_only_matching\|can_navigate_to_url\|can_auto_select_tab" --group=Browser`

**Step 3: Run code formatting**

Run: `./vendor/bin/pint --parallel`

**Step 4: Final commit if any formatting changes**

```bash
git add -A
git commit -m "chore: format code"
```

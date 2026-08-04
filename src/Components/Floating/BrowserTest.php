<?php

namespace TallStackUi\Components\Floating;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function does_not_lock_the_body_when_the_configuration_is_off(): void
    {
        Livewire::visit(new class extends Component
        {
            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => false]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="Menu">
                        <x-dropdown.items text="Lorem" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the body must stay scrollable while the feature is off');
                Assert::assertNull($this->marker($browser), 'no overflow marker should be written while the feature is off');
                Assert::assertSame(0, $this->locks($browser), 'no lock should be taken while the feature is off');
            });
    }

    #[Test]
    public function does_not_release_the_lock_owned_by_a_modal(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-modal wire>
                        <x-dropdown text="Menu">
                            <x-dropdown.items text="Lorem" />
                        </x-dropdown>
                    </x-modal>

                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->click('@open')
            ->waitForText('Menu')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('modal', $this->marker($browser), 'the modal should own the lock');
            })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(function (Browser $browser): void {
                // The modal already locked the body, so the floating must not
                // overwrite the marker: whoever wrote it is the one allowed to
                // clear it.
                Assert::assertSame('modal', $this->marker($browser), 'the floating must not steal the marker from the modal');
                Assert::assertSame('hidden', $this->overflow($browser));
            })
            ->click('@tallstackui_open_dropdown')
            ->waitUntilMissingText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('hidden', $this->overflow($browser), 'closing the floating must not unlock a body owned by the modal');
                Assert::assertSame('modal', $this->marker($browser), 'the modal marker must survive the floating closing');
            });
    }

    #[Test]
    public function does_not_take_the_focus_back_when_the_user_moved_it_out_themselves(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $role = null;

            public function render(): string
            {
                return <<<'HTML'
                <div class="space-y-4">
                    <x-select.styled label="Role" wire:model="role" :options="['Admin', 'Editor']" />
                    <x-input id="last" label="Last" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_select_open_close')
            ->pause($this->paused(1))
            ->keys('@tallstackui_select_open_close', '{arrow_down}')
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertSame('LI', $this->focused($browser)['tag'], 'the arrow should move the focus into the panel');
            })
            // Clicking away closes the panel through click.outside, but the
            // focus left on its own before that. Restoring here would yank the
            // caret out of the field the user just clicked into. Driven by
            // script because the open panel covers the field.
            ->tap(fn (Browser $browser) => $browser->script(
                "const input = document.getElementById('last');
                 input.focus();
                 input.dispatchEvent(new MouseEvent('click', {bubbles: true}));"
            ))
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertSame('last', $this->focused($browser)['id'], 'the focus must stay where the user put it');
            });
    }

    #[Test]
    public function escape_closes_the_floating_without_closing_the_modal(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public ?string $role = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="open" wire:click="$set('modal', true)">Open</x-button>

                    <x-modal wire title="Edit" dusk="modal">
                        <x-select.styled dusk="select"
                                         :options="[['label' => 'Admin', 'value' => 1], ['label' => 'User', 'value' => 2]]"
                                         wire:model="role" />
                    </x-modal>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForLivewire()->click('@open')
            ->waitForText('Edit')
            ->click('@tallstackui_select_open_close')
            ->waitForText('Admin')
            ->keys('', '{escape}')
            ->pause(350)
            // The popup owns this press. Both listeners sit on window, so without
            // the claim the modal would take it too and drop the form.
            ->assertDontSee('Admin')
            ->assertSee('Edit')
            ->keys('', '{escape}')
            ->pause(350)
            ->assertDontSee('Edit');
    }

    /**
     * Two sibling floatings never stay open together, because opening one
     * fires the other's click-outside. What this guards is the handover: each
     * one holds its own reference, so cycling between them must leave the
     * count back at zero instead of leaking a reference per open.
     */
    #[Test]
    public function hands_the_lock_over_between_sibling_floatings(): void
    {
        Livewire::visit(new class extends Component
        {
            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                // Side by side, and far apart, so neither teleported popup
                // ends up covering the other trigger.
                return <<<'HTML'
                <div class="flex justify-between">
                    <x-dropdown>
                        <x-slot:action>
                            <x-button id="first" x-on:click="show = !show">First</x-button>
                        </x-slot:action>
                        <x-dropdown.items text="Lorem" />
                    </x-dropdown>

                    <x-dropdown>
                        <x-slot:action>
                            <x-button id="second" x-on:click="show = !show">Second</x-button>
                        </x-slot:action>
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->click('#first')
            ->waitForText('Lorem')
            ->tap(fn (Browser $browser) => Assert::assertSame(1, $this->locks($browser)))
            ->click('#second')
            ->waitForText('Ipsum')
            ->waitUntilMissingText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertSame(1, $this->locks($browser), 'the closing sibling must drop its reference as the other takes one');
                Assert::assertSame('hidden', $this->overflow($browser), 'the body must stay locked across the handover');
            })
            ->click('#second')
            ->waitUntilMissingText('Ipsum')
            ->tap(function (Browser $browser): void {
                Assert::assertSame(0, $this->locks($browser), 'cycling between siblings must not leak references');
                Assert::assertNotSame('hidden', $this->overflow($browser));
                Assert::assertNull($this->marker($browser));
            });
    }

    #[Test]
    public function hands_the_marker_over_when_a_modal_outlives_the_floating_that_took_it(): void
    {
        Livewire::visit(new class extends Component
        {
            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="Menu">
                        <x-dropdown.items text="Lorem" />
                    </x-dropdown>

                    <x-modal id="outliving" title="Outliving">Modal body</x-modal>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('floating', $this->marker($browser), 'the floating opened first, so it owns the marker');
            })
            // The modal opens while the floating still holds the lock, so it
            // never gets to write a marker of its own.
            ->tap(fn (Browser $browser) => $browser->script("window.\$tsui.open.modal('outliving');"))
            ->waitForText('Modal body')
            ->tap(fn (Browser $browser) => $browser->script("document.querySelector('[dusk=tallstackui_open_dropdown]').click();"))
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertSame(0, $this->locks($browser), 'the floating must drop its reference');
                Assert::assertSame('hidden', $this->overflow($browser), 'the modal still needs the body locked');
            })
            // The marker still says floating, and the modal is the last one out.
            // Bailing on a marker it does not own would lock the body forever.
            ->tap(fn (Browser $browser) => $browser->script("window.\$tsui.close.modal('outliving');"))
            ->waitUntilMissingText('Modal body')
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the body must unlock once nothing is left holding it');
                Assert::assertNull($this->marker($browser), 'the orphaned marker must not survive');
            });
    }

    #[Test]
    public function keeps_the_lock_when_a_nested_submenu_closes(): void
    {
        Livewire::visit(new class extends Component
        {
            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="Menu">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.submenu text="Deeper">
                            <x-dropdown.items text="Ipsum" />
                        </x-dropdown.submenu>
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(fn (Browser $browser) => Assert::assertSame(1, $this->locks($browser), 'the dropdown should hold a single lock'))
            ->clickAtVisibleXPath('//button[contains(., "Deeper")]')
            ->waitForText('Ipsum')
            ->tap(function (Browser $browser): void {
                // The submenu renders a floating of its own, nested inside the
                // parent one. It must add a reference instead of re-locking.
                Assert::assertSame(2, $this->locks($browser), 'the submenu should add a reference to the existing lock');
                Assert::assertSame('hidden', $this->overflow($browser));
            })
            ->clickAtVisibleXPath('//button[contains(., "Deeper")]')
            ->waitUntilMissingText('Ipsum')
            ->tap(function (Browser $browser): void {
                Assert::assertSame(1, $this->locks($browser), 'closing the submenu should drop only its own reference');
                Assert::assertSame('hidden', $this->overflow($browser), 'the body must stay locked while the parent dropdown is open');
            })
            ->click('@tallstackui_open_dropdown')
            ->waitUntilMissingText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertSame(0, $this->locks($browser));
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the last floating to close must release the lock');
                Assert::assertNull($this->marker($browser));
            });
    }

    #[Test]
    public function locks_the_body_while_a_floating_is_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="Menu">
                        <x-dropdown.items text="Lorem" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('hidden', $this->overflow($browser), 'the body should be locked while the floating is open');
                Assert::assertSame('floating', $this->marker($browser), 'the body should carry the floating overflow marker');
                Assert::assertSame(1, $this->locks($browser));
                // Floatings are deliberately kept out of the overlay registry,
                // so they never take escape and click-outside away from a modal.
                Assert::assertSame(0, $this->elements($browser), 'a floating must not join the overlay stack');
            })
            ->click('@tallstackui_open_dropdown')
            ->waitUntilMissingText('Lorem')
            ->tap(function (Browser $browser): void {
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the body scroll-lock must be restored on close');
                Assert::assertNull($this->marker($browser), 'the overflow marker must be cleared on close');
                Assert::assertSame(0, $this->locks($browser));
            });
    }

    #[Test]
    public function releases_the_lock_when_the_floating_is_removed_from_the_dom(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $mounted = true;

            public function boot(): void
            {
                config(['ts-ui.floating_scroll_lock' => true]);
            }

            public function render(): string
            {
                // The trigger sits below the button so the popup, which opens
                // downwards, cannot intercept the click on it.
                return <<<'HTML'
                <div class="flex flex-col gap-4">
                    <x-button dusk="remove" wire:click="$set('mounted', false)">Remove</x-button>

                    @if ($mounted)
                        <x-dropdown text="Menu">
                            <x-dropdown.items text="Lorem" />
                        </x-dropdown>
                    @endif
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->tap(fn (Browser $browser) => Assert::assertSame('hidden', $this->overflow($browser)))
            // Tearing the dropdown out of the DOM while it is open never runs a
            // close watcher, so the release has to happen on the teardown path.
            ->click('@remove')
            ->waitUntilMissingText('Menu')
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertSame(0, $this->locks($browser), 'the destroyed floating must drop its reference');
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the body scroll-lock must be restored when the floating is destroyed');
                Assert::assertNull($this->marker($browser));
            });
    }

    #[Test]
    public function returns_the_focus_to_the_anchor_when_the_panel_closes_holding_it(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $role = null;

            public function render(): string
            {
                return <<<'HTML'
                <div class="space-y-4">
                    <x-input id="first" label="First" />
                    <x-select.styled label="Role" wire:model="role" :options="['Admin', 'Editor']" />
                    <x-input id="last" label="Last" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_select_open_close')
            ->pause($this->paused(1))
            ->keys('@tallstackui_select_open_close', '{arrow_down}')
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                Assert::assertSame('LI', $this->focused($browser)['tag'], 'the arrow should move the focus into the teleported panel');
            })
            // The panel lives at the end of <body>, so hiding it while it holds
            // the focus hands activeElement back to <body> and the next Tab
            // restarts at the top of the document.
            ->tap(fn (Browser $browser) => $browser->script('document.activeElement.click();'))
            ->pause($this->paused(1))
            ->tap(function (Browser $browser): void {
                $focused = $this->focused($browser);

                Assert::assertNotSame('BODY', $focused['tag'], 'the closing panel must not drop the focus on the body');
                Assert::assertSame('tallstackui_select_open_close', $focused['dusk'], 'the focus belongs back on the anchor');
            });
    }

    private function elements(Browser $browser): int
    {
        return $browser->script('return (window.__tsui_elements ?? []).length;')[0];
    }

    private function focused(Browser $browser): array
    {
        return $browser->script(
            "const el = document.activeElement;

             return {
                tag: el ? el.tagName : null,
                id: el ? el.id : null,
                dusk: el ? el.getAttribute('dusk') : null,
             };"
        )[0];
    }

    private function locks(Browser $browser): int
    {
        return $browser->script('return (window.__tsui_floating_locks ?? []).length;')[0];
    }

    private function marker(Browser $browser): ?string
    {
        return $browser->script("return document.body.getAttribute('data-overflow');")[0];
    }

    private function overflow(Browser $browser): string
    {
        return $browser->script('return document.body.style.overflow;')[0];
    }
}

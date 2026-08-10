<?php

namespace TallStackUi\Components\Form\Tag;

use Facebook\WebDriver\WebDriverKeys;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_be_lazy(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <x-tag :lazy="3" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'ab')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '[]')
            ->assertSeeIn('@tagged', '[]')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'foo');
    }

    #[Test]
    public function can_be_lazy_with_prefix(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <x-tag prefix="@" :lazy="3" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', '@ab')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '[]')
            ->assertSeeIn('@tagged', '[]')
            ->type('@tags', '@foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '@foo')
            ->assertSeeIn('@tagged', '@foo');
    }

    #[Test]
    public function can_be_limited(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag :limit="2" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'baz')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar')
            ->assertDontSeeIn('@tagged', 'baz');
    }

    #[Test]
    public function can_drop_a_prefixed_option_from_the_list_once_it_is_picked(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" prefix="#" :options="['#laravel', '#livewire']" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->assertSeeIn('@tallstackui_tag_options', '#laravel')
            ->clickAtVisibleXPath('(//li[@dusk="tallstackui_tag_option"])[1]')
            ->waitForTextIn('@tagged', '#laravel')
            ->assertDontSeeIn('@tallstackui_tag_options', '#laravel')
            ->assertSeeIn('@tallstackui_tag_options', '#livewire');
    }

    #[Test]
    public function can_erase_all(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar')
            ->click('@tallstackui_tag_erase')
            ->waitUntilMissingText('foo')
            ->waitUntilMissingText('bar')
            ->assertDontSeeIn('@tagged', 'foo')
            ->assertDontSeeIn('@tagged', 'bar');
    }

    #[Test]
    public function can_fill_using_comma(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', ',')
            ->type('@tags', 'bar')
            ->keys('@tags', ',')
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar');
    }

    #[Test]
    public function can_fill_using_enter(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar');
    }

    #[Test]
    public function can_fill_using_prefixes(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag prefix="@" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '@foo')
            ->waitForTextIn('@tagged', '@bar')
            ->assertSeeIn('@tagged', '@foo')
            ->assertSeeIn('@tagged', '@bar');
    }

    #[Test]
    public function can_filter_the_floating_list_while_typing(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" :options="['php', 'laravel', 'livewire']" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->type('@tags', 'liv')
            ->waitUntilMissingText('laravel')
            ->assertSee('livewire')
            ->assertDontSee('laravel');
    }

    #[Test]
    public function can_pick_a_reusable_tag_from_the_floating_list(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" :options="['php', 'laravel', 'livewire']" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->assertSee('laravel')
            ->clickAtVisibleXPath('(//li[@dusk="tallstackui_tag_option"])[2]')
            ->waitForTextIn('@tagged', 'laravel')
            ->assertSeeIn('@tagged', 'laravel');
    }

    #[Test]
    public function can_pick_a_reusable_tag_with_the_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" :options="['php', 'laravel']" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->keys('@tags', WebDriverKeys::ARROW_DOWN, WebDriverKeys::ARROW_DOWN, WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'laravel')
            ->assertSeeIn('@tagged', 'laravel');
    }

    #[Test]
    public function can_remove_manually(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar')
            ->clickAtVisibleXPath('/html/body/div[3]/div/div/div/div[1]/span[2]/button')
            ->waitUntilMissingText('bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertDontSeeIn('@tagged', 'bar');
    }

    #[Test]
    public function can_remove_using_backspace(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'bar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', 'foo')
            ->waitForTextIn('@tagged', 'bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'bar')
            ->keys('@tags', WebDriverKeys::BACKSPACE)
            ->waitForTextIn('@tagged', 'foo')
            ->assertSeeIn('@tagged', 'foo')
            ->assertDontSeeIn('@tagged', 'bar');
    }

    #[Test]
    public function can_use_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $tags = [];

            public ?string $add = null;

            public ?string $remove = null;

            public ?array $erase = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>

                    <p dusk="add">{{ $add }}</p>
                    <p dusk="remove">{{ $remove }}</p>
                    <p dusk="erase">@json($erase)</p>
                    
                    <x-tag dusk="tags" 
                           label="Tags"
                           wire:model="tags" 
                           x-on:add="$wire.set('add', $event.detail.tag)"
                           x-on:remove="$wire.set('remove', $event.detail.tag)"
                           x-on:erase="$wire.set('erase', $event.detail.tags)" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@add', 'foo')
            ->assertSeeIn('@add', 'foo')
            ->keys('@tags', WebDriverKeys::BACKSPACE)
            ->waitForTextIn('@remove', 'foo')
            ->assertSeeIn('@remove', 'foo')
            ->type('@tags', 'foobar')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@add', 'foobar')
            ->click('@tallstackui_tag_erase')
            ->waitForTextIn('@erase', '["foobar"]')
            ->assertSeeIn('@erase', 'foobar');
    }

    #[Test]
    public function cannot_duplicate(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '["foo"]')
            ->assertSeeIn('@tagged', 'foo')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '["foo"]')
            ->assertSeeIn('@tagged', 'foo');
    }

    #[Test]
    public function cannot_duplicate_using_prefixes(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag prefix="#" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', 'foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '["#foo"]')
            ->assertSeeIn('@tagged', '#foo')
            ->type('@tags', '#foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->type('@tags', '#foo')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '["#foo"]')
            ->assertSeeIn('@tagged', '#foo');
    }

    #[Test]
    public function cannot_insert_empty_prefix(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="tagged">@json($tags)</p>
                    
                    <x-tag prefix="#" dusk="tags" wire:model.live="tags" label="Tags" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->type('@tags', '#')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '[]')
            ->assertSeeIn('@tagged', '[]')
            ->waitForText('Tags')
            ->type('@tags', '#   ')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitForTextIn('@tagged', '[]')
            ->assertSeeIn('@tagged', '[]');
    }

    #[Test]
    public function drops_an_option_already_taken_from_the_floating_list(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = ['php'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" :options="['php', 'laravel']" />
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->assertSee('laravel')
            ->assertScript('document.querySelectorAll(\'[dusk="tallstackui_tag_option"]\').length', 1);
    }

    #[Test]
    public function shows_the_empty_message_and_keeps_the_after_slot_reachable(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $tags = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="created"></p>

                    <x-tag dusk="tags" wire:model.live="tags" label="Tags" :options="['php']">
                        <x-slot:after>
                            <button type="button" dusk="create" x-on:click="$refs.created.textContent = 'clicked'">New tag</button>
                        </x-slot:after>
                    </x-tag>
                    <span x-ref="created"></span>
                </div>
                HTML;
            }
        })
            ->waitForText('Tags')
            ->click('@tags')
            ->waitFor('@tallstackui_tag_options')
            ->type('@tags', 'zzz')
            ->waitForText('No results found')
            ->assertSee('No results found')
            ->assertVisible('@create');
    }
}

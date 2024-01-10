<?php

namespace Tests\Browser\Form;

use Facebook\WebDriver\WebDriverKeys;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class TagTest extends BrowserTestCase
{
    /** @test */
    public function can_be_limited()
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

    /** @test */
    public function can_erase_all()
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

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
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
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[1]/span[2]/button')
            ->waitUntilMissingText('bar')
            ->assertSeeIn('@tagged', 'foo')
            ->assertDontSeeIn('@tagged', 'bar');
    }

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
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
}

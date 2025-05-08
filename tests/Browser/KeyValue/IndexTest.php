<?php

namespace Tests\Browser\KeyValue;

use Facebook\WebDriver\WebDriverBy;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    #[Test]
    public function can_add_row()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" />
                    </div>
                HTML;
            }
        })
            ->assertSee('No rows added.')
            ->click('@tallstackui_add_row_button')
            ->pause(500)
            ->assertPresent('@tallstackui_input_key');
    }

    #[Test]
    public function can_change_label_title()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" label="Foo" />
                    </div>
                HTML;
            }
        })
            ->assertSee('No rows added.')
            ->assertSee('Foo');
    }

    #[Test]
    public function can_change_value_title()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" value="Foo" />
                    </div>
                HTML;
            }
        })
            ->assertSee('No rows added.')
            ->assertSee('Foo');
    }

    #[Test]
    public function can_delete_row()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" deletable />
                    </div>
                HTML;
            }
        })
            ->assertSee('No rows added.')
            ->click('@tallstackui_add_row_button')
            ->pause(100)
            ->assertPresent('@tallstackui_input_key')
            ->pause(100)
            ->click('@tallstackui_delete_row_button')
            ->pause(100)
            ->assertNotPresent('@tallstackui_input_key');
    }

    #[Test]
    public function can_delete_row_and_call_livewire_method()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public bool $deleted = false;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        @if ($deleted)
                            <p dusk="deleted">Deleted</p>
                        @endif

                        <x-key-value wire:model="metadata" deletable delete-method="delete" />
                    </div>
                HTML;
            }

            public function delete()
            {
                $this->deleted = true;
            }
        })
            ->assertSee('No rows added.')
            ->click('@tallstackui_add_row_button')
            ->pause(100)
            ->assertPresent('@tallstackui_input_key')
            ->pause(100)
            ->click('@tallstackui_delete_row_button')
            ->pause(100)
            ->assertNotPresent('@tallstackui_input_key')
            ->assertPresent('@deleted');
    }

    #[Test]
    public function can_see_header()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata">
                            <x-slot:header>
                                FooBarBazBah
                            </x-slot:header>
                        </x-key-value>
                    </div>
                HTML;
            }
        })
            ->assertSee('No rows added.')
            ->assertSee('FooBarBazBah');
    }

    #[Test]
    public function cannot_exceed_limit()
    {
        $browser = Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" :limit="2" />
                    </div>
                HTML;
            }
        });

        $browser
            ->assertSee('No rows added.')
            ->click('@tallstackui_add_row_button')
            ->pause(100)
            ->assertPresent('@tallstackui_input_key')
            ->pause(100)
            ->click('@tallstackui_add_row_button')
            ->assertNotVisible('@tallstackui_add_row_button');

        $this->assertTrue(
            count(
                $browser->driver->findElements(WebDriverBy::xpath('//html/body/div[3]/div/div[2]/div[3]'))
            ) > 0
        );

        $this->assertTrue(
            count(
                $browser->driver->findElements(WebDriverBy::xpath('//html/body/div[3]/div/div[2]/div[4]'))
            ) == 0
        );

        $this->assertTrue(
            count(
                $browser->driver->findElements(WebDriverBy::xpath('//html/body/div[3]/div/div[2]/div[5]'))
            ) == 0
        );
    }

    #[Test]
    public function cannot_see_add_button_when_already_set_and_in_limit()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [
                0 => [
                    'key' => 'foo',
                    'value' => 'bar',
                ],
            ];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" :limit="1" />
                    </div>
                HTML;
            }
        })
            ->pause(100)
            ->assertDontSee('No rows added.')
            ->assertNotVisible('@tallstackui_add_row_button');
    }

    #[Test]
    public function cannot_see_delete_button_when_static()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [
                0 => [
                    'key' => 'foo',
                    'value' => 'bar',
                ],
            ];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" static />
                    </div>
                HTML;
            }
        })
            ->pause(100)
            ->assertDontSee('No rows added.')
            ->assertNotPresent('@tallstackui_delete_row_button');
    }

    #[Test]
    public function cannot_use_bind_to_something_different_than_array()
    {
        Livewire::visit(new class extends Component
        {
            public $metadata = '';

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" />
                    </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Keyvalue: The [value] must be an array.');
    }

    #[Test]
    public function cannot_use_index_or_value_with_different_names()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [
                [
                    'key' => 'blabla',
                    'value' => 'xoxo',
                ],
                [
                    'key' => 'blabla',
                    'asd' => 'xoxo',
                ],
            ];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" />
                    </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Keyvalue: The [value] must be an array of arrays with [key] and [value] keys.');
    }

    #[Test]
    public function cannot_use_static_and_limit_at_same_time()
    {
        Livewire::visit(new class extends Component
        {
            public array $metadata = [];

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-key-value wire:model="metadata" :limit="2" static />
                    </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Keyvalue: The [static] and [limit] attributes cannot be used at the same time.');
    }
}

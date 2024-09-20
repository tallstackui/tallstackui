<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class NumberTest extends BrowserTestCase
{
    /** @test */
    public function can_decrease(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="decreased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@sync')
            ->waitForTextIn('@decreased', '0');
    }

    /** @test */
    public function can_decrease_by_step(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 20;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="decreased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" step="5" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@sync')
            ->waitForTextIn('@decreased', '5');
    }

    /** @test */
    public function can_decrease_more_than_zero()
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="quantity">{{ $quantity }}</p>
                
                    <x-number label="Quantity" wire:model.live="quantity" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Quantity')
            ->assertSeeIn('@quantity', '0')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@quantity', '-1')
            ->assertSeeIn('@quantity', '-1')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@quantity', '-2')
            ->assertSeeIn('@quantity', '-2')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@quantity', '-3')
            ->assertSeeIn('@quantity', '-3');
    }

    /** @test */
    public function can_decrease_pressing(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 10;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="decreased">{{ $quantity }}</p>
                    
                    <x-number label="Quantity" min="5" wire:model.live="quantity" delay="1" />
                </div>
                HTML;
            }
        })
            ->assertSee('Quantity')
            ->clickAndHold('@tallstackui_form_number_decrement')
            ->waitForTextIn('@decreased', '5');
    }

    /** @test */
    public function can_decrease_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="decreased">{{ $quantity }}</p>
                    
                    <x-number wire:model.live="quantity" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@decreased', '2')
            ->assertSeeIn('@decreased', '2')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@decreased', '1')
            ->assertSeeIn('@decreased', '1')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@decreased', '0')
            ->assertSeeIn('@decreased', '0');
    }

    /** @test */
    public function can_increase(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@sync')
            ->waitForTextIn('@increased', '3');
    }

    /** @test */
    public function can_increase_by_step(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" step="5" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@sync')
            ->waitForTextIn('@increased', '15');
    }

    /** @test */
    public function can_increase_and_dispatch_change_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number label="Quantity" wire:change="sync" wire:model="quantity" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Quantity')
            ->waitForLivewire()->click('@tallstackui_form_number_increment')
            ->waitForLivewire()->click('@tallstackui_form_number_increment')
            ->waitForLivewire()->click('@tallstackui_form_number_increment')
            ->waitForTextIn('@increased', '3');
    }

    /** @test */
    public function can_increase_pressing(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number label="Quantity" wire:model.live="quantity" delay="1" max="10" />
                </div>
                HTML;
            }
        })
            ->assertSee('Quantity')
            ->clickAndHold('@tallstackui_form_number_increment')
            ->waitForTextIn('@increased', '10');
    }

    /** @test */
    public function can_increase_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number wire:model.live="quantity" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->click('@tallstackui_form_number_increment')
            ->waitForTextIn('@increased', '1')
            ->assertSeeIn('@increased', '1')
            ->click('@tallstackui_form_number_increment')
            ->waitForTextIn('@increased', '2')
            ->assertSeeIn('@increased', '2')
            ->click('@tallstackui_form_number_increment')
            ->waitForTextIn('@increased', '3')
            ->assertSeeIn('@increased', '3');
    }

    /** @test */
    public function cannot_decrease_beyond_min(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="decreased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" min="2" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@sync')
            ->waitForTextIn('@decreased', '2');
    }

    /** @test */
    public function cannot_decrease_beyond_zero()
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="quantity">{{ $quantity }}</p>
                
                    <x-number label="Quantity" wire:model.live="quantity" min="0" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Quantity')
            ->type('@tallstackui_form_number_input', 0)
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->waitForTextIn('@quantity', '0');
    }

    /** @test */
    public function cannot_increase_beyond_max(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 9;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" max="10" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@sync')
            ->waitForTextIn('@increased', '10');
    }

    /** @test */
    public function cannot_increase_or_decrease_beyond_values(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="quantity">{{ $quantity }}</p>
                    
                    <x-number wire:model="quantity" min="0" max="3" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@tallstackui_form_number_increment')
            ->click('@sync')
            ->waitForTextIn('@quantity', '3')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@tallstackui_form_number_decrement')
            ->click('@sync')
            ->waitForTextIn('@quantity', '0');
    }
}

<?php

namespace Tests\Browser\Rating;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_rating(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $rating = '3.5';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="rating">{{ $rating }}</p>

                    <x-rating />
                </div>
                HTML;
            }

            public function evaluate(string $rating): void
            {
                $this->rating = $rating;
            }
        })
            ->assertSeeIn('@rating', '3.5')
            ->clickAtXPath('/html/body/div[3]/div/button[4]')
            ->waitForTextIn('@rating', '4')
            ->assertSeeIn('@rating', '4')
            ->assertSee('4');
    }

    /** @test */
    public function can_rating_using_custom_method(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $rating = '3.5';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="rating">{{ $rating }}</p>

                    <x-rating :rate="$rating" evaluate-method="fooBar" />
                </div>
                HTML;
            }

            public function fooBar(string $rating): void
            {
                $this->rating = $rating;
            }
        })
            ->assertSeeIn('@rating', '3.5')
            ->clickAtXPath('/html/body/div[3]/div/button[4]')
            ->waitForTextIn('@rating', '4')
            ->assertSeeIn('@rating', '4')
            ->assertSee('4');
    }

    /** @test */
    public function can_render_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $rating = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="rating">{{ $rating }}</p>

                    <x-rating>
                        FooBar
                    </x-rating>
                </div>
                HTML;
            }

            public function evaluate(string $rating): void
            {
                $this->rating = $rating;
            }
        })->assertSee('FooBar');
    }

    /** @test */
    public function can_use_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $rating = '3.5';

            public ?string $rated = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($rated)
                        <p dusk="rated">{{ $rated }}</p>
                    @endif

                    <p dusk="rating">{{ $rating }}</p>

                    <x-rating :rate="$rating" x-on:evaluate="$wire.set('rated', 'rated')" />
                </div>
                HTML;
            }

            public function evaluate(string $rating): void
            {
                $this->rating = $rating;
            }
        })
            ->assertSeeIn('@rating', '3.5')
            ->clickAtXPath('/html/body/div[3]/div/button[4]')
            ->waitForTextIn('@rating', '4')
            ->assertSeeIn('@rating', '4')
            ->assertVisible('@rated')
            ->assertSeeIn('@rated', 'rated');
    }
}

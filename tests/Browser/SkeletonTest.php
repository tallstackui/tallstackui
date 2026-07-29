<?php

namespace Tests\Browser;

use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;

class SkeletonTest extends BrowserTestCase
{
    #[Test]
    public function can_paint_the_skeleton_before_the_lazy_component_resolves(): void
    {
        Livewire::visit(new #[Lazy] class extends Component
        {
            public array $headers = [
                ['index' => 'name', 'label' => 'Name'],
                ['index' => 'email', 'label' => 'E-mail'],
            ];

            // Skipped on the first paint and only called when the lazy request
            // lands, which is what holds the skeleton on screen long enough
            // for the assertion below to observe it.
            public function mount(): void
            {
                usleep(500000);
            }

            public function placeholder(): string
            {
                return <<<'HTML'
                <div>
                    <x-table :$headers skeleton="3" />
                </div>
                HTML;
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-table :$headers :rows="[['name' => 'Taylor', 'email' => 'taylor@laravel.com']]" />
                </div>
                HTML;
            }
        })
            ->waitFor('[aria-busy="true"]')
            ->assertPresent('[aria-busy="true"]')
            ->assertSourceHas('Name')
            ->assertSourceHas('E-mail')
            ->assertDontSee('Taylor')
            ->waitForText('Taylor')
            ->assertSee('Taylor')
            ->assertMissing('[aria-busy="true"]');
    }
}

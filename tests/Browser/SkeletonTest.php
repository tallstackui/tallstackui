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

            // A lazy component only loads once it intersects the viewport, so
            // the margin holds the skeleton below the fold until the test
            // scrolls to it, instead of racing the lazy request.
            public function placeholder(): string
            {
                return <<<'HTML'
                <div style="margin-top: 200vh">
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
            ->scrollIntoView('[aria-busy="true"]')
            ->waitForText('Taylor')
            ->assertSee('Taylor')
            ->assertMissing('[aria-busy="true"]');
    }
}

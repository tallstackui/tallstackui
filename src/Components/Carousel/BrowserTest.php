<?php

namespace Tests\Browser\Carousel;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $next = false;

            public bool $previous = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($next)
                        <p dusk="next">next</p>
                    @endif
                    
                    @if ($previous)
                        <p dusk="previous">previous</p>
                    @endif
                
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" x-on:next="$wire.set('next', true)" x-on:previous="$wire.set('previous', true)" />
                </div>
            HTML;
            }
        })
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->pressAndWaitFor('@tallstackui_carousel_next')
            ->assertPresent('@next')
            ->pressAndWaitFor('@tallstackui_carousel_previous')
            ->assertPresent('@previous');
    }

    #[Test]
    public function can_navigate_automatically(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $next = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($next)
                        <p dusk="next">next</p>
                    @endif
                
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" autoplay interval="1" x-on:next="$wire.set('next', true)" />
                </div>
            HTML;
            }
        })
            ->waitForText('1-foo')
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->assertPresent('@next');
    }

    #[Test]
    public function can_navigate_next(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->pressAndWaitFor('@tallstackui_carousel_next')
            ->assertSee('2-foo')
            ->assertSee('2-bar')
            ->waitUntilMissingText('1-foo')
            ->waitUntilMissingText('1-bar')
            ->waitUntilMissingText('3-foo')
            ->waitUntilMissingText('3-bar');
    }

    #[Test]
    public function can_navigate_previous(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->pressAndWaitFor('@tallstackui_carousel_previous')
            ->assertSee('3-foo')
            ->assertSee('3-bar')
            ->waitUntilMissingText('1-foo')
            ->waitUntilMissingText('1-bar')
            ->waitUntilMissingText('2-foo')
            ->waitUntilMissingText('2-bar');
    }

    #[Test]
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->assertDontSee('2-foo')
            ->assertDontSee('2-bar')
            ->assertDontSee('3-foo')
            ->assertDontSee('3-bar');
    }

    #[Test]
    public function cannot_navigate_next_without_loop(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                            'cover' => true,
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" without-loop />
                </div>
            HTML;
            }
        })
            ->assertSee('2-foo')
            ->assertSee('2-bar')
            ->pressAndWaitFor('@tallstackui_carousel_next')
            ->assertSee('3-foo')
            ->assertSee('3-bar')
            ->pressAndWaitFor('@tallstackui_carousel_next')
            ->assertSee('3-foo')
            ->assertSee('3-bar')
            ->waitUntilMissingText('2-foo')
            ->waitUntilMissingText('2-bar')
            ->waitUntilMissingText('1-foo')
            ->waitUntilMissingText('1-bar');
    }

    #[Test]
    public function cannot_navigate_previous_without_loop(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'title' => '3-foo',
                            'description' => '3-bar',
                        ],
                    ]" without-loop />
                </div>
            HTML;
            }
        })
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->pressAndWaitFor('@tallstackui_carousel_previous')
            ->assertSee('1-foo')
            ->assertSee('1-bar')
            ->waitUntilMissingText('2-foo')
            ->waitUntilMissingText('2-bar')
            ->waitUntilMissingText('3-foo')
            ->waitUntilMissingText('3-bar');
    }

    #[Test]
    public function cannot_render_with_empty_images(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel />
                </div>
            HTML;
            }
        })
            ->assertSee('[TallStackUI] Carousel: The [images] attribute is required.');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipOnGitHubActions('This test is not compatible with GitHub Actions');
    }
}

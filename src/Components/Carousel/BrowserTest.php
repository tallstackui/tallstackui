<?php

namespace TallStackUi\Components\Carousel;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
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
    public function can_expand_image_when_clickable(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel clickable :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'alt' => 'lightbox-image-1',
                            'title' => '1-foo',
                            'description' => '1-bar',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'alt' => 'lightbox-image-2',
                            'title' => '2-foo',
                            'description' => '2-bar',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_carousel_expand')
            ->pause(300)
            ->assertVisible('@tallstackui_carousel_close')
            ->click('@tallstackui_carousel_close')
            ->pause(300)
            ->assertNotVisible('@tallstackui_carousel_close');
    }

    #[Test]
    public function can_expand_image_with_footer_caption(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel clickable caption="footer" :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'alt' => 'image-1',
                            'title' => 'Caption title in footer',
                            'description' => 'Caption description in footer',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_carousel_expand')
            ->pause(300)
            ->assertSee('Caption title in footer')
            ->assertSee('Caption description in footer')
            ->click('@tallstackui_carousel_close')
            ->pause(300)
            ->assertNotVisible('@tallstackui_carousel_close');
    }

    #[Test]
    public function can_expand_image_with_overlay_caption(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-carousel clickable caption="overlay" :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'alt' => 'image-1',
                            'title' => 'Caption title in overlay',
                            'description' => 'Caption description in overlay',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_carousel_expand')
            ->pause(300)
            ->assertSee('Caption title in overlay')
            ->assertSee('Caption description in overlay')
            ->click('@tallstackui_carousel_close')
            ->pause(300)
            ->assertNotVisible('@tallstackui_carousel_close');
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
            ->waitFor('@next');
    }

    #[Test]
    public function can_navigate_inside_lightbox_and_restore_body_overflow(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div style="height: 200vh">
                    <x-carousel clickable navigable :images="[
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            'alt' => 'image-1',
                            'title' => '1-foo',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            'alt' => 'image-2',
                            'title' => '2-foo',
                        ],
                        [
                            'src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            'alt' => 'image-3',
                            'title' => '3-foo',
                        ],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_carousel_expand')
            ->pause(300)
            ->assertVisible('@tallstackui_carousel_expanded_next')
            ->click('@tallstackui_carousel_expanded_next')
            ->pause(150)
            ->click('@tallstackui_carousel_expanded_next')
            ->pause(150)
            ->click('@tallstackui_carousel_expanded_previous')
            ->pause(150)
            ->click('@tallstackui_carousel_close')
            ->pause(300)
            ->assertNotVisible('@tallstackui_carousel_close')
            ->tap(function (Browser $browser): void {
                $overflow = $browser->script('return document.body.style.overflow;')[0];
                $registry = $browser->script('return (window.__tsui_elements ?? []).length;')[0];
                $current = $browser->script("var trigger = document.querySelector('[dusk=\"tallstackui_carousel_expand\"]'); return trigger ? Alpine.\$data(trigger.closest('[x-data]')).current : null;")[0];

                Assert::assertNotSame('hidden', $overflow, 'body overflow should be restored after closing the lightbox');
                Assert::assertSame(0, $registry, 'the UI element registry should be empty after closing the lightbox');
                Assert::assertSame(2, $current, 'the main carousel should sync to the last image viewed inside the lightbox');
            });
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
}

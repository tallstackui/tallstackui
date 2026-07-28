<?php

namespace TallStackUi\Components\Gallery;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_close_the_lightbox_with_escape(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-gallery clickable :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'alt' => 'image-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'alt' => 'image-2'],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_gallery_expand_0')
            ->pause(300)
            ->assertVisible('@tallstackui_gallery_close')
            ->keys('', '{escape}')
            ->pause(300)
            ->assertNotVisible('@tallstackui_gallery_close');
    }

    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $expanded = false;

            public bool $collapsed = false;

            public bool $next = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($expanded)
                        <p dusk="expanded">expanded</p>
                    @endif

                    @if ($collapsed)
                        <p dusk="collapsed">collapsed</p>
                    @endif

                    @if ($next)
                        <p dusk="next">next</p>
                    @endif

                    <x-gallery clickable navigable :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'alt' => 'image-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'alt' => 'image-2'],
                    ]"
                    x-on:expand="$wire.set('expanded', true)"
                    x-on:collapse="$wire.set('collapsed', true)"
                    x-on:next="$wire.set('next', true)" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_gallery_expand_0')
            ->pause(300)
            ->assertPresent('@expanded')
            ->click('@tallstackui_gallery_expanded_next')
            ->pause(300)
            ->assertPresent('@next')
            ->click('@tallstackui_gallery_close')
            ->pause(300)
            ->assertPresent('@collapsed');
    }

    #[Test]
    public function can_expand_and_close_the_lightbox(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-gallery clickable :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'alt' => 'image-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'alt' => 'image-2'],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_gallery_expand_0')
            ->pause(300)
            ->assertVisible('@tallstackui_gallery_close')
            ->click('@tallstackui_gallery_close')
            ->pause(300)
            ->assertNotVisible('@tallstackui_gallery_close');
    }

    #[Test]
    public function can_expand_from_the_remaining_overlay_at_the_right_index(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-gallery feature clickable navigable caption="overlay" :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'title' => 'title-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'title' => 'title-2'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp', 'title' => 'title-3'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'title' => 'title-4'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'title' => 'title-5'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp', 'title' => 'title-6'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'title' => 'title-7'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'title' => 'title-8'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp', 'title' => 'title-9'],
                    ]" />
                </div>
            HTML;
            }
        })
            ->assertSee('+2')
            ->click('@tallstackui_gallery_expand_6')
            ->pause(300)
            ->assertSee('title-7')
            ->click('@tallstackui_gallery_expanded_next')
            ->pause(300)
            ->assertSee('title-8');
    }

    #[Test]
    public function can_navigate_with_the_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-gallery clickable navigable caption="overlay" :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'title' => 'title-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'title' => 'title-2'],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_gallery_expand_0')
            ->pause(300)
            ->assertSee('title-1')
            ->keys('', '{right}')
            ->pause(300)
            ->assertSee('title-2')
            ->keys('', '{left}')
            ->pause(300)
            ->assertSee('title-1');
    }

    #[Test]
    public function cannot_navigate_beyond_the_edges_without_loop(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-gallery clickable navigable without-loop :images="[
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp', 'alt' => 'image-1'],
                        ['src' => 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp', 'alt' => 'image-2'],
                    ]" />
                </div>
            HTML;
            }
        })
            ->click('@tallstackui_gallery_expand_0')
            ->pause(300)
            ->assertDisabled('@tallstackui_gallery_expanded_previous')
            ->click('@tallstackui_gallery_expanded_next')
            ->pause(300)
            ->assertDisabled('@tallstackui_gallery_expanded_next')
            ->assertEnabled('@tallstackui_gallery_expanded_previous');
    }
}

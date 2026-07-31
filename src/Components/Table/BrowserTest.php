<?php

namespace TallStackUi\Components\Table;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function a_crafted_persistent_cannot_escape_the_scroll_snippet(): void
    {
        Livewire::visit(new class extends Component
        {
            use WithPagination;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @php
                        $headers = [['index' => 'id', 'label' => '#']];
                        $rows = new Illuminate\Pagination\LengthAwarePaginator(collect([['id' => 1]]), 30, 10, 1, ['path' => '/']);
                        $crafted = "x'); alert(1); //";
                    @endphp
                    <x-table :$headers :$rows paginate :persistent="$crafted" />
                </div>
                HTML;
            }
        })
            ->assertSourceMissing("getElementById('x'); alert")
            ->assertSee('#');
    }

    #[Test]
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows filter loading />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Quantity');
    }

    #[Test]
    public function can_render_empty_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows>
                        <x-slot:empty>
                            <p dusk="custom-empty">No records found here</p>
                        </x-slot:empty>
                    </x-table>
                </div>
                HTML;
            }
        })
            ->waitForText('No records found here')
            ->assertVisible('@custom-empty');
    }

    #[Test]
    public function can_render_expandable(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows expandable>
                        @interact('sub_table', $row)
                            <p dusk="sub-{{ $row['id'] }}">Details for {{ $row['name'] }}</p>
                        @endinteract
                    </x-table>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertDontSee('Details for Foo')
            ->assertDontSee('Details for Bar');
    }

    #[Test]
    public function can_render_headless(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $rows = [
                            ['id' => 1, 'name' => 'Foo'],
                            ['id' => 2, 'name' => 'Bar'],
                        ];
                
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    
                    <x-table :$headers :$rows headerless />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertDontSee('Name');
    }

    #[Test]
    public function can_render_manipulating_columns(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows filter loading>
                        @interact('column_name', $row)
                            {{ $row['name'] }} Test
                        @endinteract
                    </x-table>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo Test')
            ->assertSee('Bar Test');
    }

    #[Test]
    public function can_render_manipulating_columns_passing_extra_variables(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                        
                        $extra = 'Extra';
                    @endphp
                    <x-table :$headers :$rows filter loading>
                        @interact('column_name', $row, $extra)
                            {{ $row['name'] }} Test {{ $extra }}
                        @endinteract
                    </x-table>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo Test Extra')
            ->assertSee('Bar Test Extra');
    }

    #[Test]
    public function can_render_manipulating_columns_without_parameters(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows filter loading>
                        @interact('column_name')
                            Test
                        @endinteract
                    </x-table>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Foo')
            ->assertDontSee('Bar')
            ->assertSee('Test');
    }

    #[Test]
    public function can_render_paginated(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Computed]
            public function rows(): LengthAwarePaginator
            {
                $items = collect([
                    ['id' => 1, 'name' => 'Foo'],
                    ['id' => 2, 'name' => 'Bar'],
                    ['id' => 3, 'name' => 'Baz'],
                    ['id' => 4, 'name' => 'Qux'],
                    ['id' => 5, 'name' => 'Quux'],
                    ['id' => 6, 'name' => 'Quuz'],
                    ['id' => 7, 'name' => 'Corge'],
                    ['id' => 8, 'name' => 'Grault'],
                    ['id' => 9, 'name' => 'Garply'],
                    ['id' => 10, 'name' => 'Waldo'],
                    ['id' => 11, 'name' => 'Fred'],
                    ['id' => 12, 'name' => 'Plugh'],
                    ['id' => 13, 'name' => 'Xyzzy'],
                    ['id' => 14, 'name' => 'Thud'],
                ]);

                return new LengthAwarePaginator($items->forPage(1, 5), $items->count(), 5, 1);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :rows="$this->rows" paginate id="foo" />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertDontSee('Quantity')
            ->assertSee('Showing 1 to 5 of 14 results');
    }

    #[Test]
    public function can_render_selectable_and_select_rows(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public array $selected = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ implode(',', $selected) }}</p>

                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    
                    <x-table wire:model.live="selected" :$headers :$rows selectable />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->click('@tallstackui_table_select_all')
            ->waitForTextIn('@selected', '1,2');

    }

    #[Test]
    public function can_render_selectable_and_select_rows_using_different_property(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo', 'email' => 'foo@bar.com'],
                ['id' => 2, 'name' => 'Bar', 'email' => 'bar@foo.com'],
            ];

            public array $selected = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ implode(',', $selected) }}</p>

                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    
                    <x-table wire:model.live="selected" :$headers :$rows selectable selectable-property="email" />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->click('@tallstackui_table_select_all')
            ->waitForTextIn('@selected', 'foo@bar.com,bar@foo.com');
    }

    #[Test]
    public function can_render_with_highlighted_rows(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo', 'highlight' => 'green'],
                ['id' => 2, 'name' => 'Bar', 'highlight' => 'red'],
                ['id' => 3, 'name' => 'Baz'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows highlight />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Baz')
            ->assertSourceHas('bg-green-100')
            ->assertSourceHas('bg-red-100');
    }

    #[Test]
    public function can_render_with_highlighted_rows_using_custom_property(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo', 'status_color' => 'blue'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp
                    <x-table :$headers :$rows highlight highlight-property="status_color" />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSourceHas('bg-blue-100');
    }

    #[Test]
    public function persistent_string_scrolls_to_the_given_element(): void
    {
        Livewire::visit(new class extends Component
        {
            use WithPagination;

            public function render(): string
            {
                return <<<'HTML'
                <div id="anchor">
                    @php
                        $headers = [['index' => 'id', 'label' => '#']];
                        $rows = new Illuminate\Pagination\LengthAwarePaginator(collect([['id' => 1]]), 30, 10, 1, ['path' => '/']);
                    @endphp
                    <x-table :$headers :$rows paginate persistent="anchor" />
                </div>
                HTML;
            }
        })
            ->assertSourceHas("document.getElementById('anchor')?.scrollIntoView();");
    }

    #[Test]
    public function selectable_select_all_reflects_only_current_page_after_pagination(): void
    {
        Livewire::visit(new class extends Component
        {
            use WithPagination;

            public array $selected = [];

            #[Computed]
            public function rows(): LengthAwarePaginator
            {
                $items = collect([
                    ['id' => 1, 'name' => 'Foo'],
                    ['id' => 2, 'name' => 'Bar'],
                    ['id' => 3, 'name' => 'Baz'],
                    ['id' => 4, 'name' => 'Qux'],
                ]);

                $page = $this->getPage();

                return new LengthAwarePaginator(
                    $items->forPage($page, 2),
                    $items->count(),
                    2,
                    $page
                );
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ implode(',', $selected) }}</p>

                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp

                    <x-table wire:model.live="selected" :$headers :rows="$this->rows" selectable paginate />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->click('@tallstackui_table_select_all')
            ->waitForTextIn('@selected', '1,2')
            ->assertChecked('@tallstackui_table_select_all')
            ->click('@nextPage.after')
            ->waitForText('Baz')
            ->assertNotChecked('@tallstackui_table_select_all')
            ->click('input[type=checkbox][value="3"]')
            ->waitForTextIn('@selected', '1,2,3')
            ->pause(150)
            ->assertNotChecked('@tallstackui_table_select_all');
    }

    #[Test]
    public function selected_event_also_fires_on_select_all(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ picked: [] }" x-on:selected="picked = $event.detail.rows">
                    <p dusk="picked" x-text="picked.length ? picked.join(',') : 'none'"></p>

                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp

                    <x-table :$headers :$rows selectable />
                </div>
                HTML;
            }
        })
            ->waitForTextIn('@picked', 'none')
            ->click('@tallstackui_table_select_all')
            ->waitForTextIn('@picked', '1,2')
            ->click('@tallstackui_table_select_all')
            ->waitForTextIn('@picked', 'none');
    }

    #[Test]
    public function selected_event_carries_the_whole_selection_outside_livewire(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $rows = [
                ['id' => 1, 'name' => 'Foo'],
                ['id' => 2, 'name' => 'Bar'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ picked: [] }" x-on:selected="picked = $event.detail.rows">
                    <p dusk="picked" x-text="picked.length ? picked.join(',') : 'none'"></p>

                    @php
                        $headers = [
                            ['index' => 'id', 'label' => '#'],
                            ['index' => 'name', 'label' => 'Name'],
                        ];
                    @endphp

                    <x-table :$headers :$rows selectable />
                </div>
                HTML;
            }
        })
            ->waitForTextIn('@picked', 'none')
            ->click('#checkbox-0')
            ->waitForTextIn('@picked', '1')
            ->click('#checkbox-1')
            ->waitForTextIn('@picked', '1,2')
            ->click('#checkbox-0')
            ->waitForTextIn('@picked', '2');
    }
}

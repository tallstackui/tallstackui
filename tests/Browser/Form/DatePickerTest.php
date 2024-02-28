<?php

namespace Tests\Browser\Form;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class DatePickerTest extends BrowserTestCase
{
    /** @test */
    public function can_select_date()
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(3000000)
            ->waitForText('2020-01-01')
            ->assertSee('2020-01-01')
            ->click('@tallstackui_datepicker_picker')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[5]/button')
            ->waitForText('2020-01-02')
            ->assertSee('2020-01-02');
    }

    public function can_select_date_on_multiple()
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['2020-01-01', '2020-01-03'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" 
                                  multiple />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_datepicker_picker')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '["2020-01-03"]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
    }

    /** @test */
    public function can_select_date_on_range()
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['2020-01-01', '2020-01-03'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" 
                                  range />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_datepicker_picker')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[7]/button')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('storage:link');

        File::ensureDirectoryExists(base_path('public/storage/dayjs'));

        File::copy(__DIR__.'/../../Fixtures/dayjs/dayjs.min.js', base_path('public/storage/dayjs/dayjs.min.js'));
        File::copy(__DIR__.'/../../Fixtures/dayjs/en.js', base_path('public/storage/dayjs/en.js'));
    }
}

<?php

namespace Tests\Browser;

use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * The [value] attribute of the fields outside Livewire, on a plain Blade page
 * that hands the submitted values back to the fields, the way `old()` does
 * after a failed validation. Every entry pins what a field holds and shows
 * for a value an application can give it. Livewire's script is loaded only
 * because that is where Alpine comes from in a real application.
 */
class PlainFormValuesTest extends BrowserTestCase
{
    private const MONTHS = "[['label' => 'January', 'value' => 1], ['label' => 'February', 'value' => 2], ['label' => 'March', 'value' => 3]]";

    private const PEOPLE = "['Silva, João', 'Souza, Ana']";

    private const PLANS = "['basic', 'pro', 'team']";

    /**
     * The values that survive the round trip: [component, value, expected].
     * A fourth entry hands the value through an escaped attribute, the way
     * `value="{{ old('field') }}"` reaches the component.
     */
    public static function stable(): array
    {
        $months = '<x-select.styled %s :options="'.self::MONTHS.'" select="label:label|value:value" />';
        $people = '<x-select.styled %s :options="'.self::PEOPLE.'" />';
        $plans = '<x-select.styled %s multiple :options="'.self::PLANS.'" />';
        $range = ['hidden' => '["2026-10-01","2026-10-05"]', 'inputs' => '2026-10-01 - 2026-10-05'];

        return [
            'select_integer' => [$months, '2', ['hidden' => '2', 'text' => 'February']],
            'select_zero' => ['<x-select.styled %s :options="[[\'label\' => \'Inactive\', \'value\' => 0], [\'label\' => \'Active\', \'value\' => 1]]" select="label:label|value:value" />', '0', ['hidden' => '0', 'text' => 'Inactive']],
            'select_empty' => [$months, '', ['hidden' => '', 'text' => 'Select an option']],
            'select_big_integer' => ['<x-select.styled %s :options="[[\'label\' => \'Big\', \'value\' => 1234567890123456789]]" select="label:label|value:value" />', '1234567890123456789', ['text' => 'Big']],
            'select_zero_padded' => [$months, '02', ['hidden' => '2', 'text' => 'February']],
            'select_plain' => ['<x-select.styled %s :options="'.self::PLANS.'" />', 'pro', ['hidden' => 'pro', 'text' => 'pro']],
            'select_comma' => [$people, 'Silva, João', ['hidden' => '"Silva, João"', 'text' => 'Silva, João']],
            'select_json_string' => [$people, '"Silva, João"', ['hidden' => '"Silva, João"', 'text' => 'Silva, João']],
            'select_long_number' => ['<x-select.styled %s :options="[[\'label\' => \'Long\', \'value\' => \'99999999999999999999\']]" select="label:label|value:value" />', '99999999999999999999', ['hidden' => '99999999999999999999', 'text' => 'Long']],
            'multiple_json' => [$plans, '["basic","team"]', ['hidden' => '["basic","team"]', 'text' => '2 basic team']],
            'multiple_comma' => [$plans, 'basic,team', ['hidden' => '["basic","team"]', 'text' => '2 basic team']],
            'multiple_json_string' => [$plans, '"basic,team"', ['hidden' => '["basic","team"]', 'text' => '2 basic team']],
            'multiple_item_with_comma' => ['<x-select.styled %s multiple :options="'.self::PEOPLE.'" />', '["Silva, João"]', ['hidden' => '["Silva, João"]', 'text' => '1 Silva, João']],
            'multiple_integer' => ['<x-select.styled %s multiple :options="'.self::MONTHS.'" select="label:label|value:value" />', '[1,2]', ['hidden' => '[1,2]', 'text' => '2 January February']],
            'multiple_zero_padded' => ['<x-select.styled %s multiple :options="'.self::MONTHS.'" select="label:label|value:value" />', '01,02', ['hidden' => '[1,2]', 'text' => '2 January February']],
            'tag_json' => ['<x-tag %s />', '["php","laravel"]', ['hidden' => '["php","laravel"]', 'text' => 'php laravel']],
            'tag_comma' => ['<x-tag %s />', 'php, laravel', ['hidden' => '["php","laravel"]', 'text' => 'php laravel']],
            'tag_json_string' => ['<x-tag %s />', '"php,laravel"', ['hidden' => '["php","laravel"]', 'text' => 'php laravel']],
            'tag_leading_zero' => ['<x-tag %s />', '["033","php"]', ['hidden' => '["033","php"]', 'text' => '033 php']],
            'tag_empty' => ['<x-tag %s />', '', ['hidden' => '', 'text' => '']],
            'tag_single' => ['<x-tag %s />', 'php', ['hidden' => 'php', 'text' => 'php']],
            'autocomplete_plain' => ['<x-autocomplete %s :items="[[\'value\' => \'Lisbon\'], [\'value\' => \'Porto\']]" />', 'Porto', ['hidden' => 'Porto', 'inputs' => 'Porto']],
            'autocomplete_comma' => ['<x-autocomplete %s :items="[[\'value\' => \'São Paulo, SP\'], [\'value\' => \'Rio, RJ\']]" />', 'São Paulo, SP', ['hidden' => 'São Paulo, SP', 'inputs' => 'São Paulo, SP']],
            'autocomplete_strict_integer' => ['<x-autocomplete %s strict :items="[[\'value\' => 1], [\'value\' => 2]]" />', '02', ['hidden' => '2', 'inputs' => '2']],
            'password_special' => ['<x-password %s />', 'pa"ss,1', ['hidden' => 'pa"ss,1']],
            'password_leading_zero' => ['<x-password %s />', '0123', ['hidden' => '0123']],
            'password_digits' => ['<x-password %s />', '123456', ['hidden' => '123456']],
            'date_single' => ['<x-date %s />', '2026-10-01', ['hidden' => '2026-10-01', 'inputs' => '2026-10-01']],
            'date_range_json' => ['<x-date %s range />', '["2026-10-01","2026-10-05"]', $range],
            'date_range_comma' => ['<x-date %s range />', '2026-10-01,2026-10-05', $range],
            'date_range_json_string' => ['<x-date %s range />', '"2026-10-01,2026-10-05"', $range],
            'date_range_start_only' => ['<x-date %s range />', '["2026-10-01",null]', ['hidden' => '["2026-10-01",null]', 'inputs' => '2026-10-01 -']],
            'date_multiple' => ['<x-date %s multiple />', '["2026-10-01","2026-10-03"]', ['hidden' => '["2026-10-01","2026-10-03"]', 'inputs' => '2026-10-01, 2026-10-03']],
            'calendar_single' => ['<x-calendar %s />', '2026-10-01', ['hidden' => '2026-10-01']],
            'calendar_range_comma' => ['<x-calendar %s range />', '2026-10-01,2026-10-05', ['hidden' => '["2026-10-01","2026-10-05"]']],
            'calendar_range_start_only' => ['<x-calendar %s range />', '["2026-10-01",null]', ['hidden' => '["2026-10-01",null]']],
            'calendar_multiple' => ['<x-calendar %s multiple />', '["2026-10-01","2026-10-03"]', ['hidden' => '["2026-10-01","2026-10-03"]']],
            'time_24' => ['<x-time %s format="24" />', '10:30', ['hidden' => '10:30', 'inputs' => '10:30']],
            'time_12' => ['<x-time %s />', '10:30 PM', ['hidden' => '10:30 PM', 'inputs' => '10:30 PM']],
            'swap_plain' => ['<x-swap %s :options="[\'Apple\', \'Banana\']" />', 'Banana', ['hidden' => 'Banana', 'slot' => 'Banana']],
            'swap_comma' => ['<x-swap %s :options="'.self::PEOPLE.'" />', 'Souza, Ana', ['hidden' => 'Souza, Ana', 'slot' => 'Souza, Ana']],
            'swap_zero_padded' => ['<x-swap %s :options="'.self::MONTHS.'" select="label:label|value:value" />', '02', ['slot' => 'February']],
            'currency_decimal' => ['<x-currency %s decimal />', '2000.00', ['hidden' => '2000.00', 'inputs' => '2,000.00']],
            'currency_mutate' => ['<x-currency %s mutate locale="pt-BR" />', '2.000,00', ['hidden' => '2.000,00', 'inputs' => '2.000,00']],
            'currency_mutate_default_locale' => ['<x-currency %s mutate />', '2,000.00', ['hidden' => '2,000.00', 'inputs' => '2,000.00']],
            'escaped_select_comma' => [$people, 'Silva, João', ['hidden' => '"Silva, João"', 'text' => 'Silva, João'], true],
            'escaped_multiple_json' => [$plans, '["basic","team"]', ['hidden' => '["basic","team"]', 'text' => '2 basic team'], true],
            'escaped_tag_json' => ['<x-tag %s />', '["php","laravel"]', ['hidden' => '["php","laravel"]', 'text' => 'php laravel'], true],
            'escaped_date_range' => ['<x-date %s range />', '["2026-10-01","2026-10-05"]', $range, true],
            'escaped_password' => ['<x-password %s />', 'pa"ss & <0123>', ['hidden' => 'pa"ss & <0123>'], true],
        ];
    }

    /**
     * The values read once, as the server hands them: [component, value, expected].
     * A digit string is an amount in units for the currency, and what the
     * field then submits in the default mode is that amount in cents.
     */
    public static function typed(): array
    {
        return [
            'currency_units' => ['<x-currency %s />', '200000', ['hidden' => '20000000', 'inputs' => '200,000.00']],
            'currency_zero_padded' => ['<x-currency %s />', '050', ['hidden' => '5000', 'inputs' => '50.00']],
            'currency_formatted' => ['<x-currency %s />', '1,234.56', ['hidden' => '123456', 'inputs' => '1,234.56']],
            'currency_integer' => ['<x-currency %s />', 1500, ['hidden' => '150000', 'inputs' => '1,500.00']],
            'currency_decimal' => ['<x-currency %s />', 1234.56, ['hidden' => '123456', 'inputs' => '1,234.56']],
            'select_decimal' => ['<x-select.styled %s :options="[1, 1.5, 2]" />', 1.5, ['hidden' => '1.5', 'text' => '1.5']],
            'select_array' => ['<x-select.styled %s multiple :options="'.self::PLANS.'" />', ['basic', 'team'], ['hidden' => '["basic","team"]', 'text' => '2 basic team']],
            'select_null' => ['<x-select.styled %s :options="'.self::PLANS.'" />', null, ['hidden' => '', 'text' => 'Select an option']],
            'tag_array' => ['<x-tag %s />', ['php', 'laravel'], ['hidden' => '["php","laravel"]', 'text' => 'php laravel']],
        ];
    }

    #[Test]
    public function can_keep_every_value_after_the_round_trip(): void
    {
        $expected = array_map(fn (array $field): array => $field[2], self::stable());

        $this->browse(function (Browser $browser) use ($expected) {
            $browser->visit('/plain-form-values/stable')->waitFor('@submit');

            $this->assertFields($browser, $expected);

            $browser->click('@submit')->waitFor('@submitted');

            $this->assertSame(array_keys($expected), $browser->script('return [...new URLSearchParams(location.search).keys()]')[0]);

            $this->assertFields($browser, $expected);
        });
    }

    #[Test]
    public function can_read_every_value_given_by_the_server(): void
    {
        $expected = array_map(fn (array $field): array => $field[2], self::typed());

        $this->browse(function (Browser $browser) use ($expected) {
            $browser->visit('/plain-form-values/typed')->waitFor('@submit');

            $this->assertFields($browser, $expected);
        });
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/plain-form-values/{page}', function (string $page): string {
            $fields = $page === 'typed' ? self::typed() : self::stable();

            $components = collect($fields)
                ->map(fn (array $field, string $name): string => '<div dusk="'.$name.'">'.sprintf(
                    $field[0],
                    'name="'.$name.'" '.(($field[3] ?? false) ? 'value="{{ $values[\''.$name.'\'] }}"' : ':value="$values[\''.$name.'\']"')
                ).'</div>')
                ->implode("\n");

            // After the submit only what the form sent comes back, so a field
            // that stopped submitting shows up empty instead of its default.
            $submitted = request()->query() !== [];

            return Blade::render(<<<HTML
            <html>
            <head>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <tallstackui:setup />
                @livewireScripts
            </head>
            <body>
                <form method="GET">
                    {$components}

                    <button type="submit" dusk="submit">Send</button>
                </form>

                @if (request()->query() !== [])
                    <p dusk="submitted">submitted</p>
                @endif
            </body>
            </html>
            HTML, ['values' => collect($fields)->map(fn (array $field, string $name): mixed => $submitted ? request()->query($name) : $field[1])->all()]);
        });
    }

    /**
     * Compares what every field holds in its named input, shows as text,
     * shows in its visible inputs and, for the swap, shows in its current
     * slot. Only the keys an entry declares are read.
     */
    private function assertFields(Browser $browser, array $expected): void
    {
        $read = function () use ($browser, $expected): array {
            $state = $browser->driver->executeScript(<<<'JS'
                return Object.fromEntries(arguments[0].map((name) => {
                    const field = document.querySelector(`[dusk="${name}"]`);
                    const data = window.Alpine.$data(field.querySelector('[x-data]'));

                    return [name, {
                        slot: Array.isArray(data.items) ? (data.items[data.slot]?.label ?? null) : null,
                        hidden: document.getElementsByName(name)[0]?.value ?? null,
                        text: field.innerText.replace(/\s+/g, ' ').trim(),
                        inputs: [...field.querySelectorAll('input:not([hidden])')]
                            .filter((input) => input.name !== name)
                            .map((input) => input.value)
                            .join(' | ')
                            .trim(),
                    }];
                }));
            JS, [array_keys($expected)]);

            return collect($expected)
                ->map(fn (array $field, string $name): array => array_intersect_key($state[$name], $field))
                ->all();
        };

        try {
            $browser->waitUsing(10, 100, fn (): bool => $read() === $expected);
        } catch (TimeoutException) {
            //
        }

        $this->assertSame($expected, $read());
    }
}

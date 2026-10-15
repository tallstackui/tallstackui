<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component as Livewire;
use TallStackUi\Components\Boolean\Component;
use TallStackUi\Support\Runtime\AbstractRuntime;

/**
 * Runs AbstractRuntime::sanitize() over a [value] attribute, the way a
 * component out of the Livewire context receives it.
 */
function sanitized(mixed $value, bool $list = false, bool $numeric = false, ?Livewire $livewire = null): mixed
{
    $runtime = new class(new Component, ['attributes' => new ComponentAttributeBag(['value' => $value])], app('view'), $livewire) extends AbstractRuntime
    {
        public bool $list = false;

        public bool $numeric = false;

        public function runtime(): array
        {
            return ['value' => $this->sanitize($this->list, $this->numeric)];
        }
    };

    $runtime->list = $list;
    $runtime->numeric = $numeric;

    return $runtime->runtime()['value'];
}

/**
 * The component as the browser reads it: entities decoded and the
 * arguments of the Alpine component on a single line.
 */
function rendered(string $component): string
{
    return preg_replace('/\s+/', ' ', html_entity_decode(Blade::render($component)));
}

it('keeps a single value as it is', function (string $value) {
    expect(sanitized($value))->toBe($value)
        ->and(sanitized($value, numeric: true))->toBe($value);
})->with([
    'plain text' => 'foo',
    'comma' => 'Silva, João',
    'comma separated numbers' => '1,2,3',
    'quote' => 'pa"ss',
    'brackets' => 'a[0]',
    'bracket list that is not JSON' => '[foo,bar]',
    'formatted currency' => '1.234,56',
    'decimal' => '2000.00',
    'date' => '2026-10-01',
    'time' => '10:30 PM',
]);

it('casts a plain integer', function (string $value, int $expected) {
    expect(sanitized($value))->toBe($expected)
        ->and(sanitized($value, numeric: true))->toBe($expected);
})->with([
    ['7', 7],
    ['123', 123],
    ['200000', 200000],
    ['9007199254740991', 9007199254740991],
]);

it('keeps the leading zeros', function (string $value) {
    expect(sanitized($value))->toBe($value);
})->with(['033', '007', '00']);

it('drops the leading zeros for a component comparing numbers', function (string $value, int $expected) {
    expect(sanitized($value, numeric: true))->toBe($expected);
})->with([
    ['02', 2],
    ['033', 33],
    ['050', 50],
    ['000', 0],
]);

it('keeps as a string a number JavaScript cannot hold', function (string $value) {
    expect(sanitized($value))->toBe($value);
})->with([
    'beyond the safe integer' => '9007199254740993',
    'beyond PHP_INT_MAX' => '99999999999999999999',
]);

it('casts up to PHP_INT_MAX for a component comparing numbers', function () {
    expect(sanitized('9007199254740993', numeric: true))->toBe(9007199254740993)
        ->and(sanitized('99999999999999999999', numeric: true))->toBe('99999999999999999999');
});

it('trims the value', function () {
    expect(sanitized(' foo '))->toBe('foo')
        ->and(sanitized('" foo "'))->toBe('foo')
        ->and(sanitized('" 12 "', numeric: true))->toBe(12)
        ->and(sanitized('[" php ", " laravel "]', list: true))->toBe(['php', 'laravel']);
});

it('decodes the escaped characters of a single value', function () {
    expect(sanitized('Tom &amp; Jerry'))->toBe('Tom & Jerry');
});

it('decodes JSON', function (string $value, mixed $expected) {
    expect(sanitized($value))->toBe($expected)
        ->and(sanitized($value, list: true))->toBe($expected);
})->with([
    'list of strings' => ['["php","laravel"]', ['php', 'laravel']],
    'list of numbers' => ['[1, 2, 3]', [1, 2, 3]],
    'escaped by Blade' => ['[&quot;php&quot;,&quot;laravel&quot;]', ['php', 'laravel']],
    'surrounded by spaces' => [' ["php","laravel"] ', ['php', 'laravel']],
    'item with a comma' => ['["Silva, João","Souza, Ana"]', ['Silva, João', 'Souza, Ana']],
    'numeric strings' => ['["12","033"]', [12, '033']],
    'empty side of a range' => ['["2026-10-01",null]', ['2026-10-01', null]],
    'numeric string' => ['"123"', 123],
    'string with leading zero' => ['"033"', '033'],
]);

it('casts the numeric strings of JSON for a component comparing numbers', function () {
    expect(sanitized('["01","02"]', list: true, numeric: true))->toBe([1, 2])
        ->and(sanitized('"02"', numeric: true))->toBe(2);
});

it('keeps the commas of a JSON string for a single value', function () {
    expect(sanitized('"Silva, João"'))->toBe('Silva, João')
        ->and(sanitized('&quot;Silva, João&quot;'))->toBe('Silva, João');
});

it('splits a comma separated string only for a list', function (string $value, mixed $expected) {
    expect(sanitized($value, list: true))->toBe($expected);
})->with([
    'strings' => ['php, laravel', ['php', 'laravel']],
    'numbers' => ['1,2,3', [1, 2, 3]],
    'dates' => ['2026-10-01,2026-10-05', ['2026-10-01', '2026-10-05']],
    'quoted strings' => ['"php","laravel"', ['php', 'laravel']],
    'JSON string' => ['"2026-10-01,2026-10-05"', ['2026-10-01', '2026-10-05']],
    'bracket list that is not JSON' => ['[foo,bar]', ['foo', 'bar']],
    'single item between brackets' => ['[foo]', ['foo']],
    'single item' => ['php', 'php'],
    'leading zeros' => ['033,044', ['033', '044']],
]);

it('does not touch a value that is not a string', function (mixed $value) {
    expect(sanitized($value))->toBe($value)
        ->and(sanitized($value, list: true, numeric: true))->toBe($value);
})->with([
    'array' => [['php', 'laravel']],
    'integer' => [200000],
    'decimal' => [1234.56],
    'decimal below one' => [0.5],
    'null' => [null],
]);

it('reads the empty markers', function () {
    expect(sanitized('null'))->toBeNull()
        ->and(sanitized('[]'))->toBe([]);
});

it('does not touch the value inside Livewire', function () {
    $livewire = new class extends Livewire
    {
        //
    };

    expect(sanitized('1,2,3', list: true, livewire: $livewire))->toBe('1,2,3')
        ->and(sanitized('033', numeric: true, livewire: $livewire))->toBe('033');
});

it('lets only the components holding a list split a comma separated value', function (string $component, string $expected) {
    expect($component)->render()->toContain($expected);
})->with([
    'single select' => ['<x-select.styled name="person" :options="[\'Silva, João\']" value="Silva, João" />', "'Silva, João'"],
    'multiple select' => ['<x-select.styled name="plans" multiple :options="[\'basic\', \'team\']" value="basic,team" />', "JSON.parse('[\\u0022basic\\u0022,\\u0022team\\u0022]')"],
    'tag' => ['<x-tag name="tags" value="php, laravel" />', "JSON.parse('[\\u0022php\\u0022,\\u0022laravel\\u0022]')"],
    'date range' => ['<x-date name="period" range value="2026-10-01,2026-10-05" />', "JSON.parse('[\\u00222026-10-01\\u0022,\\u00222026-10-05\\u0022]')"],
    'calendar range' => ['<x-calendar name="period" range value="2026-10-01,2026-10-05" />', "JSON.parse('[\\u00222026-10-01\\u0022,\\u00222026-10-05\\u0022]')"],
    'autocomplete' => ['<x-autocomplete name="city" :items="[[\'value\' => \'Rio, RJ\']]" value="Rio, RJ" />', "'Rio, RJ'"],
    'swap' => ['<x-swap name="person" :options="[\'Silva, João\']" value="Silva, João" />', "'Silva, João'"],
]);

it('keeps the leading zeros of a component that does not compare numbers', function (string $component, string $expected) {
    expect($component)->render()->toContain($expected);
})->with([
    'password' => ['<x-password name="password" value="0123" />', "'0123'"],
    'tag' => ['<x-tag name="tags" value="[&quot;0123&quot;]" />', "JSON.parse('[\\u00220123\\u0022]')"],
]);

it('drops the leading zeros of a component comparing numbers', function (string $component, string $expected) {
    expect(rendered($component))->toContain($expected);
})->with([
    'select' => ['<x-select.styled name="month" :options="[123]" value="0123" />', "'month', 123,"],
    'multiple select' => ['<x-select.styled name="months" multiple :options="[1, 2, 3]" value="01,02" />', "'months', JSON.parse('[1,2]')"],
    'swap' => ['<x-swap name="month" :options="[123]" value="0123" />', ', 123, null)'],
    'autocomplete' => ['<x-autocomplete name="code" :items="[[\'value\' => 123]]" value="0123" />', "'code', 123,"],
    'currency' => ['<x-currency name="price" value="0123" />', "'price', 123,"],
]);

it('keeps the cents of a decimal value', function (string $component, string $expected) {
    expect(rendered($component))->toContain($expected);
})->with([
    'currency' => ['<x-currency name="price" :value="1234.56" />', "'price', 1234.56,"],
    'select' => ['<x-select.styled name="rate" :options="[1, 1.5]" :value="1.5" />', "'rate', 1.5,"],
    'swap' => ['<x-swap name="rate" :options="[1, 1.5]" :value="1.5" />', ', 1.5, null)'],
]);

it('can render a date range holding only the start date', function (string $component) {
    expect($component)->render()->toContain("JSON.parse('[\\u00222026-10-01\\u0022,null]')");
})->with([
    'date' => '<x-date name="period" range value="[&quot;2026-10-01&quot;,null]" />',
    'calendar' => '<x-calendar name="period" range value="[&quot;2026-10-01&quot;,null]" />',
]);

<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use TallStackUi\Components\Table\Component as TableComponent;
use Tests\TestCase;

use function Livewire\invade;

uses(TestCase::class)->group('Feature');

dataset('table.headers', [
    [[['index' => 'name', 'label' => 'Name'], ['index' => 'email', 'label' => 'E-mail']]],
]);

function tablePaginator(int $total = 30, int $perPage = 10, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
{
    $rows = collect(range(1, $total))->map(fn (int $index): array => ['name' => "User {$index}", 'email' => "user{$index}@bar.com"]);

    return new LengthAwarePaginator($rows->forPage($page, $perPage), $total, $perPage, $page, [
        'path' => 'http://localhost/users',
        'pageName' => $pageName,
    ]);
}

// The component configuration is memoized in a static, so a config() set
// after the first read is invisible without the flush.
function tableConfig(array $values): void
{
    foreach ($values as $key => $value) {
        config()->set("ts-ui.components.table.1.{$key}", $value);
    }

    __ts_get_component_configuration(TableComponent::class, flush: true);
}

// Renders the paginator on its own, the only way to reach the Livewire
// branch without booting a Livewire component.
function tablePaginatorView(array $data = []): string
{
    $paginator = $data['paginator'] ?? tablePaginator();

    $variant = $data['variant'] ?? 'simple';

    return view("ts-ui::components.table.paginators.{$variant}", array_merge([
        'paginator' => $paginator,
        'elements' => invade($paginator)->elements(),
        'livewire' => true,
        'simple' => false,
        'name' => $paginator->getPageName(),
        'dusk' => $paginator->getPageName() === 'page' ? '' : '.'.$paginator->getPageName(),
        'fragment' => '',
        'scroll' => '',
    ], Arr::except($data, ['variant'])))->render();
}

it('can render the skeleton outside the livewire context')
    ->expect('<x-table skeleton />')
    ->render()
    ->toContain('animate-pulse');

it('can render the skeleton keeping the real header labels', function (array $headers) {
    expect('<x-table skeleton :$headers />')
        ->render(['headers' => $headers])
        ->toContain('Name')
        ->toContain('E-mail');
})->with('table.headers');

it('can render the skeleton deriving the columns from the headers', function (array $headers) {
    $html = Blade::render('<x-table skeleton="3" :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
})->with('table.headers');

it('can render the skeleton falling back to four columns without headers', function () {
    $html = Blade::render('<x-table skeleton="2" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(12);
});

it('can render the skeleton with the selectable column', function (array $headers) {
    $html = Blade::render('<x-table skeleton="2" selectable :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(7);
})->with('table.headers');

it('can render the skeleton with the expandable column', function (array $headers) {
    $html = Blade::render('<x-table skeleton="2" expandable :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
})->with('table.headers');

it('can render the skeleton with the filter bar')
    ->expect('<x-table skeleton filter />')
    ->render()
    ->toContain('h-9');

it('can render the skeleton without the filter bar')
    ->expect('<x-table skeleton />')
    ->render()
    ->not->toContain('h-9');

it('can render the skeleton with the pagination footer')
    ->expect('<x-table skeleton paginate />')
    ->render()
    ->toContain('h-8 w-64');

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-table skeleton="0" />')->render();
});

describe('outside the livewire context', function () {
    it('can render the rows', function (array $headers) {
        expect('<x-table :$headers :$rows />')
            ->render(['headers' => $headers, 'rows' => [['name' => 'AJ', 'email' => 'aj@bar.com']]])
            ->toContain('AJ')
            ->toContain('aj@bar.com');
    })->with('table.headers');

    it('can paginate through anchors instead of wire:click', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows paginate />', ['headers' => $headers, 'rows' => tablePaginator()]);

        expect($html)->toContain('href="http://localhost/users?page=2"')
            ->and($html)->not->toContain('wire:click')
            ->and($html)->not->toContain('gotoPage');
    })->with('table.headers');

    it('can keep the active filter on the page links', function (array $headers) {
        request()->merge(['search' => 'foo']);
        request()->server->set('QUERY_STRING', 'search=foo');

        expect('<x-table :$headers :$rows filter paginate />')
            ->render(['headers' => $headers, 'rows' => tablePaginator()])
            ->toContain('search=foo&amp;page=2');
    })->with('table.headers');

    it('can sort through anchors carrying the inverted direction', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows :$sort />', [
            'headers' => $headers,
            'rows' => [['name' => 'AJ', 'email' => 'aj@bar.com']],
            'sort' => ['column' => 'name', 'direction' => 'asc'],
        ]);

        expect($html)->toContain('sort%5Bcolumn%5D=name')
            ->and($html)->toContain('sort%5Bdirection%5D=desc')
            ->and($html)->not->toContain('$set(');
    })->with('table.headers');

    it('can drop the page when sorting', function (array $headers) {
        request()->merge(['page' => 3]);
        request()->server->set('QUERY_STRING', 'page=3');

        expect('<x-table :$headers :$rows :$sort />')
            ->render([
                'headers' => $headers,
                'rows' => [['name' => 'AJ', 'email' => 'aj@bar.com']],
                'sort' => ['column' => 'name', 'direction' => 'asc'],
            ])
            ->not->toContain('page=3');
    })->with('table.headers');

    it('can filter through the query string instead of wire:model', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows filter />', [
            'headers' => $headers,
            'rows' => [['name' => 'AJ', 'email' => 'aj@bar.com']],
        ]);

        expect($html)->toContain('navigate(')
            ->and($html)->toContain('x-on:select.capture')
            ->and($html)->not->toContain('wire:model');
    })->with('table.headers');
});

describe('the persistent anchor', function () {
    it('can anchor the table on itself deriving the id from the page name', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows paginate persistent />', [
            'headers' => $headers,
            'rows' => tablePaginator(pageName: 'simple'),
        ]);

        expect($html)->toContain('id="table-simple"')
            ->and($html)->toContain('#table-simple');
    })->with('table.headers');

    it('can anchor the table on the given id', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows paginate id="foo" persistent />', [
            'headers' => $headers,
            'rows' => tablePaginator(),
        ]);

        expect($html)->toContain('id="foo"')
            ->and($html)->toContain('#foo');
    })->with('table.headers');

    it('can anchor on an external element without claiming the id', function (array $headers) {
        $html = Blade::render('<x-table :$headers :$rows paginate persistent="users" />', [
            'headers' => $headers,
            'rows' => tablePaginator(),
        ]);

        expect($html)->toContain('#users')
            ->and($html)->not->toContain('id="users"')
            ->and($html)->not->toContain('id="table-page"');
    })->with('table.headers');

    it('can carry the anchor into the filter navigation', function (array $headers) {
        expect('<x-table :$headers :$rows filter paginate persistent="users" />')
            ->render(['headers' => $headers, 'rows' => tablePaginator()])
            ->toContain("navigate({ 'search': \$event.target.value }, 'users')");
    })->with('table.headers');

    it('cannot be an empty string', function (array $headers) {
        $this->expectException(ViewException::class);

        expect('<x-table :$headers :$rows persistent="" />')
            ->render(['headers' => $headers, 'rows' => [['name' => 'AJ', 'email' => 'aj@bar.com']]]);
    })->with('table.headers');

    it('cannot leave the table anchored when persistent is absent', function (array $headers) {
        expect('<x-table :$headers :$rows paginate />')
            ->render(['headers' => $headers, 'rows' => tablePaginator()])
            ->not->toContain('#table-page');
    })->with('table.headers');
});

describe('backward compatibility inside the livewire context', function () {
    it('can keep paginating through wire:click', function () {
        $html = tablePaginatorView(['paginator' => tablePaginator(page: 2)]);

        expect($html)->toContain('wire:click="gotoPage(3, \'page\')"')
            ->and($html)->toContain('wire:click="nextPage(\'page\')"')
            ->and($html)->toContain('wire:click="previousPage(\'page\')"')
            ->and($html)->not->toContain('<a href');
    });

    it('can keep the dusk hooks on the pagination buttons', function () {
        $html = tablePaginatorView(['paginator' => tablePaginator(page: 2)]);

        expect($html)->toContain('dusk="nextPage.before"')
            ->and($html)->toContain('dusk="nextPage.after"')
            ->and($html)->toContain('dusk="previousPage.before"')
            ->and($html)->toContain('dusk="previousPage.after"');
    });

    it('can keep the named page in the dusk hooks', function () {
        $html = tablePaginatorView(['paginator' => $paginator = tablePaginator(pageName: 'foo')]);

        expect($html)->toContain('dusk="nextPage.foo.before"')
            ->and($html)->toContain('wire:click="gotoPage(2, \'foo\')"')
            ->and($paginator->getPageName())->toBe('foo');
    });

    it('can keep the wire:key on every page element', function () {
        expect(tablePaginatorView())->toContain('wire:key="paginator-page-page1"')
            ->toContain('wire:key="paginator-page-page2"');
    });

    it('can keep scrolling to the table itself when persistent is a boolean', function () {
        expect(tablePaginatorView(['scroll' => '\$refs.persist.scrollIntoView();']))->toContain('$refs.persist.scrollIntoView();');
    });

    it('can scroll to an external element when persistent is a string', function () {
        $html = tablePaginatorView(['scroll' => "document.getElementById('users')?.scrollIntoView();"]);

        expect($html)->toContain("document.getElementById('users')?.scrollIntoView();")
            ->and($html)->not->toContain('$refs.persist');
    });

    it('cannot scroll when persistent is absent', function () {
        expect(tablePaginatorView())->toContain('x-on:click=""');
    });

    it('can keep the summary counting the whole result set', function () {
        expect(tablePaginatorView())->toContain('Showing')
            ->toContain('>1<')
            ->toContain('>10<')
            ->toContain('>30<');
    });

    it('can keep rendering only previous and next with simple pagination', function () {
        $html = tablePaginatorView(['simple' => true]);

        expect($html)->not->toContain('gotoPage')
            ->and($html)->toContain('nextPage')
            ->and($html)->not->toContain('Showing');
    });
});

describe('the paginator variants', function () {
    it('can render each bundled variant', function (string $variant) {
        $html = Blade::render('<x-table :$headers :$rows paginate :$variant />', [
            'headers' => [['index' => 'name', 'label' => 'Name']],
            'rows' => tablePaginator(),
            'variant' => $variant,
        ]);

        expect($html)->toContain('page=2');
    })->with(['simple', 'minimal', 'compact']);

    it('can default to the configured variant', function () {
        tableConfig(['paginator' => 'compact']);

        expect('<x-table :$headers :$rows paginate />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->toContain('tabular-nums');
    });

    it('can override the configured variant inline', function () {
        tableConfig(['paginator' => 'compact']);

        expect('<x-table :$headers :$rows paginate paginator="simple" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->not->toContain('tabular-nums');
    });

    it('can collapse the page list into an indicator on compact', function () {
        $html = Blade::render('<x-table :$headers :$rows paginate paginator="compact" />', [
            'headers' => [['index' => 'name', 'label' => 'Name']],
            'rows' => tablePaginator(total: 120, perPage: 10, page: 3),
        ]);

        expect($html)->toContain('>3</span>')
            ->and($html)->toContain('>12</span>')
            ->and($html)->not->toContain('Go to page');
    });

    it('can mark the current page with a rule on minimal', function () {
        expect('<x-table :$headers :$rows paginate paginator="minimal" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->toContain('after:bg-primary-600');
    });

    it('can take a view path of its own', function () {
        expect('<x-table :$headers :$rows paginate paginator="ts-ui::components.table.paginators.minimal" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->toContain('after:bg-primary-600');
    });

    it('cannot take an unknown variant', function () {
        $this->expectException(ViewException::class);

        expect('<x-table :$headers :$rows paginate paginator="fancy" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()]);
    });
});

describe('the global defaults', function () {
    it('can paginate every table from the configuration', function () {
        tableConfig(['paginate' => true]);

        expect('<x-table :$headers :$rows />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->toContain('page=2');
    });

    it('can turn the configured pagination back off inline', function () {
        tableConfig(['paginate' => true]);

        expect('<x-table :$headers :$rows :paginate="false" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->not->toContain('page=2');
    });

    it('can filter every table from the configuration', function () {
        tableConfig(['filter' => true]);

        expect('<x-table :$headers :$rows />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => [['name' => 'AJ']]])
            ->toContain('Search something here');
    });

    it('can turn the configured filter back off inline', function () {
        tableConfig(['filter' => true]);

        expect('<x-table :$headers :$rows :filter="false" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => [['name' => 'AJ']]])
            ->not->toContain('Search something here');
    });

    it('can map the filter property names from the configuration', function () {
        tableConfig(['filter' => ['search' => 'term']]);

        expect('<x-table :$headers :$rows />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => [['name' => 'AJ']]])
            ->toContain("navigate({ 'term'");
    });

    it('can set the quantity options from the configuration', function () {
        tableConfig(['filter' => true, 'quantity' => [3, 6]]);

        $html = Blade::render('<x-table :$headers :$rows />', [
            'headers' => [['index' => 'name', 'label' => 'Name']],
            'rows' => [['name' => 'AJ']],
        ]);

        expect($html)->toContain(base64_encode(json_encode([3, 6])));
    });

    it('can reduce every table to previous and next from the configuration', function () {
        tableConfig(['simple-pagination' => true]);

        expect('<x-table :$headers :$rows paginate />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->not->toContain('Showing');
    });

    it('can turn the configured simple pagination back off inline', function () {
        tableConfig(['simple-pagination' => true]);

        expect('<x-table :$headers :$rows paginate :simple-pagination="false" />')
            ->render(['headers' => [['index' => 'name', 'label' => 'Name']], 'rows' => tablePaginator()])
            ->toContain('Showing');
    });
});

it('renders the simple paginator when the rows are not length aware', function (array $headers) {
    $rows = collect(range(1, 11))->map(fn (int $index): array => ['name' => "User {$index}", 'email' => "user{$index}@bar.com"]);

    $paginator = new Paginator($rows, 10, 1, ['path' => 'http://localhost/users']);

    expect('<x-table :$headers :rows="$rows" paginate />')
        ->render(['headers' => $headers, 'rows' => $paginator])
        ->toContain('User 1');
})->with('table.headers');

describe('compact', function () {
    it('tightens the header and the rows', function (array $headers) {
        $rows = [['name' => 'Foo', 'email' => 'foo@bar.com']];

        expect('<x-table :$headers :$rows compact />')
            ->render(['headers' => $headers, 'rows' => $rows])
            ->toContain('px-3 py-2 text-left')
            ->toContain('px-3 py-2.5 text-sm')
            ->not->toContain('py-3.5')
            ->not->toContain('px-3 py-4');
    })->with('table.headers');

    it('keeps the roomy padding by default', function (array $headers) {
        $rows = [['name' => 'Foo', 'email' => 'foo@bar.com']];

        expect('<x-table :$headers :$rows />')
            ->render(['headers' => $headers, 'rows' => $rows])
            ->toContain('py-3.5')
            ->toContain('px-3 py-4')
            ->not->toContain('py-2.5');
    })->with('table.headers');

    it('tightens the empty message', function (array $headers) {
        expect('<x-table :$headers compact />')
            ->render(['headers' => $headers])
            ->toContain('col-span-full whitespace-nowrap px-3 py-2.5');
    })->with('table.headers');

    it('tightens the expandable content', function (array $headers) {
        $rows = [['name' => 'Foo', 'email' => 'foo@bar.com']];

        $component = <<<'BLADE'
        <x-table :$headers :$rows compact expandable>
            @interact('sub_table', $row)
                Sub
            @endinteract
        </x-table>
        BLADE;

        expect($component)
            ->render(['headers' => $headers, 'rows' => $rows])
            ->toContain('px-4 py-2')
            ->not->toContain('px-4 py-3');
    })->with('table.headers');

    it('tightens the skeleton too', function (array $headers) {
        expect('<x-table skeleton :$headers compact />')
            ->render(['headers' => $headers])
            ->toContain('px-3 py-2 text-left')
            ->toContain('px-3 py-2.5 text-sm')
            ->not->toContain('py-3.5');
    })->with('table.headers');
});

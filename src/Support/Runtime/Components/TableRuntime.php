<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Js;
use TallStackUi\Support\Runtime\AbstractRuntime;

class TableRuntime extends AbstractRuntime
{
    /** A headerless skeleton, or one whose headers only arrive in mount(), still needs a width. */
    private const FALLBACK_COLUMNS = 4;

    /** @throws Exception */
    public function runtime(): array
    {
        $headers = $this->data('headers');
        $rows = $this->data('rows');
        $paginated = $rows instanceof AbstractPaginator && $rows->hasPages();

        return [
            ...$this->bind()->only('entangle'),
            'lines' => $this->skeleton(5),
            'columns' => count($headers) ?: self::FALLBACK_COLUMNS,
            'anchor' => $anchor = $this->anchor($rows),
            'anchored' => $anchor !== null && $this->data('persistent') === true && ! $this->wireable(),
            'paginated' => $paginated,
            'pagination' => $paginated ? $this->pagination($rows) : null,
            'paginating' => $paginated ? $this->paginating($rows, $anchor) : [],
            'quantifying' => $this->query('quantity'),
            'searching' => $this->query('search'),
        ];
    }

    /** Where the reader should land after paginating or filtering. */
    private function anchor(mixed $rows): ?string
    {
        $persistent = $this->data('persistent');

        if (is_string($persistent)) {
            return $persistent;
        }

        // Inside Livewire the x-ref is enough; only a full page load needs a fragment.
        if ($persistent !== true || $this->wireable()) {
            return null;
        }

        $id = $this->data('attributes')->get('id');

        if (is_string($id) && filled($id)) {
            return $id;
        }

        // The id must survive the next request, or the fragment points at nothing.
        return $rows instanceof AbstractPaginator ? 'table-'.$rows->getPageName() : null;
    }

    /** Everything the paginator views share, resolved once instead of in each variant. */
    private function paginating(AbstractPaginator $rows, ?string $anchor): array
    {
        $name = $rows->getPageName();

        return [
            'livewire' => $this->wireable(),
            // The full paginator views call total(), lastPage() and $elements, which
            // only a length-aware paginator answers.
            'simple' => $this->data('simplePagination') || ! $rows instanceof LengthAwarePaginator,
            'name' => $name,
            'dusk' => $name === 'page' ? '' : '.'.$name,
            'fragment' => $anchor !== null ? '#'.$anchor : '',
            'scroll' => $this->scroll(),
        ];
    }

    private function pagination(AbstractPaginator $rows): AbstractPaginator
    {
        $rows->onEachSide($this->data('onEachSide'));

        // Real URLs drop the active filter and sort unless they are appended.
        return $this->wireable() ? $rows : $rows->withQueryString();
    }

    private function query(string $key): ?string
    {
        $parameter = $this->data("filter.{$key}");

        if ($this->wireable() || blank($parameter)) {
            return null;
        }

        $value = request()->query($parameter);

        return is_string($value) ? $value : null;
    }

    /**
     * Inside Livewire nothing reloads, so the scroll runs by hand. A string
     * [persistent] targets an element the table does not own, out of x-ref reach.
     * Js::from, never interpolation: the browser decodes the attribute before
     * Alpine evaluates it, so an escaped quote would come back as a quote.
     */
    private function scroll(): string
    {
        $persistent = $this->data('persistent');

        if (! $this->wireable() || blank($persistent) || $persistent === false) {
            return '';
        }

        return is_string($persistent)
            ? 'document.getElementById('.Js::from($persistent).')?.scrollIntoView();'
            : '$refs.persist.scrollIntoView();';
    }
}

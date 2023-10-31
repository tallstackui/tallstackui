<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Select\Styled;
use Throwable;

class SelectStyledValidations
{
    /** @throws Throwable */
    public function __invoke(Styled $select): void
    {
        throw_if(blank($select->placeholders['default']), new Exception('The placeholder [default] cannot be empty.'));
        throw_if(blank($select->placeholders['search']), new Exception('The placeholder [search] cannot be empty.'));
        throw_if(blank($select->placeholders['empty']), new Exception('The placeholder [empty] cannot be empty.'));

        if ($select->ignoreValidations) {
            return;
        }

        if (filled($select->options) && filled($select->request)) {
            throw new InvalidArgumentException('You cannot define [options] and [request] at the same time.');
        }

        if (($select->common && isset($select->options[0]) && (is_array($select->options[0]) && ! $select->select)) || ! $select->common && ! $select->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }

        if ($select->common || $select->request && ! is_array($select->request)) {
            return;
        }

        if (! isset($select->request['url'])) {
            throw new InvalidArgumentException('The [url] is required in the request array');
        }

        $select->request['method'] ??= 'get';
        $select->request['method'] = strtolower($select->request['method']);

        if (! in_array($select->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [method] must be get or post');
        }

        if (! isset($select->request['params'])) {
            return;
        }

        if (! is_array($select->request['params']) || blank($select->request['params'])) {
            throw new InvalidArgumentException('The [params] must be an array and cannot be empty');
        }
    }
}

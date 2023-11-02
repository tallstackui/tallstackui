<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Select\Styled;
use Throwable;

class SelectStyledValidations
{
    /** @throws Throwable */
    public function __invoke(Styled $component): void
    {
        throw_if(blank($component->placeholders['default']), new Exception('The placeholder [default] cannot be empty.'));
        throw_if(blank($component->placeholders['search']), new Exception('The placeholder [search] cannot be empty.'));
        throw_if(blank($component->placeholders['empty']), new Exception('The placeholder [empty] cannot be empty.'));

        if ($component->ignoreValidations) {
            return;
        }

        if (filled($component->options) && filled($component->request)) {
            throw new InvalidArgumentException('You cannot define [options] and [request] at the same time.');
        }

        if (($component->common && isset($component->options[0]) && (is_array($component->options[0]) && ! $component->select)) || ! $component->common && ! $component->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }

        if ($component->common || $component->request && ! is_array($component->request)) {
            return;
        }

        if (! isset($component->request['url'])) {
            throw new InvalidArgumentException('The [url] is required in the request array');
        }

        $component->request['method'] ??= 'get';
        $component->request['method'] = strtolower($component->request['method']);

        if (! in_array($component->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [method] must be get or post');
        }

        if (! isset($component->request['params'])) {
            return;
        }

        if (! is_array($component->request['params']) || blank($component->request['params'])) {
            throw new InvalidArgumentException('The [params] must be an array and cannot be empty');
        }
    }
}

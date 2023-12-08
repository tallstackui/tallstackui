<?php

namespace TallStackUi\Actions\Traits;

use InvalidArgumentException;
use TallStackUi\Actions\AbstractInteraction;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithConfirmation
{
    public function confirm(string|array $data, ?string $description = null, ?array $options = null): AbstractInteraction
    {
        throw_if(
            (is_string($data) && ! $options) || (is_array($data) && ! isset($data['options'])),
            new InvalidArgumentException('You must provide options for the interaction')
        );

        $options['confirm']['text'] ??= __('tallstack-ui::messages.toast.button.confirm');
        $options['cancel']['text'] ??= __('tallstack-ui::messages.toast.button.cancel');

        $default = [
            'type' => 'question',
            'confirm' => true,
        ];

        if (is_string($data)) {
            return $this->send([
                ...$default,
                'title' => $data,
                'description' => $description,
                'options' => $options,
            ]);
        }

        return $this->send([...array_merge($default, $data)]);
    }
}

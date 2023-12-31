<?php

namespace TallStackUi\Actions\Traits;

use Exception;
use TallStackUi\Actions\AbstractInteraction;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithConfirmation
{
    public function confirm(string|array $data, ?string $description = null, ?array $options = null): AbstractInteraction
    {
        $class = [
            Dialog::class => 'dialog',
            Toast::class => 'toast',
        ][static::class];

        if ((is_string($data) && ! $options) || (is_array($data) && ! isset($data['options']))) {
            throw new Exception("You must provide options for the [$class] interaction");
        }

        if (! isset($options['confirm']['method'])) {
            throw new Exception("You must provide a method for the [$class] confirm button");
        }

        [$confirm, $cancel] = $this->messages();

        $options['confirm']['text'] ??= $confirm;
        $options['cancel']['text'] ??= $cancel;

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

    abstract public function messages(): array;
}

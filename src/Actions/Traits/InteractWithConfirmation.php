<?php

namespace TallStackUi\Actions\Traits;

use TallStackUi\Actions\Dialog;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithConfirmation
{
    public function cancel(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        [, $message] = $this->messages();

        $this->data['options']['cancel'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $message,
            'method' => $method,
            'params' => is_array($params) ? [...$params] : $params,
        ];

        return $this;
    }

    public function confirm(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        [$message] = $this->messages();

        $this->data['options']['confirm'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $message,
            'method' => $method,
            'params' => is_array($params) ? [...$params] : $params,
        ];

        return $this;
    }

    private function static(): void
    {
        // We just need this for the Dialog because only the Dialog needs
        // to display the basic buttons when not confirming or canceling.
        if (! $this instanceof Dialog) {
            return;
        }

        // For the Dialog this is necessary when not confirming
        // or canceling, to display the basic button in the center.
        $this->data['options']['confirm'] = ['static' => true];
    }
}

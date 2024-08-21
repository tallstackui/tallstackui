<?php

namespace TallStackUi\Foundation\Actions\Traits;

use TallStackUi\Foundation\Actions\Dialog;

/**
 * @internal
 */
trait InteractWithConfirmation
{
    /** Sets the cancellation aspect */
    public function cancel(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        $this->wireable('cancel');

        $message = $this->messages()[1];

        $this->data['options']['cancel'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $message,
            'method' => $method,
            'params' => is_array($params) ? [...$params] : $params,
        ];

        return $this;
    }

    /** Sets the confirmation aspect */
    public function confirm(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        $this->wireable('confirm');

        $message = $this->messages()[0];

        $this->data['options']['confirm'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $message,
            'method' => $method,
            'params' => is_array($params) ? [...$params] : $params,
        ];

        return $this;
    }

    /**
     * Determine the interaction as static for normal types (success, error, warning, info).
     */
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

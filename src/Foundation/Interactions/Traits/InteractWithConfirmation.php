<?php

namespace TallStackUi\Foundation\Interactions\Traits;

use TallStackUi\Foundation\Interactions\Dialog;

/**
 * @internal
 */
trait InteractWithConfirmation
{
    /** Sets the cancellation aspect */
    public function cancel(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        $this->wireable('cancel');

        $this->data['options']['cancel'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $this->messages()[1],
            'method' => $method,
            'params' => is_array($params) ? [...$params] : $params,
        ];

        return $this;
    }

    /** Sets the confirmation aspect */
    public function confirm(?string $text = null, ?string $method = null, array|string|int|null $params = null): self
    {
        $this->wireable('confirm');

        $this->data['options']['confirm'] = [
            'static' => blank($text) && blank($method),
            'text' => $text ?? $this->messages()[0],
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

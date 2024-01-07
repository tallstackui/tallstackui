<?php

namespace TallStackUi\Actions\Traits;

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
            'params' => ! is_array($params) ? $params : [...$params],
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
            'params' => ! is_array($params) ? $params : [...$params],
        ];

        return $this;
    }

    private function static(): void
    {
        $this->data['options']['confirm'] = ['static' => true];
    }
}

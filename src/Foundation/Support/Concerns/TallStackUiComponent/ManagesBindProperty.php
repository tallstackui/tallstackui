<?php

namespace TallStackUi\Foundation\Support\Concerns\TallStackUiComponent;

use Exception;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Support\Blade\BladeBindProperty;

trait ManagesBindProperty
{
    /** @throws Exception */
    public function bind(ComponentAttributeBag $attributes, ?ViewErrorBag $errors = null, bool $livewire = false): array
    {
        return app(BladeBindProperty::class, [
            'attributes' => $attributes,
            'errors' => $errors,
            'invalidate' => $this->data()['invalidate'] ?? false,
            'livewire' => $livewire,
        ])->data();
    }
}

<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use Closure;
use Illuminate\Contracts\View\View;
use ReflectionClass;
use TallStackUi\Foundation\Attributes\RequireLivewireContext;
use TallStackUi\Foundation\Exceptions\MissingLivewireException;
use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;

trait ManagesRender
{
    abstract public function blade(): View;

    public function render(): Closure
    {
        return function (array $data): View|string {
            // This is an approach used to avoid having to "manually" check (isset($__livewire))
            // whether the component is being used within the Livewire context or not.
            $livewire = isset($this->factory()->getShared()['__livewire']);

            $require = (new ReflectionClass($this))->getAttributes(RequireLivewireContext::class) !== [];

            if (! $livewire && $require) {
                throw new MissingLivewireException(app(BladeComponentPrefix::class)->remove($this->componentName));
            }

            return $this->output($this->blade()->with(array_merge($this->compile($data), [
                'livewire' => $livewire,
            ])), $data);
        };
    }
}

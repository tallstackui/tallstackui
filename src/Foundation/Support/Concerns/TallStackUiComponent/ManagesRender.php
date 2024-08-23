<?php

namespace TallStackUi\Foundation\Support\Concerns\TallStackUiComponent;

use Closure;
use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\RequireLivewireContext;
use TallStackUi\Foundation\Exceptions\MissingLivewireException;
use TallStackUi\Foundation\Support\Blade\ComponentPrefix;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;
use TallStackUi\Foundation\Support\Runtime\CompileRuntime;

trait ManagesRender
{
    abstract public function blade(): View;

    public function render(): Closure
    {
        if (method_exists($this, 'setup')) {
            $this->setup();
        }

        return function (array $data): View|string {
            $shared = $this->factory()->getShared();

            // This is an approach used to avoid having to "manually" check (isset($__livewire))
            // whether the component is being used within the Livewire context or not.
            $livewire = isset($shared['__livewire']);

            $require = app(ReflectComponent::class, ['component' => static::class])->attribute(RequireLivewireContext::class);

            if (! $livewire && $require !== null) {
                throw new MissingLivewireException(app(ComponentPrefix::class)->remove($this->componentName));
            }

            return $this->output($this->blade()->with(array_merge($this->compile($data), [
                'livewire' => $livewire,
                ...CompileRuntime::of($data, component: $this, livewire: $livewire, errors: $shared['errors'] ?? null),
            ])), $data);
        };
    }
}

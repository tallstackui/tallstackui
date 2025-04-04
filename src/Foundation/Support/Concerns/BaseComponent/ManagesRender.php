<?php

namespace TallStackUi\Foundation\Support\Concerns\BaseComponent;

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
        // The idea of this method is to concentrate large amounts of code that must be
        // executed when using the component. This is just an alternative to having to
        // store a lot of logic in the __construct of the component classes. For small
        // amounts of code, we can use __construct, for a lot of code, we use setup.
        if (method_exists($this, 'setup')) {
            $this->setup($this->data());
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
                ...CompileRuntime::of($this, data: $data, shared: $shared),
            ])), $data);
        };
    }
}

<?php

namespace TallStackUi\Support\Concerns\BaseComponent;

use Closure;
use Illuminate\Contracts\View\View;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Exceptions\MissingLivewireException;
use TallStackUi\Support\Blade\ComponentPrefix;
use TallStackUi\Support\Miscellaneous\ReflectComponent;
use TallStackUi\Support\Runtime\CompileRuntime;

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
            $factory = $this->factory();
            $shared = $factory->getShared();

            // This is an approach used to avoid having to "manually" check (isset($__livewire))
            // whether the component is being used within the Livewire context or not.
            $livewire = isset($shared['__livewire']);

            $require = app(ReflectComponent::class, ['component' => static::class])->attribute(RequireLivewireContext::class);

            // A skeleton binds nothing to Livewire, and the placeholder of a
            // #[Lazy] component renders outside its context. Requiring it there
            // would make the placeholder unable to draw the very component it
            // stands in for.
            $skeleton = method_exists($this, 'skeletonized') && $this->skeletonized();

            if (! $livewire && $require !== null && ! $skeleton) {
                throw new MissingLivewireException(app(ComponentPrefix::class)->remove($this->componentName));
            }

            return $this->output($this->blade()->with(array_merge($this->compile($data), [
                'livewire' => $livewire,
                ...CompileRuntime::of($this, $factory, $data, $shared),
            ])), $data);
        };
    }
}

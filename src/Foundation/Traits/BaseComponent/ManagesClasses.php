<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use Exception;
use Illuminate\Support\Arr;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

trait ManagesClasses
{
    /**
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function classes(): array
    {
        if (! $this instanceof Personalization) {
            return [];
        }

        // Trying to get the attribute from the current class, and if we
        // can't find it, we get the attribute from the parent class in
        // case of the deep personalization extending the original component.
        $attribute = rescue(
            /** @throws Exception */
            function () {
                $attribute = collect((new ReflectionClass($this))->getAttributes(SoftPersonalization::class))->first();

                // We need to throw an exception here to trigger the rescue.
                if ($attribute === null) {
                    throw new Exception('No attribute found');
                }

                return $attribute;
            }, fn () => collect((new ReflectionClass(get_parent_class($this)))->getAttributes(SoftPersonalization::class))->first(), false);

        if (! $attribute || empty($attribute->getArguments())) {
            return [];
        }

        unset($this->attributes['personalize']);

        $soft = TallStackUi::personalize(str_replace('tallstack-ui::personalizations.', '', $attribute->newInstance()->key()))
            ->instance()
            ->toArray();

        $scope = $this->attributes['scope'] ?? null;
        $scoped = [];

        if ($scope) {
            unset($this->attributes['scope']);

            // TODO: test it!
            // For cases where the component may be being personalized through deep
            // personalization we look in the current component or the parent component.
            // Since the attribute will be found anywhere, we use the attribute -
            // flipping components, rather than something like static::class.
            $component = __ts_search_component($attribute->newInstance()->key(), true);

            $scoped = rescue(fn () => app()->get(__ts_scope_container_key($component, $scope))->toArray(), [], false);

            if (filled($scoped)) {
                $scoped = data_get($scoped, $scope, $scoped);
            }
        }

        $merge = is_null($scope)
            // When null, we only use the default personalization that comes from the component.
            ? $soft
            // Otherwise, we merge the soft with scoped, but prioritize scoped.
            : Arr::only(array_merge($soft, $scoped), array_keys($scoped));

        // Here we do a second merge, now with the original classes and the result
        // of the previous operation that will use the scope smooth prioritization
        // and customization settings. This is extremely necessary for cases where
        // $merge does not contain all the necessary keys in use by the component.
        $classes = Arr::only(array_merge($personalization = $this->personalization(), $merge), array_keys($personalization));

        // Lastly, we just pass the classes to a special hook method to allow
        // manipulation when necessary - a good example for this is the flat button.
        if (method_exists($this, 'manipulation')) {
            $classes = $this->manipulation($classes);
        }

        return $classes;
    }
}

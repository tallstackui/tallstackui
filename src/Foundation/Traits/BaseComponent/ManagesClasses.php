<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use Exception;
use Illuminate\Support\Arr;
use ReflectionClass;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\BuildScopePersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

trait ManagesClasses
{
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

        $scope = app(BuildScopePersonalization::class, [
            'classes' => $personalization = $this->personalization(),
            'attributes' => $this->attributes['personalize'],
        ])->execute();

        unset($this->attributes['personalize']); // We unset this because is useless after here.

        $soft = TallStackUi::personalize(str_replace('tallstack-ui::personalizations.', '', $attribute->newInstance()->key()))
            ->instance()
            ->toArray();

        $merged = empty($scope) ? $soft : Arr::only(array_merge($soft, $scope), array_keys($scope)); // We merge scope with soft personalization changes, but we prioritize scope changes.

        // Here we do a second merge, now with the original classes and
        // the result of the previous operation that will use scoped
        // prioritization and soft personalization definitions.
        $classes = Arr::only(array_merge($personalization, $merged), array_keys($personalization));

        // We use 'manipulation' method to manipulate default
        // classes that come from the personalization method.
        if (method_exists($this, 'manipulation')) {
            $classes = $this->manipulation($classes);
        }

        return $classes;
    }
}

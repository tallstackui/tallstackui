<?php

namespace TallStackUi\Support\Concerns;

use Exception;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\DynamicComponent;
use TallStackUi\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Support\Icons\IconGuideMap;

trait BuildRawIcon
{
    // Laravel's dynamic component binds each attribute of a class-less icon
    // again, which encodes a second time what the tag had already encoded.
    // Its template tells when that happens, so the decoding never reaches
    // an icon that prints the attributes as they are.
    public function forward(ComponentAttributeBag $attributes, ?string $path = null): ComponentAttributeBag
    {
        $decoded = array_map(
            fn (mixed $value): mixed => is_string($value) ? htmlspecialchars_decode($value, ENT_QUOTES) : $value,
            $attributes->getAttributes(),
        );

        if ($decoded === $attributes->getAttributes()) {
            return $attributes;
        }

        $template = (new DynamicComponent($this->raw($path)))->withAttributes(['forward' => true])->render()(['__laravel_slots' => []]); // @phpstan-ignore-line

        return str_contains($template, ':forward="$forward"') ? new ComponentAttributeBag($decoded) : $attributes;
    }

    /** @throws Exception */
    public function raw(?string $path = null): string
    {
        InappropriateIconGuideExecution::validate(static::class);

        return IconGuideMap::build($this, $path);
    }
}

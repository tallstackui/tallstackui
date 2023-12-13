<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\Facades\Blade;
use Livewire\WireDirective;

class BladeSupport
{
    public function entangle(?WireDirective $attribute = null): string
    {
        if (! $attribute) {
            return Blade::render('null');
        }

        $value = $attribute->value();

        return $attribute->hasModifier('live') || $attribute->hasModifier('blur')
            ? Blade::render("@entangle('{$value}').live")
            : Blade::render("@entangle('{$value}')");
    }

    public function json(array $data = []): string
    {
        if (count(array_filter(array_keys($data), 'is_string')) === 0) {
            return "JSON.parse(atob('".base64_encode(json_encode($data))."'))";
        }

        $expressions = '';

        $parse = function ($value) {
            return match (true) {
                $value instanceof WireDirective => function () use ($value) {
                    return $this->entangle($value);
                },
                is_array($value), is_object($value) => fn () => "JSON.parse(atob('".base64_encode(json_encode($value))."'))",
                is_string($value) => fn () => "'".str_replace("'", "\'", $value)."'",
                default => fn () => json_encode($value),
            };
        };

        foreach ($data as $key => $value) {
            $expressions .= "{$key}:{$parse($value)()},";
        }

        return "{{$expressions}}";
    }
}

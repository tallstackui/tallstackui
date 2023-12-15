@php
    $personalize = $classes();
    $wire = $wireable($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
@endphp

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <div x-data="tallstackui_formPin(
             @entangleable($attributes),
             @js($prefix),
             @js($prefixed),
             @js($length),
             @js($clear)
         )"
         @class($personalize['wrapper'])
         x-on:paste="pasting = true; paste($event)"
         x-cloak>
        <template x-for="(value, index) in prefix" :key="key(index)">
            <input @class([
                       $personalize['input.base'],
                       $personalize['input.color.base'],
                       $personalize['input.color.background'],
                   ]) maxlength="1" :value="value" disabled />
        </template>
        <template x-for="(value, index) in length" :key="key(index)">
            <input :id="key(index)"
                   @if ($mask) x-mask="{{ $mask }}" @endif
                   @class([
                       $personalize['input.base'],
                       $personalize['input.color.background'],
                       $personalize['input.color.base'] => !$error,
                       $personalize['input.color.error'] => $error,
                   ]) maxlength="1"
                   x-on:keyup="go(index, $event)"
                   x-on:keyup.left="left(index)"
                   x-on:keyup.right="right(index)"
                   x-on:keydown.backspace="back(index)" />
        </template>
        <template x-if="clear && model">
            <button class="cursor-pointer" x-on:click="erase();">
                <x-icon name="x-circle" solid @class($personalize['button']) />
            </button>
        </template>
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error)
        <x-error :$wire/>
    @endif
</div>

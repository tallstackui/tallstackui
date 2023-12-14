@php($personalize = tallstackui_personalization('tab.items', $personalization()))

@aware(['id'])

<li x-on:click="select('{{ $tab }}')"
    x-bind:class="{
        '{{ $personalize['select'] }}' : tab === '{{ $tab }}',
        '{{ $personalize['unselect'] }}' : tab !== '{{ $tab }}'
    }">
   <div @class($personalize['wrapper'])>
        @if ($left)
           {{ $left }}
        @endif
            {{ $tab }}
        @if ($right)
            {{ $right }}
        @endif
   </div>
</li>

<template x-teleport="#tab-select-{{ $id }}">
    <option value="{{ $tab }}">{{ $tab }}</option>
</template>

<template x-teleport="#tab-content-{{ $id }}">
    <div x-show="tab === '{{ $tab }}'">
        {{ $slot }}
    </div>
</template>

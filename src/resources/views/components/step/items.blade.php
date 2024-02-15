@aware(['variation', 'id'])
@php
    $view = "tallstack-ui::components.step.variation.$variation";
@endphp

@include($view, ['step' => $step, 'title' => $title, 'description' => $description])

{{-- <x-dynamic-component :component="$view" :$step :$title :$description /> --}}

<template x-teleport="#step-content-{{ $id }}">
    <div x-show="currentStep == {{ $step }}">
        {{ $slot }}
    </div>
</template>

@php
    $customization = $classes();
@endphp

@livewire(\TallStackUi\Livewire\Comments\Component::class, [
    'model' => $model,
    'settings' => $settings,
    'classes' => $customization,
], key($key()))

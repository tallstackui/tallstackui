<div role="tabpanel" 
     x-show="selected == '{{ $step }}'" 
     x-init="steps.push({ step: '{{ $step }}', title: '{{ $title }}', description: '{{ $description }}' });">
    {{ $slot }}
</div>

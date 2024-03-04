<div role="tabpanel"
     x-show="selected === {{ $step }}"
     x-init="steps.push({ step: @js($step), title: @js($title), description: @js($description), completed: @js($completed) })">
    {{ $slot }}
</div>

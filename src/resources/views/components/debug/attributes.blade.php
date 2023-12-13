@php
    $ignores = ['slot', 'trigger', 'content'];

    $lines = collect($data)
        ->filter(fn (mixed $value, string $key) => ! is_array($value) && ! is_callable($value) && ! in_array($key, $ignores));
@endphp

<div>
    <span class="flex items-center justify-center text-red-500">{{ $lines->get('componentName')  }}</span>
    <ul class="mt-2">
        @forelse ($lines->except('componentName') as $key => $value)
            <li>{{ $key }}: <span class="text-red-500">{{ $value }}</span></li>
            @if ($loop->last && $data['slot']->isNotEmpty())
                <li class="inline-flex gap-x-1">slot mode: <x-tallstack-ui::icon.solid.check class="w-4 h-4 text-green-500" /></li>
            @endif
        @empty
            <span class="text-white">No attributes</span>
        @endforelse
    </ul>
</div>


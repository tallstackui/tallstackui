@php
    $name = $data['componentName'];
    $attributes = $data['attributes'];
    $ignores = ['slot', 'trigger', 'content', 'componentName'];

    // Although we can use a single filter here, it was
    // preferable to do it this way to increase readability.
    $properties = collect($data)
        ->filter(fn (mixed $value, string $key) => ! is_array($value))
        ->filter(fn (mixed $value, string $key) => ! is_callable($value))
        ->filter(fn (mixed $value, string $key) => ! in_array($key, $ignores));
@endphp

<div>
    <span class="py flex justify-center rounded-lg bg-red-500 px-1">
        {{ $name }}
    </span>
    <ul class="mt-2">
        @forelse ($properties as $key => $value)
            <li>{{ $key }}: <span class="text-red-500">{{ $value }}</span></li>
            @if ($loop->last && $data['slot']->isNotEmpty())
                <li class="inline-flex gap-x-1">slot mode: <x-tallstack-ui::icon.solid.check class="w-4 h-4 text-green-500" /></li>
            @endif
        @empty
            <span class="text-white">No attributes</span>
        @endforelse
    </ul>
    @if (is_string($attributes) && (string) $attributes !== '')
        <span class="mt-1 py flex justify-center text-red-500 px-1">
            Attributes
        </span>
        <ul class="mt-0.5">
            @foreach ($attributes as $key => $value)
                <li>{{ $key }}: <span class="text-red-500">{{ $value }}</span></li>
            @endforeach
        </ul>
    @endif
</div>


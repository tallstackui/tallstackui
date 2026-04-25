@php
    $customization = $classes();
@endphp

<div
    {{ $attributes->class([
        $customization['wrapper.vertical'] => ! $horizontal && ! $compact,
        $customization['wrapper.vertical-compact'] => ! $horizontal && $compact,
        $customization['wrapper.horizontal'] => $horizontal && ! $compact,
        $customization['wrapper.horizontal-compact'] => $horizontal && $compact,
    ]) }}
    dusk="tallstackui_timeline"
>
    @if ($computedItems !== [])
        @foreach ($computedItems as $item)
            <x-dynamic-component :component="TallStackUi::prefix('timeline.items')"
                                 :title="$item['title']"
                                 :description="$item['description']"
                                 :date="$item['date']"
                                 :icon="$item['icon']"
                                 :color="$item['color']"
                                 :reversed="$item['reversed']"
                                 :style="$style"
                                 :horizontal="$horizontal"
                                 :alternate="$alternate"
                                 :compact="$compact"
            />
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>

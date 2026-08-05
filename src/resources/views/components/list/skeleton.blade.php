@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite"
     {{ $attributes->class([$customization['wrapper'], $customization['skeleton.animation']]) }}>
    @if ($label)
        <div @class([$customization['skeleton.bar'], $customization['skeleton.label']])></div>
    @endif

    <div class="{{ $customization['box'] }}">
        @if ($searchable)
            <div @class([
                    $customization['search.wrapper'] => ! $compact,
                    $customization['search.wrapper-compact'] => $compact,
                 ])>
                <div @class([$customization['skeleton.bar'], $customization['skeleton.search']])></div>
            </div>
        @endif

        <div @class([
            $customization['skeleton.items.wrapper'],
            $customization['items.scroll'] => $height !== null,
            ($customization['items.height.'.$height] ?? '') => $height !== null,
        ])>
            @for ($line = 1; $line <= $lines; $line++)
                <div @class([
                        $customization['skeleton.items.row'] => ! $compact,
                        $customization['skeleton.items.row-compact'] => $compact,
                     ])>
                    <div class="{{ $customization['skeleton.items.content'] }}">
                        <div @class([$customization['skeleton.bar'], $customization['skeleton.name']])></div>
                        <div @class([$customization['skeleton.bar'], $customization['skeleton.caption']])></div>
                    </div>
                    <div @class([$customization['skeleton.bar'], $customization['skeleton.menu']])></div>
                </div>
            @endfor
        </div>
    </div>

    @if ($hint)
        <div @class([$customization['skeleton.bar'], $customization['skeleton.hint']])></div>
    @endif
</div>

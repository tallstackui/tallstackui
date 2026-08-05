@aware(['compact' => false])

@php
    $customization = $classes();
@endphp

<div @class([
        $customization['wrapper'] => ! $compact,
        $customization['wrapper-compact'] => $compact,
     ])
     data-list-row
     data-list-on
     x-bind:data-list-name="item.name">
    <div class="{{ $customization['content.wrapper'] }}">
        <div class="{{ $customization['content.inner'] }}">
            <span class="{{ $customization['name'] }}"
                  x-text="item.name"></span>
            <span class="{{ $customization['caption'] }}"
                  x-show="item.caption"
                  x-text="item.caption"></span>
        </div>
    </div>
</div>

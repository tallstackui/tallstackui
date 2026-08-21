@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite" {{ $attributes->class([$customization['skeleton.animation']]) }}>
    <nav @if ($variation === 'panels') class="overflow-hidden {{ $customization['panels-shape'] }}" @endif>
        <ul role="list" @class($customization['wrapper.'.$variation])>
            @for ($step = 1; $step <= $lines; $step++)
                @if ($variation === 'circles')
                    <li class="{{ $customization['circles.li'] }}">
                        <div class="{{ $customization['circles.wrapper'] }}">
                            <div @class([$customization['skeleton.bar'], $customization['skeleton.circle']])></div>
                            <div @class([$customization['circles.divider.wrapper'], $customization['circles.divider.inactive']])></div>
                        </div>
                        <div class="{{ $customization['circles.text.wrapper'] }} space-y-2">
                            <div @class([$customization['skeleton.bar'], $customization['skeleton.title']])></div>
                            <div @class([$customization['skeleton.bar'], $customization['skeleton.description']])></div>
                        </div>
                    </li>
                @elseif ($variation === 'panels')
                    <li class="{{ $customization['panels.li'] }}">
                        <div class="{{ $customization['panels.wrapper'] }}">
                            <div class="{{ $customization['panels.item'] }}">
                                <div @class([$customization['skeleton.bar'], $customization['skeleton.panel-circle']])></div>
                                <div class="ml-4 space-y-2">
                                    <div @class([$customization['skeleton.bar'], $customization['skeleton.title']])></div>
                                    <div @class([$customization['skeleton.bar'], $customization['skeleton.description']])></div>
                                </div>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="{{ $customization['simple.li'] }}">
                        <div @class([$customization['simple.bar.wrapper'], $customization['simple.bar.inactive'], 'space-y-2'])>
                            <div @class([$customization['skeleton.bar'], $customization['skeleton.title']])></div>
                            <div @class([$customization['skeleton.bar'], $customization['skeleton.description']])></div>
                        </div>
                    </li>
                @endif
            @endfor
        </ul>
    </nav>
    <div class="{{ $customization['content'] }}">
        <div @class([$customization['skeleton.bar'], $customization['skeleton.content']])></div>
    </div>
    @if ($helpers)
        <div class="{{ $customization['helpers.wrapper'] }}">
            <div>
                @if ($navigatePrevious)
                    <div @class([$customization['skeleton.bar'], $customization['skeleton.helper']])></div>
                @endif
            </div>
            <div @class([$customization['skeleton.bar'], $customization['skeleton.helper']])></div>
        </div>
    @endif
</div>

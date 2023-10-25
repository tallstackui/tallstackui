@php($customize = tallstackui_personalization('wrapper.input', $personalization()))

<div @if ($alpine) x-data="{!! $alpine !!}" @endif>
    @if ($label)
        <x-label :$error>
            {{ $label }} @if ($attributes['required']) <i class="text-red-500 font-bold not-italic">*</i> @endif
        </x-label>
    @endif
    <div @class($customize['wrapper']) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error && $validate)
        <x-error :$computed :$error/>
    @endif
</div>

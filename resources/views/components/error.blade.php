@props(['computed', 'error'])

@error ($computed)
<span class="mt-2 text-sm text-red-500">
    {{ $message }}
</span>
@enderror
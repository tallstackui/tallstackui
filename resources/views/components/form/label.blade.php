<label @if ($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'block text-sm font-medium leading-6 text-gray-900']) }}>{{ $text ?? $slot }}</label>

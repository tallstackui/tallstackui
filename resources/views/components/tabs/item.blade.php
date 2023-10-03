<div x-show="selected(@js($tab))" role="tabpanel" {{ $attributes->merge(['class' => 'bg-white rounded-bl-lg rounded-br-lg rounded-tr-lg dark:bg-gray-600 p-6']) }}>
    {{ $slot }}
</div>

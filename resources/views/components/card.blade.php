<div class="flex justify-center gap-4">
    <div class="flex w-full flex-col rounded-lg bg-white shadow-md">
        <div @class(['flex items-center justify-between', 'border-b px-4 py-2.5' => $header !== null])>
            @if ($header)
                <h3 class="font-medium text-md text-secondary-700">
                    {{ $header }}
                </h3>
            @endif
        </div>
        <div {{ $attributes->class(['grow rounded-b-xl px-2 py-5 text-secondary-700 md:px-4']) }}>
            {{ $slot }}
        </div>
        @if ($footer)
            <div class="rounded-lg rounded-t-none border-t px-4 py-4 bg-secondary-50 sm:px-6">
                <div class="flex items-center justify-end gap-2">
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>

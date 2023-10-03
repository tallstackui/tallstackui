<div x-data="tasteui_tabs(@js($selected))" class="w-full" x-cloak>
    <ul x-ref="tablist"
        role="tablist"
        class="-mb-px flex items-stretch overflow-auto">
        @foreach ($options as $tab)
            <li id="{{ $tab }}"
                class="inline-flex truncate px-5 py-2.5 text-gray-700 transition cursor-pointer rounded-t-lg"
                x-on:click="select(@js($tab))"
                x-bind:aria-selected="selected(@js($tab))"
                x-bind:class="selected(@js($tab)) ? 'bg-white text-primary font-semibold' : 'opacity-50'"
                role="tab">
                {{ $tab }}
            </li>
        @endforeach
    </ul>
    <div>
        {{ $slot }}
    </div>
</div>

<div class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden text-sm">
    <div class="grid grid-cols-2 bg-gray-200 px-4 py-2 font-semibold text-gray-600">
        <div>Key</div>
        <div>Value</div>
    </div>
    <div class="divide-y divide-gray-300">
        <div class="grid grid-cols-2 px-4 py-2 items-start relative">
            <div class="text-gray-600">description</div>
            <div class="relative pr-8">
                <div class="overflow-x-auto custom-scrollbar whitespace-nowrap text-gray-600">
                    Filament is a collection of Laravel packages that has a very long description that might overflow the visible space of this cell.
                </div>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('trash')"
                                     internal
                                     class="absolute top-0 right-0 h-5 w-5 cursor-pointertext-red-500 hover:text-red-700" />
            </div>
        </div>
        <div class="grid grid-cols-2 px-4 py-2 items-start relative">
            <div class="text-gray-600">og:type</div>
            <div class="relative pr-8">
                <div class="overflow-x-auto whitespace-nowrap text-gray-600">
                    website
                </div>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('trash')"
                                     internal
                                     class="absolute top-0 right-0 h-5 w-5 cursor-pointertext-red-500 hover:text-red-700" />
            </div>
        </div>
    </div>
    <div class="px-4 py-2 text-center text-gray-600 hover:text-white hover:underline cursor-pointer bg-gray-200">
        Add row
    </div>
</div>

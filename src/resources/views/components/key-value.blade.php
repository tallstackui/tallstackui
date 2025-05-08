<div class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden text-sm">
    <div class="grid grid-cols-2 bg-gray-200 px-4 py-2 font-semibold text-gray-600">
        <p>KEY</p>
        <p>VALUE</p>
    </div>
    <div class="divide-y divide-gray-300">
        <div class="grid grid-cols-2 px-4 py-2 items-start relative">
            <div class="text-gray-600">
                <input value="description" class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
            </div>
            <div class="relative pr-8">
                <div class="text-gray-600">
                    <input value="Filament is a collection of Laravel packages that has a very long description that might overflow the visible space of this cell." class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
                </div>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('trash')"
                                     internal
                                     class="absolute top-2 right-0 h-5 w-5 cursor-pointer text-red-500 hover:text-red-700" />
            </div>
        </div>
        <div class="grid grid-cols-2 px-4 py-2 items-start relative">
            <div class="text-gray-600">
                <input value="description" class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
            </div>
            <div class="relative pr-8">
                <div class="text-gray-600">
                    <input value="Filament is a collection of Laravel packages that has a very long description that might overflow the visible space of this cell." class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
                </div>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('trash')"
                                     internal
                                     class="absolute top-2 right-0 h-5 w-5 cursor-pointer text-red-500 hover:text-red-700" />
            </div>
        </div>
    </div>
    <div class="px-4 py-2 text-center text-gray-600 hover:underline cursor-pointer bg-gray-200">
        ADD ROW
    </div>
</div>

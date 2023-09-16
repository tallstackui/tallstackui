<div>
    <label for="combobox" class="block text-sm font-medium leading-6 text-gray-900">Assigned to</label>
    <div class="relative mt-2">
        <div class="flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false">
            <div class="relative inset-y-0 left-0 flex w-full items-center space-x-2 overflow-hidden rounded-lg pl-3">
                <span class="text-gray-700"> 4 </span>
                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Badge</span>
                <span class="inline-flex items-center gap-x-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
            Badge
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
                <span class="inline-flex items-center gap-x-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
            Badge
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
                <span class="inline-flex items-center gap-x-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
            Badge
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
                <span class="inline-flex items-center gap-x-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
            Badge
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
                <span class="inline-flex items-center gap-x-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
            Badge
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </span>
            </div>
            <div>
                <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
        <button type="button" class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <ul class="rounded- absolute z-10 mt-1 max-h-60 w-full overflow-auto bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" id="options" role="listbox">
            <!--
            Combobox option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

            Active: "text-white bg-indigo-600", Not Active: "text-gray-900"
          -->
            <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="option-0" role="option" tabindex="-1">
                <div class="flex items-center">
                    <!-- Online: "bg-green-400", Not Online: "bg-gray-200" -->
                    <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-green-400" aria-hidden="true"></span>
                    <!-- Selected: "font-semibold" -->
                    <span class="ml-3 truncate">
              Leslie Alexander
              <span class="sr-only"> is online</span>
            </span>
                </div>

                <!--
                Checkmark, only display for selected option.

                Active: "text-white", Not Active: "text-indigo-600"
              -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
          </span>
            </li>
            <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-red-500 hover:text-white" id="option-0" role="option" tabindex="-1">
                <div class="flex items-center">
                    <!-- Online: "bg-green-400", Not Online: "bg-gray-200" -->
                    <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-green-400" aria-hidden="true"></span>
                    <!-- Selected: "font-semibold" -->
                    <span class="ml-3 truncate">
              Leslie Alexander
              <span class="sr-only"> is online</span>
            </span>
                </div>

                <!--
                Checkmark, only display for selected option.

                Active: "text-white", Not Active: "text-indigo-600"
              -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
          </span>
            </li>

            <!-- More items... -->
        </ul>
    </div>
</div>

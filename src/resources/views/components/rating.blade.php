 <div x-data="{ currentVal: 3, stars: @js($star) }" class="flex items-center gap-1">
    <template x-for="(star, index) in Array.from({ length: stars })" :key="index">
        <label :for="`star-${index}`" class="has-[:focus]:scale-125 cursor-pointer transition hover:scale-125">
            <span class="sr-only" x-text="`${index + 1} star`"></span>
            <input x-model="currentVal" :id="`star-${index}`" type="radio" class="sr-only" name="rating"
                    :value="index + 1">
            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"
                    class="h-5 w-5"
                    :class="{
                        'text-amber-500': currentVal >= index + 1,
                        'text-gray-300 dark:text-gray-300': currentVal < index + 1
                    }">
                <path fill-rule="evenodd"
                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                        clip-rule="evenodd">
                </path>
            </svg>
        </label>
    </template>
</div>
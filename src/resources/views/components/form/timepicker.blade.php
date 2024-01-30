@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div x-data="{
      model: {!! $entangle !!},
      show: false,
      hours: '0',
      minutes: '0',
      interval: 'AM',
      init() {
        // check if the model is not empty and if yes set the hours, minutes and interval
        if (this.model) {
          const [time, interval] = this.model.split(' ')
          const [hours, minutes] = time.split(':')
          this.hours = hours
          this.minutes = minutes
          this.interval = interval

          // set the input value
          this.$refs.input.value = this.model
        }

        this.$watch('hours', (value) => {
            this.model = this.$refs.input.value = `${value}:${this.minutes} ${this.interval}`
        })

        this.$watch('minutes', (value) => {
            this.model = this.$refs.input.value = `${this.hours}:${value} ${this.interval}`
        })

        this.$watch('interval', (value) => {
            this.model = this.$refs.input.value = `${this.hours}:${this.minutes} ${value}`
        })
      },
    }"
    x-ref="wrapper"
    x-cloak>
    <x-input :$label
             :$hint
             :$invalidate
             icon="clock"
             position="right"
             x-on:click="show = !show"
             readonly
             x-ref="input"
             class="cursor-pointer" />
    <div x-cloak
        x-show="show"
        x-on:click.away="show = false"
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-anchor.bottom-end="$refs.wrapper"
        class="absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-200 shadow-lg w-[18rem] dark:border-dark-600">
        <div class="overflow-auto rounded-md bg-white p-4 shadow-xs soft-scrollbar dark:bg-dark-800">
            <div class="flex select-none items-center justify-center gap-1">
                <span x-text="hours.padStart(2, '0')"
                    x-ref="hours"
                    class="w-20 rounded-full p-2 text-center text-4xl font-medium transition text-primary-600 dark:text-dark-300 dark:border-dark-700">
                </span>
                <span class="h-14 text-5xl text-gray-300 dark:text-dark-700">:</span>
                <span x-text="minutes.padStart(2, '0')"
                    x-ref="minutes"
                    class="w-20 rounded-full p-2 text-center text-4xl font-medium transition text-primary-600 dark:text-dark-300 dark:border-dark-700">
                </span>
                @if (!$withoutPeriod)
                    <div class="m-2 flex h-14 flex-col justify-between">
                        <div class="w-12">
                            <input type="radio" id="am" x-model="interval" value="AM" class="hidden peer">
                            <label for="am"
                                class="inline-flex w-full cursor-pointer items-center justify-between rounded-t-lg border border-gray-300 bg-white p-1 text-gray-500 peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:text-primary-600 peer-checked:font-bold hover:bg-gray-100 hover:text-gray-600 dark:peer-checked:text-dark-100 peer-checked:dark:bg-dark-700 peer-checked:dark:border-dark-500 dark:border-dark-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-dark-300 dark:hover:bg-gray-700">
                                <div class="w-full text-center text-sm">AM</div>
                            </label>
                        </div>
                        <div class="w-12">
                            <input type="radio" id="pm" x-model="interval" value="PM" class="hidden peer">
                            <label for="pm"
                                class="inline-flex w-full cursor-pointer items-center justify-between rounded-b-lg border border-t-0 border-gray-300 bg-white p-1 text-gray-500 peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:text-primary-600 peer-checked:font-bold hover:bg-gray-100 hover:text-gray-600 dark:peer-checked:text-dark-100 peer-checked:dark:bg-dark-700 peer-checked:dark:border-dark-500 dark:border-dark-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-dark-300 dark:hover:bg-gray-700">
                                <div class="w-full text-center text-sm font-medium">PM</div>
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <div @class(['mt-2 flex flex-col space-y-6', 'mb-2' => !$helper])>
                <input type="range"
                    min="{{ $minHour }}"
                    max="{{ $maxHour }}"
                    x-model="hours"
                    x-on:mouseenter="$refs.hours.classList.add('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                    x-on:mouseleave="$refs.hours.classList.remove('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                    @class([$personalize['range.base'], $personalize['range.thumb']])>
                <input type="range"
                    min="{{ $minMinute }}"
                    max="{{ $maxMinute }}"
                    x-model="minutes"
                    x-on:mouseenter="$refs.minutes.classList.add('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                    x-on:mouseleave="$refs.minutes.classList.remove('bg-primary-50', 'border-primary-600', 'dark:bg-dark-700')"
                    @class([$personalize['range.base'], $personalize['range.thumb']])>
            </div>
            @if ($helper)
            <div class="mt-4">
                {{--TODO:Translate it--}}
                <x-button class="w-full uppercase" xs>Current Time</x-button>
            </div>
            @endif
        </div>
    </div>

</div>

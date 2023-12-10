@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<div class="flex space-x-3" x-data="app()" x-on:paste="pasting = true; paste($event)">
    <template x-for="(value, index) in prefixes" :key="`pin-${hash}-${index}`">
        <input class="block w-16 text-center text-gray-700 bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-lg focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 dark:focus:border-b-gray-600"
               maxlength="1"
               max="9"
               min="0"
               inputmode="decimal"
               :value="value" disabled>
    </template>
    <template x-for="(value, index) in length" :key="`pin-${hash}-${index}`">
        <input :id="`pin-${hash}-${index}`"
               class="block w-[38px] text-center text-gray-700 bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-2xl focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 dark:focus:border-b-gray-600"
               maxlength="1"
               max="9"
               min="0"
               inputmode="decimal"
               @keyup="go(index, $event)" @keyup.left="left(index)" @keyup.right="right(index)" @keydown.backspace="back(index)">
    </template>
</div>

<script type="text/javascript">
    function app() {
        return {
            model: @entangle($property).live,
            prefixes: ['XSE'],
            hash: Math.random().toString(36).substring(2, 15),
            length: 4,
            pasting: false,
            init() {
                window.onload = () => {
                    if (this.model) {
                        for (let index = 0; index < this.length; index++) {
                            this.input(index).value = this.model[index];
                        }
                    }
                }
            },
            /**
             * @param index {Number}
             * @returns {void}
             */
            focus(index) {
                this.input(index).focus();
            },
            /**
             * @param index {Number}
             */
            left(index) {
                if (index === 0) return;
                this.focus(index - 1)
            },
            /**
             * @param index {Number}
             */
            right(index) {
                if (index === this.length - 1) return;
                this.focus(index + 1);
            },
            /**
             * @param index {Number}
             */
            go(index) {
                if (this.pasting) {
                    this.pasting = false;
                    this.sync();

                    return;
                }

                const input = this.input(index);

                if (input.value && index !== (this.length - 1)) {
                    if (this.input(index + 1).value !== '') {
                        this.focus(index + 1)
                        return;
                    }

                    this.focus(index + 1);
                }

                this.sync();
            },
            /**
             * @param index {Number}
             */
            back(index) {
                const current = this.input(index);

                if (current.value !== '') {
                    current.value = '';
                    return;
                }

                const previous = this.input(index - 1);
                previous.value = '';
                this.focus(index - 1);

                this.sync();
            },
            /**
             * @returns {void}
             */
            sync() {
                let code = '';

                for (let index = 0; index < this.length; index++) {
                    code += this.input(index).value;
                }

                this.model = code;
            },
            /**
             * @param index
             * @returns {HTMLElement}
             */
            input(index) { return document.getElementById(`pin-${this.hash}-${index}`) },
            /**
             * @param event {ClipboardEvent}
             */
            paste(event) {
                event.preventDefault();

                const data = event.clipboardData.getData('text');

                if (data.length !== this.length) return;

                for (let index = 0; index < this.length; index++) {
                    this.input(index).value = data[index];
                }

                this.sync();
            }
        }
    }
</script>

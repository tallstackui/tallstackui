@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $disabled = $attributes->get('disabled');
    $readonly = $attributes->get('readonly');
@endphp

<div class="flex" x-data="app()" x-on:paste="pasting = true; paste($event)">
    <template x-for="(value, index) in length" :key="`pin-${hash}-${index}`">
        <input :id="`pin-${hash}-${index}`"
               class="h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-thin text-3xl"
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
            model: null,
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

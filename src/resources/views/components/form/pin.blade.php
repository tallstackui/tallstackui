<div class="flex" x-data="app()" x-on:paste="pasting = true; paste($event)">
    <p x-text="model"></p>
    <template x-for="(value, index) in length" :key="`pin-${index}`">
        <input :id="`pin-${index}`"
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
            length: 4,
            pasting: false,
            init() {
                window.onload = () => {
                    if (this.model) {
                        for (let i = 0; i < this.length; i++) {
                            this.input(i).model = this.model[i];
                        }
                    }
                }
            },
            /**
             * @param index {Number}
             */
            left(index) {
                if (index === 0) return;
                const prevInput = this.input(index - 1);
                prevInput.focus();
            },
            /**
             * @param index {Number}
             */
            right(index) {
                if (index === this.length - 1) return;
                const nextInput = this.input(index + 1);
                nextInput.focus();
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
                    const nextInput = this.input(index + 1);

                    if (nextInput.value !== '') {
                        nextInput.focus();
                        return;
                    }

                    nextInput.focus();
                }

                this.sync();

            },
            /**
             *
             * @param index {Number}
             */
            back(index) {
                const currentInput = this.input(index);

                if (currentInput.value !== '') {
                    currentInput.value = '';
                    return;
                }

                const prevInput = this.input(index - 1);
                prevInput.focus();
                prevInput.value = '';
                this.sync();
            },
            /**
             * @returns {Promise<void>}
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
            input(index) { return document.getElementById(`pin-${index}`) },
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

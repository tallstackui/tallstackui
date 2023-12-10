<div class="flex" x-data="app()" x-on:paste="pasting = true; handlePaste($event)">
    <template x-for="(l,i) in length" :key="`codefield_${i}`">
        <input :autofocus="i == 0" :id="`codefield_${i}`" class="h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-thin text-3xl" value="" maxlength="1" max="9" min="0" inputmode="decimal"
               @keyup="stepForward(i, $event)" @keyup.left="moveLeft(i)" @keyup.right="moveRight(i)" @keydown.backspace="stepBack(i)">
    </template>
</div>

<script type="text/javascript">
    function app() {
        return {
            value: null,
            length: 4,
            pasting: false,
            //dep
            resetValue(i) {
                for (let x = 0; x < this.length; x++) {
                    if (x >= i) this.getInput(x).value = '';
                }
            },
            moveLeft(i) {
                if (i === 0) return;
                const prevInput = this.getInput(i - 1);
                prevInput.focus();
            },
            moveRight(i) {
                if (i === this.length - 1) return;
                const nextInput = this.getInput(i + 1);
                nextInput.focus();
            },
            stepForward(i, event) {

                if (this.pasting) {
                    this.pasting = false;
                    return;
                }
                const input = this.getInput(i);

                if (input.value && i !== (this.length - 1)) {
                    const nextInput = this.getInput(i + 1);

                    if (nextInput.value !== '') {
                        nextInput.focus();
                        return;
                    }

                    nextInput.focus();
                    nextInput.value = '';
                }
                this.checkPin();
            },
            stepBack(i) {
                const currentInput = this.getInput(i);

                if (currentInput.value !== '') {
                    currentInput.value = '';
                    return;
                }

                const prevInput = this.getInput(i - 1);
                prevInput.focus();
                prevInput.value = '';
                this.checkPin();
            },
            checkPin() {
                let code = '';
                for (let i = 0; i < this.length; i++) {
                    code += this.getInput(i).value;
                }
                this.value = code;
            },
            getInput(i) {
                return document.getElementById(`codefield_${i}`);
            },
            handlePaste(e) {
                e.preventDefault();
                const data = e.clipboardData.getData('text');
                if (data.length !== this.length) return;
                for (let i = 0; i < this.length; i++) {
                    this.getInput(i).value = data[i];
                }
                this.checkPin();
            }
        }
    }
</script>

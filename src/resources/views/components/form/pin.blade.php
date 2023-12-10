<div class="flex space-x-3" x-data="app()">
    <template x-for="(l,i) in pinlength" :key="`codefield_${i}`">
        <input :autofocus="i == 0" :id="`codefield_${i}`" class="block w-[38px] text-center border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" value="" maxlength="1" max="9" min="0" inputmode="decimal" @keyup="stepForward(i)" @keydown.backspace="stepBack(i)" @focus="resetValue(i)"></input>
    </template>
</div>

<script type="text/javascript">
    function app() {
        return {
            pinlength: 4,
            resetValue(i) {
                for (x = 0; x < this.pinlength; x++) {
                    if (x >= i) document.getElementById(`codefield_${x}`).value = ''
                }
            },
            stepForward(i) {
                if (document.getElementById(`codefield_${i}`).value && i != this.pinlength - 1) {
                    document.getElementById(`codefield_${i+1}`).focus()
                    document.getElementById(`codefield_${i+1}`).value = ''
                }
                this.checkPin()
            },
            stepBack(i) {
                if (document.getElementById(`codefield_${i-1}`).value && i != 0) {
                    document.getElementById(`codefield_${i-1}`).focus()
                    document.getElementById(`codefield_${i-1}`).value = ''
                }
            },
            checkPin() {
                let code = ''
                for (i = 0; i < this.pinlength; i++) {
                    code = code + document.getElementById(`codefield_${i}`).value
                }
                if (code.length == this.pinlength) {
                    this.validatePin(code)
                }
            },
            validatePin(code) {
                // Check pin on server
                if (code == '1234') alert('success')
            }
        }
    }
</script>

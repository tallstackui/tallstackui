@php
    $personalize = $classes();
    $wire = $wireable($attributes);
    $error = !$invalidate && $wire && $errors->has($wire->value());
@endphp

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <div @class($personalize['wrapper']) x-data="app()" x-on:paste="pasting = true; paste($event)" x-cloak>
        <template x-for="(value, index) in prefix" :key="key(index)">
            <input @class([
                       $personalize['input.base'],
                       $personalize['input.color.base'],
                       $personalize['input.color.background'],
                   ]) maxlength="1" :value="value" disabled />
        </template>
        <template x-for="(value, index) in length" :key="key(index)">
            <input :id="key(index)"
                   @if ($mask) x-mask="{{ $mask }}" @endif
                   @class([
                       $personalize['input.base'],
                       $personalize['input.color.background'],
                       $personalize['input.color.base'] => !$error,
                       $personalize['input.color.error'] => $error,
                   ]) maxlength="1"
                   x-on:keyup="go(index, $event)"
                   x-on:keyup.left="left(index)"
                   x-on:keyup.right="right(index)"
                   x-on:keydown.backspace="back(index)" />
        </template>
        <template x-if="clear && model">
            <button class="cursor-pointer" x-on:click="erase();">
                <x-icon name="x-circle" solid @class($personalize['button']) />
            </button>
        </template>
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error)
        <x-error :$wire/>
    @endif
</div>

<script type="text/javascript">
    function app() {
        return {
            model: @entangleable($attributes),
            prefix: @js($prefix),
            prefixed: @js($prefixed),
            hash: Math.random().toString(36).substring(2, 15),
            length: @js($length),
            clear: @js($clear),
            pasting: false,
            init() {
                this.length = this.model.length;

                this.$nextTick(() => {
                    if (!this.model) {
                        return;
                    }

                    for (let index = 0; index < this.length; index++) {
                        this.input(index).value = this.model[index];
                    }

                    this.length = this.model.length;
                })

                this.$watch('model', (value) => {
                    if (!value || !this.prefixed) {
                        return;
                    }

                    this.model = `${this.prefix}${value}`;
                });
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
             * @return {void}
             */
            left(index) {
                if (index === 0) return;

                this.focus(index - 1)
            },
            /**
             * @param index {Number}
             * @return {void}
             */
            right(index) {
                if (index === this.length - 1) return;
                this.focus(index + 1);
            },
            /**
             * @param index {Number}
             * @return {void}
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
             * @returns {void}
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
             * @returns {void}
             */
            paste(event) {
                event.preventDefault();

                const data = event.clipboardData.getData('text');

                if (data.length !== this.length) return;

                for (let index = 0; index < this.length; index++) {
                    this.input(index).value = data[index];
                }

                this.sync();
            },
            /**
             * @returns {void}
             */
            erase() {
                for (let index = 0; index < this.length; index++) {
                    this.input(index).value = '';
                }

                this.sync();
            },
            /**
             * @param index
             * @return {string}
             */
            key(index) {
                return `pin-${this.hash}-${index}`;
            }
        }
    }
</script>

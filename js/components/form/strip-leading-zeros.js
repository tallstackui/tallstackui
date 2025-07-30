export default (property) => ({
  init() {
    this.$refs[property].addEventListener('input', this.handleInput.bind(this));
  },

  handleInput(event) {
    const input = event.target;
    const { value, type: inputType } = input;

    if (!this.shouldProcessValue(value)) {
      return;
    }

    const strippedValue = this.stripZeros(value);
    
    if (inputType === 'number') {
      this.updateNumberInput(input, strippedValue);
    } else {
      this.updateTextInput(input, value, strippedValue);
    }
  },

  shouldProcessValue(value) {
    return value && value.startsWith('0') && value.length > 1;
  },

  stripZeros(value) {
    return value.replace(/^0+/, '') || '0';
  },

  updateNumberInput(input, strippedValue) {
    if (/^0+\d/.test(input.value)) {
      this.setValue(input, strippedValue);
    }
  },

  updateTextInput(input, originalValue, strippedValue) {
    if (/^\d+$/.test(originalValue)) {
      const cursorPosition = input.selectionStart;
      const removedZeros = originalValue.length - strippedValue.length;
      
      this.setValue(input, strippedValue);
      this.setCursorPosition(input, cursorPosition - removedZeros);
    }
  },

  setValue(input, value) {
    input.value = value;
    input.dispatchEvent(new Event('input', { bubbles: true }));
  },

  setCursorPosition(input, position) {
    const newPosition = Math.max(0, position);
    
    try {
      input.setSelectionRange(newPosition, newPosition);
    } catch (error) {
      // Browser doesn't support setSelectionRange
    }
  },
});

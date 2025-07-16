export default (property) => ({
  init() {
    this.$refs[property].addEventListener('input', (event) => {
      this.stripLeadingZeros(event);
    });
  },
  stripLeadingZeros(event) {
    const input = event.target;
    const value = input.value;
    
    if (value.length > 1 && value.startsWith('0') && /^\d+$/.test(value)) {
      const cursorPosition = input.selectionStart;
      const strippedValue = value.replace(/^0+/, '') || '0';
      const removedZeros = value.length - strippedValue.length;
      
      input.value = strippedValue;
      
      const newCursorPosition = Math.max(0, cursorPosition - removedZeros);
      input.setSelectionRange(newCursorPosition, newCursorPosition);
      
      input.dispatchEvent(new Event('input', { bubbles: true }));
    }
  },
});

import {overflow} from '../../helpers';

export default (state, overflowing) => ({
  show: state,
  init() {
    this.$watch('show', (value) => {
      overflow(value, 'modal', overflowing);

      this.$el.dispatchEvent(new CustomEvent(value ? 'open' : 'close'));
    });
  },
});

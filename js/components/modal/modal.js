import {overflow} from '../../helpers';

export default (state) => ({
  show: state,
  init() {
    this.$watch('show', (value) => overflow(value, 'modal'));
  },
});

import {overflow} from '../../helpers';

export default () => ({
  init() {
    window.Livewire.hook('morph.updated', ({el, component}) => {
      if (this.$refs.loading.style.display === 'inline-block') {
        overflow(false);
      }
    });
    window.Livewire.hook('commit.prepare', () => {
      if (this.$refs.loading.style.display !== 'inline-block') {
        overflow(true);
      }
    });
  },
});

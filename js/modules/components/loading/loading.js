import {overflow} from '../../helpers';

export default () => ({
  init() {
    window.Livewire.hook('morph.updated', () => overflow(false));
    window.Livewire.hook('commit.prepare', () => overflow(true));
  },
});

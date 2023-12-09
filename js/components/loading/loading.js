import {overflow} from '../../helpers';

export default () => ({
  init() {
    window.Livewire.hook('commit.prepare', () => overflow(true, 'loading'));
    window.Livewire.hook('morph.updated', () => overflow(false, 'loading'));
  },
});

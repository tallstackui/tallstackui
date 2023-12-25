import {overflow} from '../../helpers';

export default () => ({
  init() {
    Livewire.hook('commit.prepare', () => overflow(true, 'loading'));
    Livewire.hook('morph.updated', () => overflow(false, 'loading'));
  },
});

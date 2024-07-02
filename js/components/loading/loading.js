import {overflow} from '../../helpers';

export default (overflowing) => ({
  init() {
    Livewire.hook('commit.prepare', () => overflow(true, 'loading', overflowing));
    Livewire.hook('morph.updated', () => overflow(false, 'loading', overflowing));
  },
});

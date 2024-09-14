import {overflow} from '../../helpers';

export default (name, overflowing) => ({
  init() {
    Livewire.hook('commit.prepare', ({ component }) => {
      if (component.name !== name) return;

      overflow(true, 'loading', overflowing)
    });
    Livewire.hook('morph.updated', ({ component }) => {
        if (component.name !== name) return;

      overflow(false, 'loading', overflowing);
    });
  },
});

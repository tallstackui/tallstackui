import { overflow } from '../../../js/helpers';

export default (name, overflowing) => ({
  init() {
    // Livewire 4 has no 'commit.prepare'; 'commit' is the hook that fires while
    // the request is being assembled. Livewire.hook does not validate names, so
    // the old one failed silently and the overflow was never locked.
    Livewire.hook('commit', ({ component, fail }) => {
      if (component.name !== name) return;

      overflow(true, 'loading', overflowing);

      // A failed or cancelled request never morphs, so the lock would outlive
      // the spinner it belongs to.
      fail(() => overflow(false, 'loading', overflowing));
    });
    Livewire.hook('morph.updated', ({ component }) => {
      if (component.name !== name) return;

      overflow(false, 'loading', overflowing);
    });
  },
});

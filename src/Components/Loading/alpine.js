import { overflow } from '../../../js/helpers';

export default (name, overflowing) => ({
  init() {
    // Livewire 4 has no 'commit.prepare'; 'commit' is the hook that fires while
    // the request is being assembled. Livewire.hook does not validate names, so
    // the old one failed silently and the overflow was never locked.
    Livewire.hook('commit', ({ component, respond, fail }) => {
      if (component.name !== name) return;

      overflow(true, 'loading', overflowing);

      // Released when the request is over, not on the morph: a renderless
      // action never morphs, and neither does a failed or cancelled request,
      // so the lock would outlive the spinner it belongs to.
      respond(() => overflow(false, 'loading', overflowing));
      fail(() => overflow(false, 'loading', overflowing));
    });
  },
});

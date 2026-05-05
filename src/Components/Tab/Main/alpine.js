export default (selected = null) => ({
  selected: selected,
  tabs: [],
  /**
   * Select a tab by clicking. Navigates to href if set, otherwise switches locally.
   *
   * @param {Object} item
   * @return {void}
   */
  select(item) {
    if (item.href) {
      if (item.navigate || item.navigateHover) {
        Livewire.navigate(item.href);
      } else {
        window.location.href = item.href;
      }

      return;
    }

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    this.selected = item.tab;

    this.$refs.ul.dispatchEvent(new CustomEvent('navigate', { detail: { select: item.tab } }));
  },
  /**
   * Handle the mobile select dropdown change event.
   *
   * @return {void}
   */
  change() {
    const tab = this.tabs.find((i) => i.tab === this.selected);

    if (tab && tab.href) {
      if (tab.navigate || tab.navigateHover) {
        Livewire.navigate(tab.href);
      } else {
        window.location.href = tab.href;
      }

      return;
    }

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    this.$refs.ul.dispatchEvent(
      new CustomEvent('navigate', { detail: { select: this.selected } })
    );
  },
  /**
   * Prefetch the href on hover for tabs with navigateHover enabled.
   *
   * @param {Object} item
   * @return {void}
   */
  prefetch(item) {
    if (item.href && item.navigateHover && !item._prefetched) {
      const link = document.createElement('link');

      link.rel = 'prefetch';
      link.href = item.href;

      document.head.appendChild(link);

      item._prefetched = true;
    }
  },
});

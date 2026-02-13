export default (selected = null) => ({
  selected: selected,
  tabs: [],
  // Selects a tab by clicking. Navigates to href if set, otherwise switches locally.
  select(item) {
    if (item.href) {
      if (item.navigate || item.navigateHover) {
        Livewire.navigate(item.href);
      } else {
        window.location.href = item.href;
      }
    } else {
      this.selected = item.tab;
      this.$refs.ul.dispatchEvent(new CustomEvent('navigate', { detail: { select: item.tab } }));
    }
  },
  // Handles the mobile select dropdown change event.
  change() {
    const tab = this.tabs.find((i) => i.tab === this.selected);
    if (tab && tab.href) {
      if (tab.navigate || tab.navigateHover) {
        Livewire.navigate(tab.href);
      } else {
        window.location.href = tab.href;
      }
    } else {
      this.$refs.ul.dispatchEvent(
        new CustomEvent('navigate', { detail: { select: this.selected } })
      );
    }
  },
  // Prefetches the href on hover for tabs with navigateHover enabled.
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

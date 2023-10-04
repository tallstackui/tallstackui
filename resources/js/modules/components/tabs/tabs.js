export default (selected) => ({
  tab: selected ?? null,
  select(tab) {
    this.tab = tab;
  },
  selected(tab) {
    return this.tab === tab;
  },
});

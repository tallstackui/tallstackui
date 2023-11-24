export default (selected) => ({
  tab: selected ?? null,
  headings: [],
  select(tab) {
    this.tab = tab;
  },
  selected(tab) {
    return this.tab === tab;
  }
});

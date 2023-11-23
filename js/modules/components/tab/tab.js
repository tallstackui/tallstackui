import {error} from "../../helpers";

export default (selected) => ({
  tab: selected ?? null,
  headings: [],
  select(tab) {
    this.tab = tab;
  },
  selected(tab) {
    return this.tab === tab;
  },
  init() {
    this.headings = [...this.$refs.tabs.children].map(function(tab) {
      return eval(`(${tab.getAttribute('x-data')})`)['name'];
    });

    if (new Set(this.headings).size !== this.headings.length) {
      error('Duplicate [tab.items] name was found.');
    }
  },
});

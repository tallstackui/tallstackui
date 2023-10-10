export default (selected) => ({
  tab: selected ?? null,
  tabHeadings: [],
  select(tab) {
    this.tab = tab;
  },
  selected(tab) {
    return this.tab === tab;
  },
  init(){
    this.tabHeadings = [...this.$refs.tabs.children].map(function(tab){
      return eval(`(${tab.getAttribute('x-data')})`)['name'];
    })
  }
});

import Interaction from './interaction';

window.$modalOpen = (name) => window.dispatchEvent(new Event(`modal:${name}-open`));
window.$modalClose = (name) => window.dispatchEvent(new Event(`modal:${name}-close`));

window.$slideOpen = (name) => window.dispatchEvent(new Event(`slide:${name}-open`));
window.$slideClose = (name) => window.dispatchEvent(new Event(`slide:${name}-close`));

window.$dialog = (title, description = null) => (new Interaction(title, description)).dialog();
window.$toast = (title, description = null) => (new Interaction(title, description)).toast();

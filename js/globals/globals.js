window.$modalOpen = (name) => window.dispatchEvent(new Event(`modal:${name}-open`));
window.$modalClose = (name) => window.dispatchEvent(new Event(`modal:${name}-close`));

window.$slideOpen = (name) => window.dispatchEvent(new Event(`slide:${name}-open`));
window.$slideClose = (name) => window.dispatchEvent(new Event(`slide:${name}-close`));

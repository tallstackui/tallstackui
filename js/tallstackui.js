import select from './modules/components/select/select';
import toastBase from './modules/components/toast/toast-base';
import toastLoop from './modules/components/toast/toast-loop';
import dialog from './modules/components/dialog/dialog';
import modal from './modules/components/modal/modal';
import slide from './modules/components/slide/slide';
import tab from './modules/components/tab/tab';
import textArea from './modules/components/form/text-area';
import darkTheme from './modules/helpers/dark-theme';

document.addEventListener('alpine:init', () => {
  window.Alpine.data('tallstackui_select', select);
  window.Alpine.data('tallstackui_toastBase', toastBase);
  window.Alpine.data('tallstackui_toastLoop', toastLoop);
  window.Alpine.data('tallstackui_dialog', dialog);
  window.Alpine.data('tallstackui_modal', modal);
  window.Alpine.data('tallstackui_slide', slide);
  window.Alpine.data('tallstackui_tab', tab);
  window.Alpine.data('tallstackui_darkTheme', darkTheme);
  window.Alpine.data('tallstackui_formTextArea', textArea);
});

window.$modalOpen = (name) => window.dispatchEvent(new Event(`modal:${name}-open`));
window.$modalClose = (name) => window.dispatchEvent(new Event(`modal:${name}-close`));

window.$slideOpen = (name) => window.dispatchEvent(new Event(`slide:${name}-open`));
window.$slideClose = (name) => window.dispatchEvent(new Event(`slide:${name}-close`));

import tooltip from './modules/directives/tooltip';
import selectStyled from './modules/components/select/select-styled';
import selectSearchable from './modules/components/select/select-searchable';
import toastBase from './modules/components/toast/toast-base';
import toastLoop from './modules/components/toast/toast-loop';
import dialog from './modules/components/dialog/dialog';
import tabs from './modules/components/tabs/tabs';
import darkTheme from './modules/helpers/dark-theme';

document.addEventListener('alpine:init', () => {
  window.Alpine.plugin(tooltip);
  window.Alpine.data('tallstackui_selectStyled', selectStyled);
  window.Alpine.data('tallstackui_selectSearchable', selectSearchable);
  window.Alpine.data('tallstackui_toastBase', toastBase);
  window.Alpine.data('tallstackui_toastLoop', toastLoop);
  window.Alpine.data('tallstackui_dialog', dialog);
  window.Alpine.data('tallstackui_tabs', tabs);
  window.Alpine.data('tallstackui_darkTheme', darkTheme);
});

window.$modalOpen = (name) => window.dispatchEvent(new Event(`modal:${name}-open`));
window.$modalClose = (name) => window.dispatchEvent(new Event(`modal:${name}-close`));

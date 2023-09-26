import tooltip from './modules/directives/tooltip';
import selectStyled from './modules/components/select/select-styled';
import selectSearchable from './modules/components/select/select-searchable';
import toastBase from './modules/components/toast/toast-base';
import toastLoop from './modules/components/toast/toast-loop';
import dialog from './modules/components/dialog/dialog';
import darkTheme from './modules/helpers/dark-theme';

document.addEventListener('alpine:init', () => {
  window.Alpine.plugin(tooltip);
  window.Alpine.data('tasteui_selectStyled', selectStyled);
  window.Alpine.data('tasteui_selectSearchable', selectSearchable);
  window.Alpine.data('tasteui_toastBase', toastBase);
  window.Alpine.data('tasteui_toastLoop', toastLoop);
  window.Alpine.data('tasteui_dialog', dialog);
  window.Alpine.data('tasteui_darkTheme', darkTheme);
});

window.$modalOpen = (name) => window.dispatchEvent(new Event(`modal:${name}-open`));
window.$modalClose = (name) => window.dispatchEvent(new Event(`modal:${name}-close`));

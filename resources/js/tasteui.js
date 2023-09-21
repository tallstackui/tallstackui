import tooltip from './modules/directives/tooltip'
import selectStyled from './modules/components/select/select-styled'
import selectSearchable from './modules/components/select/select-searchable'
import toastBase from './modules/components/toast/toast-base'
import toastLoop from './modules/components/toast/toast-loop'

document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
    window.Alpine.data('tasteui_selectStyled', selectStyled);
    window.Alpine.data('tasteui_selectSearchable', selectSearchable);
    window.Alpine.data('tasteui_toastBase', toastBase);
    window.Alpine.data('tasteui_toastLoop', toastLoop);
})

window.$modalOpen  = name => window.dispatchEvent(new Event(`${name}-open`));
window.$modalClose = name => window.dispatchEvent(new Event(`${name}-close`));

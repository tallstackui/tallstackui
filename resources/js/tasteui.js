import tooltip from './modules/directives/tooltip'
import selectStyled from './modules/components/select/select-styled'
import selectSearchable from './modules/components/select/select-searchable'
import notificationBase from './modules/components/notification/notification-base'
import notificationLoop from './modules/components/notification/notification-loop'

document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
    window.Alpine.data('tasteui_selectStyled', selectStyled);
    window.Alpine.data('tasteui_selectSearchable', selectSearchable);
    window.Alpine.data('tasteui_notificationBase', notificationBase);
    window.Alpine.data('tasteui_notificationLoop', notificationLoop);
})

window.$modalOpen  = name => window.dispatchEvent(new Event(`${name}-open`));
window.$modalClose = name => window.dispatchEvent(new Event(`${name}-close`));
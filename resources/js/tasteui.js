import tooltip from './modules/directives/tooltip'
import selectStyled from './modules/components/select/select-styled'
import selectSearchable from './modules/components/select/select-searchable'

document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
    window.Alpine.data('tasteui_selectStyled', selectStyled);
    window.Alpine.data('tasteui_selectSearchable', selectSearchable);
})

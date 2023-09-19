import tooltip from './modules/directives/tooltip'
import selectStyled from './modules/components/select/select-styled'
import selectMultiple from './modules/components/select/select-multiple'

document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
    window.Alpine.data('tasteui_selectStyled', selectStyled);
    window.Alpine.data('tasteui_selectMultiple', selectMultiple);
})

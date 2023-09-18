import tooltip from './modules/tooltip'
import selectStyled from './modules/select-styled'
import selectMultiple from './modules/select-multiple'

document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
    window.Alpine.data('tasteui_selectStyled', selectStyled);
    window.Alpine.data('tasteui_selectMultiple', selectMultiple);
})

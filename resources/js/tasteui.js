import tooltip from './modules/tooltip'
document.addEventListener("alpine:init", () => {
    window.Alpine.plugin(tooltip)
})

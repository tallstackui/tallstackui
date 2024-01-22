import 'tippy.js/dist/tippy.css';
import tippy from 'tippy.js';

export default (model, content, position) => ({
  show: false,
  quantity: model,
  init() {
    const that = this;

    tippy(this.$refs.button, {
      content: content,
      trigger: 'click',
      allowHTML: true,
      interactive: true,
      hideOnClick: false,
      placement: position,
      onClickOutside(instance) {
        that.show = false;
        instance.hide();
      },
    });

    this.$watch('show', (value) => {
      if (value) {
        this.$refs.button._tippy.show();

        return;
      }

      this.$refs.button._tippy.hide();
    });
  },
});

import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

export default function (Alpine) {
    Alpine.directive('tooltip', (el, { expression }) => {
        tippy(el, {
            content: expression,
            placement: el.dataset.position ?? 'top',
            duration: 0,
            allowHTML: true,
        })
    })
}
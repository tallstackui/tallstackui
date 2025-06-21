import tippy from 'tippy.js';

export default function (Alpine) {
  /**
   * Standard tooltip directive
   */
  Alpine.directive('tooltip', (el, { expression }) => {
    tippy(el, {
      content: expression,
      placement: el.dataset.position ?? 'top',
      duration: 0,
      allowHTML: true,
    });
  });

  /**
   * Conditional tooltip directive - enables/disables based on condition
   */
  Alpine.directive('tooltip-conditional', (el, { expression }) => {
    const content = expression;
    const condition = el.getAttribute('x-tooltip-condition');

    if (!condition) {
      console.warn('x-tooltip-conditional requires x-tooltip-condition attribute');
      return;
    }

    // Initialize tippy
    const tippyInstance = tippy(el, {
      content: content,
      placement: el.dataset.position ?? 'top',
      duration: 0,
      allowHTML: true,
    });

    // Get the actual tippy instance
    const instance = Array.isArray(tippyInstance) ? tippyInstance[0] : tippyInstance;

    // Function to evaluate condition and update tooltip
    const updateTooltipState = () => {
      try {
        // Evaluate the condition expression in Alpine context
        const shouldShow = Alpine.evaluate(el, condition);

        if (shouldShow) {
          instance.enable();
        } else {
          instance.disable();
        }
      } catch (error) {
        console.warn('Error evaluating tooltip condition:', error);
      }
    };

    // Initial evaluation
    setTimeout(() => updateTooltipState(), 0);

    // Watch for Alpine store changes
    Alpine.effect(() => {
      updateTooltipState();
    });

    // Handle Livewire navigation
    const handleNavigation = () => {
      setTimeout(() => updateTooltipState(), 100);
    };

    document.addEventListener('livewire:navigated', handleNavigation);

    // Cleanup on element removal
    Alpine.mutateDom(() => {
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          mutation.removedNodes.forEach((node) => {
            if (node === el || (node.nodeType === 1 && node.contains(el))) {
              document.removeEventListener('livewire:navigated', handleNavigation);
              instance.destroy();
              observer.disconnect();
            }
          });
        });
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true
      });
    });
  });
}

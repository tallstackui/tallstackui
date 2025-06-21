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
   * Lazy loading conditional tooltip directive with Intersection Observer
   */
  Alpine.directive('tooltip-conditional', (el, { expression }) => {
    const content = expression;
    const condition = el.getAttribute('x-tooltip-condition');

    if (!condition) {
      console.warn(
        'x-tooltip-conditional requires x-tooltip-condition attribute'
      );
      return;
    }

    let instance = null;
    let isInitialized = false;

    // Function to evaluate condition and update tooltip
    const updateTooltipState = () => {
      try {
        // Only proceed if tooltip is initialized
        if (!instance) return;

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

    // Initialize tooltip when element becomes visible
    const initializeTooltip = () => {
      if (isInitialized) return;

      // Create tippy instance
      const tippyInstance = tippy(el, {
        content: content,
        placement: el.dataset.position ?? 'right',
        duration: 0,
        allowHTML: true,
      });

      // Get the actual tippy instance
      instance = Array.isArray(tippyInstance)
        ? tippyInstance[0]
        : tippyInstance;
      isInitialized = true;

      // Initial state evaluation
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

      // Cleanup function
      const cleanup = () => {
        if (instance) {
          document.removeEventListener('livewire:navigated', handleNavigation);
          instance.destroy();
          instance = null;
          isInitialized = false;
        }
      };

      // Cleanup on element removal
      Alpine.mutateDom(() => {
        const observer = new MutationObserver((mutations) => {
          mutations.forEach((mutation) => {
            mutation.removedNodes.forEach((node) => {
              if (node === el || (node.nodeType === 1 && node.contains(el))) {
                cleanup();
                observer.disconnect();
              }
            });
          });
        });

        observer.observe(document.body, {
          childList: true,
          subtree: true,
        });
      });
    };

    // Lazy loading with Intersection Observer
    const observerCallback = (entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          // Initialize tooltip when element becomes visible
          initializeTooltip();

          // Stop observing once initialized
          observer.unobserve(entry.target);
        }
      });
    };

    // Create intersection observer with optimized options
    const intersectionObserver = new IntersectionObserver(observerCallback, {
      threshold: 0.1, // Trigger when 10% of element is visible
      rootMargin: '50px', // Start loading 50px before element enters viewport
    });

    // Start observing the element
    intersectionObserver.observe(el);

    // Fallback: Initialize immediately if element is already visible
    // This handles cases where element is visible on initial page load
    const rect = el.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight && rect.bottom > 0;

    if (isVisible) {
      // Small delay to ensure Alpine is ready
      setTimeout(() => {
        initializeTooltip();
        intersectionObserver.unobserve(el);
      }, 50);
    }
  });
}

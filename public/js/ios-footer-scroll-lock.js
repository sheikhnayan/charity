/**
 * iOS Footer Scroll Lock
 * Prevents scrolling below footer on iOS devices without blocking normal page scroll
 * Works with dynamic content and responsive layouts
 */

(function() {
    'use strict';

    // Detect if device is iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    // Only apply on iOS devices
    if (!isIOS()) {
        console.log('📱 Not an iOS device, footer scroll lock not applied');
        return;
    }

    console.log('📱 iOS detected - enabling footer scroll lock');

    let lastScrollTop = 0;
    let scrollDirection = 'down';
    let preventScroll = false;

    // Detect footer element dynamically
    function getFooter() {
        return document.querySelector('footer') || document.querySelector('[role="contentinfo"]');
    }

    // Get document's actual scrollable height (dynamic)
    function getScrollableHeight() {
        return Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight
        );
    }

    // Handle scroll event to prevent overscroll past footer
    function handleScroll() {
        const currentScroll = window.scrollY || window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = getScrollableHeight();

        // Calculate how far we can actually scroll
        const maxScroll = documentHeight - windowHeight;

        // If we're trying to scroll past the maximum, prevent it
        if (currentScroll > maxScroll && currentScroll > lastScrollTop) {
            // User is scrolling down past the bottom
            window.scrollTo(0, maxScroll);
            preventScroll = true;
        } else {
            preventScroll = false;
        }

        scrollDirection = currentScroll > lastScrollTop ? 'down' : 'up';
        lastScrollTop = currentScroll;
    }

    // Handle touch events to prevent overscroll momentum on iOS
    function handleTouchMove(e) {
        const currentScroll = window.scrollY || window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = getScrollableHeight();
        const maxScroll = documentHeight - windowHeight;

        // Only prevent if scrolling down and at or past the bottom
        if (currentScroll >= maxScroll) {
            // Check if the touch is trying to scroll down
            const touch = e.touches[0];
            const touchStartY = touch.clientY;

            // If trying to scroll down while at bottom, prevent it
            if (touchStartY < window.innerHeight - 50) {
                // Allow normal scrolling, but prevent overscroll
                // Don't preventDefault here - it would block all scrolling
                // Instead, we'll use the scroll event to clamp the position
            }
        }
    }

    // Prevent scroll boost on iOS by clamping scroll position
    function clampScroll() {
        const currentScroll = window.scrollY || window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = getScrollableHeight();
        const maxScroll = Math.max(0, documentHeight - windowHeight);

        if (currentScroll > maxScroll) {
            // Use requestAnimationFrame to clamp without blocking
            requestAnimationFrame(() => {
                window.scrollTo(0, maxScroll);
            });
        }
    }

    // Initialize scroll lock
    function init() {
        console.log('🔒 Initializing iOS footer scroll lock');

        // Add scroll event listener
        window.addEventListener('scroll', handleScroll, { passive: true });

        // Add touch move listener for momentum scroll prevention
        document.addEventListener('touchmove', handleTouchMove, { passive: true });

        // Monitor for scroll clamping (catches momentum scroll)
        let scrollMonitor = null;
        
        // Reapply clamping when scroll stabilizes
        window.addEventListener('scroll', () => {
            clearTimeout(scrollMonitor);
            scrollMonitor = setTimeout(() => {
                clampScroll();
            }, 50);
        }, { passive: true });

        // Handle window resize (footer position might change)
        window.addEventListener('resize', () => {
            handleScroll();
        }, { passive: true });

        // Monitor DOM changes for dynamic content
        const observer = new MutationObserver(() => {
            // When content changes, recalculate scroll limits
            handleScroll();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });

        console.log('✅ iOS footer scroll lock initialized');
    }

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also run after a delay to catch late-loading content
    setTimeout(() => {
        handleScroll();
    }, 1000);

    // Expose methods for manual control if needed
    window.footerScrollLock = {
        clampScroll: clampScroll,
        getScrollableHeight: getScrollableHeight,
        getMaxScroll: function() {
            const windowHeight = window.innerHeight;
            const documentHeight = getScrollableHeight();
            return Math.max(0, documentHeight - windowHeight);
        }
    };

    console.log('📱 Footer scroll lock ready - use window.footerScrollLock for manual control');
})();

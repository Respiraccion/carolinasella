/**
 * Shop Category Filter - JavaScript
 * Handles AJAX filtering and smooth transitions (optional enhancement)
 * By default, this uses standard page navigation. AJAX can be enabled for smoother UX.
 */

(function ($) {
    'use strict';

    // Configuration
    const config = {
        useAjax: false, // Set to true to enable AJAX filtering instead of page reload
        animationDuration: 300,
    };

    /**
     * Initialize the shop filter
     */
    function init() {
        const $filter = $('.shop-category-filter');
        if (!$filter.length) return;

        // Add smooth scroll on mobile
        enableMobileScroll($filter);

        // If AJAX mode is enabled
        if (config.useAjax) {
            bindAjaxEvents();
        }

        // Add entrance animation
        animateFilterEntrance($filter);
    }

    /**
     * Enable horizontal scrolling on mobile with touch
     */
    function enableMobileScroll($filter) {
        const $list = $filter.find('.shop-category-filter__list');
        const $activeItem = $list.find('.is-active');

        // Scroll to active item on load (for mobile)
        if ($activeItem.length && window.innerWidth <= 480) {
            setTimeout(function () {
                const activeOffset = $activeItem.position().left;
                const listWidth = $list.width();
                const activeWidth = $activeItem.outerWidth();
                const scrollTo = activeOffset - (listWidth / 2) + (activeWidth / 2);

                $filter.animate({
                    scrollLeft: Math.max(0, scrollTo)
                }, 300);
            }, 100);
        }
    }

    /**
     * Bind AJAX events for filtering (when enabled)
     */
    function bindAjaxEvents() {
        $('.shop-category-filter__link').on('click', function (e) {
            e.preventDefault();

            const $link = $(this);
            const category = $link.data('category');

            // Update active state
            $('.shop-category-filter__link').removeClass('is-active').removeAttr('aria-current');
            $link.addClass('is-active').attr('aria-current', 'page');

            // Update URL without reload (for browser history)
            const newUrl = $link.attr('href');
            window.history.pushState({ category: category }, '', newUrl);

            // Load products via AJAX
            loadProducts(category);
        });

        // Handle browser back/forward
        $(window).on('popstate', function (e) {
            if (e.originalEvent.state && e.originalEvent.state.category) {
                loadProducts(e.originalEvent.state.category);
                updateActiveLink(e.originalEvent.state.category);
            }
        });
    }

    /**
     * Load products via AJAX
     */
    function loadProducts(category) {
        const $container = $('.products');
        if (!$container.length) return;

        // Add loading state
        $container.addClass('is-loading');

        $.ajax({
            url: carolinaShopFilter.ajaxUrl,
            type: 'POST',
            data: {
                action: 'filter_products',
                nonce: carolinaShopFilter.nonce,
                category: category
            },
            success: function (response) {
                if (response.success) {
                    // Fade out, replace, fade in
                    $container.fadeOut(config.animationDuration, function () {
                        $(this).html($(response.data.html).find('.products').html() || response.data.html);
                        $(this).fadeIn(config.animationDuration);
                    });
                }
            },
            error: function () {
                console.warn('Failed to load products');
            },
            complete: function () {
                $container.removeClass('is-loading');
            }
        });
    }

    /**
     * Update active link state
     */
    function updateActiveLink(category) {
        $('.shop-category-filter__link').removeClass('is-active').removeAttr('aria-current');
        $('.shop-category-filter__link[data-category="' + category + '"]')
            .addClass('is-active')
            .attr('aria-current', 'page');
    }

    /**
     * Animate filter entrance
     */
    function animateFilterEntrance($filter) {
        $filter.css({ opacity: 0, transform: 'translateY(-10px)' });

        setTimeout(function () {
            $filter.css({
                transition: 'opacity 0.4s ease, transform 0.4s ease',
                opacity: 1,
                transform: 'translateY(0)'
            });
        }, 50);
    }

    // Initialize on DOM ready
    $(document).ready(init);

})(jQuery);

<?php
/**
 * Twenty Twenty-Five Child functions and definitions
 */

add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_styles');

function twentytwentyfive_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}

// Add Google Fonts
add_action('wp_head', 'twentytwentyfive_child_google_fonts');

function twentytwentyfive_child_google_fonts()
{
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Lato:wght@300;400;700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap"
        rel="stylesheet">
    <?php
}

/**
 * Hide Admin Bar for Subscribers (WooCommerce Customers).
 */
add_action('after_setup_theme', 'carolina_remove_admin_bar');
function carolina_remove_admin_bar() {
    if (!current_user_can('edit_posts')) {
        show_admin_bar(false);
    }
}

/**
 * Redirect My Account to Home for non-admins.
 */
add_action( 'template_redirect', 'carolina_redirect_my_account' );
function carolina_redirect_my_account() {
    if ( function_exists('is_account_page') && is_account_page() && ! current_user_can( 'edit_posts' ) ) {
        wp_redirect( home_url() );
        exit;
    }
}

/**
 * Remove WooCommerce account menu items from navigation.
 */
add_filter( 'woocommerce_account_menu_items', '__return_empty_array', 999 );

/**
 * Disable WooCommerce customer account creation.
 */
add_filter( 'woocommerce_enable_guest_checkout', '__return_true' );
add_filter( 'woocommerce_enable_signup_and_login_from_checkout', '__return_false' );

/**
 * Remove the account icon/link from WooCommerce navigation blocks.
 */
add_action( 'wp_footer', 'carolina_hide_account_icon_script' );
function carolina_hide_account_icon_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any elements with user/account icons
        const selectors = [
            'a[href*="my-account"]',
            '.wc-block-components-account-link',
            'svg.user-icon',
            '.header-section-right a[href*="account"]'
        ];
        
        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                el.style.display = 'none';
                el.remove();
            });
        });
    });
    </script>
    <?php
}


/**
 * Image Optimization
 */
require_once get_stylesheet_directory() . '/inc/image-optimization.php';

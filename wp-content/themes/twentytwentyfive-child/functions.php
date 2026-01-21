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

// Add Google Fonts with preloading to prevent FOUT (Flash of Unstyled Text)
add_action('wp_head', 'twentytwentyfive_child_google_fonts', 1);

function twentytwentyfive_child_google_fonts()
{
    ?>
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Preload Bodoni Moda to prevent font flash -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400&display=block">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="https://fonts.gstatic.com/s/bodonimoda/v25/aFTU7PNzY382FjmfwXqK7BQkUG5x6A.woff2">
    
    <!-- Load all fonts - using display=block to prevent flash -->
    <link
        href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=block"
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

/**
 * Shop Category Filter - Horizontal navigation for WooCommerce shop
 */
/**
 * SendPulse Newsletter Integration
 */
require_once get_stylesheet_directory() . '/inc/shop-category-filter.php';

/**
 * ConvertKit Newsletter Integration
 */
require_once get_stylesheet_directory() . '/inc/convertkit-newsletter.php';

/**
 * EU Cookie Consent Banner (GDPR Compliant)
 * - No third-party dependencies
 * - Stores consent in localStorage + cookie
 * - Blocks non-essential scripts until consent
 */
require_once get_stylesheet_directory() . '/inc/cookie-consent/cookie-consent.php';

/**
 * Custom Contact Form (with reCAPTCHA, honeypot, image upload)
 * - Only loads on /contacto page
 * - Stores submissions in custom post type (searchable archive)
 */
require_once get_stylesheet_directory() . '/inc/contact-form.php';

/**
 * Image Protection
 * - Blocks right-click and dragging on images
 */
require_once get_stylesheet_directory() . '/inc/image-protection.php';


/**
 * Add custom body class for children of Artistic Tattoos (ID 94)
 */
function twentytwentyfive_child_add_body_class( $classes ) {
    global $post;
    if ( is_page() && ( $post->post_parent == 94 || in_array( 94, get_post_ancestors( $post ) ) ) ) {
        $classes[] = 'child-of-artistic-tattoos';
    }
    return $classes;
}
add_filter( 'body_class', 'twentytwentyfive_child_add_body_class' );





/**
 * Add a custom admin menu page to explain custom CSS utility classes.
 */
add_action('admin_menu', 'carolina_add_guide_menu');

function carolina_add_guide_menu() {
    add_menu_page(
        'Guía de Clases CSS',           // Page Title
        'Guía CSS',                     // Menu Title
        'edit_posts',                   // Capability
        'carolina-css-guide',           // Menu Slug
        'carolina_guide_page_content',  // Callback Function
        'dashicons-editor-code',        // Icon
        90                              // Position (near bottom)
    );
}

function carolina_guide_page_content() {
    ?>
    <div class="wrap">
        <h1>Guía de Clases CSS Personalizadas</h1>
        <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-left: 4px solid #72677C; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px; max-width: 800px;">
            <p style="font-size: 16px;">Utiliza estas clases CSS especiales en el editor de bloques (sección <strong>"Avanzado" > "Clase(s) CSS adicional(es)"</strong>) para controlar la visibilidad y el estilo de cualquier elemento:</p>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <div style="margin-bottom: 20px;">
                <h3 style="color: #72677C; margin-bottom: 5px;">.movil</h3>
                <p style="margin-top: 0;">Muestra el elemento <strong>solo en dispositivos móviles</strong> (teléfonos).<br>El elemento se ocultará automáticamente en tablets y computadoras.</p>
            </div>

            <div style="margin-bottom: 20px;">
                <h3 style="color: #72677C; margin-bottom: 5px;">.desktop</h3>
                <p style="margin-top: 0;">Muestra el elemento <strong>solo en computadoras y tablets</strong>.<br>El elemento se ocultará automáticamente en teléfonos móviles.</p>
            </div>

            <div style="margin-bottom: 20px;">
                <h3 style="color: #72677C; margin-bottom: 5px;">.hide</h3>
                <p style="margin-top: 0;">Oculta el elemento completamente en <strong>todos los dispositivos</strong>.<br>Útil para borrar visualmente algo temporalmente sin eliminar el bloque.</p>
            </div>

            <div style="margin-bottom: 20px;">
                <h3 style="color: #72677C; margin-bottom: 5px;">.boton1</h3>
                <p style="margin-top: 0;">Transforma un bloque de botón estándar en el <strong>estilo personalizado del sitio</strong> (bordes finos, hover específico, etc.).</p>
            </div>

            <div style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 30px;">
                <p style="margin: 0; color: #666; font-style: italic;"><strong>Nota:</strong> Si un elemento no se ve como esperas, verifica que no tenga clases contradictorias (por ejemplo, usar <code>.movil</code> y <code>.desktop</code> al mismo tiempo en el mismo bloque).</p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Enqueue Scroll Popup for Post 94
 */
function twentytwentyfive_child_enqueue_scroll_popup() {
    if ( is_page(94) ) {
        wp_enqueue_style( 'scroll-popup-style', get_stylesheet_directory_uri() . '/assets/css/scroll-popup.css', array(), '1.0' );
        wp_enqueue_script( 'scroll-popup-script', get_stylesheet_directory_uri() . '/assets/js/scroll-popup.js', array(), '1.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_scroll_popup' );


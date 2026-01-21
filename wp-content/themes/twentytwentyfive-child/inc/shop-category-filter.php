<?php
/**
 * Shop Category Filter - Horizontal category navigation for WooCommerce shop
 * 
 * Displays: All | Prints | Decorative Objects | Giclée | Originals
 * AJAX-powered filtering without page reload
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Enqueue scripts and styles for shop category filter
 */
add_action('wp_enqueue_scripts', 'carolina_shop_filter_enqueue');
function carolina_shop_filter_enqueue() {
    if (is_shop() || is_product_category()) {
        // Enqueue custom CSS
        wp_enqueue_style(
            'carolina-shop-filter',
            get_stylesheet_directory_uri() . '/assets/css/shop-filter.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue custom JS
        wp_enqueue_script(
            'carolina-shop-filter',
            get_stylesheet_directory_uri() . '/assets/js/shop-filter.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Pass data to JS
        wp_localize_script('carolina-shop-filter', 'carolinaShopFilter', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shop_filter_nonce'),
            'shopUrl' => get_permalink(wc_get_page_id('shop')),
        ));
    }
}

/**
 * Display the horizontal category filter bar
 */
add_action('woocommerce_before_shop_loop', 'carolina_shop_category_filter', 5);
function carolina_shop_category_filter() {
    // Define the categories to show (in order)
    $category_order = array(
        'all' => __('All', 'twentytwentyfive-child'),
        'prints' => __('Prints', 'twentytwentyfive-child'),
        'decorative-objects' => __('Decorative Objects', 'twentytwentyfive-child'),
        'giclee' => __('Giclée', 'twentytwentyfive-child'),
        'originals' => __('Originals', 'twentytwentyfive-child'),
    );
    
    // Get current category
    $current_cat = get_queried_object();
    $current_slug = '';
    if (is_product_category() && $current_cat) {
        $current_slug = $current_cat->slug;
    }
    
    ?>
    <nav class="shop-category-filter" aria-label="<?php esc_attr_e('Product Categories', 'twentytwentyfive-child'); ?>">
        <ul class="shop-category-filter__list">
            <?php foreach ($category_order as $slug => $name) : 
                $is_active = ($slug === 'all' && !$current_slug) || ($slug === $current_slug);
                $url = ($slug === 'all') ? get_permalink(wc_get_page_id('shop')) : get_term_link($slug, 'product_cat');
                
                // Skip if term doesn't exist (except 'all')
                if ($slug !== 'all' && is_wp_error($url)) continue;
            ?>
                <li class="shop-category-filter__item">
                    <a href="<?php echo esc_url($url); ?>" 
                       class="shop-category-filter__link <?php echo $is_active ? 'is-active' : ''; ?>"
                       data-category="<?php echo esc_attr($slug); ?>"
                       <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                        <?php echo esc_html($name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php
}

/**
 * AJAX handler for filtering products (optional enhancement)
 */
add_action('wp_ajax_filter_products', 'carolina_ajax_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'carolina_ajax_filter_products');
function carolina_ajax_filter_products() {
    check_ajax_referer('shop_filter_nonce', 'nonce');
    
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'post_status' => 'publish',
    );
    
    if ($category !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        woocommerce_product_loop_start();
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        woocommerce_product_loop_end();
    } else {
        echo '<p class="woocommerce-info">' . __('No products found in this category.', 'twentytwentyfive-child') . '</p>';
    }
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}

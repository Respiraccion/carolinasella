<?php
/**
 * ConvertKit Newsletter Integration
 * 
 * Functions:
 * 1. Admin Settings Page (to enter API keys)
 * 2. API Connection (to talk to ConvertKit)
 * 3. AJAX Handler (to process the form from the footer)
 */

if (!defined('ABSPATH')) {
    exit;
}

// -----------------------------------------------------------------------------
// 1. SETTINGS PAGE
// -----------------------------------------------------------------------------

/**
 * Register the settings menu
 */
add_action('admin_menu', 'carolina_convertkit_add_admin_menu');
function carolina_convertkit_add_admin_menu() {
    add_options_page(
        'Newsletter Settings',
        'Newsletter',
        'manage_options',
        'carolina_newsletter',
        'carolina_newsletter_options_page'
    );
}

/**
 * Register the settings
 */
add_action('admin_init', 'carolina_convertkit_settings_init');
function carolina_convertkit_settings_init() {
    register_setting('carolina_newsletter_group', 'convertkit_api_key');
    register_setting('carolina_newsletter_group', 'convertkit_form_id');

    add_settings_section(
        'carolina_newsletter_section',
        'ConvertKit Configuration',
        'carolina_newsletter_section_callback',
        'carolina_newsletter'
    );

    add_settings_field(
        'convertkit_api_key',
        'API Key',
        'carolina_convertkit_api_key_render',
        'carolina_newsletter',
        'carolina_newsletter_section'
    );

    add_settings_field(
        'convertkit_form_id',
        'Form ID',
        'carolina_convertkit_form_id_render',
        'carolina_newsletter',
        'carolina_newsletter_section'
    );
}

// Render callbacks
function carolina_newsletter_section_callback() {
    echo '<p>Enter your ConvertKit credentials below. You can find these in your ConvertKit account under <strong>Settings > Advanced</strong>.</p>';
}

function carolina_convertkit_api_key_render() {
    $option = get_option('convertkit_api_key');
    echo '<input type="text" name="convertkit_api_key" value="' . esc_attr($option) . '" class="regular-text" />';
    echo '<p class="description">Your public API Key.</p>';
}

function carolina_convertkit_form_id_render() {
    $option = get_option('convertkit_form_id');
    echo '<input type="text" name="convertkit_form_id" value="' . esc_attr($option) . '" class="regular-text" />';
    echo '<p class="description">Go to <strong>Grow > Landing Pages & Forms</strong>, click your form, and find the ID in the URL (e.g., https://app.convertkit.com/forms/designers/<strong>123456</strong>/edit)</p>';
}

/**
 * Render the settings page HTML
 */
function carolina_newsletter_options_page() {
    ?>
    <div class="wrap">
        <h1>Newsletter Settings (ConvertKit)</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('carolina_newsletter_group');
            do_settings_sections('carolina_newsletter');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// -----------------------------------------------------------------------------
// 2. API CONNECTION
// -----------------------------------------------------------------------------

/**
 * Add subscriber to ConvertKit Form
 */
function carolina_convertkit_add_subscriber($email) {
    $api_key = get_option('convertkit_api_key');
    $form_id = get_option('convertkit_form_id');
    
    if (empty($api_key) || empty($form_id)) {
        return new WP_Error('config_error', 'ConvertKit credentials not configured');
    }
    
    $url = "https://api.convertkit.com/v3/forms/{$form_id}/subscribe";
    
    $response = wp_remote_post($url, array(
        'headers' => array(
            'Content-Type' => 'application/json; charset=utf-8'
        ),
        'body' => json_encode(array(
            'api_key' => $api_key,
            'email' => $email
        )),
        'timeout' => 30,
    ));
    
    if (is_wp_error($response)) {
        return $response;
    }
    
    $status_code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    // ConvertKit returns 200 for success
    if ($status_code === 200) {
        return true;
    }
    
    $error_message = isset($body['message']) ? $body['message'] : 'Unknown error';
    return new WP_Error('api_error', $error_message);
}

// -----------------------------------------------------------------------------
// 3. AJAX HANDLER
// -----------------------------------------------------------------------------

add_action('wp_ajax_carolina_newsletter_subscribe', 'carolina_newsletter_subscribe_handler');
add_action('wp_ajax_nopriv_carolina_newsletter_subscribe', 'carolina_newsletter_subscribe_handler');

function carolina_newsletter_subscribe_handler() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'carolina_newsletter_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    // Validate email
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address'));
        return;
    }
    
    // Add to ConvertKit
    $result = carolina_convertkit_add_subscriber($email);
    
    if (is_wp_error($result)) {
        // Log the error
        error_log('ConvertKit Error: ' . $result->get_error_message());
        
        // Return friendly error
        if ($result->get_error_code() === 'config_error') {
            wp_send_json_error(array('message' => 'Configuration Error: Please check Newsletter Settings in Dashboard.'));
        } else {
            wp_send_json_error(array('message' => 'Unable to subscribe at this moment. Please try again later.'));
        }
        return;
    }
    
    wp_send_json_success(array('message' => 'Thank you! Only one step left: please check your email to confirm your subscription.'));
}

/**
 * Enqueue JS script
 */
add_action('wp_enqueue_scripts', 'carolina_newsletter_enqueue_scripts');
function carolina_newsletter_enqueue_scripts() {
    wp_enqueue_script(
        'carolina-newsletter',
        get_stylesheet_directory_uri() . '/assets/js/newsletter.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_localize_script('carolina-newsletter', 'carolinaNewsletter', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carolina_newsletter_nonce'),
        'loading' => 'Subscribing...',
        'success' => 'Thank you!' // The handler message will override this usually, but good fallback
    ));
}

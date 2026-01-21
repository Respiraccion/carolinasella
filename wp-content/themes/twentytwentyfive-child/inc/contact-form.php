<?php
/**
 * Carolina Sella - Custom Contact Form
 * 
 * Features:
 * - Custom Post Type for storing submissions (searchable archive)
 * - Google reCAPTCHA v3 integration
 * - Honeypot anti-spam field
 * - Image upload with size restriction & compression
 * - Only loads on /contacto page
 * - AJAX form submission
 * 
 * @package TwentyTwentyFive_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

// =============================================================================
// 1. CUSTOM POST TYPE - Contact Submissions Archive
// =============================================================================

add_action('init', 'carolina_register_contact_submission_cpt');
function carolina_register_contact_submission_cpt() {
    $labels = array(
        'name'               => 'Contact Submissions',
        'singular_name'      => 'Contact Submission',
        'menu_name'          => 'Contact Forms',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Submission',
        'edit_item'          => 'View Submission',
        'new_item'           => 'New Submission',
        'view_item'          => 'View Submission',
        'search_items'       => 'Search Submissions',
        'not_found'          => 'No submissions found',
        'not_found_in_trash' => 'No submissions in trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-email-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'custom-fields'),
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => false,
        'show_in_rest'        => false,
    );

    register_post_type('contact_submission', $args);
}

// Custom columns for the submissions list
add_filter('manage_contact_submission_posts_columns', 'carolina_contact_submission_columns');
function carolina_contact_submission_columns($columns) {
    $new_columns = array(
        'cb'         => $columns['cb'],
        'title'      => 'Name',
        'email'      => 'Email',
        'reason'     => 'Reason',
        'attachment' => 'Attachment',
        'date'       => 'Date',
    );
    return $new_columns;
}

add_action('manage_contact_submission_posts_custom_column', 'carolina_contact_submission_column_data', 10, 2);
function carolina_contact_submission_column_data($column, $post_id) {
    switch ($column) {
        case 'email':
            echo esc_html(get_post_meta($post_id, '_contact_email', true));
            break;
        case 'reason':
            echo esc_html(get_post_meta($post_id, '_contact_reason', true));
            break;
        case 'attachment':
            $attachment_id = get_post_meta($post_id, '_contact_attachment_id', true);
            if ($attachment_id) {
                $url = wp_get_attachment_url($attachment_id);
                echo '<a href="' . esc_url($url) . '" target="_blank">View Image</a>';
            } else {
                echo '-';
            }
            break;
    }
}

// Make columns sortable
add_filter('manage_edit-contact_submission_sortable_columns', 'carolina_contact_submission_sortable_columns');
function carolina_contact_submission_sortable_columns($columns) {
    $columns['email'] = 'email';
    $columns['reason'] = 'reason';
    return $columns;
}

// =============================================================================
// 2. ADMIN SETTINGS PAGE
// =============================================================================

add_action('admin_menu', 'carolina_contact_form_add_admin_menu');
function carolina_contact_form_add_admin_menu() {
    add_options_page(
        'Contact Form Settings',
        'Contact Form',
        'manage_options',
        'carolina_contact_form',
        'carolina_contact_form_options_page'
    );
}

add_action('admin_init', 'carolina_contact_form_settings_init');
function carolina_contact_form_settings_init() {
    // Register settings
    register_setting('carolina_contact_form_group', 'recaptcha_site_key');
    register_setting('carolina_contact_form_group', 'recaptcha_secret_key');
    register_setting('carolina_contact_form_group', 'contact_form_email');
    register_setting('carolina_contact_form_group', 'contact_form_max_file_size'); // in MB

    // Section
    add_settings_section(
        'carolina_contact_form_section',
        'reCAPTCHA Configuration',
        'carolina_contact_form_section_callback',
        'carolina_contact_form'
    );

    // Fields
    add_settings_field(
        'recaptcha_site_key',
        'reCAPTCHA v3 Site Key',
        'carolina_recaptcha_site_key_render',
        'carolina_contact_form',
        'carolina_contact_form_section'
    );

    add_settings_field(
        'recaptcha_secret_key',
        'reCAPTCHA v3 Secret Key',
        'carolina_recaptcha_secret_key_render',
        'carolina_contact_form',
        'carolina_contact_form_section'
    );

    add_settings_field(
        'contact_form_email',
        'Notification Email',
        'carolina_contact_form_email_render',
        'carolina_contact_form',
        'carolina_contact_form_section'
    );

    add_settings_field(
        'contact_form_max_file_size',
        'Max File Size (MB)',
        'carolina_contact_form_max_file_size_render',
        'carolina_contact_form',
        'carolina_contact_form_section'
    );
}

function carolina_contact_form_section_callback() {
    echo '<p>Configure your Google reCAPTCHA v3 credentials. Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin Console</a>.</p>';
}

function carolina_recaptcha_site_key_render() {
    $option = get_option('recaptcha_site_key');
    echo '<input type="text" name="recaptcha_site_key" value="' . esc_attr($option) . '" class="regular-text" />';
    echo '<p class="description">Your reCAPTCHA v3 Site Key (public)</p>';
}

function carolina_recaptcha_secret_key_render() {
    $option = get_option('recaptcha_secret_key');
    echo '<input type="password" name="recaptcha_secret_key" value="' . esc_attr($option) . '" class="regular-text" />';
    echo '<p class="description">Your reCAPTCHA v3 Secret Key (keep private!)</p>';
}

function carolina_contact_form_email_render() {
    $option = get_option('contact_form_email', get_option('admin_email'));
    echo '<input type="email" name="contact_form_email" value="' . esc_attr($option) . '" class="regular-text" />';
    echo '<p class="description">Email address to receive contact form notifications</p>';
}

function carolina_contact_form_max_file_size_render() {
    $option = get_option('contact_form_max_file_size', 5);
    echo '<input type="number" name="contact_form_max_file_size" value="' . esc_attr($option) . '" min="1" max="20" step="1" style="width: 80px;" /> MB';
    echo '<p class="description">Maximum file size for image uploads (default: 5MB)</p>';
}

function carolina_contact_form_options_page() {
    ?>
    <div class="wrap">
        <h1>Contact Form Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('carolina_contact_form_group');
            do_settings_sections('carolina_contact_form');
            submit_button();
            ?>
        </form>
        
        <hr>
        <h2>Shortcode Usage</h2>
        <p>Use the following shortcode to display the contact form:</p>
        <code>[carolina_contact_form]</code>
        
        <hr>
        <h2>Quick Stats</h2>
        <?php
        $count = wp_count_posts('contact_submission');
        $total = isset($count->publish) ? $count->publish : 0;
        ?>
        <p><strong>Total Submissions:</strong> <?php echo esc_html($total); ?></p>
        <p><a href="<?php echo admin_url('edit.php?post_type=contact_submission'); ?>" class="button">View All Submissions</a></p>
    </div>
    <?php
}

// =============================================================================
// 3. ENQUEUE SCRIPTS & STYLES (ONLY ON /contacto PAGE)
// =============================================================================

add_action('wp_enqueue_scripts', 'carolina_contact_form_enqueue_scripts');
function carolina_contact_form_enqueue_scripts() {
    // Only load on contacto page
    if (!is_page('contact') && !is_page('contacto')) { // Added 'contact' just in case
        return;
    }

    $site_key = get_option('recaptcha_site_key');

    // Enqueue reCAPTCHA v3 script (only if key is set)
    if (!empty($site_key)) {
        wp_enqueue_script(
            'google-recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . esc_attr($site_key),
            array(),
            null,
            true
        );
    }

    // Enqueue our contact form JS
    wp_enqueue_script(
        'carolina-contact-form',
        get_stylesheet_directory_uri() . '/assets/js/contact-form.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // Enqueue contact form CSS
    wp_enqueue_style(
        'carolina-contact-form',
        get_stylesheet_directory_uri() . '/assets/css/contact-form.css',
        array(),
        '1.0.0'
    );

    // Localize script with data
    $max_size = get_option('contact_form_max_file_size', 5);
    wp_localize_script('carolina-contact-form', 'carolinaContactForm', array(
        'ajaxUrl'       => admin_url('admin-ajax.php'),
        'nonce'         => wp_create_nonce('carolina_contact_form_nonce'),
        'recaptchaSiteKey' => $site_key,
        'maxFileSize'   => intval($max_size) * 1024 * 1024, // Convert to bytes
        'maxFileSizeMB' => intval($max_size),
        'messages' => array(
            'loading'     => 'Sending...',
            'success'     => 'Thank you! Your message has been sent successfully.',
            'error'       => 'There was an error. Please try again.',
            'fileTooLarge' => 'File is too large. Max: ' . $max_size . 'MB',
            'invalidFileType' => 'Invalid file type. Please upload an image.',
        )
    ));
}

// =============================================================================
// 4. SHORTCODE - [carolina_contact_form]
// =============================================================================

add_shortcode('carolina_contact_form', 'carolina_contact_form_shortcode');
function carolina_contact_form_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts);
    
    ob_start();
    ?>
    <div class="carolina-contact-form-wrapper">
        <form id="carolina-contact-form" class="carolina-contact-form" enctype="multipart/form-data" novalidate>
            
            <!-- Name Field -->
            <div class="form-group">
                <label for="contact-name">Name <span class="required">*</span></label>
                <input type="text" id="contact-name" name="contact_name" required>
            </div>
            
            <!-- Email Field -->
            <div class="form-group">
                <label for="contact-email">Email <span class="required">*</span></label>
                <input type="email" id="contact-email" name="contact_email" required>
            </div>
            
            <!-- Reason Dropdown -->
            <div class="form-group">
                <label for="contact-reason">Reason</label>
                <select id="contact-reason" name="contact_reason">
                    <option value="">— Select —</option>
                    <option value="tattoo">Tattoo Inquiry</option>
                    <option value="art">Art & Commissions</option>
                    <option value="other">General Inquiry / Other</option>
                </select>
            </div>
            
            <!-- Message Field -->
            <div class="form-group">
                <label for="contact-message">Message <span class="required">*</span></label>
                <textarea id="contact-message" name="contact_message" rows="6" required></textarea>
            </div>
            
            <!-- Image Upload (Optional) -->
            <div class="form-group">
                <label for="contact-image">Attach Image (optional)</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="contact-image" name="contact_image" accept="image/*">
                    <div class="file-upload-info">
                        <span class="file-name">No file selected</span>
                        <button type="button" class="file-clear-btn" style="display:none;">×</button>
                    </div>
                    <p class="file-help">Formats: JPG, PNG, GIF, HEIC, WebP. Max: <?php echo esc_html(get_option('contact_form_max_file_size', 5)); ?>MB</p>
                </div>
                <div class="image-preview" style="display:none;">
                    <img src="" alt="Preview">
                </div>
            </div>
            
            <!-- Honeypot Field (hidden from humans, visible to bots) -->
            <div class="form-group hp-field" aria-hidden="true">
                <label for="contact-website">Website</label>
                <input type="text" id="contact-website" name="contact_website" tabindex="-1" autocomplete="off">
            </div>
            
            <!-- reCAPTCHA notice -->
            <div class="recaptcha-notice">
                <small>This site is protected by reCAPTCHA and the Google 
                <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and 
                <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.</small>
            </div>
            
            <!-- Hidden token field for reCAPTCHA -->
            <input type="hidden" id="recaptcha-token" name="recaptcha_token" value="">
            
            <!-- Submit Button -->
            <div class="form-group form-submit">
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Send Message</span>
                    <span class="btn-loading" style="display:none;">
                        <svg class="spinner" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                        </svg>
                        Sending...
                    </span>
                </button>
            </div>
            
            <!-- Form Messages -->
            <div class="form-messages" style="display:none;"></div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

// =============================================================================
// 5. AJAX HANDLER - Form Submission
// =============================================================================

add_action('wp_ajax_carolina_contact_form_submit', 'carolina_contact_form_submit_handler');
add_action('wp_ajax_nopriv_carolina_contact_form_submit', 'carolina_contact_form_submit_handler');

function carolina_contact_form_submit_handler() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'carolina_contact_form_nonce')) {
        wp_send_json_error(array('message' => 'Security error. Please reload the page and try again.'));
        return;
    }

    // Honeypot check - if filled, it's a bot
    if (!empty($_POST['contact_website'])) {
        // Pretend success but do nothing
        wp_send_json_success(array('message' => 'Thank you! Your message has been sent.'));
        return;
    }

    // Verify reCAPTCHA
    $recaptcha_secret = get_option('recaptcha_secret_key');
    if (!empty($recaptcha_secret)) {
        $recaptcha_token = isset($_POST['recaptcha_token']) ? sanitize_text_field($_POST['recaptcha_token']) : '';
        
        if (empty($recaptcha_token)) {
            wp_send_json_error(array('message' => 'Verification error. Please reload.'));
            return;
        }

        $recaptcha_response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        ));

        if (is_wp_error($recaptcha_response)) {
            error_log('reCAPTCHA Error: ' . $recaptcha_response->get_error_message());
            wp_send_json_error(array('message' => 'Verification error. Please try again.'));
            return;
        }

        $recaptcha_data = json_decode(wp_remote_retrieve_body($recaptcha_response), true);
        
        // Check if successful and score is acceptable (0.5 or higher)
        if (!$recaptcha_data['success'] || (isset($recaptcha_data['score']) && $recaptcha_data['score'] < 0.5)) {
            error_log('reCAPTCHA Failed: Score = ' . (isset($recaptcha_data['score']) ? $recaptcha_data['score'] : 'N/A'));
            wp_send_json_error(array('message' => 'Verification failed. Please try again.'));
            return;
        }
    }

    // Validate required fields
    $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
    $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
    $reason = isset($_POST['contact_reason']) ? sanitize_text_field($_POST['contact_reason']) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Please complete all required fields.'));
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address.'));
        return;
    }

    // Map reason values to labels
    $reason_labels = array(
        'tattoo' => 'Tattoo Inquiry',
        'art'    => 'Art & Commissions',
        'other'  => 'General Inquiry / Other',
    );
    $reason_label = isset($reason_labels[$reason]) ? $reason_labels[$reason] : 'Unspecified';

    // Handle file upload
    $attachment_id = 0;
    if (!empty($_FILES['contact_image']['name'])) {
        $file = $_FILES['contact_image'];
        
        // Validate file type
        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/heic', 'image/heif');
        $file_type = wp_check_filetype($file['name']);
        $mime_type = $file['type'];
        
        // Check if it's an image
        if (!in_array(strtolower($mime_type), $allowed_types) && !preg_match('/^image\//', $mime_type)) {
            wp_send_json_error(array('message' => 'File type not allowed. Images only.'));
            return;
        }

        // Check file size
        $max_size = get_option('contact_form_max_file_size', 5) * 1024 * 1024;
        if ($file['size'] > $max_size) {
            wp_send_json_error(array('message' => 'File too large. Max: ' . get_option('contact_form_max_file_size', 5) . 'MB'));
            return;
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(array('message' => 'Error uploading file. Please try again.'));
            return;
        }

        // Include required files for media handling
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Upload the file
        $upload = wp_handle_upload($file, array('test_form' => false));
        
        if (isset($upload['error'])) {
            error_log('Contact Form Upload Error: ' . $upload['error']);
            wp_send_json_error(array('message' => 'Error processing image. Please try again.'));
            return;
        }

        // Create attachment
        $attachment = array(
            'post_mime_type' => $upload['type'],
            'post_title'     => sanitize_file_name($file['name']),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $upload['file']);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error(array('message' => 'Error saving image.'));
            return;
        }

        // Generate metadata for attachment
        $attach_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $attach_data);

        // Compress image if it's JPEG, PNG, or WebP
        carolina_compress_contact_image($upload['file'], $upload['type']);
    }

    // Create the submission post
    $post_data = array(
        'post_type'    => 'contact_submission',
        'post_title'   => $name . ' - ' . $email,
        'post_content' => $message,
        'post_status'  => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Error saving message. Please try again.'));
        return;
    }

    // Save meta data
    update_post_meta($post_id, '_contact_name', $name);
    update_post_meta($post_id, '_contact_email', $email);
    update_post_meta($post_id, '_contact_reason', $reason_label);
    update_post_meta($post_id, '_contact_message', $message);
    update_post_meta($post_id, '_contact_ip', $_SERVER['REMOTE_ADDR']);
    update_post_meta($post_id, '_contact_user_agent', $_SERVER['HTTP_USER_AGENT']);
    
    if ($attachment_id) {
        update_post_meta($post_id, '_contact_attachment_id', $attachment_id);
    }

    // Send email notification
    $admin_email = get_option('contact_form_email', get_option('admin_email'));
    $site_name = get_bloginfo('name');
    
    $subject = "[{$site_name}] New Contact: {$reason_label}";
    
    $email_body = "You have received a new contact message:\n\n";
    $email_body .= "Name: {$name}\n";
    $email_body .= "Email: {$email}\n";
    $email_body .= "Reason: {$reason_label}\n\n";
    $email_body .= "Message:\n{$message}\n\n";
    
    if ($attachment_id) {
        $email_body .= "Attachment: " . wp_get_attachment_url($attachment_id) . "\n\n";
    }
    
    $email_body .= "—\n";
    $email_body .= "View in admin: " . admin_url('post.php?post=' . $post_id . '&action=edit') . "\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    wp_mail($admin_email, $subject, $email_body, $headers);

    // Success!
    wp_send_json_success(array('message' => 'Thank you! Your message has been sent successfully. We will be in touch soon.'));
}

// =============================================================================
// 6. IMAGE COMPRESSION HELPER
// =============================================================================

function carolina_compress_contact_image($file_path, $mime_type) {
    // Only compress supported formats
    $supported = array('image/jpeg', 'image/jpg', 'image/png', 'image/webp');
    if (!in_array($mime_type, $supported)) {
        return false;
    }

    // Get the image editor
    $image = wp_get_image_editor($file_path);
    if (is_wp_error($image)) {
        return false;
    }

    // Resize if larger than 2000px on any side
    $size = $image->get_size();
    $max_dimension = 2000;
    
    if ($size['width'] > $max_dimension || $size['height'] > $max_dimension) {
        $image->resize($max_dimension, $max_dimension, false);
    }

    // Set quality (compression level)
    $image->set_quality(80);

    // Save the compressed image
    $saved = $image->save($file_path);

    return !is_wp_error($saved);
}

// =============================================================================
// 7. ADMIN METABOX FOR VIEWING SUBMISSION DETAILS
// =============================================================================

add_action('add_meta_boxes', 'carolina_contact_submission_metabox');
function carolina_contact_submission_metabox() {
    add_meta_box(
        'contact_submission_details',
        'Submission Details',
        'carolina_contact_submission_metabox_content',
        'contact_submission',
        'side',
        'high'
    );
}

function carolina_contact_submission_metabox_content($post) {
    $name = get_post_meta($post->ID, '_contact_name', true);
    $email = get_post_meta($post->ID, '_contact_email', true);
    $reason = get_post_meta($post->ID, '_contact_reason', true);
    $ip = get_post_meta($post->ID, '_contact_ip', true);
    $attachment_id = get_post_meta($post->ID, '_contact_attachment_id', true);
    
    ?>
    <table class="form-table" style="margin: 0;">
        <tr>
            <td><strong>Name:</strong></td>
            <td><?php echo esc_html($name); ?></td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td>
        </tr>
        <tr>
            <td><strong>Reason:</strong></td>
            <td><?php echo esc_html($reason); ?></td>
        </tr>
        <tr>
            <td><strong>IP:</strong></td>
            <td><?php echo esc_html($ip); ?></td>
        </tr>
        <?php if ($attachment_id): ?>
        <tr>
            <td colspan="2">
                <strong>Attachment:</strong><br>
                <?php echo wp_get_attachment_image($attachment_id, 'medium'); ?>
                <br>
                <a href="<?php echo esc_url(wp_get_attachment_url($attachment_id)); ?>" target="_blank" class="button">View Full Size</a>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}

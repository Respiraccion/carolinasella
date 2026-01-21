<?php
/**
 * Image Protection
 * Blocks right-click and dragging on images.
 */

function carlinasella_enqueue_image_protection() {
    wp_enqueue_script(
        'carolinasella-image-protection',
        get_stylesheet_directory_uri() . '/assets/js/image-protection.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'carlinasella_enqueue_image_protection');

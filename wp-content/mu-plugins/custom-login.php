<?php
/**
 * Plugin Name: Custom Login URL & Protection
 * Description: Filters login URLs to point to /acceder. Nginx handles the rewrite and protection.
 */

defined('ABSPATH') or die();

class Custom_Login_Protection {

    private $slug = 'acceder';

    public function __construct() {
        // Filters to change generated URLs
        add_filter('site_url', [$this, 'filter_site_url'], 10, 4);
        add_filter('network_site_url', [$this, 'filter_site_url'], 10, 3);
        add_filter('wp_redirect', [$this, 'filter_wp_redirect'], 10, 2);
        
        // Ensure the login form displays correctly if there are any quirks
        // But mainly the site_url filter handles the form action.
    }

    public function filter_site_url($url, $path, $scheme, $blog_id = null) {
        return $this->replace_login_url($url);
    }

    public function filter_wp_redirect($location, $status) {
        return $this->replace_login_url($location);
    }

    private function replace_login_url($url) {
        // We only want to replace the base wp-login.php, preserving query args
        if (strpos($url, 'wp-login.php') !== false) {
            // Check if it's already modified or something else
            // Simple replace. 
            // Note: site_url might return http://example.com/wp-login.php?action=logout
            $url = str_replace('wp-login.php', $this->slug, $url);
        }
        return $url;
    }
}

new Custom_Login_Protection();

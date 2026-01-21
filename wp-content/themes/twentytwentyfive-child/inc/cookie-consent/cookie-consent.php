<?php
/**
 * Carolina Sella - EU Cookie Consent Banner
 * 
 * GDPR-compliant cookie consent management without plugins.
 * 
 * @package TwentyTwentyFive_Child
 * @subpackage Cookie_Consent
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Enqueue cookie consent scripts and styles.
 */
function carolina_cookie_consent_enqueue() {
    $base_path = get_stylesheet_directory_uri() . '/inc/cookie-consent/';
    $version = '1.0.0';
    
    // Enqueue CSS
    wp_enqueue_style(
        'carolina-cookie-consent',
        $base_path . 'cookie-consent.css',
        array(),
        $version
    );
    
    // Enqueue JS - in footer, after page load
    wp_enqueue_script(
        'carolina-cookie-consent',
        $base_path . 'cookie-consent.js',
        array(), // No dependencies
        $version,
        true // Load in footer
    );
    
    // Pass configuration to JS
    wp_localize_script('carolina-cookie-consent', 'cookieConsentConfig', array(
        'cookieName'     => 'carolina_cookie_consent',
        'consentVersion' => '1.0',
        'expiryDays'     => 365,
        'policyUrl'      => home_url('/privacy-policy/#cookies'),
        'texts'          => array(
            'bannerTitle'    => 'We value your privacy',
            'bannerMessage'  => 'We use cookies to enhance your browsing experience. Essential cookies are necessary for the website to function. You can choose to accept or decline optional cookies.',
            'acceptAll'      => 'Accept All',
            'rejectOptional' => 'Essential Only',
            'customize'      => 'Customize',
            'savePrefs'      => 'Save Preferences',
            'policyLink'     => 'Cookie Policy',
            // Category labels
            'essential'      => 'Essential',
            'essentialDesc'  => 'Required for the website to function properly. Cannot be disabled.',
            'functional'     => 'Functional',
            'functionalDesc' => 'Remember your preferences and settings.',
            'analytics'      => 'Analytics',
            'analyticsDesc'  => 'Help us understand how visitors use our website.',
            'marketing'      => 'Marketing',
            'marketingDesc'  => 'Used to deliver personalized advertisements.',
        )
    ));
}
add_action('wp_enqueue_scripts', 'carolina_cookie_consent_enqueue');

/**
 * Output the cookie consent banner HTML.
 */
function carolina_cookie_consent_banner() {
    ?>
    <!-- Cookie Consent Banner -->
    <div id="cookie-consent-banner" class="cookie-consent-banner" role="dialog" aria-modal="true" aria-labelledby="cookie-consent-title" aria-describedby="cookie-consent-desc" style="display: none;">
        <div class="cookie-consent-content">
            <div class="cookie-consent-text">
                <h3 id="cookie-consent-title" class="cookie-consent-title">We value your privacy</h3>
                <p id="cookie-consent-desc" class="cookie-consent-message">
                    We use cookies to enhance your browsing experience. Essential cookies are necessary for the website to function. 
                    You can choose to accept or decline optional cookies.
                    <a href="<?php echo esc_url(home_url('/privacy-policy/#cookies')); ?>" class="cookie-consent-policy-link">Cookie Policy</a>
                </p>
            </div>
            <div class="cookie-consent-actions">
                <button type="button" id="cookie-accept-all" class="cookie-consent-btn cookie-consent-btn-accept">Accept All</button>
                <button type="button" id="cookie-reject-optional" class="cookie-consent-btn cookie-consent-btn-reject">Essential Only</button>
                <button type="button" id="cookie-customize" class="cookie-consent-btn cookie-consent-btn-customize">Customize</button>
            </div>
        </div>
        
        <!-- Settings Panel (Hidden by default) -->
        <div id="cookie-consent-settings" class="cookie-consent-settings" style="display: none;">
            <h4 class="cookie-consent-settings-title">Cookie Preferences</h4>
            
            <div class="cookie-consent-category">
                <div class="cookie-consent-category-header">
                    <label>
                        <input type="checkbox" id="cookie-cat-essential" checked disabled>
                        <span class="cookie-consent-category-name">Essential</span>
                    </label>
                    <span class="cookie-consent-always-on">Always Active</span>
                </div>
                <p class="cookie-consent-category-desc">Required for the website to function properly. Cannot be disabled.</p>
            </div>
            
            <div class="cookie-consent-category">
                <div class="cookie-consent-category-header">
                    <label>
                        <input type="checkbox" id="cookie-cat-functional">
                        <span class="cookie-consent-category-name">Functional</span>
                    </label>
                </div>
                <p class="cookie-consent-category-desc">Remember your preferences and settings.</p>
            </div>
            
            <div class="cookie-consent-category">
                <div class="cookie-consent-category-header">
                    <label>
                        <input type="checkbox" id="cookie-cat-analytics">
                        <span class="cookie-consent-category-name">Analytics</span>
                    </label>
                </div>
                <p class="cookie-consent-category-desc">Help us understand how visitors use our website.</p>
            </div>
            
            <div class="cookie-consent-category">
                <div class="cookie-consent-category-header">
                    <label>
                        <input type="checkbox" id="cookie-cat-marketing">
                        <span class="cookie-consent-category-name">Marketing</span>
                    </label>
                </div>
                <p class="cookie-consent-category-desc">Used to deliver personalized advertisements.</p>
            </div>
            
            <div class="cookie-consent-settings-actions">
                <button type="button" id="cookie-save-preferences" class="cookie-consent-btn cookie-consent-btn-save">Save Preferences</button>
            </div>
        </div>
    </div>
    
    <!-- Cookie Settings Trigger (for footer/settings page) -->
    <button type="button" id="cookie-consent-trigger" class="cookie-consent-trigger" aria-label="Cookie Settings" style="display: none;">
        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/Cookie.png' ); ?>" alt="Cookie Settings" class="cookie-icon-img">
    </button>
    <?php
}
add_action('wp_footer', 'carolina_cookie_consent_banner', 999);

/**
 * Add shortcode to display cookie settings button anywhere.
 * Usage: [cookie_settings text="Manage Cookies"]
 */
function carolina_cookie_settings_shortcode($atts) {
    $atts = shortcode_atts(array(
        'text' => 'Cookie Settings'
    ), $atts);
    
    return '<button type="button" class="cookie-consent-open-settings cookie-consent-btn cookie-consent-btn-customize">' . esc_html($atts['text']) . '</button>';
}
add_shortcode('cookie_settings', 'carolina_cookie_settings_shortcode');

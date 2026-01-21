# 🍪 EU Cookie Banner Implementation Plan

> **For: carolinasella.com**  
> **Date: January 18, 2026**  
> **Status: Planning Phase**  
> **Language: English**

---

## 📋 Table of Contents

1. [Overview](#1-overview)
2. [EU Cookie Compliance Requirements](#2-eu-cookie-compliance-requirements)
3. [Technical Architecture](#3-technical-architecture)
4. [File Structure](#4-file-structure)
5. [Implementation Steps](#5-implementation-steps)
6. [Code Specifications](#6-code-specifications)
7. [Testing Checklist](#7-testing-checklist)
8. [Maintenance Notes](#8-maintenance-notes)

---

## 1. Overview

### 1.1 Purpose
Implement a lightweight, GDPR-compliant cookie consent banner without relying on third-party plugins. The solution must:

- ✅ Comply with EU GDPR & ePrivacy Directive requirements
- ✅ Match the minimalistic, elegant design aesthetic of the site
- ✅ Use the existing font families (Lato, Bodoni Moda)
- ✅ Use the existing color palette (Grafito, Violeta Polvo, Base)
- ✅ Be self-contained and easy to maintain
- ✅ Work correctly with caching solutions

### 1.2 Current Site Status
- **Tracking**: None currently active (no Google Analytics, Facebook Pixel, etc.)
- **Essential Cookies**: WordPress session cookies only
- **Third-party services**: WooCommerce (shopping cart), ConvertKit (newsletter)

### 1.3 Cookie Categories for This Site

| Category | Description | Consent Required? |
|----------|-------------|-------------------|
| **Essential** | WordPress session, WooCommerce cart, login cookies | No |
| **Functional** | User preferences (language, theme) | Yes |
| **Analytics** | Future: Google Analytics, Plausible | Yes |
| **Marketing** | Future: ConvertKit tracking, social widgets | Yes |

---

## 2. EU Cookie Compliance Requirements

### 2.1 GDPR Requirements (Mandatory)

1. **Prior Consent**: Cookies (except essential) must NOT be set until user consents
2. **Informed Consent**: Users must understand WHAT they're consenting to
3. **Granular Control**: Users must be able to accept/reject categories individually
4. **Equal Options**: "Accept" and "Reject" must be equally accessible (no dark patterns)
5. **Easy Withdrawal**: Users must be able to change preferences anytime
6. **Proof of Consent**: Store consent timestamp and version
7. **Cookie Policy**: Link to a detailed policy explaining all cookies

### 2.2 ePrivacy Directive Requirements

1. Clear information about cookie purposes
2. Consent before any tracking cookies are set
3. Secure storage of consent preferences

### 2.3 Implementation Choices

| Requirement | Our Implementation |
|-------------|-------------------|
| Consent storage | localStorage + essential cookie |
| Consent duration | 365 days (re-ask annually) |
| Consent version | Stored for compliance tracking |
| Banner display | Bottom of screen, non-intrusive overlay |
| Cookie categories | Essential (auto) + Optional (consent required) |

---

## 3. Technical Architecture

### 3.1 Component Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        WordPress Theme                          │
│                  twentytwentyfive-child                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    functions.php                         │   │
│  │  require_once '/inc/cookie-consent/cookie-consent.php'   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                  │
│  ┌───────────────────────────▼─────────────────────────────┐   │
│  │              inc/cookie-consent/                         │   │
│  │  ┌──────────────────────────────────────────────────┐   │   │
│  │  │  cookie-consent.php                               │   │   │
│  │  │  - Main loader                                    │   │   │
│  │  │  - Enqueue scripts/styles                        │   │   │
│  │  │  - Render banner HTML                            │   │   │
│  │  └──────────────────────────────────────────────────┘   │   │
│  │                                                          │   │
│  │  ┌──────────────────────────────────────────────────┐   │   │
│  │  │  cookie-consent.js                                │   │   │
│  │  │  - Consent logic                                  │   │   │
│  │  │  - Cookie management                              │   │   │
│  │  │  - localStorage handling                         │   │   │
│  │  │  - Banner show/hide                              │   │   │
│  │  └──────────────────────────────────────────────────┘   │   │
│  │                                                          │   │
│  │  ┌──────────────────────────────────────────────────┐   │   │
│  │  │  cookie-consent.css                               │   │   │
│  │  │  - Banner styles                                  │   │   │
│  │  │  - Matches site design                           │   │   │
│  │  │  - Responsive design                              │   │   │
│  │  └──────────────────────────────────────────────────┘   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Consent Flow

```
                    ┌──────────────────┐
                    │  User Visits     │
                    │    Website       │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │  Check Consent   │
                    │  (localStorage)  │
                    └────────┬─────────┘
                             │
              ┌──────────────┴──────────────┐
              │                             │
     ┌────────▼────────┐          ┌────────▼────────┐
     │ Consent Exists  │          │   No Consent    │
     │   & Valid       │          │     Found       │
     └────────┬────────┘          └────────┬────────┘
              │                             │
     ┌────────▼────────┐          ┌────────▼────────┐
     │  Load Allowed   │          │  Show Banner    │
     │    Services     │          │  (No tracking   │
     │                 │          │   until accept) │
     └─────────────────┘          └────────┬────────┘
                                           │
                    ┌──────────────────────┴───────────────────────┐
                    │                                              │
           ┌────────▼────────┐    ┌────────▼────────┐    ┌────────▼────────┐
           │  Accept All     │    │ Reject Optional │    │  Customize      │
           └────────┬────────┘    └────────┬────────┘    └────────┬────────┘
                    │                      │                      │
           ┌────────▼────────┐    ┌────────▼────────┐    ┌────────▼────────┐
           │ Store: all:true │    │ Store: all:false│    │ Show Settings   │
           │ Load all scripts│    │ Essential only  │    │    Panel        │
           └─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## 4. File Structure

### 4.1 New Files to Create

```
wp-content/themes/twentytwentyfive-child/
├── inc/
│   └── cookie-consent/                    # NEW FOLDER
│       ├── cookie-consent.php             # Main PHP loader
│       ├── cookie-consent.js              # JavaScript logic
│       └── cookie-consent.css             # Styles
└── functions.php                          # Add require_once
```

### 4.2 Files to Modify

| File | Modification |
|------|-------------|
| `functions.php` | Add `require_once` for cookie-consent.php |

### 4.3 Optional: Cookie Policy Page

A new WordPress page should be created at `/cookie-policy` containing:
- What cookies are used
- Cookie categories explanation
- How to manage/delete cookies
- Contact information

---

## 5. Implementation Steps

### Step 1: Create Folder Structure
```bash
mkdir -p wp-content/themes/twentytwentyfive-child/inc/cookie-consent
```

### Step 2: Create cookie-consent.php (Main Loader)

**Purpose**: Register and enqueue all assets, render banner HTML

**Key Functions**:
- `carolina_cookie_consent_enqueue()` - Load JS/CSS
- `carolina_cookie_consent_banner()` - Output HTML in footer
- `carolina_cookie_consent_customizer_link()` - Allow preference changes

### Step 3: Create cookie-consent.js (Logic)

**Purpose**: Handle all consent logic client-side

**Key Functions**:
- `checkConsent()` - Check if valid consent exists
- `showBanner()` / `hideBanner()` - Toggle visibility
- `setConsent(preferences)` - Store user choices
- `getConsent()` - Retrieve stored preferences
- `loadConditionalScripts()` - Load tracking scripts if allowed
- `toggleSettingsPanel()` - Show/hide granular controls

**Consent Storage Format**:
```javascript
{
  version: "1.0",
  timestamp: "2026-01-18T13:00:00.000Z",
  preferences: {
    essential: true,      // Always true
    functional: false,
    analytics: false,
    marketing: false
  }
}
```

### Step 4: Create cookie-consent.css (Styles)

**Design Requirements**:
- Use CSS custom properties for theming
- Match existing color palette
- Minimalistic, no visual clutter
- Smooth animations (fade in/out)
- Responsive (mobile-first)
- Accessible (focus states, ARIA)

### Step 5: Add require_once to functions.php

```php
/**
 * EU Cookie Consent Banner (GDPR Compliant)
 * - No third-party dependencies
 * - Stores consent in localStorage + cookie
 * - Blocks non-essential scripts until consent
 */
require_once get_stylesheet_directory() . '/inc/cookie-consent/cookie-consent.php';
```

### Step 6: Create Cookie Policy Page (WordPress Admin)

**Page Details**:
- URL: `/cookie-policy`
- Template: Default
- Content: Explain all cookies used on the site

### Step 7: Test Across Browsers & Devices

### Step 8: Validate Consent Blocking Works

---

## 6. Code Specifications

### 6.1 cookie-consent.php

```php
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
        'policyUrl'      => home_url('/cookie-policy/'),
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
                    <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>" class="cookie-consent-policy-link">Cookie Policy</a>
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
        🍪 Cookie Settings
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
```

### 6.2 cookie-consent.js

```javascript
/**
 * Carolina Sella - Cookie Consent Manager
 * 
 * GDPR-compliant cookie consent handling.
 * No external dependencies.
 * 
 * @version 1.0.0
 */

(function() {
    'use strict';

    // Configuration (passed from PHP via wp_localize_script)
    const config = window.cookieConsentConfig || {
        cookieName: 'carolina_cookie_consent',
        consentVersion: '1.0',
        expiryDays: 365,
        policyUrl: '/cookie-policy/'
    };

    // DOM Elements (cached after init)
    let elements = {};

    /**
     * Initialize the consent manager.
     */
    function init() {
        // Cache DOM elements
        elements = {
            banner: document.getElementById('cookie-consent-banner'),
            settings: document.getElementById('cookie-consent-settings'),
            trigger: document.getElementById('cookie-consent-trigger'),
            acceptAll: document.getElementById('cookie-accept-all'),
            rejectOptional: document.getElementById('cookie-reject-optional'),
            customize: document.getElementById('cookie-customize'),
            savePrefs: document.getElementById('cookie-save-preferences'),
            // Checkboxes
            catFunctional: document.getElementById('cookie-cat-functional'),
            catAnalytics: document.getElementById('cookie-cat-analytics'),
            catMarketing: document.getElementById('cookie-cat-marketing'),
            // External triggers
            openSettingsButtons: document.querySelectorAll('.cookie-consent-open-settings')
        };

        // Check if we have valid consent already
        const consent = getConsent();

        if (consent && consent.version === config.consentVersion) {
            // Valid consent exists - load allowed scripts
            loadConditionalScripts(consent.preferences);
            showTrigger();
        } else {
            // No consent or outdated - show banner
            showBanner();
        }

        // Bind event listeners
        bindEvents();
    }

    /**
     * Bind all event listeners.
     */
    function bindEvents() {
        // Main banner buttons
        if (elements.acceptAll) {
            elements.acceptAll.addEventListener('click', handleAcceptAll);
        }
        if (elements.rejectOptional) {
            elements.rejectOptional.addEventListener('click', handleRejectOptional);
        }
        if (elements.customize) {
            elements.customize.addEventListener('click', toggleSettings);
        }
        if (elements.savePrefs) {
            elements.savePrefs.addEventListener('click', handleSavePreferences);
        }

        // Trigger button (shows after consent given)
        if (elements.trigger) {
            elements.trigger.addEventListener('click', showBanner);
        }

        // External "Cookie Settings" buttons
        elements.openSettingsButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                showBanner();
                // Small delay to ensure banner is visible, then expand settings
                setTimeout(toggleSettings, 100);
            });
        });

        // Close banner on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && elements.banner && elements.banner.style.display !== 'none') {
                // Only close if consent already given
                if (getConsent()) {
                    hideBanner();
                }
            }
        });
    }

    /**
     * Show the consent banner.
     */
    function showBanner() {
        if (elements.banner) {
            elements.banner.style.display = 'block';
            // Trigger reflow for animation
            elements.banner.offsetHeight;
            elements.banner.classList.add('is-visible');
            
            // Focus first button for accessibility
            if (elements.acceptAll) {
                elements.acceptAll.focus();
            }

            // Hide trigger button while banner is open
            if (elements.trigger) {
                elements.trigger.style.display = 'none';
            }

            // Restore checkbox states if consent exists
            const consent = getConsent();
            if (consent && consent.preferences) {
                if (elements.catFunctional) {
                    elements.catFunctional.checked = consent.preferences.functional;
                }
                if (elements.catAnalytics) {
                    elements.catAnalytics.checked = consent.preferences.analytics;
                }
                if (elements.catMarketing) {
                    elements.catMarketing.checked = consent.preferences.marketing;
                }
            }
        }
    }

    /**
     * Hide the consent banner.
     */
    function hideBanner() {
        if (elements.banner) {
            elements.banner.classList.remove('is-visible');
            // Wait for animation to complete
            setTimeout(() => {
                elements.banner.style.display = 'none';
                // Also hide settings if open
                if (elements.settings) {
                    elements.settings.style.display = 'none';
                }
            }, 300);

            // Show trigger button
            showTrigger();
        }
    }

    /**
     * Show the cookie settings trigger button.
     */
    function showTrigger() {
        if (elements.trigger) {
            elements.trigger.style.display = 'block';
        }
    }

    /**
     * Toggle settings panel visibility.
     */
    function toggleSettings() {
        if (elements.settings) {
            const isVisible = elements.settings.style.display !== 'none';
            elements.settings.style.display = isVisible ? 'none' : 'block';
            
            // Update customize button text
            if (elements.customize) {
                elements.customize.textContent = isVisible ? 'Customize' : 'Hide Options';
            }
        }
    }

    /**
     * Handle "Accept All" button click.
     */
    function handleAcceptAll() {
        const preferences = {
            essential: true,
            functional: true,
            analytics: true,
            marketing: true
        };
        
        setConsent(preferences);
        loadConditionalScripts(preferences);
        hideBanner();
    }

    /**
     * Handle "Essential Only" button click.
     */
    function handleRejectOptional() {
        const preferences = {
            essential: true,
            functional: false,
            analytics: false,
            marketing: false
        };
        
        setConsent(preferences);
        // Only essential cookies - no additional scripts to load
        hideBanner();
    }

    /**
     * Handle "Save Preferences" button click.
     */
    function handleSavePreferences() {
        const preferences = {
            essential: true, // Always true
            functional: elements.catFunctional ? elements.catFunctional.checked : false,
            analytics: elements.catAnalytics ? elements.catAnalytics.checked : false,
            marketing: elements.catMarketing ? elements.catMarketing.checked : false
        };
        
        setConsent(preferences);
        loadConditionalScripts(preferences);
        hideBanner();
    }

    /**
     * Store user consent in localStorage and cookie.
     * 
     * @param {Object} preferences - Cookie category preferences
     */
    function setConsent(preferences) {
        const consentData = {
            version: config.consentVersion,
            timestamp: new Date().toISOString(),
            preferences: preferences
        };

        // Store in localStorage (primary)
        try {
            localStorage.setItem(config.cookieName, JSON.stringify(consentData));
        } catch (e) {
            console.warn('Cookie consent: localStorage not available');
        }

        // Also set a simple cookie as backup (for server-side checks if needed)
        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + config.expiryDays);
        
        // Create a simple string representation
        const cookieValue = [
            'v=' + config.consentVersion,
            'e=' + (preferences.essential ? '1' : '0'),
            'f=' + (preferences.functional ? '1' : '0'),
            'a=' + (preferences.analytics ? '1' : '0'),
            'm=' + (preferences.marketing ? '1' : '0')
        ].join('|');
        
        document.cookie = config.cookieName + '=' + encodeURIComponent(cookieValue) + 
                         '; expires=' + expiryDate.toUTCString() + 
                         '; path=/; SameSite=Lax; Secure';

        // Dispatch custom event for other scripts to listen to
        window.dispatchEvent(new CustomEvent('cookieConsentUpdated', {
            detail: consentData
        }));
    }

    /**
     * Retrieve stored consent from localStorage.
     * 
     * @returns {Object|null} Consent data or null if not found
     */
    function getConsent() {
        try {
            const stored = localStorage.getItem(config.cookieName);
            if (stored) {
                return JSON.parse(stored);
            }
        } catch (e) {
            console.warn('Cookie consent: Error reading from localStorage');
        }
        return null;
    }

    /**
     * Load conditional scripts based on consent.
     * 
     * Add your tracking scripts here. They will only load if the user
     * has given consent for the appropriate category.
     * 
     * @param {Object} preferences - User's consent preferences
     */
    function loadConditionalScripts(preferences) {
        // === ANALYTICS SCRIPTS ===
        if (preferences.analytics) {
            // Example: Google Analytics 4
            // Uncomment and add your GA4 ID when ready to use
            /*
            (function() {
                var script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX';
                document.head.appendChild(script);
                
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'G-XXXXXXXXXX');
            })();
            */

            console.log('Cookie Consent: Analytics scripts would load here');
        }

        // === MARKETING SCRIPTS ===
        if (preferences.marketing) {
            // Example: Facebook Pixel
            // Uncomment and add your Pixel ID when ready to use
            /*
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', 'YOUR_PIXEL_ID');
            fbq('track', 'PageView');
            */

            console.log('Cookie Consent: Marketing scripts would load here');
        }

        // === FUNCTIONAL SCRIPTS ===
        if (preferences.functional) {
            // Any functionality that enhances UX but isn't essential
            console.log('Cookie Consent: Functional features enabled');
        }
    }

    /**
     * Utility: Check if a specific category is consented.
     * Can be called from other scripts: window.cookieConsentCheck('analytics')
     * 
     * @param {string} category - Category name
     * @returns {boolean}
     */
    function checkCategory(category) {
        const consent = getConsent();
        if (consent && consent.preferences) {
            return !!consent.preferences[category];
        }
        return false;
    }

    // Expose utility functions globally
    window.cookieConsentCheck = checkCategory;
    window.cookieConsentOpen = showBanner;

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
```

### 6.3 cookie-consent.css

```css
/**
 * Carolina Sella - Cookie Consent Banner Styles
 * 
 * Matches the minimalistic, elegant site design.
 * Uses the existing color palette and typography.
 * 
 * Colors used:
 * - Grafito: #3A3A3A (text, buttons)
 * - Violeta Polvo: #A59BB3 (accent)
 * - Base: #E8E8E4 (background)
 * - Gris Muy Claro: #F1F1EF (hover states)
 * 
 * Font: Lato (body text)
 */

/* ═══════════════════════════════════════════════════════════════════════════
   CSS CUSTOM PROPERTIES (Easy theming)
   ═══════════════════════════════════════════════════════════════════════════ */
:root {
    --cc-bg: #E8E8E4;
    --cc-text: #3A3A3A;
    --cc-text-muted: #5F6C73;
    --cc-accent: #A59BB3;
    --cc-btn-primary-bg: #3A3A3A;
    --cc-btn-primary-text: #F1F1EF;
    --cc-btn-secondary-bg: transparent;
    --cc-btn-secondary-text: #3A3A3A;
    --cc-btn-secondary-border: #3A3A3A;
    --cc-overlay: rgba(58, 58, 58, 0.5);
    --cc-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    --cc-font: 'Lato', -apple-system, BlinkMacSystemFont, sans-serif;
    --cc-radius: 0;
    --cc-transition: 0.3s ease;
    --cc-z-index: 99999;
}

/* ═══════════════════════════════════════════════════════════════════════════
   BANNER CONTAINER
   ═══════════════════════════════════════════════════════════════════════════ */
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--cc-bg);
    box-shadow: var(--cc-shadow);
    font-family: var(--cc-font);
    z-index: var(--cc-z-index);
    
    /* Animation */
    opacity: 0;
    transform: translateY(100%);
    transition: opacity var(--cc-transition), transform var(--cc-transition);
}

.cookie-consent-banner.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* ═══════════════════════════════════════════════════════════════════════════
   BANNER CONTENT
   ═══════════════════════════════════════════════════════════════════════════ */
.cookie-consent-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.cookie-consent-text {
    flex: 1;
    min-width: 280px;
}

.cookie-consent-title {
    font-family: var(--cc-font);
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--cc-text);
    margin: 0 0 8px 0;
    letter-spacing: 0.02em;
}

.cookie-consent-message {
    font-size: 0.9375rem;
    line-height: 1.6;
    color: var(--cc-text);
    margin: 0;
}

.cookie-consent-policy-link {
    color: var(--cc-accent);
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color var(--cc-transition);
}

.cookie-consent-policy-link:hover,
.cookie-consent-policy-link:focus {
    color: var(--cc-text);
}

/* ═══════════════════════════════════════════════════════════════════════════
   BUTTONS
   ═══════════════════════════════════════════════════════════════════════════ */
.cookie-consent-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.cookie-consent-btn {
    font-family: var(--cc-font);
    font-size: 0.875rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 14px 28px;
    border-radius: var(--cc-radius);
    cursor: pointer;
    transition: all var(--cc-transition);
    min-width: 140px;
    text-align: center;
}

/* Primary button (Accept All) */
.cookie-consent-btn-accept {
    background: var(--cc-btn-primary-bg);
    color: var(--cc-btn-primary-text);
    border: 2px solid var(--cc-btn-primary-bg);
}

.cookie-consent-btn-accept:hover,
.cookie-consent-btn-accept:focus {
    background: var(--cc-accent);
    border-color: var(--cc-accent);
}

/* Secondary buttons (Reject, Customize) */
.cookie-consent-btn-reject,
.cookie-consent-btn-customize {
    background: var(--cc-btn-secondary-bg);
    color: var(--cc-btn-secondary-text);
    border: 2px solid var(--cc-btn-secondary-border);
}

.cookie-consent-btn-reject:hover,
.cookie-consent-btn-reject:focus,
.cookie-consent-btn-customize:hover,
.cookie-consent-btn-customize:focus {
    background: var(--cc-btn-secondary-text);
    color: var(--cc-bg);
}

/* Save button in settings */
.cookie-consent-btn-save {
    background: var(--cc-btn-primary-bg);
    color: var(--cc-btn-primary-text);
    border: 2px solid var(--cc-btn-primary-bg);
}

.cookie-consent-btn-save:hover,
.cookie-consent-btn-save:focus {
    background: var(--cc-accent);
    border-color: var(--cc-accent);
}

/* Focus states for accessibility */
.cookie-consent-btn:focus {
    outline: 2px solid var(--cc-accent);
    outline-offset: 2px;
}

/* ═══════════════════════════════════════════════════════════════════════════
   SETTINGS PANEL
   ═══════════════════════════════════════════════════════════════════════════ */
.cookie-consent-settings {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px 24px;
    border-top: 1px solid rgba(58, 58, 58, 0.1);
}

.cookie-consent-settings-title {
    font-family: var(--cc-font);
    font-size: 1rem;
    font-weight: 600;
    color: var(--cc-text);
    margin: 20px 0 16px;
    letter-spacing: 0.02em;
}

/* Category blocks */
.cookie-consent-category {
    padding: 16px 0;
    border-bottom: 1px solid rgba(58, 58, 58, 0.08);
}

.cookie-consent-category:last-of-type {
    border-bottom: none;
}

.cookie-consent-category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.cookie-consent-category-header label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}

.cookie-consent-category-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--cc-text);
}

.cookie-consent-always-on {
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--cc-text-muted);
    background: rgba(165, 155, 179, 0.2);
    padding: 4px 10px;
}

.cookie-consent-category-desc {
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--cc-text-muted);
    margin: 8px 0 0;
    padding-left: 32px;
}

/* Checkbox styling */
.cookie-consent-category input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--cc-text);
}

.cookie-consent-category input[type="checkbox"]:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Settings actions */
.cookie-consent-settings-actions {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid rgba(58, 58, 58, 0.1);
    display: flex;
    justify-content: flex-end;
}

/* ═══════════════════════════════════════════════════════════════════════════
   TRIGGER BUTTON (Shows after consent given)
   ═══════════════════════════════════════════════════════════════════════════ */
.cookie-consent-trigger {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background: var(--cc-bg);
    color: var(--cc-text);
    font-family: var(--cc-font);
    font-size: 0.8125rem;
    font-weight: 500;
    padding: 10px 16px;
    border: 1px solid rgba(58, 58, 58, 0.2);
    border-radius: var(--cc-radius);
    cursor: pointer;
    z-index: calc(var(--cc-z-index) - 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all var(--cc-transition);
}

.cookie-consent-trigger:hover,
.cookie-consent-trigger:focus {
    background: var(--cc-text);
    color: var(--cc-bg);
    border-color: var(--cc-text);
}

/* ═══════════════════════════════════════════════════════════════════════════
   RESPONSIVE DESIGN
   ═══════════════════════════════════════════════════════════════════════════ */

/* Tablets and smaller */
@media screen and (max-width: 768px) {
    .cookie-consent-content {
        flex-direction: column;
        align-items: stretch;
        padding: 20px 24px;
    }
    
    .cookie-consent-text {
        text-align: center;
    }
    
    .cookie-consent-actions {
        justify-content: center;
    }
    
    .cookie-consent-btn {
        flex: 1;
        min-width: 120px;
    }
    
    .cookie-consent-settings {
        padding: 0 24px 20px;
    }
    
    .cookie-consent-category-desc {
        padding-left: 0;
    }
    
    .cookie-consent-settings-actions {
        justify-content: center;
    }
}

/* Mobile phones */
@media screen and (max-width: 480px) {
    .cookie-consent-content {
        padding: 16px;
    }
    
    .cookie-consent-title {
        font-size: 1rem;
    }
    
    .cookie-consent-message {
        font-size: 0.875rem;
    }
    
    .cookie-consent-actions {
        flex-direction: column;
    }
    
    .cookie-consent-btn {
        width: 100%;
        padding: 12px 20px;
    }
    
    .cookie-consent-settings {
        padding: 0 16px 16px;
    }
    
    .cookie-consent-trigger {
        bottom: 16px;
        left: 16px;
        font-size: 0.75rem;
        padding: 8px 12px;
    }
    
    .cookie-consent-category-header {
        flex-wrap: wrap;
    }
    
    .cookie-consent-always-on {
        margin-left: 30px;
    }
}

/* ═══════════════════════════════════════════════════════════════════════════
   ACCESSIBILITY
   ═══════════════════════════════════════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {
    .cookie-consent-banner,
    .cookie-consent-btn,
    .cookie-consent-trigger,
    .cookie-consent-policy-link {
        transition: none;
    }
    
    .cookie-consent-banner.is-visible {
        transform: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .cookie-consent-banner {
        border-top: 2px solid var(--cc-text);
    }
    
    .cookie-consent-btn {
        border-width: 3px;
    }
}
```

---

## 7. Testing Checklist

### 7.1 Functional Tests

| Test | Expected Result | Status |
|------|-----------------|--------|
| First visit shows banner | Banner appears at bottom | ⬜ |
| "Accept All" stores all:true | All categories enabled | ⬜ |
| "Essential Only" stores minimal | Only essential=true | ⬜ |
| Customize opens settings panel | Checkboxes visible | ⬜ |
| Save preferences stores correctly | Custom selection saved | ⬜ |
| Page refresh respects consent | Banner doesn't reappear | ⬜ |
| 🍪 button reopens banner | Can change preferences | ⬜ |
| Escape key closes banner (if consented) | Banner hides | ⬜ |
| localStorage disabled fallback | Cookie backup works | ⬜ |

### 7.2 Design Tests

| Test | Expected Result | Status |
|------|-----------------|--------|
| Desktop layout correct | Horizontal buttons | ⬜ |
| Tablet layout correct | Responsive width | ⬜ |
| Mobile layout correct | Stacked buttons | ⬜ |
| Font matches site (Lato) | Consistent typography | ⬜ |
| Colors match palette | Grafito, Violeta Polvo | ⬜ |
| Animations smooth | Fade in/out | ⬜ |
| Button hover states | Colors change | ⬜ |

### 7.3 Accessibility Tests

| Test | Expected Result | Status |
|------|-----------------|--------|
| Keyboard navigation | Tab through buttons | ⬜ |
| Focus visible | Outline on focused elements | ⬜ |
| Screen reader | Reads title and description | ⬜ |
| ARIA attributes | role="dialog", aria-labelledby | ⬜ |
| Reduced motion | Respects prefers-reduced-motion | ⬜ |

### 7.4 Compliance Tests

| Test | Expected Result | Status |
|------|-----------------|--------|
| No tracking before consent | Scripts blocked | ⬜ |
| Reject option equally prominent | Same size/style as Accept | ⬜ |
| Consent timestamp stored | Appears in localStorage | ⬜ |
| Version tracking | Updates when policy changes | ⬜ |
| Cookie policy linked | Link works | ⬜ |

---

## 8. Maintenance Notes

### 8.1 Adding New Tracking Scripts

When adding Google Analytics, Facebook Pixel, or other tracking:

1. **Edit `cookie-consent.js`**
2. Find the `loadConditionalScripts()` function
3. Uncomment the relevant script block
4. Replace placeholder IDs with your actual IDs

Example for Google Analytics:
```javascript
if (preferences.analytics) {
    var script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX'; // Your ID
    document.head.appendChild(script);
    
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX'); // Your ID
}
```

### 8.2 Updating Consent Version

When you change your cookie policy significantly:

1. **Edit `cookie-consent.php`**
2. Find `'consentVersion' => '1.0'`
3. Update to `'1.1'` or appropriate version
4. All users will be asked for consent again

### 8.3 Adding Cookie Settings to Footer

Use the shortcode in any post, page, or widget:

```
[cookie_settings text="Manage Cookies"]
```

Or add a link in WordPress footer that triggers the banner:

```html
<a href="#" onclick="window.cookieConsentOpen(); return false;">Cookie Settings</a>
```

### 8.4 Server-Side Consent Check (Optional)

The consent is also stored as a cookie for potential server-side checks:

```php
if (isset($_COOKIE['carolina_cookie_consent'])) {
    $consent = rawurldecode($_COOKIE['carolina_cookie_consent']);
    // Parse: "v=1.0|e=1|f=0|a=1|m=0"
    // e=essential, f=functional, a=analytics, m=marketing
}
```

---

## 📝 Notes

### What This Site Currently Uses
- **Essential cookies only**: WordPress session, WooCommerce cart
- **No analytics**: Not tracking visitors (add later if needed)
- **ConvertKit**: Newsletter signup (may set cookies when embedded)

### Recommendations
1. Create the `/cookie-policy/` page before implementing
2. Test in Private/Incognito mode to verify fresh consent flow
3. After 1 year, consider reviewing consent version to re-confirm compliance

---

## 🚀 Ready to Implement

Once this plan is approved, the implementation involves:

1. Creating the `inc/cookie-consent/` folder
2. Creating the three files (PHP, JS, CSS) with the code above
3. Adding the `require_once` to `functions.php`
4. Creating a Cookie Policy page in WordPress
5. Testing across devices

**Estimated implementation time**: 15-20 minutes (automated by agent)

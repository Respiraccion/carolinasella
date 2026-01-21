/**
 * Carolina Sella - Cookie Consent Manager
 * 
 * GDPR-compliant cookie consent handling.
 * No external dependencies.
 * 
 * @version 1.0.0
 */

(function () {
    'use strict';

    // Configuration (passed from PHP via wp_localize_script)
    const config = window.cookieConsentConfig || {
        cookieName: 'carolina_cookie_consent',
        consentVersion: '1.0',
        expiryDays: 365,
        policyUrl: '/privacy-policy/#cookies'
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
            btn.addEventListener('click', function () {
                showBanner();
                // Small delay to ensure banner is visible, then expand settings
                setTimeout(toggleSettings, 100);
            });
        });

        // Close banner on Escape key
        document.addEventListener('keydown', function (e) {
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

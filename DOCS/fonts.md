# Font & Typography Audit

This document provides a comprehensive audit of all font styles, families, and typography settings used across the Carolina Sella codebase. It lists every file where fonts are defined, registered, or applied.

## 1. Overview of Font Families
The following font families are actively used or registered in the system:

*   **Cronde** (Custom Font, Serif)
    *   **Usage**: Site Title (exclusively).
    *   **Source**: Local files in `fonts/` directory.
*   **Bodoni Moda** (Google Font, Serif)
    *   **Usage**: Specific headings, "Elegant" design tokens.
    *   **Source**: Google Fonts (Variable: 400..900 + Italic).
*   **Lato** (Google Font, Sans-Serif)
    *   **Usage**: Body text, Navigation, Buttons, UI elements (Cookie Banner, Forms).
    *   **Source**: Google Fonts (Weights: 100, 300, 400, 700, 900 + Italics).
*   **Cormorant Garamond** (Google Font, Serif)
    *   **Usage**: Shop Category Filter stack fallback.
    *   **Source**: Google Fonts (Weights: 300, 400 + Italics).
*   **EB Garamond** (Google Font, Serif)
    *   **Usage**: Block Quotes (Pull quotes).
    *   **Source**: Google Fonts (Variable: 400..800 + Italics).

---

## 2. Detailed Audit by File

### 📄 `wp-content/themes/twentytwentyfive-child/functions.php`
*Responsible for enforcing Google Fonts loading strategy (Preload/Swap).*

| Line(s) | Functionality | Description |
| :--- | :--- | :--- |
| **25-26** | **Preload** | Preloads `Bodoni Moda` to prevent Flash of Unstyled Text (FOUT). |
| **29-31** | **Enqueue** | Loads all Google Fonts: `Bodoni Moda`, `Cormorant Garamond`, `EB Garamond`, `Lato`. <br> Uses `display=block` to ensure text is invisible until font loads (avoiding layout shifts).  |
| **168, 173, etc.** | **Admin Page** | The custom "CSS Guide" admin page rendering function uses inline styles/defaults (System Sans-Serif) for the WP Admin dashboard area. |

---

### 📄 `wp-content/themes/twentytwentyfive-child/theme.json`
*Defines the global font palette/tokens and default block styles for Gutenberg.*

| Line(s) | Section | Setting | Value |
| :--- | :--- | :--- | :--- |
| **50-71** | `settings.typography.fontFamilies` | **Font Registration** | Registers slugs: `bodoni-moda`, `cormorant-garamond`, `lato`, `eb-garamond`. |
| **96-100** | `styles.typography` | **Global Default** | `fontFamily: "var(--wp--preset--font-family--lato)"` <br> `fontSize: "1.125rem"` <br> `lineHeight: "1.8"` |
| **111-128** | `styles.elements.h1-h3` | **Headings** | Sets sizes using `clamp()` logic and weights (300/400). |
| **150-155** | `styles.elements.button` | **Buttons** | `fontFamily: "var(--wp--preset--font-family--lato)"` <br> `fontSize: "0.9rem"` <br> `letterSpacing: "0.15em"` <br> `textTransform: "uppercase"` |
| **172-178** | `styles.blocks.core/site-title` | **Site Title** | *Note: This definition (Bodoni Moda) is overridden by `style.css` to use Cronde.* |
| **204-207** | `styles.blocks.core/quote` | **Quote Block** | `fontFamily: "var(--wp--preset--font-family--eb-garamond)"` <br> `fontStyle: "italic"` <br> `fontSize: "1.25rem"` |
| **214-219** | `styles.blocks.core/navigation` | **Navigation** | `fontFamily: "var(--wp--preset--font-family--lato)"` <br> `fontSize: "0.875rem"` <br> `letterSpacing: "0.15em"` |

---

### 📄 `wp-content/themes/twentytwentyfive-child/style.css`
*Main stylesheet. Contains custom font imports, overrides, and responsive rules.*

| Line(s) | Selector | Property | Value | Context |
| :--- | :--- | :--- | :--- | :--- |
| **34-42** | `@font-face` | **Definition** | **Cronde** (Regular) | Custom font import (local). |
| **44-52** | `@font-face` | **Definition** | **Cronde** (Italic) | Custom font import (local). |
| **56-58** | `.has-bodoni-moda-font-family` | `font-family` | `'Bodoni Moda', serif !important` | Helper utility to force font. |
| **89** | `.boton1` variants | `font-weight` | `500` | Inherits family (Lato). `0.9rem`, `0.15em` spacing. |
| **282** | `.elegant-site-title a`, `.wp-block-site-title a` | `font-family` | `'Cronde', serif !important` | **Overrides theme.json** Site Title. |
| **284** | (Site Title) | `letter-spacing` | `0.15em !important` | |
| **286** | (Site Title) | `font-size` | `clamp(1.7rem, 3.5vw, 2.2rem) !important` | |
| **406** | `.wp-block-navigation__submenu-container`... | `font-size` | `1.1rem !important` | Submenu items. |
| **446** | `.is-menu-open` links | `font-size` | `1.3rem !important` | Mobile/Overlay Menu links. |
| **532** | `body` | `font-size` | `1.125rem` | Base body size. |
| **717-718** | `:root` (Vars) | `--mobile-base-font` | `17px`, `18px` | Responsive variable definitions. |
| **880-905** | `h1` - `h6` (Mobile) | `font-size` | Various `clamp()` values | Mobile specific heading sizes. |
| **1407-1413** | `.blog-post-card .wp-block-post-title` | `font-size` | `1.25rem`, `600` weight | Blog card titles. |

---

### 📄 `wp-content/themes/twentytwentyfive-child/assets/css/shop-filter.css`
*Styles for the Horizontal Category Filter on the Shop page.*

| Line(s) | Selector | Property | Value |
| :--- | :--- | :--- | :--- |
| **41** | `.shop-category-filter__link` | `font-family` | `'Lato', 'Cormorant Garamond', serif` |
| **42** | `.shop-category-filter__link` | `font-size` | `0.9rem` |
| **43** | `.shop-category-filter__link` | `font-weight` | `400` |
| **44** | `.shop-category-filter__link` | `letter-spacing` | `0.08em` |
| **45** | `.shop-category-filter__link` | `text-transform` | `uppercase` |
| **68** | `.shop-category-filter__link.is-active` | `font-weight` | `500` |
| **133** | (Tablet Media Query) | `font-size` | `0.8rem` |
| **158** | (Mobile Media Query) | `font-size` | `0.75rem` |

---

### 📄 `wp-content/themes/twentytwentyfive-child/inc/cookie-consent/cookie-consent.css`
*Styles for the GDPR Cookie Banner.*

| Line(s) | Selector | Property | Value |
| :--- | :--- | :--- | :--- |
| **31** | `:root` (`--cc-font`) | `font-family` | `'Lato', -apple-system, BlinkMacSystemFont, sans-serif` |
| **47** | `.cookie-consent-banner` | `font-family` | `var(--cc-font)` (Lato) |
| **81** | `.cookie-consent-title` | `font-family` | `var(--cc-font)` (Lato) |
| **82** | `.cookie-consent-title` | `font-size` | `1.125rem` (`600` weight) |
| **90** | `.cookie-consent-message` | `font-size` | `0.9375rem` |
| **118** | `.cookie-consent-btn` | `font-family` | `var(--cc-font)` (Lato) |
| **119** | `.cookie-consent-btn` | `font-size` | `0.875rem` (`500` weight) |
| **121** | `.cookie-consent-btn` | `letter-spacing` | `0.08em` |

---

### 📄 `wp-content/themes/twentytwentyfive-child/parts/footer.html`
*Newsletter section inline styles (Footer).*

| Line(s) | Selector | Property | Value |
| :--- | :--- | :--- | :--- |
| **10-91** | `<style>` block | | Contains CSS rules scoped to `.newsletter-box` |
| **30-31** | `.newsletter-box h2` | `font-weight`, `size` | `300`, `2rem` |
| **60** | `.newsletter-form input[type="email"]` | `font-family` | `var(--wp--preset--font-family--lato)` |
| **70-71** | `::placeholder` | `font-size`, `letter-spacing` | `0.8rem`, `0.1em` |
| **79** | `.newsletter-form button` | `font-family` | `var(--wp--preset--font-family--lato)` |
| **80-81** | `.newsletter-form button` | `font-size`, `letter-spacing` | `0.85rem`, `0.15em` |

---

### 📄 `wp-content/themes/twentytwentyfive-child/assets/css/contact-form.css`
*Styles for the detailed contact form on the `/contact` page.*

| Line(s) | Selector | Property | Value |
| :--- | :--- | :--- | :--- |
| **67-68** | `.carolina-contact-form label` | `font-size`, `weight` | `0.9rem`, `500` |
| **89** | `input`, `select`, `textarea` | `font-family` | `inherit` (Inherits Lato from global) |
| **88** | `input`, `select`, `textarea` | `font-size` | `1rem` |
| **153** | `input[type="file"]` | `font-size` | `0.95rem` |
| **176** | `::file-selector-button` | `font-weight`, `size` | `500`, `0.85rem` |
| **295-296** | `.submit-btn` | `font-size`, `weight` | `1rem`, `600` |

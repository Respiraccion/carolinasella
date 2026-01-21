# 🧠 Domain Memory & Learnings

This file is the **Persistent Memory** for Carolina Sella's project. Read this before starting work and update it after finishing.

## 🟢 Successful Workflows

### 📁 Project Architecture
- **Standard**: WP Site Editor → `theme.json` → `style.css` (last resort).
- **Docs**: Centralized in `AGENTS.md`. Key guides: `DECISION-TREE.md` and `03-WORDPRESS-GUIDE.md`.
- **Theme**: Twenty Twenty-Five (Child). **Do NOT migrate to Elementor.**

### 🛒 Shop & WooCommerce Implementation
- **Category Filter**: A custom horizontal filter bar is active on the shop page. 
  - **Files**: `inc/shop-category-filter.php`, `assets/css/shop-filter.css`, `assets/js/shop-filter.js`.
  - **Categories**: All | Prints | Decorative Objects | Giclée | Originals.
  - **Logic**: Linked via `functions.php`. It uses standard WooCommerce category links but styled as pills.
- **Shop Page Hierarchy**: 
  - The file `templates/page-shop.html` exists in the child theme but WooCommerce typically uses its own archive logic for `/shop`.
  - **Editing**: Work on the "Shop" page in WordPress (ID 10) for content, but the filter bar is injected via PHP hook `woocommerce_before_shop_loop`.

### 📰 Newsletter & Integrations
- **ConvertKit**: Integration logic in `inc/convertkit-newsletter.php`.
- **SendPulse**: Integration logic (check `functions.php` for include).
- **Enqueued**: Both are loaded via `functions.php`.

### 📰 Blog Grid (3-Column Layout)
- **Structure**: 3-column responsive grid (3→2→1) using card-based design.
- **Implementation**: Handled by updating the page content (Page ID 11) with Gutenberg blocks for maximum editor control.
- **Classes**: `.blog-grid`, `.blog-card`, `.blog-card-image`, `.blog-card-content`.

### 🎨 Design Tokens
- **Hero**: Reduced height (50vh), stacked layout ensuring text visibility.
- **Header**: Sticky, violet background (`#violeta-polvo`), Cart icon (32px) absolutely positioned to the right to prevent title overlap.
- **Typography**: Base font 1.125rem, side margins set via variables in `:root`.

---

## 🏗️ Core Rules (Avoid these mistakes)

### ⚠️ CSS & Spacing
- **NEVER use the "Nuclear Option"**: Avoid `* { margin: 0 !important }`. It breaks all WordPress Editor spacing controls.
- **Specificity**: Target specific selectors (e.g., `.has-violeta-polvo-background-color + .hero-section`) instead of global element resets.
- **Margins**: Let WordPress handle block spacing; only override when theme defaults create unwanted gaps between specific custom sections (like Header -> Hero).

### ⚙️ Template Resolution
- **Page Content vs Templates**: For the Blog/Shop pages, updating the **page content** in the editor is often more effective than modifying `.html` templates, as WordPress prioritizes the `page_for_posts` content for block themes.

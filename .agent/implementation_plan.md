# Implementation Plan - Mobile Menu Update

## Objective
Update the mobile menu to add "Ink & Ritual" as the first item, keeping the previous reordering and renaming.

## Changes
- **File**: `/var/www/carolinasella/wp-content/themes/twentytwentyfive-child/parts/header.html`
- **Action**: Added `Ink & Ritual` navigation link to the top of the mobile menu.
- **Details**:
    - Added `<!-- wp:navigation-link {"label":"Ink \u0026 Ritual","url":"/ink-and-ritual/","kind":"custom","isTopLevelLink":true} /-->` before "Art gallery".

## Verification
- Verified the `wp:navigation` block now starts with Ink & Ritual.
- Order is now: Ink & Ritual, Art Gallery, Oracle Cards, Shop, Contact, Blog.

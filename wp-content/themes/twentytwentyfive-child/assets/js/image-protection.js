
// Use capture phase to prevent events before they bubble
window.addEventListener('contextmenu', function (e) {
    // Check if target is an image, has background image, or is inside an image block/gallery
    if (e.target.tagName === 'IMG' ||
        (e.target.style && e.target.style.backgroundImage) ||
        e.target.closest('.wp-block-image') ||
        e.target.closest('.wp-block-gallery') ||
        e.target.closest('figure')) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
}, true); // Capture phase

window.addEventListener('dragstart', function (e) {
    if (e.target.tagName === 'IMG') {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
}, true); // Capture phase

// Prevent standard touch long-press behavior (often context menu on mobile)
window.addEventListener('touchstart', function (e) {
    if (e.target.tagName === 'IMG') {
        // We don't preventDefault here because it breaks scrolling
        // But CSS -webkit-touch-callout: none should handle the menu
    }
}, { passive: true });

// Prevent generic key combinations for save/print if focused on image (less critical)
window.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        // Prevent Ctrl+S / Cmd+S (Save Page) - this is aggressive but effective for "Save As"
        // e.preventDefault(); 
        // Commented out as it might be too annoying for users trying to save the PAGE
    }
}, true);

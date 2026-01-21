document.addEventListener('DOMContentLoaded', function () {
    let popupShown = false;
    const popupText = "Click on a picture to display full size";
    const displayDuration = 3500; // 3.5 seconds
    const scrollThreshold = 100; // px
    let popup = null;

    // Create popup immediately but keep it hidden
    function initPopup() {
        popup = document.createElement('div');
        popup.id = 'scroll-popup-notification';
        popup.innerHTML = popupText.replace("display", "display<br>"); // Optional line break for better mobile width
        // Actually, CSS width constraint handles wrapping better, keep text clean:
        popup.textContent = popupText;
        document.body.appendChild(popup);
    }

    // Initialize immediately
    initPopup();

    function handleScroll() {
        if (popupShown || !popup) return;

        const scrolled = window.scrollY;

        if (scrolled > scrollThreshold) {
            popupShown = true;

            // Force browser to acknowledge it's there before animating (optimization)
            requestAnimationFrame(() => {
                popup.classList.add('visible');
            });

            // Hide after duration
            setTimeout(() => {
                popup.classList.remove('visible');
                // Remove from DOM after transition finishes (optional, but keeps DOM clean)
                setTimeout(() => {
                    if (popup && popup.parentNode) {
                        popup.parentNode.removeChild(popup);
                    }
                }, 700); // 0.6s transition + buffer
            }, displayDuration);

            // Remove listener
            window.removeEventListener('scroll', handleScroll);
        }
    }

    // Initialize
    window.addEventListener('scroll', handleScroll, { passive: true }); // passive improves mobile scroll performance
});

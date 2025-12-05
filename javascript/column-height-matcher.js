/**
 * Column Height Matcher
 * Ensures both columns in the two-column layout match heights
 * Fallback for browsers that don't support flexbox properly
 */
(function() {
    'use strict';

    /**
     * Match heights of two-column layout
     */
    function matchColumnHeights() {
        var leftColumn = document.querySelector('.home-column.left .cbox-content');
        var rightColumn = document.querySelector('.home-column.right .cbox-content');

        if (!leftColumn || !rightColumn) return;

        // Reset heights to auto to get natural heights
        leftColumn.style.height = 'auto';
        rightColumn.style.height = 'auto';

        // Get natural heights
        var leftHeight = leftColumn.offsetHeight;
        var rightHeight = rightColumn.offsetHeight;

        // Set both to taller height
        var maxHeight = Math.max(leftHeight, rightHeight);
        leftColumn.style.minHeight = maxHeight + 'px';
        rightColumn.style.minHeight = maxHeight + 'px';
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        matchColumnHeights();
    }

    // Run on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Re-run on resize (if content reflows)
    window.addEventListener('resize', matchColumnHeights);
})();

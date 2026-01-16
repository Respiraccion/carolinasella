<?php
require('wp-load.php');

// Define new menu content
// Using dynamic links (no labels) wherever possible so they sync with Page Titles.
// Home needs a label because the page title is empty/Home.
$content = '<!-- wp:navigation-link {"label":"Home","type":"page","id":25,"url":"/","kind":"post-type"} /-->';

// Ink & Ritual (ID 7)
$content .= '<!-- wp:navigation-link {"type":"page","id":7,"kind":"post-type"} /-->';

// Art Gallery (ID 9) with Submenu Project Alpha(13), Project Beta(14)
$content .= '<!-- wp:navigation-submenu {"type":"page","id":9,"kind":"post-type"} -->';
$content .= '<!-- wp:navigation-link {"type":"page","id":13,"kind":"post-type"} /-->';
$content .= '<!-- wp:navigation-link {"type":"page","id":14,"kind":"post-type"} /-->';
$content .= '<!-- /wp:navigation-submenu -->';

// Oracle Cards (ID 36) with Submenu Bach(37)
$content .= '<!-- wp:navigation-submenu {"type":"page","id":36,"kind":"post-type"} -->';
$content .= '<!-- wp:navigation-link {"type":"page","id":37,"kind":"post-type"} /-->';
$content .= '<!-- /wp:navigation-submenu -->';

// Shop (ID 10)
$content .= '<!-- wp:navigation-link {"type":"page","id":10,"kind":"post-type"} /-->';

// Blog (ID 11)
$content .= '<!-- wp:navigation-link {"type":"page","id":11,"kind":"post-type"} /-->';

// Contacto (ID 12)
$content .= '<!-- wp:navigation-link {"type":"page","id":12,"kind":"post-type"} /-->';

// Update Navigation Post 5
$post_data = array(
    'ID' => 5,
    'post_content' => $content,
);
wp_update_post($post_data);

// Set "Tattoo Ritual" (8) to draft to hide it if logic falls back, and to imply it's unused.
$page_8 = array(
    'ID' => 8,
    'post_status' => 'draft'
);
wp_update_post($page_8);

echo "Menu and pages updated successfully.";

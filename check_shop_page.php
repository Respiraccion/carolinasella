<?php
require('wp-load.php');

$page_id = 10;
$page = get_post($page_id);

if ($page) {
    echo "Page ID $page_id exists.\n";
    echo "Title: " . $page->post_title . "\n";
    echo "Status: " . $page->post_status . "\n";
    
    if ($page->post_status !== 'publish') {
        echo "Publishing page...\n";
        wp_update_post(array(
            'ID' => $page_id,
            'post_status' => 'publish'
        ));
        echo "Page published.\n";
    }
} else {
    echo "Page ID $page_id NOT found.\n";
    // Try to find it by title
    $page_by_title = get_page_by_title('Shop');
    if ($page_by_title) {
         echo "Found 'Shop' page with ID: " . $page_by_title->ID . "\n";
    }
}

// Also check if the navigation post (ID 5) was actually updated
$nav_post = get_post(5);
if ($nav_post) {
    echo "\nNavigation Post Content:\n";
    echo $nav_post->post_content;
}



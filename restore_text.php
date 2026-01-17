
<?php
$conn = new mysqli('localhost', 'carolinasella', 'CarolinaSella2024!Db', 'carolinasella');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT post_content FROM wp_posts WHERE ID = 25";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $content = $row['post_content'];
    
    $updated = false;
    
    // 1. Restore Paragraph Text
    $text = "I guide transformation processes through art and energy.<br>Creating bridges between the visual and spiritual worlds, turning creativity into a sacred ritual.";
    // Match the style exactly as seen in the DB dump
    $search_p = 'style="color:#FFFFFF;font-size:2.2rem;font-style:italic;font-weight:100;line-height:1.4"></p>';
    
    if (strpos($content, $search_p) !== false) {
        $replace_p = str_replace('></p>', '>' . $text . '</p>', $search_p);
        $content = str_replace($search_p, $replace_p, $content);
        $updated = true;
        echo "Paragraph text restored.\n";
    }
    
    // 2. Restore Button Text
    $search_b = '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"></a></div>';
    $replace_b = '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/ink-and-ritual/">Explore Ink &amp; Ritual</a></div>';
    
    if (strpos($content, $search_b) !== false) {
        $content = str_replace($search_b, $replace_b, $content);
        $updated = true;
        echo "Button text restored.\n";
    }
    
    if ($updated) {
        $stmt = $conn->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = 25");
        $stmt->bind_param("s", $content);
        $stmt->execute();
        echo "Successfully updated homepage content in DB.\n";
    } else {
        echo "No changes needed or targets not found.\n";
    }

} else {
    echo "Post 25 not found\n";
}

$conn->close();
?>

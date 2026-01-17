
import mysql.connector

try:
    conn = mysql.connector.connect(
        user='carolinasella',
        password='CarolinaSella2024!Db',
        host='localhost',
        database='carolinasella'
    )
    cursor = conn.cursor()
    
    # Get current content
    cursor.execute("SELECT post_content FROM wp_posts WHERE ID = 25")
    row = cursor.fetchone()
    if not row:
        print("Post 25 not found")
        exit(1)
        
    content = row[0]
    
    # The text we want to restore
    restored_text = "I guide transformation processes through art and energy.<br>Creating bridges between the visual and spiritual worlds, turning creativity into a sacred ritual."
    
    # The specific empty paragraph we saw in the curl output
    # Note: we should match the style string loosely or exactly
    # exact style from curl: style="color:#FFFFFF;font-size:clamp(1.352rem, 1.352rem + ((1vw - 0.2rem) * 1.33), 2.2rem);font-style:italic;font-weight:100;line-height:1.4"
    
    search_str = 'style="color:#FFFFFF;font-size:clamp(1.352rem, 1.352rem + ((1vw - 0.2rem) * 1.33), 2.2rem);font-style:italic;font-weight:100;line-height:1.4"></p>'
    replace_str = 'style="color:#FFFFFF;font-size:clamp(1.352rem, 1.352rem + ((1vw - 0.2rem) * 1.33), 2.2rem);font-style:italic;font-weight:100;line-height:1.4">' + restored_text + '</p>'
    
    if search_str in content:
        new_content = content.replace(search_str, replace_str)
        
        # Check if actually changed
        if new_content != content:
            sql = "UPDATE wp_posts SET post_content = %s WHERE ID = 25"
            cursor.execute(sql, (new_content,))
            conn.commit()
            print("Successfully updated post 25")
        else:
            print("Replace logic failed (strings matched but no change?)")
    else:
        print("Search string not found EXACTLY. attempting looser match.")
        # Fallback: find empty paragraph with just the color and partial style?
        # Or print the paragraph to debug
        start_marker = 'wp-block-cover__inner-container'
        idx = content.find(start_marker)
        if idx != -1:
             snippet = content[idx:idx+500]
             print("Snippet around cover inner container:")
             print(snippet)
        else:
             print("Could not find cover inner container either.")

except mysql.connector.Error as err:
    print(f"Error: {err}")
finally:
    if conn.is_connected():
        cursor.close()
        conn.close()

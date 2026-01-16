# 🧠 Project Memory & Documentation Index

This file serves as the **Persistent Memory** and **Central Index** for the Carolina Sella project.

**⚠️ CRITICAL INSTRUCTION FOR AGENTS:**
Before starting any task, **READ `DOCS/00-Domain-memory.md`**.
After completing any task, **UPDATE `DOCS/00-Domain-memory.md`** with:
1.  **Learnings**: What worked? What didn't?
2.  **Struggles**: Any blockers or difficulties encountered? (Also update current state in `README.md` if changed).

---

## 📚 Documentation Index
- `DOCS/04-TODOS.xml`: **Active Task List** (Highest Priority)
- `DOCS/04-HUMAN.md`: Human tasks & feedback
- `DOCS/00-Domain-memory.md`: **Persistent Memory & Agent Learnings**

---

## 🛠️ Technology & Specs

### Setup
- **Platform**: WordPress (LocalWP)
- **Theme**: Twenty Twenty-Five Child (`twentytwentyfive-child`)
- **Block Styles**: Defined in `theme.json`

### WP-CLI Configuration
**IMPORTANT**: Always use WP-CLI for WordPress operations from the command line.

#### Database Connection
LocalWP uses a custom MySQL socket. The `wp-config.php` has been configured with:
```php
define( 'DB_HOST', 'localhost:/Users/santiagosella/Library/Application Support/Local/run/NFATIoKEq/mysql/mysqld.sock' );
```

#### Common WP-CLI Commands
Run from: `/Users/santiagosella/Local Sites/carolina-sella/app/public`

```bash
# List all pages
wp post list --post_type=page --fields=ID,post_title

# Update page content
wp post update <ID> --post_content='<gutenberg blocks>'

# Create new page
wp post create --post_type=page --post_title='Page Title' --post_status=publish

# Set front page
wp option update show_on_front page
wp option update page_on_front <ID>

# List options
wp option list --search=page_on_front
```

### Color Palette
- **Gris muy claro cálido**: `#F1F1EF` (Background)
- **Gris niebla**: `#D8D6D2` (Neutral/Secondary)
- **Grafito**: `#3A3A3A` (Text/Primary)
- **Azul humo**: `#5F6C73` (Highlights)
- **Violeta polvo**: `#A59BB3` (Accent)

### Site Structure (Pages)
The following pages have been created:
- About Me
- Tattoos
- Tattoo Ritual
- Art
    - Project Alpha
    - Project Beta
- Prints
- Blog
- Contact

### Status
- **Theme**: Active (`twentytwentyfive-child`)
- **Pages**: Created
- **Colors**: Applied in `theme.json`

---

## 🧠 Memory Access
> **STOP**: Before starting, read `DOCS/00-Domain-memory.md` to avoid past mistakes.


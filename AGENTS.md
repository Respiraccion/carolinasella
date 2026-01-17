# Carolina Sella - Artist Profile & Ecommerce (CURRENT WORKFLOW)

## Proyecto
Plataforma de e-commerce y portfolio para la artista Carolina Sella. El sitio exhibirá perfil de artista, pinturas, tatuajes y otros trabajos artísticos.

## 🛠️ Technology & Specs
- **Platform**: WordPress (Local/Live)
- **Theme**: Twenty Twenty-Five Child (`twentytwentyfive-child`)
- **Block Styles**: Defined in `theme.json`
- **Domain**: [carolinasella.com](https://carolinasella.com)
- **Server IP**: 157.180.70.21

### Site Structure (Pages)
- **Home** (Front Page)
- **Shop** (Main Store)
    - Cart
    - Checkout
    - My Account
- **Prints** (Printful Integrated)
- **Art gallery**
    - Project Alpha
    - Project Beta
- **Tattoos**
    - Ink & ritual
    - Artistic Tattoos
- **Oracle Cards**
    - Bach Flowers Oracle
- **Blog**
- **Contacto**

### E-Commerce Status
- **Platform**: WooCommerce
- **Products**: Print-on-demand via **Printful** (Example: 'Poster' ID 145)
- **Payments**: Stripe (Pending human config)

## ⚠️ NO GIT YET
**We are NOT using Git/Github yet.** Do not run git commands. Changes are local to the server.

## 🧠 PERSISTENT MEMORY PROTOCOL (CRITICAL)
Since we don't have Git history, we rely on **Explicit Documentation Memory**.

1.  **BEFORE STARTING**:
    -   Read `AGENTS.md` (this file).
    -   Read `DOCS/00-Domain-memory.md` for previous learnings.
    -   Check `DOCS/01-TODOS.xml` for active tasks.

2.  **DURING WORK**:
    -   **🛑 RECHECK PROTOCOL**: Follow the Anti-Hallucination rules below.
    -   If you discover a new trick, fix, or blocker, **NOTE IT**.
    -   Be conversational with the user, ask questions.
    -   **ROOT FIXES ONLY**: Don't make patches; fix the source.
    -   **CLEAN UP**: Always delete temporary codes after use.
    -   **USEFUL, NOT RECKLESS**: Be helpful but avoid "super-proactivity" that leads to assumptions or hallucinations.

3.  **AFTER FINISHING**:
    -   **Update `DOCS/00-Domain-memory.md`**:
        -   Add new learnings / struggles.
        -   Document what was done.
    -   **Update `DOCS/01-TODOS.xml`**: Mark completed tasks.

---

## 📚 Documentation Index
- `DOCS/01-TODOS.xml`: **Active Task List** (Highest Priority)
- `DOCS/02-HUMAN.md`: Human tasks, styling instructions & feedback
- `DOCS/00-Domain-memory.md`: **Persistent Memory & Agent Learnings**
- `DOCS/ssh_windows_setup.txt`: Instructions for SSH access from Windows

---

## 📏 Documentation Rules
- **Límites de Tamaño**:
    - Markdown (.md): Max 500 líneas.
    - XML (.xml): Max 800 líneas.
    - Código: Max 300 líneas.
- **📁 Location**: All docs in `DOCS/`.
- **🔄 Updates**: When modifying a file, update all relevant indices.

---

## 🛑 ANTI-HALLUCINATION & VERIFICATION

To ensure the stability of the project, all agents must follow these rules:

### 1. Never Assume
- Do **NOT** assume a file exists or contains specific code based on memory. Always use `view_file`.
- Do **NOT** assume the environment (e.g., if `wp` command works). Always verify paths/binaries first.

### 2. Path & Command Precision
- Use **absolute paths** only.
- If a command fails, investigate why before trying a "blind fix".

### 3. Iteration & Recheck Process (Mandatory)
After completing any task, you must:
1.  **Local Verification**: Run a command to verify the change (e.g., `cat` the file, check `ls`, or run a test script).
2.  **Consistency Check**: Verify that the change doesn't conflict with the **Site Structure** defined above.
3.  **Traceability**: State clearly in your report: "I have verified this by [Action]".

### 4. Controlled Proactivity
- Be useful, but do **not** perform sweeping changes (like deleting folders or bulk-updating database records) unless explicitly requested or verified as safe via a dry run.
- If in doubt about the user's intent: **ASK**.

---

## 🎨 Styling Strategy (Priority)
1. **WordPress Site Editor**: Use the Site Editor (Global Styles) whenever possible. This allows the user to maintain and change styles easily.
2. **theme.json**: Use for defining the design system (colors, typography, spacing).
3. **style.css**: ONLY for specific overrides or complex CSS that the Site Editor cannot handle. 
**Avoid hardcoding styles if they can be managed in the WordPress UI.**
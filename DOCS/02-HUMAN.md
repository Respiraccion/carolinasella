# 02 - HUMAN (Tasks & Feedback)

This file tracks tasks that require human action and provides guidance on how to manage the site.

---

## 🎨 STYLING PRIORITY (Read carefully)

The site uses the **Twenty Twenty-Five** block theme. To ensure you maintain control over the design, we follow this priority:

1.  **Site Editor (Recommended)**:
    -   Go to **Appearance** → **Editor** → **Styles**.
    -   Use this for colors, typography, and general layout.
    -   *Why?* This keeps the site easy for you to edit without touching code.
2.  **Child Theme (`style.css`)**:
    -   We only use this for things the Editor can't do (like complex animations or very specific mobile fixes).
    -   **Current Status**: Some CSS has been added to hide the user icon and fix the hero section.

---

## 📋 PENDING HUMAN TASKS

### 1. SSH Access (Windows)
- Follow the instructions in `DOCS/ssh_windows_setup.txt` to set up your direct connection to the server.

### 2. Verify Hero Section (Homepage)
- Please check the homepage. I've performed deep fixes to make the Hero text and image visible.
- **Question for User**: Does the hero text look correctly aligned to you now, or would you like me to adjust it further via the Site Editor?

### 4. Shop Architecture Verification
- I have updated the site structure in `AGENTS.md` to match the actual pages on your server (Cart, Checkout, Oracle Cards, etc.).
- **Action**: Please verify if the products you expect (like the "Poster") are appearing in the Shop page correctly.
- **Action**: Ensure the "My Account" page is hidden from the public if you don't want customers to access it yet (as we discussed in previous sessions).

---

## 🛠️ MANUAL INSTRUCTIONS

### Step 1: Remove "Account" Block from Header
1. Go to **Appearance** → **Editor**.
2. Click **Patterns** → **Header**.
3. Look for a small "User" or "Account" block on the right side.
4. Click the three dots and select **Delete**.
5. **Save** everything.

---

## 💬 QUESTIONS FOR YOU (Carolina/User)
- **Styling**: Do you prefer doing most styling yourself through the WordPress Editor, or do you want me to handle specific CSS files directly? I recommend the Editor so you are not dependent on an agent for small changes like colors or fonts.
- **Plugins**: Are there any new plugins you've installed that I should be aware of for the setup?

# GO Day Mega Menu — WordPress Plugin

Adds a GO Day mega menu dropdown to any WordPress nav menu item. Works with Elementor Nav Menu, standard WordPress menus, and any theme that uses `wp_nav_menu()`.

## Installation

1. Download or clone this repository
2. Upload the `goday-mega-menu` folder to `/wp-content/plugins/`
3. Activate the plugin in **Plugins > Installed Plugins**

No other dependencies required (no Elementor Pro, no page builder requirement).

## Setup

1. Go to **Appearance > Menus** in WordPress admin
2. Add a **Custom Link** with:
   - **URL:** `#goday-mega-menu`
   - **Link Text:** `Go Day` (or whatever label you want)
3. Place it wherever you want in your menu (e.g., replace the "Home" item)
4. Save the menu

That's it. The plugin automatically detects that menu item and attaches the mega menu dropdown to it.

## How It Works

- The plugin uses WordPress's `wp_nav_menu_objects` filter to find any menu item with URL `#goday-mega-menu`
- It marks that `<li>` with a CSS class so the frontend JS can find it
- The mega menu panel HTML is injected into the page footer (hidden by default)
- On hover or click of the menu item, the panel slides open with staggered animations

## What's Included

- **3-column mega menu** with animated unfurl:
  - "Come Join Us" image card with GO Day info
  - "Leaders & Pastors" image card (coming soon)
  - Quick links: Set Your Calendar, Invite a Friend, Who We Are, News & Stories, Resources
- **Calendar download** (.ics file) when clicking "Set Your Calendar"
- **Hover + click** interaction with 200ms close delay
- **Keyboard accessible** — Escape key closes the menu
- **Scroll-aware** — menu closes on page scroll
- **Click-away** overlay to close when clicking outside

## Customization

To change content (images, links, text), edit the `wp_footer` output in `goday-mega-menu.php`. Images are in `assets/images/`.

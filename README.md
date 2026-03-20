# GO Day Mega Menu — WordPress Plugin

**Version:** 1.4.3
**Author:** PERC Engage
**Support:** [percengage.com](https://percengage.com)

Adds a GO Day mega menu dropdown to any WordPress nav menu item. Works with Elementor Nav Menu, standard WordPress menus, and any theme that uses `wp_nav_menu()`.

## Installation

1. Download the versioned `.zip` file (e.g., `goday-mega-menu-1.0.0.zip`)
2. In WordPress admin, go to **Plugins > Add New > Upload Plugin**
3. Upload the zip and click **Install Now**
4. Activate the plugin in **Plugins > Installed Plugins**

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
- **Calendar provider dropdown** — "Set Your Calendar" opens a dropdown with options for Google Calendar, Apple Calendar, Outlook, and Yahoo Calendar
- **Hover + click** interaction with 200ms close delay
- **Keyboard accessible** — Escape key closes the menu
- **Scroll-aware** — menu closes on page scroll
- **Click-away** overlay to close when clicking outside

## Customization

To change content (images, links, text), edit the `wp_footer` output in `goday-mega-menu.php`. Images are in `assets/images/`.

## Support

This plugin is built and maintained by **PERC Engage**. For questions, support, or customization requests, visit [percengage.com](https://percengage.com).

---

## Changelog

### 1.4.3
- Restore exact v1.4.0 mobile logic that worked on Chrome emulator

### 1.4.2
- Fix Safari mobile — use setTimeout to defer navigation outside event handler context

### 1.4.1
- Fix Safari mobile — don't call preventDefault, instead change href and let browser navigate natively

### 1.4.0
- Rewrite mobile detection — match by "GO Day" text content instead of href to catch Elementor's re-rendered mobile menu elements
- Add touchend capture handler as Safari backup

### 1.3.9
- Restore click capture handler for mobile as fallback alongside href rewrite

### 1.3.8
- Fix Safari mobile — rewrite link href to goday.world on mobile instead of intercepting events

### 1.3.7
- Fix Safari mobile — use location.href instead of window.open to avoid popup blocker

### 1.3.6
- Fix mobile tap — use touchstart capture to redirect to goday.world before Elementor intercepts

### 1.3.5
- Same-site links (Who We Are, News & Stories, Resources) open in same window
- External links (goday.world) continue to open in new tab

### 1.3.4
- Update "Invite a Friend" link to goday.world/#invite to trigger invite form popup

### 1.3.3
- Fix hover flicker — debounce open, only close when mouse leaves the panel (not the trigger)

### 1.3.2
- Fix hover flicker — check relatedTarget to prevent animation loop when mouse moves between child elements

### 1.3.1
- Fix hover: use document-level mouseover/mouseout so hover works even after Elementor re-renders menu DOM

### 1.3.0
- Mobile: clicking Go Day in hamburger menu navigates directly to goday.world
- Desktop: mega menu opens on hover (rollover) with click as backup toggle

### 1.2.1
- Link "Come Join Us" card and info section to goday.world
- Link "GO Day" in the bottom snippet text to goday.world

### 1.2.0
- Rewrite JS to use capture-phase click handler — intercepts clicks before Elementor's anchor handling
- Remove Elementor's e-anchor class from trigger to prevent scroll behavior
- Increase retry attempts for late-rendering Elementor widgets

### 1.1.2
- Add debug console logging to diagnose Elementor integration

### 1.1.1
- Fix Elementor timing issue — retry initialization up to 6 seconds to wait for Elementor widgets to render

### 1.1.0
- Add Elementor Menu widget compatibility — plugin now detects `#goday-mega-menu` links via JavaScript fallback when the WordPress menu filter doesn't apply

### 1.0.0
- Initial release
- 3-column mega menu with animated unfurl
- Calendar provider dropdown (Google, Apple, Outlook, Yahoo)
- Hover + click interaction with close delay
- Keyboard and scroll-aware accessibility
- WordPress menu hook integration — no Elementor dependency

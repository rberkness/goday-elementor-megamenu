# GO Day Mega Menu — Elementor Widget Plugin

An Elementor widget that adds the GO Day mega menu dropdown to your WordPress site header. Matches the design from [goday.world](https://goday.world).

## Installation

1. Download or clone this repository
2. Upload the `goday-mega-menu` folder to `/wp-content/plugins/`
3. Activate the plugin in **Plugins > Installed Plugins**
4. Requires **Elementor** to be installed and active

## Usage

1. Open your **Header template** in Elementor (via Theme Builder or your theme's header editing)
2. Search for **"GO Day Mega Menu"** in the Elementor widget panel (under the "GO Day" category)
3. Drag the widget into your header, alongside or replacing your existing navigation
4. Save and preview — hover over the trigger to see the mega menu unfurl

## What's Included

- **3-column mega menu** with animated unfurl:
  - "Come Join Us" image card
  - "Leaders & Pastors" image card (coming soon)
  - Quick links: Set Your Calendar, Invite a Friend, Who We Are, News & Stories, Resources
- **Calendar download** (.ics file) when clicking "Set Your Calendar"
- **Hover + click** interaction with smooth CSS animations
- **Keyboard accessible** — Escape key closes the menu
- **Scroll-aware** — menu closes on page scroll

## Customization

The trigger label text and color can be configured in the Elementor editor sidebar. To change content (images, links, text), edit `widgets/class-goday-mega-menu-widget.php`.

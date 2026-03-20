# GO Day Mega Menu — Development Guidelines

## Versioning & Release Workflow

- **Zip files must include the version number** in the filename: `goday-mega-menu-X.X.X.zip`
- **Place zip files in the project root**, not the Desktop or any other location
- When a version bump occurs:
  1. Update the version in the plugin header in `goday-mega-menu.php` (`Version:` and `GODAY_MEGA_MENU_VERSION`)
  2. Add a new entry to the Changelog section in `README.md`
  3. Build the zip with the new version number in the filename
- **Do not commit zip files to git** — they are local build artifacts

## Plugin Info

- **Author:** PERC Engage
- **Support URL:** https://percengage.com

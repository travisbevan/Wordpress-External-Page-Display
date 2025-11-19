# External Page Display

A WordPress plugin that broadcasts page content to external websites with full WordPress/Gutenberg styling support.

![Version](https://img.shields.io/badge/version-1.5-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)
![License](https://img.shields.io/badge/license-GPL--2.0-green.svg)

## 🎉 What's New in v1.5

- ✅ **Full WordPress Block Support** - Columns, buttons, and all Gutenberg blocks work perfectly on external sites
- ✅ **External JavaScript File** - Future updates work automatically without changing embed codes
- ✅ **Fixed Links** - All links properly open in new tabs
- ✅ **CSS Variable Support** - WordPress preset spacing and styles work correctly
- ✅ **Better Error Handling** - Clear loading states and error messages

**IMPORTANT:** v1.5 requires a ONE-TIME embed code update for full styling support. See [UPDATE-TO-v1.5.md](UPDATE-TO-v1.5.md) for details.

## Features

- ✅ Simple checkbox to enable page broadcasting
- ✅ Automatically generated embed code
- ✅ **Full WordPress/Gutenberg block styling** (NEW in v1.5)
- ✅ **Columns, buttons, images display perfectly** (NEW in v1.5)
- ✅ Responsive design that works everywhere
- ✅ Includes featured images automatically
- ✅ No iframe limitations
- ✅ REST API for secure content delivery
- ✅ **Auto-updating - change plugin without changing embeds** (NEW in v1.5)

## Installation

### Via WordPress Admin

1. Download the latest release (v1.5) as a ZIP file
2. Go to **Plugins → Add New** in your WordPress admin
3. Click **Upload Plugin** and choose the ZIP file
4. Click **Install Now** and then **Activate**

### Manual Installation

1. Download or clone this repository
2. Upload the `magazine-page-display` folder to `/wp-content/plugins/`
3. Activate the plugin through the **Plugins** menu in WordPress

### From GitHub

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/yourusername/magazine-page-display.git
```

Then activate the plugin in WordPress admin.

## Usage

### Setting Up a Broadcast Page

1. Edit any WordPress page
2. Look for the **"Broadcast Page"** meta box in the sidebar
3. Check **"Yes, broadcast this page"**
4. Click **Update** to save the page
5. Copy the generated embed code from the meta box

### Embedding on External Sites

Paste the embed code into any HTML page:

```html
<!-- Magazine Page Display Embed -->
<div id="magazine-broadcast-content"></div>
<script src="https://yoursite.com/wp-content/plugins/magazine-page-display/embed.js?page=123&api=https%3A%2F%2Fyoursite.com%2Fwp-json%2Fmagazine%2Fv1%2Fbroadcast%2F&v=1.5"></script>
<!-- End Magazine Page Display Embed -->
```

The content will automatically load with full WordPress styling!

## How It Works

1. **Enable Broadcasting**: Check the broadcast option on any WordPress page
2. **Generate Code**: Plugin creates embed code with external JavaScript reference
3. **REST API**: Content is served via WordPress REST API endpoint
4. **JavaScript Loads**: External `embed.js` file fetches and displays content
5. **Styling Applied**: Full WordPress/Gutenberg CSS is included automatically
6. **Dynamic Display**: Content appears with perfect formatting

## Supported WordPress Blocks

v1.5 includes full styling support for:

- ✅ **Columns** - Multi-column layouts work perfectly
- ✅ **Buttons** - Styled buttons with hover effects
- ✅ **Images** - Responsive images with alignment
- ✅ **Headings** - All heading levels with proper styling
- ✅ **Groups** - Block groups with flexbox layout
- ✅ **Paragraphs** - Proper text formatting
- ✅ **Links** - Open in new tabs automatically
- ✅ And more!

## REST API Endpoint

The plugin registers a public REST API endpoint:

```
GET /wp-json/magazine/v1/broadcast/{page_id}
```

**Response:**
```json
{
  "title": "Page Title",
  "content": "<div>Full HTML content with blocks</div>",
  "feature_image": "https://example.com/image.jpg",
  "version": "1.5"
}
```

## Customization

### Override Styles

All content is wrapped in `#magazine-broadcast-content`. Add custom CSS:

```css
#magazine-broadcast-content {
    max-width: 900px;
    /* Your custom styles */
}

#magazine-broadcast-content .wp-block-button__link {
    background-color: #your-color;
}
```

### Modify Behavior

The `embed.js` file can be customized for your specific needs while maintaining automatic updates for all embeds.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- REST API enabled (enabled by default)
- Modern web browser for embeds

## Security

- ✅ Nonce verification for admin actions
- ✅ Capability checks for saving settings
- ✅ Input validation and sanitization
- ✅ Output escaping
- ✅ Only published pages can be broadcast
- ✅ Broadcast permission must be explicitly enabled

## Upgrading from v1.4

See [UPDATE-TO-v1.5.md](UPDATE-TO-v1.5.md) for complete upgrade instructions.

**Quick version:**
1. Update plugin files to v1.5
2. Get new embed code from WordPress admin
3. Replace old embed codes on external sites
4. Done! Future updates work automatically

## Frequently Asked Questions

**Q: Do I need to update embed codes after updating the plugin?**  
A: After the one-time update to v1.5, NO! Future updates to `embed.js` apply automatically.

**Q: Will WordPress blocks work on non-WordPress sites?**  
A: Yes! v1.5 includes all necessary WordPress/Gutenberg CSS in the embed.

**Q: Do columns and buttons work on external sites?**  
A: Yes! This was the main fix in v1.5 - full block styling support.

**Q: Can I customize the appearance?**  
A: Yes, use `#magazine-broadcast-content` to target and override styles.

**Q: What happens if I disable broadcasting?**  
A: The embed shows an error message and stops displaying content.

**Q: Do links open in new tabs?**  
A: Yes, v1.5 automatically adds `target="_blank"` to all links.

## Troubleshooting

### Content Not Loading
1. Verify page is **Published** (not Draft)
2. Check **broadcast checkbox is checked**
3. Test API URL directly: `https://yoursite.com/wp-json/magazine/v1/broadcast/[PAGE_ID]`
4. Check browser console for errors (F12)

### Columns/Buttons Not Styled
1. Make sure you're using v1.5 embed code
2. Check that `embed.js` is loading (Network tab in dev tools)
3. Clear browser cache

### Links Not Opening in New Tabs
1. Verify you're using v1.5
2. Re-save the broadcast page in WordPress
3. Clear any caching

## Support

For bugs, features, or questions:
- Check [UPDATE-TO-v1.5.md](UPDATE-TO-v1.5.md) for upgrade info
- Review [TESTING.md](TESTING.md) for troubleshooting
- Open an issue on [GitHub](https://github.com/yourusername/magazine-page-display/issues)

## Changelog

### Version 1.5 (Current)
- Added external `embed.js` file for auto-updating
- Included complete WordPress/Gutenberg block styles
- Fixed link targets (open in new tabs)
- Added CSS variable support
- Improved error handling and loading states
- Better responsive design

### Version 1.4
- Fixed CSS selector mismatches
- Added security measures
- Dynamic API URLs

### Version 1.3
- Initial release

## License

This plugin is licensed under GPL v2 or later.

```
Magazine Page Display
Copyright (C) 2024

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## Credits

Developed for Matrix Group Inc.

---

**Made with ❤️ for WordPress**

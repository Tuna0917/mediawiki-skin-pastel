# Pastel MediaWiki Skin

A modern, beautiful MediaWiki skin featuring soft pastel colors inspired by contemporary design trends.

## Features

- 🎨 Soft pastel color palette (sky blue, mint green, soft pink)
- 📱 Fully responsive design
- 🎯 Modern, clean interface
- ⚡ Smooth animations and transitions
- 🔍 Enhanced search functionality
- 📚 Table of contents auto-generation
- 💻 Code block copy buttons
- 🌐 Internationalization support (English & Korean)

## Installation

1. Download or clone this repository to your MediaWiki `skins/` directory:
   ```bash
   cd /path/to/mediawiki/skins/
   git clone https://github.com/yourusername/mediawiki-skin-pastel.git Pastel
   ```

2. Add the following line to your `LocalSettings.php`:
   ```php
   wfLoadSkin( 'Pastel' );
   ```

3. Set Pastel as the default skin (optional):
   ```php
   $wgDefaultSkin = 'pastel';
   ```

4. Navigate to Special:Preferences in your wiki to select the skin for your user account.

## Requirements

- MediaWiki 1.39.0 or later
- PHP 7.4 or later

## Configuration

The skin works out of the box, but you can customize colors by editing:
- `resources/styles/variables.css` - Color palette and design tokens

## File Structure

```
Pastel/
├── includes/
│   └── SkinPastel.php          # Main skin class
├── resources/
│   ├── styles/
│   │   ├── variables.css       # Color variables and design tokens
│   │   ├── layout.css          # Layout structure
│   │   ├── header.css          # Header styles
│   │   ├── sidebar.css         # Sidebar navigation
│   │   ├── content.css         # Article content
│   │   └── footer.css          # Footer styles
│   └── js/
│       └── main.js             # JavaScript enhancements
├── i18n/
│   ├── en.json                 # English translations
│   └── ko.json                 # Korean translations
├── skin.json                   # Skin metadata
└── README.md                   # This file
```

## Customization

### Changing Colors

Edit `resources/styles/variables.css` to customize the color palette:

```css
:root {
	--color-primary: #a8d8ea;      /* Main brand color */
	--color-secondary: #b8e9d4;    /* Secondary accent */
	--color-accent: #ffc9d4;       /* Highlight color */
	/* ... more colors */
}
```

### Modifying Layout

The layout can be adjusted in `resources/styles/layout.css`:

```css
:root {
	--header-height: 4rem;         /* Header height */
	--sidebar-width: 16rem;        /* Sidebar width */
	--max-content-width: 1200px;   /* Maximum content width */
}
```

## Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Credits

Inspired by modern design trends and pastel aesthetics.

## License

GPL-2.0-or-later

## Support

For issues and feature requests, please visit the issue tracker.

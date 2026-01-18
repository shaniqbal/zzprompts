---
description: Repository Information Overview
alwaysApply: true
---

# zzprompts WordPress Theme Information

## Summary
zzprompts is a high-performance, standalone WordPress theme specifically designed for AI prompt libraries and collections. It features custom post types, multiple taxonomies, copy-to-clipboard functionality, like system with localStorage, full translation support, SEO optimization with JSON-LD schema, and an integrated blog section with monetization ready ad placements.

## Structure
- **inc/**: Core theme functionality including CPT definitions, customizer settings, SEO schema, widgets, and theme features
- **template-parts/**: Modular template components for different layouts (blog, home, prompt, footer)
- **assets/**: CSS and JavaScript files for frontend styling and functionality
- **page-templates/**: Custom page template layouts
- **Examples/**: Example template variations for different display styles
- **languages/**: Internationalization and translation support files

### Main Root Files
- `functions.php`: Theme initialization, enqueue scripts/styles, hooks, and main theme setup
- `style.css`: Theme metadata and header information
- `header.php`, `footer.php`: Main theme wrapper templates
- `index.php`, `single.php`, `page.php`, `archive.php`: Default template hierarchy
- `archive-prompt.php`, `single-prompt.php`: Custom post type templates
- `archive.php`, `taxonomy-*.php`: Archive and taxonomy page templates

## Language & Runtime
**Language**: PHP (with JavaScript and CSS)  
**PHP Version**: 7.4 or later  
**WordPress Version**: 6.0+ (tested up to 6.4)  
**Theme Version**: 1.2.0  
**Package Manager**: npm  
**Build Tool**: BrowserSync (for live reloading during development)

## Dependencies
**Development Dependencies**:
- browser-sync: ^2.29.3 (for live reload and synchronization during theme development)

**Built-in Third-Party Resources**:
- Feather Icons (MIT License) - https://feathericons.com/
- System fonts stack (no external font requests)

## Build & Installation
```bash
# Install development dependencies
npm install

# Start local development server with BrowserSync
npm run sync

# Start development server and open browser
npm run sync-open
```

**BrowserSync Configuration**: Configured via `bs-config.js` to proxy `http://zzprompts.test` and watch all PHP, CSS, and JS files for live reload.

## Main Files & Entry Points

**Theme Initialization**: `functions.php` (880 lines) - Contains theme setup, hook initialization, and core functionality

**Key Include Files** (`inc/`):
- `cpt-prompts.php`: Custom Post Type and Taxonomy definitions
- `theme-settings.php`: Customizer and theme options configuration
- `features.php`: Like system and copy-to-clipboard AJAX handlers
- `helpers.php`: Utility functions and template helpers
- `widgets.php`: Custom widget registrations
- `seo-schema.php`: JSON-LD schema markup for SEO
- `tgm-config.php`: TGM Plugin Activation configuration
- `customizer-css.php`: Dynamic customizer styles

**Frontend Assets**:
- `assets/js/main.js` (48 KB): Core JavaScript functionality
- `assets/css/main.css` (356 KB): Primary stylesheet with all custom styles
- `assets/js/admin.js`, `assets/css/admin.css`: WordPress admin customizations

**Template Structure**:
- Prompt templates: `template-parts/prompt/` with grid and classic layouts
- Blog section: `template-parts/blog/` with single and archive layouts
- Homepage: `template-parts/home/` with v1 and v2 variants
- Footer: `template-parts/footer/` with classic and modern styles

## Configuration
- **Text Domain**: `zzprompts` (for translation support)
- **Supported Features**: title-tag, post-thumbnails, html5, responsive-embeds, custom-logo, navigation-menus
- **Registered Menus**: Primary menu and Footer menu
- **Custom Post Types**: Prompts with categories and AI tool taxonomies

## Security & Practices
- Security checks via AJAX nonce verification
- SQL injection prevention with proper sanitization
- XSS protection with escaping functions
- Data validation and type casting throughout codebase
- Follows WordPress coding standards and best practices


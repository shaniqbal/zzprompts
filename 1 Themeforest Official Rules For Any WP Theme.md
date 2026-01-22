# ThemeForest Pre-Submission Audit Checklist

**Theme Name:** _________________  
**Version:** _________________  
**Reviewer:** _________________  
**Date:** _________________

---

## 1. ThemeForest Mandatory Requirements

- [ ] Theme passes Envato Theme Check plugin with ALL `REQUIRED` errors fixed
- [ ] All `WARNING` items from Theme Check addressed or documented as intentional
- [ ] WordPress Unit Test data imported and theme displays correctly
- [ ] HTML validates with W3C Validator (no critical errors)
- [ ] CSS validates with W3C Validator (no critical errors)
- [ ] No user tracking/data transmission without explicit opt-in
- [ ] License verification data transmission limited to update checks only
- [ ] All demo images have commercial licenses and documentation included
- [ ] All fonts have commercial licenses and documentation included
- [ ] All third-party code/libraries are GPL-compatible or properly licensed
- [ ] Extended licenses obtained for any premium libraries/assets included

---

## 2. WordPress Core Feature Support

- [ ] `add_theme_support( 'custom-logo' )` implemented (no custom logo upload fields)
- [ ] `add_theme_support( 'custom-background' )` implemented
- [ ] `add_theme_support( 'custom-header' )` implemented
- [ ] `add_theme_support( 'title-tag' )` implemented (no `<title>` tag in header.php)
- [ ] `wp_nav_menu()` used for all menus (no custom menu managers)
- [ ] `add_theme_support( 'post-thumbnails' )` implemented
- [ ] `add_theme_support( 'html5' )` implemented for relevant features
- [ ] `add_theme_support( 'editor-styles' )` implemented
- [ ] `add_theme_support( 'align-wide' )` implemented for Gutenberg
- [ ] Block editor fully supported and styled

---

## 3. Plugin Territory (Theme/Plugin Separation)

- [ ] NO custom post types registered in theme files
- [ ] NO shortcodes defined in theme files
- [ ] NO analytics functionality in theme files
- [ ] NO SEO options in theme files
- [ ] NO social sharing functionality in theme files
- [ ] All plugin functionality moved to companion plugin
- [ ] TGM Plugin Activation library implemented for plugin recommendations
- [ ] `force_activation` set to `false` in TGM config
- [ ] `force_deactivation` set to `false` in TGM config
- [ ] Plugin ZIP files NOT bundled in theme (fetched from repo)

---

## 4. Coding Standards & Prefixing

- [ ] All functions prefixed with `themename_` or `authorname_`
- [ ] All classes prefixed with `Themename_` or `Authorname_`
- [ ] All constants prefixed with `THEMENAME_` or `AUTHORNAME_`
- [ ] All global variables prefixed
- [ ] All image sizes prefixed (`themename-size-name`)
- [ ] All script/style handles prefixed (`themename-script-name`)
- [ ] All custom database options prefixed
- [ ] All AJAX actions prefixed
- [ ] No generic function names used (e.g., `get_posts`, `main_style`)

---

## 5. File Loading & Structure

- [ ] `get_theme_file_path()` used instead of `dirname( __FILE__ )`
- [ ] `get_theme_file_uri()` used for asset URLs
- [ ] Child theme compatibility verified for all file includes
- [ ] No hardcoded file paths used
- [ ] All template files properly organized in theme directory

---

## 6. JavaScript & CSS Standards

- [ ] All scripts enqueued via `wp_enqueue_script()` (no inline `<script>` tags)
- [ ] All styles enqueued via `wp_enqueue_style()` (no inline `<link>` tags)
- [ ] WordPress-included jQuery used (no custom jQuery bundled)
- [ ] jQuery loaded in footer where possible
- [ ] Dependencies properly declared in enqueue functions
- [ ] Version numbers added to all enqueued assets
- [ ] `wp_localize_script()` used to pass PHP data to JavaScript
- [ ] No JavaScript errors in browser console
- [ ] Google Fonts loaded with only required weights
- [ ] Google Font requests combined to minimize HTTP requests

---

## 7. Security: Input Validation

- [ ] All `$_POST` data validated before processing
- [ ] All `$_GET` data validated before processing
- [ ] All `$_REQUEST` data validated before processing
- [ ] Email inputs validated with `is_email()`
- [ ] Numeric inputs validated with `is_numeric()` or `absint()`
- [ ] File uploads validated for type and size
- [ ] Invalid data rejected (not processed further)

---

## 8. Security: Data Sanitization

- [ ] All text field inputs sanitized with `sanitize_text_field()`
- [ ] All email inputs sanitized with `sanitize_email()`
- [ ] All filenames sanitized with `sanitize_file_name()`
- [ ] All keys/slugs sanitized with `sanitize_key()`
- [ ] All HTML classes sanitized with `sanitize_html_class()`
- [ ] All URLs sanitized with `esc_url_raw()`
- [ ] All data sanitized BEFORE database storage
- [ ] HTML content sanitized with `wp_kses()` or `wp_kses_post()`

---

## 9. Security: Database Queries

- [ ] NO direct SQL queries with user input
- [ ] All custom SQL queries use `$wpdb->prepare()`
- [ ] Placeholders (`%s`, `%d`, `%f`) used correctly in prepared statements
- [ ] No string concatenation in SQL queries
- [ ] Table names use `$wpdb->prefix` or predefined `$wpdb` properties

---

## 10. Security: Output Escaping

- [ ] All HTML body content escaped with `esc_html()`
- [ ] All HTML attributes escaped with `esc_attr()`
- [ ] All URLs escaped with `esc_url()`
- [ ] All JavaScript strings escaped with `esc_js()`
- [ ] All textarea content escaped with `esc_textarea()`
- [ ] Translation functions use escaping variants (`esc_html__()`, `esc_html_e()`)
- [ ] HTML content escaped with `wp_kses_post()` (when HTML allowed)
- [ ] Escaping applied at point of output (not storage)
- [ ] Database-retrieved content escaped before display

---

## 11. Security: Nonces & Permissions

- [ ] All form submissions include nonce fields
- [ ] All AJAX requests include nonce verification
- [ ] Nonces verified with `wp_verify_nonce()` before processing
- [ ] User capabilities checked with `current_user_can()` before actions
- [ ] Appropriate capability levels used for each action
- [ ] Admin-only actions restricted to admin users
- [ ] CSRF protection implemented for all state-changing operations

---

## 12. Customizer Settings

- [ ] Native WordPress Customizer used for theme options
- [ ] All settings have `sanitize_callback` defined
- [ ] Appropriate sanitization functions used for each setting type
- [ ] Default values provided for all settings
- [ ] Live preview working correctly
- [ ] No settings saved to database without sanitization

---

## 13. Internationalization (i18n)

- [ ] Text domain matches theme slug exactly
- [ ] Text domain loaded with `load_theme_textdomain()`
- [ ] All user-facing strings wrapped in translation functions
- [ ] `esc_html__()` / `esc_html_e()` used for translatable strings
- [ ] `_n()` used for plural strings
- [ ] `sprintf()` / `printf()` used with placeholders (no variables in strings)
- [ ] No JavaScript alerts/messages without translation
- [ ] Context provided for ambiguous strings using `_x()`
- [ ] `.pot` file generated and included

---

## 14. Performance & Optimization

- [ ] Images optimized for web delivery
- [ ] No unnecessary scripts/styles loaded on all pages
- [ ] Conditional loading implemented for page-specific assets
- [ ] No excessive database queries (check Query Monitor)
- [ ] Transient API used for expensive operations
- [ ] Object caching considered for repeated queries
- [ ] Lazy loading implemented for images where appropriate
- [ ] Minified versions of CSS/JS available for production

---

## 15. Accessibility (a11y)

- [ ] Proper heading hierarchy maintained (`h1` → `h2` → `h3`)
- [ ] Skip to content link included
- [ ] ARIA labels used where appropriate
- [ ] Form inputs have associated `<label>` elements
- [ ] Sufficient color contrast ratios (WCAG AA minimum)
- [ ] Keyboard navigation functional throughout theme
- [ ] Focus states visible on interactive elements
- [ ] Alt text required for images
- [ ] Screen reader text used for icon-only buttons

---

## 16. Responsive Design & UX

- [ ] Mobile-responsive design tested on multiple devices
- [ ] Breakpoints tested: 320px, 768px, 1024px, 1440px
- [ ] Touch targets minimum 44×44px on mobile
- [ ] Typography scales appropriately across devices
- [ ] Navigation functional on mobile devices
- [ ] No horizontal scrolling on small screens
- [ ] Retina/HiDPI images provided where appropriate

---

## 17. WordPress Widgets & Default Styles

- [ ] Calendar widget styled
- [ ] Archives widget styled
- [ ] Categories widget styled
- [ ] Tag cloud widget styled
- [ ] Search widget styled
- [ ] Recent Posts widget styled
- [ ] Recent Comments widget styled
- [ ] RSS widget styled
- [ ] Custom widgets (if any) follow WordPress standards

---

## 18. Comments & Post Formats

- [ ] Comments display correctly
- [ ] Nested/threaded comments styled
- [ ] Comment form styled and functional
- [ ] Pingbacks/trackbacks handled
- [ ] Long comment author names don't break layout
- [ ] Gravatar images display correctly
- [ ] Post formats supported and styled (if theme uses them)

---

## 19. RTL (Right-to-Left) Support

- [ ] `rtl.css` file created (if RTL support claimed)
- [ ] RTL layout tested with RTL language
- [ ] Text alignment flipped correctly
- [ ] Floats reversed appropriately
- [ ] Margin/padding adjusted for RTL
- [ ] Icons/images positioned correctly for RTL

---

## 20. Plugin Compatibility Testing

- [ ] WooCommerce compatibility tested (if supported)
- [ ] Contact Form 7 compatibility tested
- [ ] Yoast SEO compatibility verified
- [ ] Classic Editor plugin tested
- [ ] Gutenberg/Block Editor tested
- [ ] Popular page builders tested (if supported)
- [ ] No JavaScript conflicts with major plugins
- [ ] No CSS conflicts with major plugins

---

## 21. Demo Content & Documentation

- [ ] Demo content XML file included
- [ ] Demo content properly attributed/licensed
- [ ] Documentation file included (installation, setup, features)
- [ ] Screenshot guidelines followed (1200×900px)
- [ ] Theme description accurate and complete
- [ ] Changelog maintained
- [ ] Credit links (if any) clearly disclosed

---

## 22. Browser Compatibility

- [ ] Chrome (latest) tested
- [ ] Firefox (latest) tested
- [ ] Safari (latest) tested
- [ ] Edge (latest) tested
- [ ] Mobile Safari tested
- [ ] Mobile Chrome tested
- [ ] No console errors in any browser

---

## 23. Code Quality & Organization

- [ ] No PHP errors/warnings when `WP_DEBUG` enabled
- [ ] No JavaScript console errors
- [ ] Code properly commented
- [ ] Functions organized logically
- [ ] No duplicate code blocks
- [ ] No deprecated WordPress functions used
- [ ] No `@` error suppression operators used
- [ ] Proper indentation and formatting throughout

---

## 24. Submission Package

- [ ] Theme folder properly named (lowercase, no spaces)
- [ ] Documentation folder included
- [ ] Licensing folder with all asset licenses included
- [ ] No development files in package (.git, node_modules, .sass, etc.)
- [ ] Main screenshot.png present (1200×900px)
- [ ] style.css header complete with all required fields
- [ ] Version number in style.css matches actual version
- [ ] All files zipped correctly for submission
- [ ] Total package size reasonable (< 10MB recommended)

---

## 25. Final Pre-Flight Checks

- [ ] Fresh WordPress install test completed
- [ ] Theme activated without errors
- [ ] Demo import process tested and documented
- [ ] All theme features tested individually
- [ ] Cross-browser testing completed
- [ ] Mobile device testing completed
- [ ] Colleague/peer review completed
- [ ] This entire checklist reviewed and signed off

---

**Reviewer Notes:**

_____________________________________________________________________

_____________________________________________________________________

_____________________________________________________________________

**Status:** ☐ Approved for Submission  ☐ Requires Fixes

**Signature:** _______________________ **Date:** _________________
# ThemeForest Approval Checklist & Guidelines

This document serves as a comprehensive master checklist for developing WordPress themes that meet Envato usability, coding, security, and design standards. Use this file as a strict reference for all future development to ensure ThemeForest approval.

---

## 1. General Requirements

### Theme Validation
- [ ] **Envato Theme Check**: Install the Envato Theme Check plugin.
    - [ ] **Fix ALL** `REQUIRED` notices. No exceptions.
    - [ ] **Resolve** `WARNING`, `RECOMMENDED`, and `INFO` notices where possible.
- [ ] **Unit Test Data**: Test against WordPress Unit Test data.
    - [ ] Posts must display correctly (title, body, comments, meta).
    - [ ] Pagination, search results (and "no results" state), sticky posts, and 404 pages must work.
    - [ ] "Read More" links must function.
    - [ ] Floating elements (images) must clear properly.
    - [ ] Long strings (titles/URLs) must not break the layout (overflow handling).
- [ ] **W3C Validation**: HTML and CSS should validate without major errors (unclosed tags, duplicate IDs).

### Data Privacy
- [ ] **No Hidden Transmissions**: Do not transmit data to external servers (including your own) without user knowledge and opt-in.
- [ ] **Exceptions**: Update mechanisms are allowed to send necessary data only.

### Licensing & Assets
- [ ] **Licenses**: Ensure you have commercial redistribution licenses for all bundled assets (images, fonts, scripts).
- [ ] **Attribution**: Check if attribution is required for any asset.
- [ ] **Google Fonts**: Load only used variants. Combine requests (e.g., `...family=Lora:400,700|Inconsolata:700`).

### Documentation
- [ ] **Online Documentation**: Must be publicly accessible (not behind a keygate).
- [ ] **Content**: Explain installation, setup, and credit all external assets used.

---

## 2. Core Features & Settings

### Mandatory Support
- [ ] **Standard Pages**: Home, Blog, Archive, Search, 404, standard Page/Post views.
- [ ] **Comments**: Support threaded comments and comment forms.
- [ ] **Sidebars & Widgets**: Widget-ready areas must only display if active (`is_active_sidebar`).
- [ ] **Navigation**: Include at least one `wp_nav_menu`.
- [ ] **Core Widgets**: Default WP widgets must be styled to match the theme.

### Core Functionality Usage
- [ ] **Do Not Re-invent Core**: Use core features instead of custom solutions for:
    - [ ] Site Logo & Icon
    - [ ] Custom Header & Background
    - [ ] Navigation Menus
    - [ ] Feed Links
- [ ] **Theme Settings**: Use the **Customizer** for theme options. (Strongly Recommended).
- [ ] **No "System" Modification**: Do not unregister default widgets or remove core filters (`wpautop`, etc.).

### WooCommerce (If supported)
- [ ] **Hooks**: Never remove WooCommerce hooks from templates.
- [ ] **Styling**: Ensure all WooCommerce pages and elements match the theme design.

---

## 3. Plugin Territory & Requirements

### Separation of Concerns
- [ ] **Theme = Presentation**: Styling and layout ONLY.
- [ ] **Plugin = Functionality**: The following MUST be in a plugin:
    - [ ] Custom Post Types (CPT) & Taxonomies
    - [ ] Shortcodes
    - [ ] Analytics / SEO / Forms
    - [ ] Non-design Meta Boxes
    - [ ] Social Sharing / Likes

### TGM Plugin Activation (TGMPA)
- [ ] **Usage**: Use TGM Plugin Activation to recommend/require plugins.
- [ ] **WordPress.org Plugins**: Download from the potential repo. Do not bundle the zip file.
- [ ] **Bundled Plugins**: Keep version numbers in TGM config updated.
- [ ] **Constraints**:
    - [ ] `force_activation` must be `false`.
    - [ ] `force_deactivation` must be `false`.
    - [ ] Do not rename the TGM class file.

### Third-Party Plugins
- [ ] **Permissions**: You need written permission to bundle CodeCanyon plugins.
- [ ] **Restricted**: Do NOT bundle premium plugins like Elementor Pro without specific license/permission.

---

## 4. Coding Standards

### PHP Requirements
- [ ] **Prefixing**: **CRITICAL**. Prefix ALL functions, classes, globals, constants, and image sizes.
    - [ ] Format: `themename_` or `authorname_`.
    - [ ] Must be unique (min 3 characters).
    - [ ] **Exceptions**: Do not prefix 3rd party libraries.
- [ ] **Errors**: No PHP notices, warnings, or errors with `WP_DEBUG` enabled.
- [ ] **File Loading**:
    - [ ] Use `get_theme_file_path()` and `get_theme_file_uri()` (WP 4.7+).
    - [ ] Avoid `dirname(__FILE__)`.
- [ ] **Coding Style**: Follow WordPress PHP Coding Standards.
    - [ ] No shorthand PHP (`<?` is forbidden, use `<?php`).
    - [ ] No `eval()`.
    - [ ] No `create_function()`.
    - [ ] Avoid Global variables using `global $var`.

### HTML & CSS
- [ ] **No Hardcoded Styles**: Do not use inline `style="..."` or `<style>` blocks.
- [ ] **Enqueuing**: Use `wp_enqueue_style` for all CSS.
- [ ] **Dynamic CSS**: Use `wp_add_inline_style`.
- [ ] **Selectors**: Use human-readable classes. Avoid over-qualification (`div.container` -> `.container`).
- [ ] **Prefixing**: Prefix ID and Class names to avoid conflicts (e.g., `.theme-container` rather than `.container`).

### JavaScript
- [ ] **Enqueuing**: Use `wp_enqueue_script`.
- [ ] **Strict Mode**: Use `"use strict";`.
- [ ] **No Console Errors**: Zero console errors on any page.
- [ ] **jQuery**: Use provided jQuery. Do not bundle your own.
    - [ ] Use `.on()` instead of `.click()`, `.bind()`, etc.
    - [ ] Wrap in `(function($) { ... })(jQuery);`.
- [ ] **Variables**: Use `wp_localize_script` to pass PHP data to JS.

### Internationalization (i18n)
- [ ] **Text Domain**: String must match theme slug.
- [ ] **Strings**: All strings must be translatable (`__`, `_e`, `esc_html__`, etc.).
- [ ] **Variables**: Do NOT include variables in translation strings. Use `printf` or `sprintf`.
    - *Bad*: `_e( "Hello $name", 'slug' );`
    - *Good*: `printf( __( 'Hello %s', 'slug' ), $name );`
- [ ] **.POT File**: Include a valid `.pot` file in `languages/`.

---

## 5. Security (Critical)

### 1. Validation (Input)
*Check data **before** processing it.*
- [ ] **Emails**: `is_email()`
- [ ] **Text**: `sanitize_text_field()`
- [ ] **Keys**: `sanitize_key()`
- [ ] **Custom**: check against allowed arrays (whitelisting).

### 2. Sanitization (Database)
*Clean data **before** saving to DB.*
- [ ] **Text**: `sanitize_text_field()`
- [ ] **HTML**: `wp_kses_post()` or `wp_kses()` for specific tags.
- [ ] **SQL**: Use `$wpdb->prepare()` for ALL custom queries. Never inject variables directly.

### 3. Escaping (Output) - "Late Escaping"
*Clean data **at the moment** of output.*
- [ ] **HTML Body**: `esc_html( $var )`
- [ ] **HTML Attributes**: `esc_attr( $var )`
- [ ] **URLs**: `esc_url( $var )`
- [ ] **JavaScript**: `esc_js( $var )`
- [ ] **Textarea**: `esc_textarea( $var )`
- [ ] **Translated Strings**: `esc_html__( 'Text', 'slug' )`, `esc_attr_e( 'Text', 'slug' )`.
- [ ] **Rule**: Escape EVERYTHING, even core function returns if they are not "safe by default".

### 4. Capabilities & Nonces
- [ ] **Permissions**: Check `current_user_can()` before saving data or performing actions.
- [ ] **Nonces**: Use `wp_create_nonce()`, `wp_verify_nonce()` (or `check_admin_referer()`) for all forms and action links.

---

## 6. Gutenberg Compatibility

- [ ] **Styling**: Editor styles must match front-end styles (`add_theme_support( 'editor-styles' )`).
- [ ] **Block Support**:
    - [ ] Support `.alignwide` and `.alignfull`.
    - [ ] Define a color palette.
- [ ] **No Errors**: Ensure no console errors in the Block Editor.

---

## 7. Submission Aesthetics & "Wow" Factor

- [ ] **Design Quality**: Must be "Premium". Avoid generic formatting. "Good enough" is a rejection.
- [ ] **Typography**: Use distinct, readable, and well-paired fonts.
- [ ] **Spacing**: Consistent padding and margins (rhythm).
- [ ] **Details**: Hover states, transitions, consistent border-radius, box-shadows.
- [ ] **Completeness**: Search results, 404, and Archive pages must be as designed as the Homepage.

---

## 8. Common Rejection Reasons (Avoid These)

1.  **Generic Design**: Looks like a default bootstrap/foundation theme.
2.  **Inconsistent Spacing/Typography**: Lack of attention to detail.
3.  **Prefixing Issues**: Forgetting to prefix classes or functions.
4.  **Plugin Territory**: Building CPTs or Shortcodes directly into `functions.php`.
5.  **Escaping**: Missing `esc_url` or `esc_attr`.
6.  **Validation Errors**: PHP warnings or JS console errors.
7.  **Unsanitized $_POST/$_GET**: Using superglobals directly without sanitization/nonces.

# ThemeForest Development Master Guide & Handbook

This guide details the technical and design requirements for submitting themes to ThemeForest. It explains the **concepts**, provides **code examples**, and highlights **critical requirements** to ensure your team understands *why* and *how* to implement features correctly.

---

## 1. General Organization & Validation

### Theme Validation Tools
Before submitting, your theme must pass specific automated tests.
*   **Envato Theme Check**: Install the plugin. You must fix **ALL** `REQUIRED` errors. `WARNING` and `RECOMMENDED` notices should also be fixed unless there is a specific, valid reason not to.
*   **Unit Test Data**: You must import the "WordPress Unit Test" data. This populates the site with edge-case content (long titles, large images, nested comments) to prove your CSS handles layouts correctly without breaking.
*   **W3C Validator**: HTML and CSS should be valid. Soft rejections occur for unclosed tags or duplicate IDs.

### Data Privacy
*   **Rule**: You cannot track users without permission.
*   **Explanation**: Themes must not transmit data (like IP addresses or site URLs) to external servers (including your own) unless the user specifically opts-in.
*   **Exception**: Automated update checks are allowed to send necessary data to verify licenses.

### Licensing & Assets
*   **Rule**: You must have the right to resell every asset in your zip.
*   **Details**:
    *   **Images/Fonts**: If you use assets in your demo, you need a commercial license.
    *   **Google Fonts**: Load only the weights you need. Combine requests to improve performance.
    *   **Code**: If you use a library (like a slider), ensure it’s GPL compatible or you have an extended license.

---

## 2. Core Features & Settings

### Standard WordPress Features
Do not reinvent the wheel. If WordPress has a native feature, you **must** support it and **must not** build your own version.

| Feature | Requirement |
| :--- | :--- |
| **Site Logo & Icon** | Use Core `add_theme_support( 'custom-logo' )`. Do not make a custom upload field. |
| **Backgrounds** | Use Core `add_theme_support( 'custom-background' )`. |
| **Headers** | Use Core `add_theme_support( 'custom-header' )`. |
| **Menus** | Use Core `wp_nav_menu()`. Do not build custom menu managers. |
| **Title Tags** | Use Core `add_theme_support( 'title-tag' )`. Do not use `<title>` in `header.php`. |

### Theme Options (Customizer)
*   **Recommendation**: Use the Native WordPress Customizer for theme settings. It provides live previewing and is familiar to users.
*   **Sanitization**: All Customizer settings must have a `sanitize_callback`.

```php
// BAD: No sanitization
$wp_customize->add_setting( 'header_color', array( 'default' => '#ffffff' ) );

// GOOD: Sanitization provided
$wp_customize->add_setting( 'header_color', array( 
    'default' => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color' 
) );
```

---

## 3. Plugin Territory (Important)

### Separation of Concerns
There is a strict line between **Theme** (Presentation) and **Plugin** (Functionality).
*   **Concept**: If a user switches themes, they should not lose their content.
*   **Forbidden in Theme**:
    *   Custom Post Types (e.g., Portfolio, Team Members).
    *   Shortcodes.
    *   Analytics logic.
    *   SEO options.
    *   Social Sharing buttons.
*   **Solution**: Bundle a plugin (or recommend one via TGM) that handles these features.

### TGM Plugin Activation
Use the **TGM Plugin Activation** library to recommend or require plugins.
*   **Do not** bundle plugin zip files inside your theme zip (unless necessary/licensed). Fetch them from the repo.
*   **Config**: `force_activation` and `force_deactivation` must be `false`. Let the user choose.

---

## 4. Coding Standards

### Prefixing (MANDATORY)
*   **Concept**: Your code runs alongside hundreds of other plugins. Common names like `main_style` or `Slider` causes fatal errors if another plugin uses the same name.
*   **Rule**: Prefix **EVERYTHING**: Functions, Classes, Constants, Global Variables, Image Sizes, Script Handles.
*   **Format**: `themename_` or `authorname_`.

```php
// BAD: Generic names
function get_posts() { ... }
define( 'VERSION', '1.0' );
add_image_size( 'big-thumb', 800, 600 );

// GOOD: Unique prefixes
function zzprompts_get_posts() { ... }
define( 'ZZPROMPTS_VERSION', '1.0' );
add_image_size( 'zzprompts-big-thumb', 800, 600 );
```

### File Loading
*   **Rule**: Use WordPress 4.7+ path functions for child-theme compatibility.
*   **Explanation**: `get_template_directory()` always points to the parent theme. `get_theme_file_path()` checks the child theme first, allowing users to override files.

```php
// BAD: Old style
require_once dirname( __FILE__ ) . '/inc/custom-functions.php';

// GOOD: Child-theme friendly
require_once get_theme_file_path( '/inc/custom-functions.php' );
```

### JavaScript Standards
*   **Enqueuing**: Never use `<script>` tags in `header.php`. Use `wp_enqueue_script`.
*   **jQuery**: Use the WordPress-included jQuery. Do not bundle your own version.
*   **Variables**: Pass PHP data to JS using `wp_localize_script()`.

```php
// Passing data to JS safely
wp_enqueue_script( 'zzprompts-main', get_theme_file_uri( '/js/main.js' ), array('jquery'), '1.0', true );
wp_localize_script( 'zzprompts-main', 'zzprompts_config', array( 
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => wp_create_nonce( 'zzprompts_nonce' )
));
```

---

## 5. Security (Critical)

This is the most common reason for rejection. Follow the mantra: **"Validate Early, Late Escape."**

### 1. Validation (checking input)
Verify data matches expectations *before* you process it.

```php
$email = $_POST['user_email'];

// Validation Check
if ( ! is_email( $email ) ) {
    // Stop processing if invalid
    return;
}
```

### 2. Sanitization (cleaning data)
Clean data before saving it to the database. Remove harmful characters.

| Data Type | Function |
| :--- | :--- |
| **Text Fields** | `sanitize_text_field( $string )` |
| **Email** | `sanitize_email( $email )` |
| **Filenames** | `sanitize_file_name( $file )` |
| **Keys/Slugs** | `sanitize_key( $string )` |
| **HTML Classes** | `sanitize_html_class( $class )` |

```php
// Sanitizing a text field input
$clean_title = sanitize_text_field( $_POST['title'] );
update_post_meta( $post_id, 'custom_title', $clean_title );
```

### 3. Database Security
Never inject variables directly into SQL queries. This causes **SQL Injection** vulnerabilities.
*   **Rule**: Always use `$wpdb->prepare()`.

```php
// BAD: SQL Injection Vulnerability
$wpdb->query( "SELECT * FROM $wpdb->posts WHERE post_title = '$user_input'" );

// GOOD: Prepare Statement
$wpdb->query( 
    $wpdb->prepare( 
        "SELECT * FROM $wpdb->posts WHERE post_title = %s", 
        $user_input 
    ) 
);
```

### 4. Output Escaping (Late Escaping)
Clean data **at the exact moment** it is printed to the screen. This prevents XSS (Cross Site Scripting).
*   **Concept**: Even if data is in your database, do not trust it. Escape it on output.

| Context | Function | Example |
| :--- | :--- | :--- |
| **HTML Body** | `esc_html()` | `<h1><?php echo esc_html( $title ); ?></h1>` |
| **HTML Attribute** | `esc_attr()` | `<div class="<?php echo esc_attr( $classes ); ?>">` |
| **URL** | `esc_url()` | `<a href="<?php echo esc_url( $link ); ?>">` |
| **JavaScript** | `esc_js()` | `var name = '<?php echo esc_js( $name ); ?>';` |
| **Textarea** | `esc_textarea()` | `<textarea><?php echo esc_textarea( $text ); ?></textarea>` |
| **Translation** | `esc_html__()` | `<?php esc_html_e( 'Read More', 'zzprompts' ); ?>` |

**Specific Exception**: If you need to output HTML (like bold text), use `wp_kses_post()` or `wp_kses()`.
```php
// Allow safe HTML (links, bold, italic) but strip scripts
echo wp_kses_post( $content_with_html );
```

### 5. Nonces & Permissions
*   **Permissions**: Always check if a user is allowed to do an action.
    ```php
    if ( ! current_user_can( 'edit_posts' ) ) { return; }
    ```
*   **Nonces**: Verify requests actually came from your site (prevents CSRF attacks).
    ```php
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'my_action' ) ) {
        return; // Fail
    }
    ```

---

## 6. Internationalization (Translation)
All text in the theme must be translatable.

*   **Text Domain**: Must match your theme slug.
*   **No Variables in strings**: Translators cannot translate dynamic variables.

```php
// BAD
_e( "Hello $user_name", 'zzprompts' );

// GOOD (Using Placeholders)
printf( 
    esc_html__( 'Hello %s', 'zzprompts' ), 
    esc_html( $user_name ) 
);
```

---

## 7. Gutenberg & Design

*   **Block Editor**: Your theme usually must support the block editor.
    *   Add `add_theme_support( 'editor-styles' );`
    *   Add `add_theme_support( 'align-wide' );`
*   **Aesthetics**:
    *   **Typography**: Use good hierarchy. Headings (`h1`-`h6`) must be distinct.
    *   **Whitespace**: Don't crowd elements. Use consistent padding/margins.
    *   **Completeness**: Style the generic WordPress widgets (Calendar, Archives, Categories) so they look part of your premium design.

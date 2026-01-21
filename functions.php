<?php
defined('ABSPATH') || exit;

/**
 * zzprompts Theme Functions
 * 
 * @package zzprompts
 * @version 1.0.0
 */

// Theme Setup
if (!function_exists('zzprompts_setup')) {
    function zzprompts_setup() {
        // Translation Support
        load_theme_textdomain('zzprompts', get_template_directory() . '/languages');
        
        // Title Tag Support
        add_theme_support('title-tag');
        
        // Featured Images
        add_theme_support('post-thumbnails');
        
        // HTML5 Support
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style'
        ));
        
        // Responsive Embeds
        add_theme_support('responsive-embeds');
        
        // Custom Logo
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ));
        
        // Register Navigation Menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'zzprompts'),
            'footer'  => esc_html__('Footer Menu', 'zzprompts'),
        ));
    }
}
add_action('after_setup_theme', 'zzprompts_setup');

// Register Widget Areas
function zzprompts_widgets_init() {
    // Main Sidebar (Blog)
    register_sidebar( array(
        'name'          => esc_html__( 'Main Sidebar', 'zzprompts' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Widgets for blog posts and blog archive pages.', 'zzprompts' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    // Single Prompt Sidebar (Prompt pages - works for Modern & Classic)
    register_sidebar( array(
        'name'          => esc_html__( 'Prompt Sidebar', 'zzprompts' ),
        'id'            => 'sidebar-prompt',
        'description'   => esc_html__( '💡 Widgets for single prompt pages. Add: Search, Popular Prompts, Trending Categories, Ads. Works with both Modern and Classic layouts.', 'zzprompts' ),
        'before_widget' => '<div id="%1$s" class="zz-sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="zz-sidebar-widget__title">',
        'after_title'   => '</h4>',
    ) );

    // ==========================================================================
    // FOOTER WIDGET AREAS (Unified - Works for Modern + Classic Layouts)
    // ==========================================================================
    // 💡 PRO TIP: Using 4 active widget areas creates the best balanced layout.
    //    Same widgets work for both Modern and Classic - CSS handles the styling.
    // ==========================================================================
    
    // Footer Column 1 - Brand / About
    register_sidebar(array(
        'name'          => esc_html__('Footer: Column 1', 'zzprompts'),
        'id'            => 'footer-1',
        'description'   => esc_html__('💡 Recommended: Logo, brand description, about info. Works with both Modern & Classic layouts.', 'zzprompts'),
        'before_widget' => '<div id="%1$s" class="zz-footer-widget zz-footer-col-1 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="zz-footer-widget__title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer Column 2 - Links / Navigation
    register_sidebar(array(
        'name'          => esc_html__('Footer: Column 2', 'zzprompts'),
        'id'            => 'footer-2',
        'description'   => esc_html__('💡 Recommended: Quick links, navigation menu, or categories.', 'zzprompts'),
        'before_widget' => '<div id="%1$s" class="zz-footer-widget zz-footer-col-2 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="zz-footer-widget__title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer Column 3 - Resources / Tags
    register_sidebar(array(
        'name'          => esc_html__('Footer: Column 3', 'zzprompts'),
        'id'            => 'footer-3',
        'description'   => esc_html__('💡 Recommended: Tag cloud, resources, or additional links.', 'zzprompts'),
        'before_widget' => '<div id="%1$s" class="zz-footer-widget zz-footer-col-3 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="zz-footer-widget__title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer Column 4 - CTA / Newsletter
    register_sidebar(array(
        'name'          => esc_html__('Footer: Column 4', 'zzprompts'),
        'id'            => 'footer-4',
        'description'   => esc_html__('💡 Recommended: Newsletter signup, CTA, or social links.', 'zzprompts'),
        'before_widget' => '<div id="%1$s" class="zz-footer-widget zz-footer-col-4 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="zz-footer-widget__title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'zzprompts_widgets_init');

// ==========================================================================
// GLOBAL VERSION CONTROL (One place to bust cache)
// ==========================================================================
if (!defined('ZZ_THEME_VERSION')) {
    define('ZZ_THEME_VERSION', '1.1.0');
}

// ==========================================================================
// LAYOUT ENGINE (Future Proofing for Classic/Modern/Neon)
// ==========================================================================
// ==========================================================================
// BODY CLASS INJECTOR (CSS Scoping)
// ==========================================================================
function zzprompts_layout_body_class($classes) {
    $classes[] = 'zz-layout-modern';
    $classes[] = 'zz-style-default';
    return $classes;
}
add_filter('body_class', 'zzprompts_layout_body_class');

// ==========================================================================
// SMART ASSET LOADER (Clean Launch)
// ==========================================================================
// Architecture:
// 1. shared-core.css   → Foundation
// 2. skin.css          → Main Layout Skin
// 3. components/*.css  → Conditional semi-global components
// 4. pages/*.css       → Page-specific styles
// ==========================================================================
function zzprompts_enqueue_assets() {
    $ver = ZZ_THEME_VERSION;
    $uri = get_template_directory_uri();
    $dir = get_template_directory();
    
    // =========================================
    // 1. SHARED CORE (Foundation)
    // =========================================
    wp_enqueue_style(
        'zz-core',
        $uri . '/assets/css/shared-core.css',
        array(),
        $ver
    );
    
    // =========================================
    // 2. MAIN SKIN
    // =========================================
    wp_enqueue_style(
        'zz-skin',
        $uri . '/assets/css/skin.css',
        array('zz-core'),
        $ver
    );
    
    // =========================================
    // 3. CONDITIONAL COMPONENTS (Semi-global)
    // =========================================
    
    // Pagination - Archives only
    if (is_archive() || is_search() || is_home()) {
        wp_enqueue_style(
            'zz-pagination',
            $uri . '/assets/css/components/pagination.css',
            array('zz-core'),
            $ver
        );
    }
    
    // Breadcrumbs - Inner pages only (not homepage)
    if (!is_front_page()) {
        wp_enqueue_style(
            'zz-breadcrumbs',
            $uri . '/assets/css/components/breadcrumbs.css',
            array('zz-core'),
            $ver
        );
    }
    
    // Badges - Prompt pages only
    if (is_singular('prompt') || is_post_type_archive('prompt') || is_tax('prompt_category') || is_tax('ai_tool')) {
        wp_enqueue_style(
            'zz-badges',
            $uri . '/assets/css/components/badges.css',
            array('zz-core'),
            $ver
        );
    }
    
    // Widgets - Global (Sidebar + Footer)
    wp_enqueue_style(
        'zz-widgets',
        $uri . '/assets/css/components/widgets.css',
        array('zz-core'),
        $ver
    );
    
    // Block Patterns - Global (used on any page via Gutenberg)
    wp_enqueue_style(
        'zz-block-patterns',
        $uri . '/assets/css/components/block-patterns.css',
        array('zz-core'),
        $ver
    );
    
    // =========================================
    // 4. PAGE SPECIFIC CSS
    // =========================================
    
    // Homepage
    if (is_front_page()) {
        wp_enqueue_style(
            'zz-home',
            $uri . '/assets/css/pages/home.css',
            array('zz-skin'),
            $ver
        );
    }
    
    // Prompt Pages (Single + Archive + Taxonomies)
    if (is_singular('prompt') || is_post_type_archive('prompt') || is_tax('prompt_category') || is_tax('ai_tool')) {
        wp_enqueue_style(
            'zz-prompts',
            $uri . '/assets/css/pages/prompts.css',
            array('zz-skin'),
            $ver
        );
    }
    
    // Prompt Archive Page (Grid + Sidebar Filters)
    if (is_post_type_archive('prompt') || is_tax('prompt_category') || is_tax('ai_tool')) {
        wp_enqueue_style(
            'zz-archive-prompts',
            $uri . '/assets/css/pages/archive-prompts.css',
            array('zz-skin'),
            $ver
        );
    }
    
    // Taxonomy Pages (Category & AI Tool)
    if (is_tax('prompt_category') || is_tax('ai_tool')) {
        wp_enqueue_style(
            'zz-taxonomy',
            $uri . '/assets/css/pages/taxonomy.css',
            array('zz-skin'),
            $ver
        );
    }
    
    // Blog Archive
    if ((is_home() && !is_front_page()) || is_category() || is_tag() || is_author() || is_date()) {
        $file = $dir . '/assets/css/pages/blog-archive.css';
        if (file_exists($file)) {
            wp_enqueue_style('zz-blog-archive', $uri . '/assets/css/pages/blog-archive.css', array('zz-skin'), $ver);
        }
    }
    
    // Single Blog Post
    if (is_singular('post')) {
        wp_enqueue_style('zz-blog-single', $uri . '/assets/css/pages/blog-single.css', array('zz-skin'), $ver);
    }
    
    // Search Results
    if (is_search()) {
        wp_enqueue_style('zz-search-results', $uri . '/assets/css/pages/search-results.css', array('zz-skin'), $ver);
    }
    
    // 404 Error Page
    if (is_404()) {
        wp_enqueue_style('zz-error-404', $uri . '/assets/css/pages/error-404.css', array('zz-skin'), $ver);
    }
    
    // About Us Page Template
    if (is_page_template('page-templates/about.php')) {
        wp_enqueue_style('zz-about', $uri . '/assets/css/pages/about.css', array('zz-skin'), $ver);
    }
    
    // Contact Us Page Template
    if (is_page_template('page-templates/contact.php')) {
        wp_enqueue_style('zz-contact', $uri . '/assets/css/pages/contact.css', array('zz-skin'), $ver);
    }
    
    // Auth Pages (Login, Forgot Password)
    if (is_page_template('page-templates/login.php') || is_page_template('page-templates/forgot-password.php')) {
        wp_enqueue_style('zz-auth', $uri . '/assets/css/pages/auth.css', array('zz-skin'), $ver);
    }
    
    // Default Pages (Terms, Privacy, Pricing, etc.)
    // Load for any page that doesn't use a specific template
    if (is_page() && !is_front_page() && !is_page_template()) {
        wp_enqueue_style('zz-page', $uri . '/assets/css/pages/page.css', array('zz-skin'), $ver);
    }
    
    // =========================================
    // 5. GOOGLE FONTS
    // =========================================
    wp_enqueue_style(
        'zz-google-fonts',
        'https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap',
        array(),
        null
    );
    
    // =========================================
    // 6. FONT AWESOME (Icons)
    // =========================================
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
    
    // =========================================
    // 7. DESIGN TOKENS (Foundation)
    // =========================================
    wp_enqueue_style(
        'zz-tokens',
        $uri . '/assets/css/core/_tokens.css',
        array('zz-core'),
        $ver
    );
    
    // =========================================
    // 8. ACCESSIBILITY (WCAG 2.2 AA)
    // =========================================
    wp_enqueue_style(
        'zz-accessibility',
        $uri . '/assets/css/core/_accessibility.css',
        array('zz-core'),
        $ver
    );
    
    // =========================================
    // 9. WORDPRESS OVERRIDES
    // =========================================
    wp_enqueue_style(
        'zz-wordpress',
        $uri . '/assets/css/vendor/_wordpress.css',
        array('zz-skin'),
        $ver
    );
    
    // =========================================
    // 10. DARK MODE (System preference + manual)
    // =========================================
    wp_enqueue_style(
        'zz-dark-mode',
        $uri . '/assets/css/themes/_dark.css',
        array('zz-skin'),
        $ver
    );
    
    // =========================================
    // 11. RTL SUPPORT (Arabic, Hebrew, etc.)
    // =========================================
    if (is_rtl()) {
        wp_enqueue_style(
            'zz-rtl',
            $uri . '/assets/css/i18n/_rtl.css',
            array('zz-skin'),
            $ver
        );
    }
    
    // =========================================
    // 7. JAVASCRIPT
    // =========================================
    $main_js_path = $dir . '/assets/js/main.js';
    if (file_exists($main_js_path)) {
        wp_enqueue_script(
            'zzprompts-main-js',
            $uri . '/assets/js/main.js',
            array('jquery'),
            $ver,
            true
        );
        
        wp_localize_script('zzprompts-main-js', 'zzprompts_vars', array(
            'ajax_url'           => admin_url('admin-ajax.php'),
            'nonce'              => wp_create_nonce('zzprompts_nonce'),
            'theme_url'          => $uri,
            'layout'             => 'modern', // Modern V1 Launch: Fixed to modern
            'copy_success_text'  => esc_html(zzprompts_get_option('copy_success_text', __('Copied! 🎉', 'zzprompts'))),
            'like_success_text'  => esc_html__('Thank you for liking!', 'zzprompts'),
            'already_liked_text' => esc_html__('You already liked this!', 'zzprompts'),
            'blog_archive_url'   => get_post_type_archive_link('post') ?: home_url('/blog/'),
            'copy_failed_text'   => esc_html__('Copy failed. Please try again.', 'zzprompts'),
            'like_failed_text'   => esc_html__('Like failed. Please try again.', 'zzprompts'),
        ));
    }
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'zzprompts_enqueue_assets');

// Admin Assets (Optional for backend)
function zzprompts_admin_enqueue_assets($hook) {
    // Always enqueue media to ensure it works in Widgets, Customizer, etc.
    wp_enqueue_media();

    wp_enqueue_style(
        'zzprompts-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        '1.0.0'
    );
    
    wp_enqueue_script(
        'zzprompts-admin',
        get_template_directory_uri() . '/assets/js/admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'zzprompts_admin_enqueue_assets');

// Load Theme Files
require_once get_template_directory() . '/inc/cpt-prompts.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/features.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/seo-schema.php';
require_once get_template_directory() . '/inc/ad-settings.php';

/**
 * TGM Plugin Activation
 */
if (file_exists(get_template_directory() . '/inc/class-tgm-plugin-activation.php')) {
    require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
    require_once get_template_directory() . '/inc/tgm-config.php';
}

/**
 * Customizer Settings
 */
/**
 * Customizer Settings
 */
require get_template_directory() . '/inc/theme-settings.php';
require get_template_directory() . '/inc/customizer-css.php';

/**
 * Block Patterns
 */
require get_template_directory() . '/inc/block-patterns.php';

// Content Width
if (!isset($content_width)) {
    $content_width = 1200;
}

/**
 * Excerpt Modifications (Cleaner Cards)
 */
function zzprompts_custom_excerpt_length($length) {
    return zzprompts_get_option('blog_excerpt_length', 20);
}
add_filter('excerpt_length', 'zzprompts_custom_excerpt_length', 999);

function zzprompts_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'zzprompts_excerpt_more');

/**
 * Smart Lazy Load Logic
 */
function zzprompts_is_lazyload_active() {
    // Check for common lazy load plugins classes or filters
    if ( function_exists( 'rocket_lazyload_images' ) ) return true; // WP Rocket
    if ( class_exists( 'AutoptimizeImages' ) ) return true; // Autoptimize
    if ( class_exists( 'LiteSpeed_Cache_Lazyload' ) ) return true; // LiteSpeed
    if ( class_exists( 'Smush\App\Lazy_Load' ) ) return true; // Smush
    return false;
}

/**
 * --------------------------------------------------------------------------
 * [V2 ENGINE] Advanced Archive Filtering Logic
 * --------------------------------------------------------------------------
 * Handles the sidebar checkboxes for Tools & Categories (and future taxonomies).
 * Works on: Prompt Archive, Prompt Taxonomy Archives, and Author Archive (Prompts).
 */
function zzprompts_apply_archive_filters( $query ) {
    // 1. Safety Check: Only run on frontend main queries
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $is_prompt_author_view = ( is_author() && isset( $_GET['post_type'] ) && 'prompt' === sanitize_key( wp_unslash( $_GET['post_type'] ) ) );

    // Run only on Prompt Archive OR supported Prompt Taxonomies OR Author (prompt view)
    if (
        ! is_post_type_archive( 'prompt' ) &&
        ! is_tax( array( 'prompt_category', 'ai_tool' ) ) &&
        ! $is_prompt_author_view
    ) {
        return;
    }

    // Ensure we are querying prompts in these views
    if ( $is_prompt_author_view || is_tax( array( 'prompt_category', 'ai_tool' ) ) ) {
        $query->set( 'post_type', 'prompt' );
    }

    // Keep V2 grids consistent
    if ( $is_prompt_author_view || is_post_type_archive( 'prompt' ) || is_tax( array( 'prompt_category', 'ai_tool' ) ) ) {
        $query->set( 'posts_per_page', 12 );
    }

    // 2. Define the Taxonomies to Filter (guarded by taxonomy_exists)
    $taxonomies = array(
        'ai_tool',
        'prompt_category',
    );

    $tax_query_parts = array();

    // 3. Loop through filters and build the tax query
    foreach ( $taxonomies as $tax ) {
        if ( ! taxonomy_exists( $tax ) ) {
            continue;
        }

        if ( empty( $_GET[ $tax ] ) ) {
            continue;
        }

        $raw_terms = (array) wp_unslash( $_GET[ $tax ] );
        $terms = array_filter( array_map( 'sanitize_title', $raw_terms ) );
        if ( empty( $terms ) ) {
            continue;
        }

        $tax_query_parts[] = array(
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => 'IN',
        );
    }

    // 4. Handle "Search" within Filters
    if ( ! empty( $_GET['s_prompt'] ) ) {
        $query->set( 's', sanitize_text_field( wp_unslash( $_GET['s_prompt'] ) ) );
    }

    // 5. Handle "Sort/Order By" dropdown
    if ( ! empty( $_GET['orderby'] ) ) {
        $orderby = sanitize_key( wp_unslash( $_GET['orderby'] ) );
        
        switch ( $orderby ) {
            case 'meta_value_num':
            case 'views':
                // Most Popular (by views)
                $query->set( 'meta_key', 'zzprompts_post_views' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;
            
            case 'popular':
            case 'likes':
                // Most Liked
                $query->set( 'meta_key', '_prompt_likes' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;
            
            case 'copies':
                // Most Copied
                $query->set( 'meta_key', '_prompt_copies' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;
            
            case 'title':
                // Alphabetical (A-Z)
                $query->set( 'orderby', 'title' );
                $query->set( 'order', 'ASC' );
                break;
            
            case 'newest':
            case 'date':
            default:
                // Newest First (default)
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
                break;
        }
    }

    // 6. Apply / Merge Tax Query (do not override existing tax archives)
    if ( ! empty( $tax_query_parts ) ) {
        $existing_tax_query = $query->get( 'tax_query' );
        if ( ! is_array( $existing_tax_query ) ) {
            $existing_tax_query = array();
        }

        $merged_tax_query = $existing_tax_query;
        foreach ( $tax_query_parts as $part ) {
            $merged_tax_query[] = $part;
        }

        if ( count( $merged_tax_query ) > 1 ) {
            $merged_tax_query['relation'] = 'AND';
        }

        $query->set( 'tax_query', $merged_tax_query );
    }
}
add_action( 'pre_get_posts', 'zzprompts_apply_archive_filters' );

/**
 * AJAX Live Search for Prompts (Homepage)
 */
function zzprompts_ajax_search_prompts() {
    check_ajax_referer('zzprompts_nonce', 'nonce');

    $query_term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    if (empty($query_term) || strlen($query_term) < 2) {
        wp_send_json_error();
    }

    $args = array(
        'post_type'      => 'prompt',
        'post_status'    => 'publish',
        'posts_per_page' => 5, // Limit to 5 results for speed
        's'              => $query_term,
    );

    $query = new WP_Query($args);
    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Get Tool Name (e.g., ChatGPT)
            $tools = get_the_terms(get_the_ID(), 'ai_tool');
            $tool_name = ($tools && !is_wp_error($tools)) ? $tools[0]->name : '';

            $results[] = array(
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'tool'      => $tool_name,
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}
add_action('wp_ajax_zzprompts_ajax_search_prompts', 'zzprompts_ajax_search_prompts');
add_action('wp_ajax_nopriv_zzprompts_ajax_search_prompts', 'zzprompts_ajax_search_prompts');

/**
 * Apply Blog Posts Per Page Setting
 */
function zzprompts_blog_posts_per_page($query) {
    if (!is_admin() && $query->is_main_query() && (is_home() || is_archive() && !is_post_type_archive('prompt') && !is_tax(array('prompt_category', 'ai_tool')))) {
        $posts_per_page = intval(get_option('zzprompts_blog_posts_per_page', 10));
        $query->set('posts_per_page', $posts_per_page);
    }
}
add_action('pre_get_posts', 'zzprompts_blog_posts_per_page');

/**
 * Smart Search Filter: Respects 'post_type' if set, otherwise defaults to Blog Posts.
 */
function zzprompts_smart_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        
        // Check 1: If 'post_type' or 'type' is already defined in URL, do not interfere.
        if (isset($_GET['post_type']) || isset($_GET['type'])) {
            return;
        }

        // Check 2: If 'post_type' is not defined, show both Blog Posts and Prompts.
        $query->set('post_type', array('post', 'prompt'));
    }
}
add_action('pre_get_posts', 'zzprompts_smart_search_filter');

/**
 * Blog Instant Search AJAX Handler
 */
function zzprompts_search_blog() {
    check_ajax_referer('zzprompts_nonce', 'nonce', false);
    
    $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
    
    if (empty($query) || strlen($query) < 2) {
        wp_send_json_error();
    }

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        's'              => $query,
    );

    $search_query = new WP_Query($args);
    $results = array();

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            // Get category for the card
            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';
            $category_url  = !empty($categories) ? get_category_link($categories[0]->term_id) : '';
            
            $results[] = array(
                'title'        => get_the_title(),
                'url'          => get_permalink(),
                'date'         => get_the_date('M d, Y'),
                'excerpt'      => wp_trim_words(get_the_excerpt(), 20),
                'author'       => get_the_author(),
                'reading_time' => zzprompts_reading_time(),
                'image'        => get_the_post_thumbnail_url(get_the_ID(), 'medium_large'),
                'category'     => $category_name,
                'category_url' => $category_url,
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}
add_action('wp_ajax_zzprompts_search_blog', 'zzprompts_search_blog');
add_action('wp_ajax_nopriv_zzprompts_search_blog', 'zzprompts_search_blog');

/**
 * ========== V2 BLOG SIDEBAR HELPER FUNCTIONS ==========
 */

/**
 * Get popular posts by views (with fallback to latest posts)
 * 
 * @param int $limit Number of posts to retrieve
 * @return array Array of post data
 */
function zzprompts_get_popular_posts($limit = 5) {
    // Try to get from transient cache (6 hours)
    $cache_key = 'zzprompts_popular_posts_' . $limit;
    $popular_posts = get_transient($cache_key);
    
    if ($popular_posts !== false) {
        return $popular_posts;
    }
    
    // First, try to get posts with highest view count
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => (int) $limit,
        'post_status'    => 'publish',
        'meta_key'       => 'post_views_count',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );
    
    $query = new WP_Query($args);
    
    // If no posts with view count, fallback to latest posts
    if (!$query->have_posts()) {
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => (int) $limit,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        $query = new WP_Query($args);
    }
    
    $popular_posts = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $popular_posts[] = array(
                'id'       => get_the_ID(),
                'title'    => get_the_title(),
                'url'      => get_permalink(),
                'date'     => get_the_date('M d, Y'),
                'image'    => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
            );
        }
        wp_reset_postdata();
    }
    
    // Cache for 6 hours
    set_transient($cache_key, $popular_posts, 6 * HOUR_IN_SECONDS);
    
    return $popular_posts;
}

/**
 * Get blog categories with post count
 * 
 * @param int $limit Number of categories to retrieve
 * @param bool $show_count Whether to include post count
 * @return array Array of category data
 */
function zzprompts_get_blog_categories($limit = 10, $show_count = true) {
    $args = array(
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'number'     => (int) $limit,
        'orderby'    => 'count',
        'order'      => 'DESC',
    );
    
    $categories = get_terms($args);
    $result = array();
    
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $result[] = array(
                'id'    => $cat->term_id,
                'name'  => $cat->name,
                'url'   => get_term_link($cat),
                'count' => $show_count ? $cat->count : 0,
            );
        }
    }
    
    return $result;
}

/**
 * Clear sidebar widget cache when posts are published/updated
 */
function zzprompts_clear_sidebar_cache() {
    // Clear all popular posts caches
    for ($i = 1; $i <= 20; $i++) {
        delete_transient('zzprompts_popular_posts_' . $i);
    }
}
add_action('publish_post', 'zzprompts_clear_sidebar_cache');
add_action('edit_post', 'zzprompts_clear_sidebar_cache');
add_action('delete_post', 'zzprompts_clear_sidebar_cache');

/**
 * ========== COMMENT REPLY LINK FILTER ==========
 * Disable reply link for unapproved comments - shows inline notice instead
 */
function zzprompts_filter_comment_reply_link($link, $args, $comment, $post) {
    // Always apply premium comment styling
    if (!is_singular('post')) {
        return $link;
    }
    
    // If comment is not approved, show inline notice instead of reply link
    if ($comment->comment_approved === '0') {
        return '<div class="zz-comment-notice">' . esc_html__('Replies are disabled until this comment is approved.', 'zzprompts') . '</div>';
    }
    
    return $link;
}
add_filter('comment_reply_link', 'zzprompts_filter_comment_reply_link', 10, 4);

/**
 * ========== COMMENTS CALLBACK ==========
 * Custom comment display callback for premium styling
 */
function zzprompts_comment_callback($comment, $args, $depth) {
    global $post;
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    $show_avatars = (bool) intval(get_option('zzprompts_comments_show_avatars', 1));
    
    // Check for Admin or Author status
    $is_author = ($comment->user_id === $post->post_author);
    $is_admin = user_can($comment->user_id, 'manage_options');
    $status_label = '';
    $extra_class = '';
    
    if ($is_author) {
        $status_label = __('Author', 'zzprompts');
        $extra_class = 'zz-comment--official';
    } elseif ($is_admin) {
        $status_label = __('Staff', 'zzprompts');
        $extra_class = 'zz-comment--official';
    }
    ?>
    <<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(array('zz-comment', $extra_class), $comment); ?>>
        <article class="zz-comment-body">
            <?php if ($show_avatars) : ?>
                <div class="zz-comment-avatar">
                    <?php echo get_avatar($comment, 48); ?>
                </div>
            <?php endif; ?>
            
            <div class="zz-comment-content">
                <header class="zz-comment-meta">
                    <span class="zz-comment-author">
                        <?php echo get_comment_author_link($comment); ?>
                        <?php if ($status_label) : ?>
                            <span class="zz-comment-badge <?php echo ($is_admin && !$is_author) ? 'zz-comment-badge--admin' : ''; ?>" title="<?php echo esc_attr($status_label); ?>">
                                <i class="<?php echo $is_author ? 'fa-solid fa-circle-check' : 'fa-solid fa-shield-halved'; ?>"></i> <?php echo esc_html($status_label); ?>
                            </span>
                        <?php endif; ?>
                    </span>
                    <time class="zz-comment-date" datetime="<?php echo esc_attr(get_comment_date('c', $comment)); ?>">
                        <?php
                        printf(
                            /* translators: 1: date, 2: time */
                            esc_html__('%1$s at %2$s', 'zzprompts'),
                            get_comment_date('', $comment),
                            get_comment_time()
                        );
                        ?>
                    </time>
                    <?php edit_comment_link(esc_html__('Edit', 'zzprompts'), '<span class="zz-comment-edit">', '</span>'); ?>
                </header>
                
                <?php if ($comment->comment_approved === '0') : ?>
                    <p class="zz-comment-awaiting">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'zzprompts'); ?>
                    </p>
                <?php endif; ?>
                
                <div class="zz-comment-text">
                    <?php comment_text(); ?>
                </div>
                
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="zz-comment-reply">',
                    'after'     => '</div>',
                )));
                ?>
            </div>
        </article>
    <?php
}

/**
 * ============================================================================
 * 12. CACHE MANAGEMENT
 * ============================================================================
 */

/**
 * Flush widget transients on prompt update/publish
 * Ensures "Popular Prompts" and "Category Tags" widgets show fresh data.
 */
function zzprompts_flush_widget_transients($post_id) {
    // Autosave check
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Permission check
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Post type check
    if (get_post_type($post_id) !== 'prompt') return;

    global $wpdb;
    
    // 1. Delete "Popular Prompts" Cache
    // Transients are stored in wp_options with prefix '_transient_'
    // Key pattern we set: zz_widget_popular_prompts_{HASH}
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_zz_widget_popular_prompts_%' 
         OR option_name LIKE '_transient_timeout_zz_widget_popular_prompts_%'"
    );
    
    // 2. Delete "Category Tags" Cache
    // Key pattern: zz_widget_cat_tags_{HASH}
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_zz_widget_cat_tags_%' 
         OR option_name LIKE '_transient_timeout_zz_widget_cat_tags_%'"
    );
}
add_action('save_post', 'zzprompts_flush_widget_transients');
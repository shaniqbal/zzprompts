<?php
/**
 * Theme Customizer Settings
 * 
 * BUYER-FRIENDLY STRUCTURE:
 * 1. Homepage Settings - Hero section content
 * 2. Prompt Settings - Prompt CPT specific options
 * 3. Blog Settings (Panel with 4 sub-sections):
 *    - Blog Archive – Content
 *    - Blog Sidebar (Archive)
 *    - Blog Single Post
 *    - Blog Comments
 * 4. Ad Management - Monetization spots
 * 5. Footer Settings - Copyright and footer
 * 6. Colors & Branding - Visual identity
 *
 * INTERNAL (not exposed to buyer):
 * - Layout selections are handled via theme_mod but not visible in Customizer
 *
 * @package zzprompts
 * @version 3.0.0 - Buyer-friendly refactor
 */

defined('ABSPATH') || exit;

/* ============================================================
   HELPER FUNCTION
   ============================================================ */

/**
 * Get Theme Option
 * 
 * @param string $key Option key
 * @param mixed $default Default value
 * @return mixed Option value
 */
if (!function_exists('zzprompts_get_option')) {
    function zzprompts_get_option($key, $default = '') {
        return get_theme_mod($key, $default);
    }
}

/* ============================================================
   REGISTER CUSTOMIZER
   ============================================================ */

function zzprompts_customize_register($wp_customize) {
    
    /* --------------------------------------------------------
       PANEL: THEME OPTIONS (Container for all sections)
       -------------------------------------------------------- */
    $wp_customize->add_panel('zzprompts_options', array(
        'title'       => esc_html__('Theme Options', 'zzprompts'),
        'description' => esc_html__('Configure all theme settings from one place.', 'zzprompts'),
        'priority'    => 10,
    ));

    /* ========================================================
       INTERNAL: LAYOUT SETTINGS (Not exposed to buyer)
       These are stored but controlled silently via templates
       ======================================================== */
    
    // Homepage Layout (internal)
    $wp_customize->add_setting('home_layout_select', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));

    // Blog Archive Layout (internal)
    $wp_customize->add_setting('blog_layout_select', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));

    // Blog Single Layout (internal)
    $wp_customize->add_setting('blog_single_layout', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));

    // Prompt Archive Layout (internal)
    $wp_customize->add_setting('prompt_archive_layout', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));

    // Single Prompt Layout Mode (internal)
    $wp_customize->add_setting('prompt_single_layout_mode', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));


    /* ========================================================
       SECTION 1: HOMEPAGE SETTINGS
       Purpose: Homepage hero area content
       ======================================================== */
    $wp_customize->add_section('zzprompts_hero_section', array(
        'title'       => esc_html__('Homepage', 'zzprompts'),
        'description' => esc_html__('Configure homepage hero area and content.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 10,
    ));

    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default'           => esc_html__('Instant AI Prompts for ChatGPT, Midjourney & More', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('hero_title', array(
        'label'       => esc_html__('Hero Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 10,
    ));

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => esc_html__('Copy & paste production-ready prompts to speed up your workflow.', 'zzprompts'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('hero_subtitle', array(
        'label'       => esc_html__('Hero Subtitle', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'textarea',
        'priority'    => 20,
    ));

    // Search Placeholder
    $wp_customize->add_setting('hero_search_placeholder', array(
        'default'           => esc_html__('Search prompts...', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_search_placeholder', array(
        'label'       => esc_html__('Search Placeholder Text', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 30,
    ));

    // Tags Label
    $wp_customize->add_setting('hero_tags_title', array(
        'default'           => esc_html__('Popular:', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_tags_title', array(
        'label'       => esc_html__('Popular Tags Label', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 40,
    ));

    // Latest Prompts Section Title
    $wp_customize->add_setting('home_latest_title', array(
        'default'           => esc_html__('Latest Prompts', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_latest_title', array(
        'label'       => esc_html__('Latest Section Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 50,
    ));


    /* ========================================================
       SECTION 2: PROMPT SETTINGS
       Purpose: Prompt CPT specific options
       ======================================================== */
    $wp_customize->add_section('zzprompts_prompt_settings', array(
        'title'       => esc_html__('Prompts', 'zzprompts'),
        'description' => esc_html__('Configure prompt display, copy button, and meta options.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 20,
    ));

    // --- COPY BUTTON ---
    $wp_customize->add_setting('copy_btn_text', array(
        'default'           => esc_html__('Copy Prompt', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('copy_btn_text', array(
        'label'       => esc_html__('Copy Button Text', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'text',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('copy_success_text', array(
        'default'           => esc_html__('Copied! 🎉', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('copy_success_text', array(
        'label'       => esc_html__('Copy Success Message', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'text',
        'priority'    => 20,
    ));

    // --- FEATURE TOGGLES ---
    $wp_customize->add_setting('enable_likes', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_likes', array(
        'label'       => esc_html__('Show Like Button', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('enable_copy_counter', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_copy_counter', array(
        'label'       => esc_html__('Show Copy Counter', 'zzprompts'),
        'description' => esc_html__('Display how many times a prompt has been copied.', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 40,
    ));

    $wp_customize->add_setting('home_v2_show_copy_count', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_v2_show_copy_count', array(
        'label'       => esc_html__('Show Copy Count on Prompt Cards', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 50,
    ));

    // --- SINGLE PROMPT LAYOUT ---
    $wp_customize->add_setting('single_prompt_layout', array(
        'default'           => 'classic-order',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_prompt_layout', array(
        'label'       => esc_html__('Single Prompt Image Position', 'zzprompts'),
        'description' => esc_html__('Desktop only. Mobile always stacks.', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'select',
        'choices'     => array(
            'classic-order' => esc_html__('Image Top (Classic)', 'zzprompts'),
            'left-image'    => esc_html__('Image Left', 'zzprompts'),
            'right-image'   => esc_html__('Image Right', 'zzprompts'),
        ),
        'priority'    => 60,
    ));

    // --- SINGLE PROMPT META TOGGLES ---
    $wp_customize->add_setting('single_prompt_meta_show_date', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_prompt_meta_show_date', array(
        'label'       => esc_html__('Show Date in Meta', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 70,
    ));

    $wp_customize->add_setting('single_prompt_meta_show_views', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_prompt_meta_show_views', array(
        'label'       => esc_html__('Show Views in Meta', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 80,
    ));

    $wp_customize->add_setting('single_prompt_meta_show_copies', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_prompt_meta_show_copies', array(
        'label'       => esc_html__('Show Copies Count in Meta', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 90,
    ));

    $wp_customize->add_setting('single_prompt_meta_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_prompt_meta_show_author', array(
        'label'       => esc_html__('Show Author in Meta', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 100,
    ));

    // --- SIDEBAR CONTRIBUTOR CARD ---
    $wp_customize->add_setting('sidebar_contributor_show_icon', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_icon', array(
        'label'       => esc_html__('Show Contributor Icon', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 110,
    ));

    $wp_customize->add_setting('sidebar_contributor_show_name', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_name', array(
        'label'       => esc_html__('Show Contributor Name', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 120,
    ));

    $wp_customize->add_setting('sidebar_contributor_show_total', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_total', array(
        'label'       => esc_html__('Show Total Prompts Count', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 130,
    ));

    // --- PROMPT ARCHIVE SIDEBAR ---
    $wp_customize->add_setting('sidebar_filter_show_counts', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_filter_show_counts', array(
        'label'       => esc_html__('Show Filter Counts in Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 140,
    ));

    // Related Prompts Title
    $wp_customize->add_setting('single_related_title', array(
        'default'           => esc_html__('Related Prompts', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_related_title', array(
        'label'       => esc_html__('Related Prompts Section Title', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'text',
        'priority'    => 150,
    ));


    /* ========================================================
       PANEL: BLOG SETTINGS
       Purpose: All blog-related controls grouped together
       ======================================================== */
    $wp_customize->add_panel('zzprompts_blog_panel', array(
        'title'       => esc_html__('Blog Settings', 'zzprompts'),
        'description' => esc_html__('Configure blog archive, sidebar, single posts, and comments.', 'zzprompts'),
        'priority'    => 30,
    ));

    /* --------------------------------------------------------
       BLOG SECTION 1: Archive – Content
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_archive', array(
        'title'       => esc_html__('Blog Archive – Content', 'zzprompts'),
        'description' => esc_html__('Controls what appears on blog listing pages.', 'zzprompts'),
        'panel'       => 'zzprompts_blog_panel',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('blog_show_image', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_image', array(
        'label'       => esc_html__('Show Featured Images', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('blog_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_date', array(
        'label'       => esc_html__('Show Date', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('blog_show_category', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_category', array(
        'label'       => esc_html__('Show Category', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('blog_excerpt_length', array(
        'default'           => 20,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_excerpt_length', array(
        'label'       => esc_html__('Excerpt Length (Words)', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'number',
        'input_attrs' => array('min' => 5, 'max' => 100),
        'priority'    => 40,
    ));

    $wp_customize->add_setting('blog_read_more_text', array(
        'default'           => esc_html__('Read Article', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_read_more_text', array(
        'label'       => esc_html__('Read More Button Text', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'text',
        'priority'    => 50,
    ));

    $wp_customize->add_setting('zzprompts_blog_posts_per_page', array(
        'default'           => 10,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('zzprompts_blog_posts_per_page', array(
        'label'       => esc_html__('Posts Per Page', 'zzprompts'),
        'description' => esc_html__('Number of posts to display on archive pages.', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 50),
        'priority'    => 60,
    ));


    /* --------------------------------------------------------
       BLOG SECTION 2: Sidebar (Archive Pages)
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_sidebar', array(
        'title'       => esc_html__('Blog Sidebar', 'zzprompts'),
        'description' => esc_html__('Controls sidebar widgets on blog archive pages. Widgets render automatically without drag-drop.', 'zzprompts'),
        'panel'       => 'zzprompts_blog_panel',
        'priority'    => 20,
    ));

    // --- SEARCH WIDGET ---
    $wp_customize->add_setting('zzprompts_sidebar_search_enabled', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_search_enabled', array(
        'label'       => esc_html__('Enable Search Widget', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_search_title', array(
        'default'           => esc_html__('Search Articles', 'zzprompts'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('zzprompts_sidebar_search_title', array(
        'label'       => esc_html__('Search Widget Title', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'text',
        'priority'    => 11,
    ));

    // --- CATEGORIES WIDGET ---
    $wp_customize->add_setting('zzprompts_sidebar_categories_enabled', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_categories_enabled', array(
        'label'       => esc_html__('Enable Categories Widget', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_categories_title', array(
        'default'           => esc_html__('Explore Topics', 'zzprompts'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('zzprompts_sidebar_categories_title', array(
        'label'       => esc_html__('Categories Widget Title', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'text',
        'priority'    => 21,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_categories_limit', array(
        'default'           => 10,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('zzprompts_sidebar_categories_limit', array(
        'label'       => esc_html__('Number of Categories', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 50),
        'priority'    => 22,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_categories_count', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_categories_count', array(
        'label'       => esc_html__('Show Post Count', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 23,
    ));

    // --- POPULAR POSTS WIDGET ---
    $wp_customize->add_setting('zzprompts_sidebar_popular_enabled', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_popular_enabled', array(
        'label'       => esc_html__('Enable Popular Posts Widget', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_popular_title', array(
        'default'           => esc_html__('Trending Now', 'zzprompts'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('zzprompts_sidebar_popular_title', array(
        'label'       => esc_html__('Popular Posts Title', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'text',
        'priority'    => 31,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_popular_limit', array(
        'default'           => 5,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('zzprompts_sidebar_popular_limit', array(
        'label'       => esc_html__('Number of Posts', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 20),
        'priority'    => 32,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_popular_date', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_popular_date', array(
        'label'       => esc_html__('Show Publication Date', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 33,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_popular_thumbnail', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_popular_thumbnail', array(
        'label'       => esc_html__('Show Thumbnail', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 34,
    ));

    // --- RECENT POSTS WIDGET ---
    $wp_customize->add_setting('zzprompts_sidebar_recent_enabled', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_recent_enabled', array(
        'label'       => esc_html__('Enable Recent Posts Widget', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 40,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_recent_title', array(
        'default'           => esc_html__('Latest Posts', 'zzprompts'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('zzprompts_sidebar_recent_title', array(
        'label'       => esc_html__('Recent Posts Title', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'text',
        'priority'    => 41,
    ));

    $wp_customize->add_setting('zzprompts_sidebar_recent_limit', array(
        'default'           => 5,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('zzprompts_sidebar_recent_limit', array(
        'label'       => esc_html__('Number of Posts', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 20),
        'priority'    => 42,
    ));

    // --- SIDEBAR STICKY ---
    $wp_customize->add_setting('zzprompts_sidebar_sticky', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_sidebar_sticky', array(
        'label'       => esc_html__('Make Sidebar Sticky on Desktop', 'zzprompts'),
        'section'     => 'zzprompts_blog_sidebar',
        'type'        => 'checkbox',
        'priority'    => 50,
    ));


    /* --------------------------------------------------------
       BLOG SECTION 3: Single Post
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_single', array(
        'title'       => esc_html__('Blog Single', 'zzprompts'),
        'description' => esc_html__('Controls for individual blog post pages.', 'zzprompts'),
        'panel'       => 'zzprompts_blog_panel',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('single_show_image', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_image', array(
        'label'       => esc_html__('Show Featured Image', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('single_sidebar_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_sidebar_enabled', array(
        'label'       => esc_html__('Show Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('sidebar_sticky_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_sticky_enabled', array(
        'label'       => esc_html__('Make Sidebar Sticky on Desktop', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    // Kept for backward compat (used in templates internally)
    $wp_customize->add_setting('blog_sidebar_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));


    /* --------------------------------------------------------
       BLOG SECTION 4: Comments
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_comments', array(
        'title'       => esc_html__('Blog Comments', 'zzprompts'),
        'description' => esc_html__('Native WordPress comment settings for blog posts. Disable website URL field to reduce spam.', 'zzprompts'),
        'panel'       => 'zzprompts_blog_panel',
        'priority'    => 40,
    ));

    $wp_customize->add_setting('zzprompts_blog_comments_enabled', array(
        'default'           => 0,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_blog_comments_enabled', array(
        'label'       => esc_html__('Enable Comments Section', 'zzprompts'),
        'description' => esc_html__('Show WordPress comments on single blog posts.', 'zzprompts'),
        'section'     => 'zzprompts_blog_comments',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('zzprompts_comments_show_count', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_comments_show_count', array(
        'label'       => esc_html__('Show Comment Count', 'zzprompts'),
        'section'     => 'zzprompts_blog_comments',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('zzprompts_comments_show_avatars', array(
        'default'           => 1,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_comments_show_avatars', array(
        'label'       => esc_html__('Show Avatars', 'zzprompts'),
        'section'     => 'zzprompts_blog_comments',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('zzprompts_comments_website_field', array(
        'default'           => 0,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
    ));
    $wp_customize->add_control('zzprompts_comments_website_field', array(
        'label'       => esc_html__('Show Website URL Field', 'zzprompts'),
        'description' => esc_html__('OFF by default to reduce spam.', 'zzprompts'),
        'section'     => 'zzprompts_blog_comments',
        'type'        => 'checkbox',
        'priority'    => 40,
    ));


    /* ========================================================
       SECTION: AD MANAGEMENT
       ======================================================== */
    $wp_customize->add_section('zzprompts_ads_section', array(
        'title'       => esc_html__('Ads & Monetization', 'zzprompts'),
        'description' => esc_html__('Manage ad spots. Supports AdSense, HTML, or image banners.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 40,
    ));

    $wp_customize->add_setting('ad_before_prompt', array(
        'default'           => '',
        'sanitize_callback' => 'zzprompts_sanitize_ad_code',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('ad_before_prompt', array(
        'label'       => esc_html__('Ad: Before Prompt Box', 'zzprompts'),
        'section'     => 'zzprompts_ads_section',
        'type'        => 'textarea',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('ad_after_prompt', array(
        'default'           => '',
        'sanitize_callback' => 'zzprompts_sanitize_ad_code',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('ad_after_prompt', array(
        'label'       => esc_html__('Ad: After Prompt Box', 'zzprompts'),
        'section'     => 'zzprompts_ads_section',
        'type'        => 'textarea',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('ad_sidebar_sticky', array(
        'default'           => '',
        'sanitize_callback' => 'zzprompts_sanitize_ad_code',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('ad_sidebar_sticky', array(
        'label'       => esc_html__('Ad: Sticky Sidebar', 'zzprompts'),
        'description' => esc_html__('This ad sticks in the sidebar when scrolling.', 'zzprompts'),
        'section'     => 'zzprompts_ads_section',
        'type'        => 'textarea',
        'priority'    => 30,
    ));


    /* ========================================================
       SECTION: FOOTER SETTINGS
       ======================================================== */
    $wp_customize->add_section('zzprompts_footer_section', array(
        'title'       => esc_html__('Footer', 'zzprompts'),
        'description' => esc_html__('Configure footer content.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 50,
    ));

    $wp_customize->add_setting('footer_copyright', array(
        'default'           => sprintf(esc_html__('© %s Prompts Library. All rights reserved.', 'zzprompts'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_copyright', array(
        'label'       => esc_html__('Copyright Text', 'zzprompts'),
        'description' => esc_html__('Supports basic HTML like links.', 'zzprompts'),
        'section'     => 'zzprompts_footer_section',
        'type'        => 'textarea',
        'priority'    => 10,
    ));


    /* ========================================================
       SECTION: COLORS & BRANDING
       ======================================================== */
    $wp_customize->add_section('zzprompts_colors_section', array(
        'title'       => esc_html__('Colors & Branding', 'zzprompts'),
        'description' => esc_html__('Customize the theme color scheme.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 60,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#5c6ac4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'       => esc_html__('Primary Color', 'zzprompts'),
        'description' => esc_html__('Main brand color for buttons, links, and active states.', 'zzprompts'),
        'section'     => 'zzprompts_colors_section',
        'priority'    => 10,
    )));

    // Accent Color
    $wp_customize->add_setting('accent_color', array(
        'default'           => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'       => esc_html__('Accent Color', 'zzprompts'),
        'description' => esc_html__('Secondary color for badges, tags, and meta highlights.', 'zzprompts'),
        'section'     => 'zzprompts_colors_section',
        'priority'    => 20,
    )));

    // Text Color
    $wp_customize->add_setting('text_color', array(
        'default'           => '#1f2937',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label'       => esc_html__('Text Color', 'zzprompts'),
        'description' => esc_html__('Global body and heading text color.', 'zzprompts'),
        'section'     => 'zzprompts_colors_section',
        'priority'    => 30,
    )));

}
add_action('customize_register', 'zzprompts_customize_register');


/* ============================================================
   SANITIZATION FUNCTIONS
   ============================================================ */

/**
 * Sanitize Checkbox
 * Returns 1 for checked, empty string for unchecked.
 * Compatible with both theme_mod and option storage.
 * 
 * @param mixed $checked Whether the checkbox is checked.
 * @return int|string
 */
function zzprompts_sanitize_checkbox($checked) {
    return (!empty($checked) && $checked !== 'false') ? 1 : '';
}

/**
 * Sanitize Ad Code
 * Allows scripts if user has unfiltered_html capability.
 *
 * @param string $input Raw ad code
 * @return string Sanitized code
 */
function zzprompts_sanitize_ad_code($input) {
    if (current_user_can('unfiltered_html')) {
        return $input;
    }
    return wp_kses_post($input);
}

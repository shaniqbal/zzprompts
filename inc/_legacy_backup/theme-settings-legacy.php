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
   HELPER FUNCTIONS
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

/**
 * Get Footer Layout Style
 * 
 * Determines which footer layout to use based on current page type
 * Returns 'v1' (Classic) or 'v2' (Modern)
 * 
 * @return string 'v1' or 'v2'
 */
if (!function_exists('zzprompts_get_footer_layout')) {
    function zzprompts_get_footer_layout() {
    // Homepage / Front Page - Read from Customizer
    if (is_front_page() || is_home()) {
        return get_theme_mod('home_layout_select', 'v1');
    }
    
    // Blog Single Post
    if (is_singular('post')) {
        return get_theme_mod('blog_single_layout', 'v1');
    }
    
    // Blog Archive / Category / Tag / Author
    if (is_archive() && !is_post_type_archive('prompt') && !is_tax(array('prompt_category', 'ai_tool'))) {
        return get_theme_mod('blog_layout_select', 'v1');
    }
    
    // Single Prompt
    if (is_singular('prompt')) {
        return get_theme_mod('prompt_single_layout_mode', 'v1');
    }
    
    // Prompt Archive or Prompt Taxonomies
    if (is_post_type_archive('prompt') || is_tax(array('prompt_category', 'ai_tool'))) {
        return get_theme_mod('prompt_archive_layout', 'v1');
    }
    
    // Default fallback to Classic V1
    return 'v1';
    }
}

/* ============================================================
   LAYOUT CHECK FUNCTIONS (for active_callback)
   ============================================================ */

/**
 * Check if Homepage is V1
 */
function zzprompts_is_home_v1() {
    return get_theme_mod('home_layout_select', 'v1') === 'v1';
}

/**
 * Check if Homepage is V2
 */
function zzprompts_is_home_v2() {
    return get_theme_mod('home_layout_select', 'v1') === 'v2';
}

/**
 * Check if Blog Archive is V1
 */
function zzprompts_is_blog_archive_v1() {
    return get_theme_mod('blog_layout_select', 'v1') === 'v1';
}

/**
 * Check if Blog Archive is V2
 */
function zzprompts_is_blog_archive_v2() {
    return get_theme_mod('blog_layout_select', 'v1') === 'v2';
}

/**
 * Check if Blog Single is V1
 */
function zzprompts_is_blog_single_v1() {
    return get_theme_mod('blog_single_layout', 'v1') === 'v1';
}

/**
 * Check if Blog Single is V2
 */
function zzprompts_is_blog_single_v2() {
    return get_theme_mod('blog_single_layout', 'v1') === 'v2';
}

/**
 * Check if Prompt Archive is V1
 */
function zzprompts_is_prompt_archive_v1() {
    return get_theme_mod('prompt_archive_layout', 'v1') === 'v1';
}

/**
 * Check if Prompt Archive is V2
 */
function zzprompts_is_prompt_archive_v2() {
    return get_theme_mod('prompt_archive_layout', 'v1') === 'v2';
}

/**
 * Check if Single Prompt is V1
 */
function zzprompts_is_single_prompt_v1() {
    return get_theme_mod('prompt_single_layout_mode', 'v1') === 'v1';
}

/**
 * Check if Single Prompt is V2
 */
function zzprompts_is_single_prompt_v2() {
    return get_theme_mod('prompt_single_layout_mode', 'v1') === 'v2';
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
       SECTION: LAYOUT MANAGER
       Purpose: Let users choose layout style for each page type
       ======================================================== */
    $wp_customize->add_section('zzprompts_layout_manager', array(
        'title'       => esc_html__('Layout Manager', 'zzprompts'),
        'description' => esc_html__('Choose layout style for each page type. Settings below will update based on your selection.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 5,
    ));

    // Homepage Layout
    $wp_customize->add_setting('home_layout_select', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_layout_select', array(
        'label'       => esc_html__('Homepage Layout', 'zzprompts'),
        'description' => esc_html__('Classic = Colorful cards, Modern = Minimal clean design.', 'zzprompts'),
        'section'     => 'zzprompts_layout_manager',
        'type'        => 'select',
        'choices'     => array(
            'v1' => esc_html__('Classic', 'zzprompts'),
            'v2' => esc_html__('Modern', 'zzprompts'),
        ),
        'priority'    => 10,
    ));

    // Blog Archive Layout
    $wp_customize->add_setting('blog_layout_select', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_layout_select', array(
        'label'       => esc_html__('Blog Archive Layout', 'zzprompts'),
        'description' => esc_html__('Classic = Grid layout, Modern = Magazine with sidebar.', 'zzprompts'),
        'section'     => 'zzprompts_layout_manager',
        'type'        => 'select',
        'choices'     => array(
            'v1' => esc_html__('Classic', 'zzprompts'),
            'v2' => esc_html__('Modern', 'zzprompts'),
        ),
        'priority'    => 20,
    ));

    // Blog Single Layout
    $wp_customize->add_setting('blog_single_layout', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_single_layout', array(
        'label'       => esc_html__('Blog Single Post Layout', 'zzprompts'),
        'description' => esc_html__('Classic = With sidebar, Modern = Reader-focused (Medium-style).', 'zzprompts'),
        'section'     => 'zzprompts_layout_manager',
        'type'        => 'select',
        'choices'     => array(
            'v1' => esc_html__('Classic', 'zzprompts'),
            'v2' => esc_html__('Modern', 'zzprompts'),
        ),
        'priority'    => 30,
    ));

    // Prompt Archive Layout
    $wp_customize->add_setting('prompt_archive_layout', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('prompt_archive_layout', array(
        'label'       => esc_html__('Prompt Archive Layout', 'zzprompts'),
        'description' => esc_html__('Classic = Full width grid, Modern = With sidebar filters.', 'zzprompts'),
        'section'     => 'zzprompts_layout_manager',
        'type'        => 'select',
        'choices'     => array(
            'v1' => esc_html__('Classic', 'zzprompts'),
            'v2' => esc_html__('Modern', 'zzprompts'),
        ),
        'priority'    => 40,
    ));

    // Single Prompt Layout
    $wp_customize->add_setting('prompt_single_layout_mode', array(
        'default'           => 'v1',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('prompt_single_layout_mode', array(
        'label'       => esc_html__('Single Prompt Layout', 'zzprompts'),
        'description' => esc_html__('Classic = Dashboard style, Modern = Sidebar right.', 'zzprompts'),
        'section'     => 'zzprompts_layout_manager',
        'type'        => 'select',
        'choices'     => array(
            'v1' => esc_html__('Classic', 'zzprompts'),
            'v2' => esc_html__('Modern', 'zzprompts'),
        ),
        'priority'    => 50,
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
       HOMEPAGE V1: WHY THIS LIBRARY SECTION (Dynamic)
       ======================================================== */

    // Why This Library (Homepage V1) - Place directly in Homepage section
    // Section Heading
    $wp_customize->add_setting('why_section_heading', array(
        'default'           => 'Why This Library?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('why_section_heading', array(
        'label'    => esc_html__('Why This Library: Section Heading', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 61,
    ));

    // Feature 1
    $wp_customize->add_setting('why_feature1_title', array(
        'default'           => 'Curated Prompts',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('why_feature1_title', array(
        'label'    => esc_html__('Why This Library: Feature 1 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 62,
    ));
    $wp_customize->add_setting('why_feature1_desc', array(
        'default'           => 'Handpicked, high-quality prompts for every use case.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature1_desc', array(
        'label'    => esc_html__('Why This Library: Feature 1 Description', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'textarea',
        'priority' => 63,
    ));

    // Feature 2
    $wp_customize->add_setting('why_feature2_title', array(
        'default'           => 'Easy to Use',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('why_feature2_title', array(
        'label'    => esc_html__('Why This Library: Feature 2 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 64,
    ));
    $wp_customize->add_setting('why_feature2_desc', array(
        'default'           => 'Copy, paste, and get results instantly with any AI.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature2_desc', array(
        'label'    => esc_html__('Why This Library: Feature 2 Description', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'textarea',
        'priority' => 65,
    ));

    // Feature 3
    $wp_customize->add_setting('why_feature3_title', array(
        'default'           => 'Regular Updates',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('why_feature3_title', array(
        'label'    => esc_html__('Why This Library: Feature 3 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 66,
    ));
    $wp_customize->add_setting('why_feature3_desc', array(
        'default'           => 'New prompts and features added frequently.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature3_desc', array(
        'label'    => esc_html__('Why This Library: Feature 3 Description', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'textarea',
        'priority' => 67,
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
        'label'       => esc_html__('Show Copy Count on Prompt Cards (Modern Homepage)', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 50,
        'active_callback' => 'zzprompts_is_home_v2',
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

    // --- SIDEBAR CONTRIBUTOR CARD (Modern Layout Only) ---
    $wp_customize->add_setting('sidebar_contributor_show_icon', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_icon', array(
        'label'       => esc_html__('Show Contributor Icon (Modern Layout)', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 110,
        'active_callback' => 'zzprompts_is_single_prompt_v2',
    ));

    $wp_customize->add_setting('sidebar_contributor_show_name', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_name', array(
        'label'       => esc_html__('Show Contributor Name (Modern Layout)', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 120,
        'active_callback' => 'zzprompts_is_single_prompt_v2',
    ));

    $wp_customize->add_setting('sidebar_contributor_show_total', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_total', array(
        'label'       => esc_html__('Show Total Prompts Count (Modern Layout)', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 130,
        'active_callback' => 'zzprompts_is_single_prompt_v2',
    ));

    // --- PROMPT ARCHIVE SIDEBAR (Modern Layout Only) ---
    $wp_customize->add_setting('sidebar_filter_show_counts', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_filter_show_counts', array(
        'label'       => esc_html__('Show Filter Counts in Sidebar (Modern Layout)', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 140,
        'active_callback' => 'zzprompts_is_prompt_archive_v2',
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
       SECTION 3: BLOG SETTINGS
       Purpose: All blog-related controls
       ======================================================== */

    /* --------------------------------------------------------
       BLOG SUB-SECTION 1: Archive – Content & Layout
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_archive', array(
        'title'       => esc_html__('Blog Archive – Content', 'zzprompts'),
        'description' => esc_html__('Controls what appears on blog listing pages.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 30,
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
       BLOG SUB-SECTION 2: Sidebar (Archive Pages)
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_sidebar', array(
        'title'       => esc_html__('Blog Sidebar (Archive)', 'zzprompts'),
        'description' => esc_html__('Controls sidebar widgets on blog archive pages. Widgets render automatically without drag-drop. (Applies to Modern layout)', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 31,
        'active_callback' => 'zzprompts_is_blog_archive_v2',
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
       BLOG SUB-SECTION 3: Single Post
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_single', array(
        'title'       => esc_html__('Blog Single Post', 'zzprompts'),
        'description' => esc_html__('Controls for individual blog post pages.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 32,
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
        'label'       => esc_html__('Show Sidebar (Classic Layout)', 'zzprompts'),
        'description' => esc_html__('Only applies to Classic layout.', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 20,
        'active_callback' => 'zzprompts_is_blog_single_v1',
    ));

    $wp_customize->add_setting('sidebar_sticky_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_sticky_enabled', array(
        'label'       => esc_html__('Make Sidebar Sticky on Desktop (Classic Layout)', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 30,
        'active_callback' => 'zzprompts_is_blog_single_v1',
    ));

    // Kept for backward compat (used in templates internally)
    $wp_customize->add_setting('blog_sidebar_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));


    /* --------------------------------------------------------
       BLOG SUB-SECTION 4: Comments
       -------------------------------------------------------- */
    $wp_customize->add_section('zzprompts_blog_comments', array(
        'title'       => esc_html__('Blog Comments', 'zzprompts'),
        'description' => esc_html__('Native WordPress comment settings for blog posts. Disable website URL field to reduce spam.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 33,
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

    /* ========================================================
       SECTION: SOCIAL MEDIA LINKS
       ======================================================== */
    $wp_customize->add_section('zzprompts_social_section', array(
        'title'       => esc_html__('Social Media', 'zzprompts'),
        'description' => esc_html__('Add your social profile URLs. Icons will appear in Footer and Brand widgets.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 55,
    ));

    // Facebook
    $wp_customize->add_setting('social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_facebook', array(
        'label'       => esc_html__('Facebook URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // Twitter/X
    $wp_customize->add_setting('social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_twitter', array(
        'label'       => esc_html__('X (Twitter) URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_instagram', array(
        'label'       => esc_html__('Instagram URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_linkedin', array(
        'label'       => esc_html__('LinkedIn URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // YouTube
    $wp_customize->add_setting('social_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_youtube', array(
        'label'       => esc_html__('YouTube URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // GitHub
    $wp_customize->add_setting('social_github', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_github', array(
        'label'       => esc_html__('GitHub URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

    // Discord
    $wp_customize->add_setting('social_discord', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_discord', array(
        'label'       => esc_html__('Discord URL', 'zzprompts'),
        'section'     => 'zzprompts_social_section',
        'type'        => 'url',
    ));

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

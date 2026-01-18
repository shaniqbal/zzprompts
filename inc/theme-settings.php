<?php
/**
 * Theme Customizer Settings - Modern V1 Launch
 * 
 * CLEAN LAUNCH VERSION:
 * Only essential panels for Modern V1 launch.
 * Layout Manager and legacy options removed.
 * 
 * SECTIONS:
 * 1. Homepage – Hero content
 * 2. Prompts – Prompt CPT options
 * 3. Blog Archive – Content settings
 * 4. Blog Single – Single post settings
 * 5. Blog Comments – Comment settings
 * 6. Ad Management – Ad slots (separate file)
 * 7. Footer – Copyright
 * 8. Colors & Branding – Theme colors
 * 9. Social Media – Social links
 *
 * @package zzprompts
 * @version 4.0.0 - Modern V1 Clean Launch
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
 * Modern V1 Launch: Always returns 'modern-v1'
 * 
 * @return string Always 'modern-v1'
 */
if (!function_exists('zzprompts_get_footer_layout')) {
    function zzprompts_get_footer_layout() {
        // Modern V1 Launch: Fixed to modern layout
        return 'modern-v1';
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
        'description' => esc_html__('Configure all theme settings.', 'zzprompts'),
        'priority'    => 10,
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

    // Why This Library Section
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
        'label'    => esc_html__('Feature 1 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 62,
    ));
    $wp_customize->add_setting('why_feature1_desc', array(
        'default'           => 'Handpicked, high-quality prompts for every use case.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature1_desc', array(
        'label'    => esc_html__('Feature 1 Description', 'zzprompts'),
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
        'label'    => esc_html__('Feature 2 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 64,
    ));
    $wp_customize->add_setting('why_feature2_desc', array(
        'default'           => 'Copy, paste, and get results instantly with any AI.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature2_desc', array(
        'label'    => esc_html__('Feature 2 Description', 'zzprompts'),
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
        'label'    => esc_html__('Feature 3 Title', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'type'     => 'text',
        'priority' => 66,
    ));
    $wp_customize->add_setting('why_feature3_desc', array(
        'default'           => 'New prompts and features added frequently.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('why_feature3_desc', array(
        'label'    => esc_html__('Feature 3 Description', 'zzprompts'),
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
        'priority'    => 50,
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
        'priority'    => 60,
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
        'priority'    => 70,
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
        'priority'    => 80,
    ));

    // --- SIDEBAR OPTIONS (Modern V1) ---
    $wp_customize->add_setting('sidebar_contributor_show_icon', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_icon', array(
        'label'       => esc_html__('Show Contributor Icon in Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 90,
    ));

    /* ========================================================
       SECTION 2.5: PROMPT ARCHIVE SEO
       Purpose: Archive page intro text for SEO
       ======================================================== */
    
    // Archive SEO Title (H1)
    $wp_customize->add_setting('archive_seo_title', array(
        'default'           => esc_html__('AI Prompt Library', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('archive_seo_title', array(
        'label'       => esc_html__('Archive Page Heading', 'zzprompts'),
        'description' => esc_html__('Main H1 heading for prompt archive page.', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'text',
        'priority'    => 95,
    ));
    
    // Archive SEO Description (intro paragraph)
    $wp_customize->add_setting('archive_seo_description', array(
        'default'           => esc_html__('Discover our curated collection of AI prompts for ChatGPT, Midjourney, Claude, and more. Copy and use instantly.', 'zzprompts'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('archive_seo_description', array(
        'label'       => esc_html__('Archive Intro Text (SEO)', 'zzprompts'),
        'description' => esc_html__('Introductory paragraph shown above prompt grid. Good for SEO.', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'textarea',
        'priority'    => 96,
    ));

    $wp_customize->add_setting('sidebar_contributor_show_name', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_name', array(
        'label'       => esc_html__('Show Contributor Name in Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 100,
    ));

    $wp_customize->add_setting('sidebar_contributor_show_total', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_contributor_show_total', array(
        'label'       => esc_html__('Show Total Prompts Count in Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 110,
    ));

    $wp_customize->add_setting('sidebar_filter_show_counts', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_filter_show_counts', array(
        'label'       => esc_html__('Show Filter Counts in Archive Sidebar', 'zzprompts'),
        'section'     => 'zzprompts_prompt_settings',
        'type'        => 'checkbox',
        'priority'    => 120,
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
        'priority'    => 130,
    ));


    /* ========================================================
       SECTION 3: BLOG ARCHIVE - Content
       ======================================================== */
    $wp_customize->add_section('zzprompts_blog_archive', array(
        'title'       => esc_html__('Blog Archive', 'zzprompts'),
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


    /* ========================================================
       SECTION 4: BLOG SINGLE POST
       ======================================================== */
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

    $wp_customize->add_setting('single_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_author', array(
        'label'       => esc_html__('Show Author', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('single_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_date', array(
        'label'       => esc_html__('Show Date', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    $wp_customize->add_setting('single_show_reading_time', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_reading_time', array(
        'label'       => esc_html__('Show Reading Time', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 40,
    ));

    $wp_customize->add_setting('single_show_share_buttons', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_share_buttons', array(
        'label'       => esc_html__('Show Share Buttons', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 50,
    ));

    $wp_customize->add_setting('single_show_tags', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('single_show_tags', array(
        'label'       => esc_html__('Show Tags', 'zzprompts'),
        'section'     => 'zzprompts_blog_single',
        'type'        => 'checkbox',
        'priority'    => 60,
    ));


    /* ========================================================
       SECTION 5: BLOG COMMENTS
       ======================================================== */
    $wp_customize->add_section('zzprompts_blog_comments', array(
        'title'       => esc_html__('Blog Comments', 'zzprompts'),
        'description' => esc_html__('Native WordPress comment settings for blog posts.', 'zzprompts'),
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
       SECTION 6: FOOTER SETTINGS
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
       SECTION 7: COLORS & BRANDING
       ======================================================== */
    $wp_customize->add_section('zzprompts_colors_section', array(
        'title'       => esc_html__('Colors & Branding', 'zzprompts'),
        'description' => esc_html__('Customize the theme color scheme.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 60,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#6366F1',
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
       SECTION 8: SOCIAL MEDIA LINKS
       ======================================================== */
    $wp_customize->add_section('zzprompts_social_section', array(
        'title'       => esc_html__('Social Media', 'zzprompts'),
        'description' => esc_html__('Add your social profile URLs. Icons appear in Footer and Brand widgets.', 'zzprompts'),
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
 * 
 * @param mixed $checked Whether the checkbox is checked.
 * @return int|string
 */
function zzprompts_sanitize_checkbox($checked) {
    return (!empty($checked) && $checked !== 'false') ? 1 : '';
}


/* ============================================================
   REMOVED SECTIONS (Clean Launch)
   ============================================================
   
   The following legacy sections have been removed for clean launch.
   See: inc/_legacy_backup/theme-settings-legacy.php for reference
   
   1. Layout Manager Section (removed layout switching)
   2. Layout Check Functions (removed active_callback layout checks)
   3. Classic Layout Controls (sidebar toggles)
   
   ============================================================ */

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
 * 6. Footer – Copyright
 * 7. Contact Page – CF7 shortcode
 * 8. Colors & Branding – Theme colors
 * 9. Social Media – Social links
 *
 * @package zzprompts
 * @version 4.1.0 - Audit Cleanup
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
        'title'       => esc_html__('ZZ Prompts Settings', 'zzprompts'),
        'description' => sprintf(
            '<div style="padding: 15px; background: #fff; border: 1px solid #ddd; border-left: 4px solid #6366F1; border-radius: 4px;">' .
            '<p style="margin-top:0;"><strong>🚀 %s</strong></p>' .
            '<p>%s</p>' .
            '<p style="margin-bottom:0;"><strong>💡 %s:</strong> %s</p>' .
            '</div>',
            esc_html__('Welcome to ZZ Prompts!', 'zzprompts'),
            esc_html__('Use these settings to customize your AI library. Most changes will show up instantly in the preview on the right.', 'zzprompts'),
            esc_html__('Beginner Tip', 'zzprompts'),
            esc_html__('You can turn off any homepage section below if you want a simpler, faster site.', 'zzprompts')
        ),
        'priority'    => 10,
    ));


    /* ========================================================
       SECTION 1: HOMEPAGE SETTINGS
       Purpose: Homepage hero area and section management
       ======================================================== */
    $wp_customize->add_section('zzprompts_hero_section', array(
        'title'       => esc_html__('🏠 Homepage', 'zzprompts'),
        'description' => esc_html__('Manage homepage content and section visibility. Tip: You can turn off sections you don\'t need for a cleaner look.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 10,
    ));

    // --- HERO SUB-SECTION ---
    $wp_customize->add_setting('zz_hr_hero', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_hero', array(
        'label'    => esc_html__('Hero Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 5,
    )));

    $wp_customize->add_setting('show_hero_section', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_hero_section', array(
        'label'       => esc_html__('Enable Hero Section', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 6,
    ));

    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default'           => esc_html__('Instant AI Prompts for ChatGPT, Midjourney & More', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
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
        'transport'         => 'refresh',
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

    // --- PROMPTS SECTION ---
    $wp_customize->add_setting('zz_hr_prompts', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_prompts', array(
        'label'    => esc_html__('Latest Prompts Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 40,
    )));

    $wp_customize->add_setting('show_home_prompts', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_home_prompts', array(
        'label'       => esc_html__('Enable Prompts Grid', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 41,
    ));

    $wp_customize->add_setting('home_prompts_count', array(
        'default'           => 8,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_prompts_count', array(
        'label'       => esc_html__('Number of Prompts to Show', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 4, 'max' => 24, 'step' => 4),
        'priority'    => 42,
    ));

    // --- HOW IT WORKS SECTION ---
    $wp_customize->add_setting('zz_hr_how', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_how', array(
        'label'    => esc_html__('How It Works Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 50,
    )));

    $wp_customize->add_setting('show_home_how', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_home_how', array(
        'label'       => esc_html__('Enable How It Works', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 51,
    ));

    $wp_customize->add_setting('home_how_title', array(
        'default'           => esc_html__('How It Works', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_how_title', array(
        'label'       => esc_html__('Section Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 52,
    ));

    $wp_customize->add_setting('home_how_subtitle', array(
        'default'           => esc_html__('Get started in three simple steps', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_how_subtitle', array(
        'label'       => esc_html__('Section Subtitle', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 53,
    ));

    // --- FEATURES SECTION ---
    $wp_customize->add_setting('zz_hr_features', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_features', array(
        'label'    => esc_html__('Why Choose Us Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 60,
    )));

    $wp_customize->add_setting('show_home_features', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_home_features', array(
        'label'       => esc_html__('Enable Features Section', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 61,
    ));

    $wp_customize->add_setting('home_features_title', array(
        'default'           => esc_html__('Why Choose Our Prompts?', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_features_title', array(
        'label'       => esc_html__('Section Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 62,
    ));

    // --- BLOG SECTION ---
    $wp_customize->add_setting('zz_hr_blog', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_blog', array(
        'label'    => esc_html__('Latest Articles Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 70,
    )));

    $wp_customize->add_setting('show_home_blog', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_home_blog', array(
        'label'       => esc_html__('Enable Blog Section', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 71,
    ));

    $wp_customize->add_setting('home_blog_title', array(
        'default'           => esc_html__('Latest Articles', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_blog_title', array(
        'label'       => esc_html__('Section Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 72,
    ));

    $wp_customize->add_setting('home_blog_subtitle', array(
        'default'           => esc_html__('Tips, tutorials, and AI insights', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_blog_subtitle', array(
        'label'       => esc_html__('Section Subtitle', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 73,
    ));

    // --- CTA SECTION ---
    $wp_customize->add_setting('zz_hr_cta', array('sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new ZZ_Customize_Header_Control($wp_customize, 'zz_hr_cta', array(
        'label'    => esc_html__('Bottom CTA Section', 'zzprompts'),
        'section'  => 'zzprompts_hero_section',
        'priority' => 80,
    )));

    $wp_customize->add_setting('show_home_cta', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('show_home_cta', array(
        'label'       => esc_html__('Enable CTA Section', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'checkbox',
        'priority'    => 81,
    ));

    $wp_customize->add_setting('home_cta_title', array(
        'default'           => esc_html__('Ready to Supercharge Your AI Workflow?', 'zzprompts'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_cta_title', array(
        'label'       => esc_html__('CTA Title', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'text',
        'priority'    => 82,
    ));

    $wp_customize->add_setting('home_cta_subtitle', array(
        'default'           => esc_html__('Join thousands of professionals using our curated prompts to save time and boost productivity.', 'zzprompts'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_cta_subtitle', array(
        'label'       => esc_html__('CTA Subtitle', 'zzprompts'),
        'section'     => 'zzprompts_hero_section',
        'type'        => 'textarea',
        'priority'    => 83,
    ));


    /* ========================================================
       SECTION 1.5: LAYOUT & SPACING
       Purpose: Header gap and Hero internal padding
       ======================================================== */
    $wp_customize->add_section('zzprompts_layout_section', array(
        'title'       => esc_html__('📐 Layout & Spacing', 'zzprompts'),
        'description' => esc_html__('Manage gaps between sections and internal padding for a professional look.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 15,
    ));

    // Header Bottom Margin (Gap to Hero)
    $wp_customize->add_setting('header_margin_bottom', array(
        'default'           => '0',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('header_margin_bottom', array(
        'label'       => esc_html__('Header Bottom Gap (px)', 'zzprompts'),
        'description' => esc_html__('Distance between header and hero. Modern standard is 0px.', 'zzprompts'),
        'section'     => 'zzprompts_layout_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 100, 'step' => 1),
        'priority'    => 10,
    ));

    // Hero Top Padding (Internal)
    $wp_customize->add_setting('hero_padding_top', array(
        'default'           => '48',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_padding_top', array(
        'label'       => esc_html__('Hero Internal Top Padding (px)', 'zzprompts'),
        'description' => esc_html__('Space inside the hero top. Recommended: 40px - 80px.', 'zzprompts'),
        'section'     => 'zzprompts_layout_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 200, 'step' => 1),
        'priority'    => 20,
    ));

    // Hero Bottom Padding (Internal)
    $wp_customize->add_setting('hero_padding_bottom', array(
        'default'           => '48',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_padding_bottom', array(
        'label'       => esc_html__('Hero Internal Bottom Padding (px)', 'zzprompts'),
        'description' => esc_html__('Space inside the hero bottom.', 'zzprompts'),
        'section'     => 'zzprompts_layout_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 200, 'step' => 1),
        'priority'    => 30,
    ));

    // Home Prompts Top Padding (Gap between Hero and Cards)
    $wp_customize->add_setting('home_prompts_padding_top', array(
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_prompts_padding_top', array(
        'label'       => esc_html__('Gap below Hero (Desktop)', 'zzprompts'),
        'description' => esc_html__('Space between category pills and prompt cards on desktop.', 'zzprompts'),
        'section'     => 'zzprompts_layout_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 100, 'step' => 1),
        'priority'    => 40,
    ));


    /* ========================================================
       SECTION 2: PROMPT SETTINGS
       Purpose: Prompt CPT specific options
       ======================================================== */
    $wp_customize->add_section('zzprompts_prompt_settings', array(
        'title'       => esc_html__('⚡ Prompts', 'zzprompts'),
        'description' => esc_html__('Configure prompt display, copy button, likes, and meta options.', 'zzprompts'),
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

    // REMOVED: enable_copy_counter (duplicate of single_prompt_meta_show_copies)

    $wp_customize->add_setting('sidebar_filter_show_counts', array(
        'default'           => false,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('sidebar_filter_show_counts', array(
        'label'       => esc_html__('Show Filter Counts in Archive Sidebar', 'zzprompts'),
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

    // REMOVED: Show Views in Meta (not implementing views yet)

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
       SECTION 3: BLOG ARCHIVE
       Purpose: Configure blog listing/archive pages
       ======================================================== */
    $wp_customize->add_section('zzprompts_blog_archive', array(
        'title'       => esc_html__('📰 Blog Archive', 'zzprompts'),
        'description' => esc_html__('Configure blog listing pages: cards, sidebar, and pagination.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 30,
    ));

    // Show Featured Image
    $wp_customize->add_setting('blog_show_image', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_image', array(
        'label'       => esc_html__('Show Featured Image', 'zzprompts'),
        'description' => esc_html__('Display featured images on blog cards.', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    // Show Category Badge
    $wp_customize->add_setting('blog_show_category', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_category', array(
        'label'       => esc_html__('Show Category', 'zzprompts'),
        'description' => esc_html__('Display category name in card meta.', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));

    // Show Sidebar
    $wp_customize->add_setting('blog_show_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'zzprompts_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_show_sidebar', array(
        'label'       => esc_html__('Show Sidebar', 'zzprompts'),
        'description' => esc_html__('Display sidebar with search, categories, and popular posts.', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    // Excerpt Length
    $wp_customize->add_setting('blog_excerpt_length', array(
        'default'           => 20,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('blog_excerpt_length', array(
        'label'       => esc_html__('Excerpt Length (Words)', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'number',
        'input_attrs' => array('min' => 5, 'max' => 100, 'step' => 1),
        'priority'    => 40,
    ));

    // Read More Text
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

    // Posts Per Page
    $wp_customize->add_setting('zzprompts_blog_posts_per_page', array(
        'default'           => 10,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('zzprompts_blog_posts_per_page', array(
        'label'       => esc_html__('Posts Per Page', 'zzprompts'),
        'description' => esc_html__('Number of posts per archive page.', 'zzprompts'),
        'section'     => 'zzprompts_blog_archive',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 50, 'step' => 1),
        'priority'    => 60,
    ));


    /* ========================================================
       SECTION 4: BLOG SINGLE POST
       ======================================================== */
    $wp_customize->add_section('zzprompts_blog_single', array(
        'title'       => esc_html__('📝 Blog Single Post', 'zzprompts'),
        'description' => esc_html__('Configure individual blog post pages: image, author, date, and share buttons.', 'zzprompts'),
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
        'title'       => esc_html__('💬 Comments', 'zzprompts'),
        'description' => esc_html__('Control comment display and functionality on blog posts.', 'zzprompts'),
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
       Purpose: Footer copyright, contact info for widgets
       ======================================================== */
    $wp_customize->add_section('zzprompts_footer_section', array(
        'title'       => esc_html__('🔻 Footer', 'zzprompts'),
        'description' => esc_html__('Configure footer copyright and contact information.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 50,
    ));

    // Copyright Text
    $wp_customize->add_setting('footer_copyright', array(
        'default'           => sprintf(esc_html__('© %s Prompts Library. All rights reserved.', 'zzprompts'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('footer_copyright', array(
        'label'       => esc_html__('Copyright Text', 'zzprompts'),
        'description' => esc_html__('Supports HTML links. Save & refresh to see changes.', 'zzprompts'),
        'section'     => 'zzprompts_footer_section',
        'type'        => 'textarea',
        'priority'    => 10,
    ));

    // Contact Email
    $wp_customize->add_setting('footer_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('footer_email', array(
        'label'       => esc_html__('Contact Email', 'zzprompts'),
        'description' => esc_html__('Displayed in footer widgets with envelope icon.', 'zzprompts'),
        'section'     => 'zzprompts_footer_section',
        'type'        => 'email',
        'priority'    => 20,
    ));

    // Contact Location
    $wp_customize->add_setting('footer_location', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('footer_location', array(
        'label'       => esc_html__('Location / Address', 'zzprompts'),
        'description' => esc_html__('Displayed in footer widgets with location icon.', 'zzprompts'),
        'section'     => 'zzprompts_footer_section',
        'type'        => 'text',
        'priority'    => 30,
    ));


    /* ========================================================
       SECTION 6.5: CONTACT PAGE
       Purpose: Contact Form 7 integration
       ======================================================== */
    $wp_customize->add_section('zzprompts_contact_section', array(
        'title'       => esc_html__('📧 Contact Page', 'zzprompts'),
        'description' => esc_html__('Integrate Contact Form 7 with your contact page.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 52,
    ));

    // Contact Form 7 Shortcode
    $wp_customize->add_setting('zz_contact_form_shortcode', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('zz_contact_form_shortcode', array(
        'label'       => esc_html__('Contact Form 7 Shortcode', 'zzprompts'),
        'description' => esc_html__('Paste your CF7 shortcode here. Example: [contact-form-7 id="123" title="Contact"]', 'zzprompts'),
        'section'     => 'zzprompts_contact_section',
        'type'        => 'text',
        'priority'    => 10,
    ));


    /* ========================================================
       SECTION 7: COLORS & BRANDING
       ======================================================== */
    $wp_customize->add_section('zzprompts_colors_section', array(
        'title'       => esc_html__('🎨 Colors & Branding', 'zzprompts'),
        'description' => esc_html__('Customize the theme color scheme. Changes apply to buttons, links, badges, and UI elements.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 55,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#6366F1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'       => esc_html__('Primary Color', 'zzprompts'),
        'description' => esc_html__('Main brand color for buttons, links, active states, and hover effects.', 'zzprompts'),
        'section'     => 'zzprompts_colors_section',
        'priority'    => 10,
    )));

    // Accent Color
    $wp_customize->add_setting('accent_color', array(
        'default'           => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'       => esc_html__('Accent / Success Color', 'zzprompts'),
        'description' => esc_html__('Used for success states, copy confirmations, and secondary highlights.', 'zzprompts'),
        'section'     => 'zzprompts_colors_section',
        'priority'    => 20,
    )));

    // NOTE: Text Color removed - breaks light/dark mode switching.
    // Text colors are controlled by CSS variables in _variables.css, _light.css, _dark.css


    /* ========================================================
       SECTION 8: SOCIAL MEDIA LINKS
       ======================================================== */
    $wp_customize->add_section('zzprompts_social_section', array(
        'title'       => esc_html__('🔗 Social Media', 'zzprompts'),
        'description' => esc_html__('Add your social profile URLs. Icons appear in footer and Brand widgets.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 60,
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


/**
 * Custom Header Control for Customizer
 * Creates a bold title to separate sections within a panel.
 */
if (class_exists('WP_Customize_Control')) {
    class ZZ_Customize_Header_Control extends WP_Customize_Control {
        public $type = 'zz_header';

        public function render_content() {
            if (!empty($this->label)) : ?>
                <span class="customize-control-title" style="margin-top: 30px; margin-bottom: 10px; padding: 12px; background: #f0f0f1; border-left: 4px solid #6366F1; font-weight: 800; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; color: #1e293b; display: block;">
                    <?php echo esc_html($this->label); ?>
                </span>
            <?php endif;
            if (!empty($this->description)) : ?>
                <span class="description customize-control-description" style="margin-bottom: 15px; display: block; font-style: italic;">
                    <?php echo esc_html($this->description); ?>
                </span>
            <?php endif;
        }
    }
}


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
    return ((isset($checked) && true == $checked) ? true : false);
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

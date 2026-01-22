<?php
/**
 * Ad Infrastructure - Smart Customizer Integration
 * 
 * Organized ad management with grouped sections and enable/disable toggles.
 * Supports AdSense, custom HTML, JavaScript, and iframe embeds.
 * 
 * @package zzprompts
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

/* =========================================
   1. REGISTER CUSTOMIZER SECTIONS & CONTROLS
   ========================================= */

function zz_ads_customizer_register($wp_customize) {
    
    // =========================================================================
    // 🎯 AD MANAGEMENT PANEL
    // =========================================================================
    $wp_customize->add_panel('zz_ad_panel', array(
        'title'       => '🎯 ' . __('Ad Management', 'zzprompts'),
        'description' => __('Manage all ad locations. Toggle ads on/off and paste your ad codes (AdSense, HTML, JavaScript).', 'zzprompts'),
        'priority'    => 200,
    ));
    
    // =========================================================================
    // 📍 SECTION 1: GLOBAL ADS
    // =========================================================================
    $wp_customize->add_section('zz_ads_global', array(
        'title'       => '📍 ' . __('Global Ads', 'zzprompts'),
        'description' => __('Ads that appear site-wide (on all pages).', 'zzprompts'),
        'panel'       => 'zz_ad_panel',
        'priority'    => 10,
    ));
    
    // Header Ad - Enable Toggle
    $wp_customize->add_setting('zz_ad_header_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_header_enable', array(
        'label'       => __('Enable Header Ad', 'zzprompts'),
        'description' => __('📌 Location: Below navigation bar (all pages except homepage).', 'zzprompts'),
        'section'     => 'zz_ads_global',
        'type'        => 'checkbox',
    ));
    
    // Header Ad - Code
    $wp_customize->add_setting('zz_ad_header_code', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_header_code', array(
        'label'           => __('Header Ad Code', 'zzprompts'),
        'description'     => __('💡 Tip: Use 728x90 banner size for best results.', 'zzprompts'),
        'section'         => 'zz_ads_global',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_header_enable', false);
        },
    ));
    
    // =========================================================================
    // 💬 SECTION 2: PROMPT PAGE ADS
    // =========================================================================
    $wp_customize->add_section('zz_ads_prompts', array(
        'title'       => '💬 ' . __('Prompt Page Ads', 'zzprompts'),
        'description' => __('Ads for single prompt pages and prompt archives.', 'zzprompts'),
        'panel'       => 'zz_ad_panel',
        'priority'    => 20,
    ));
    
    // Prompt Top - Enable Toggle
    $wp_customize->add_setting('zz_ad_prompt_top_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_prompt_top_enable', array(
        'label'       => __('Enable "Before Prompt Terminal" Ad', 'zzprompts'),
        'description' => __('📌 Location: Above the prompt code box on single prompt pages.', 'zzprompts'),
        'section'     => 'zz_ads_prompts',
        'type'        => 'checkbox',
    ));
    
    // Prompt Top - Code
    $wp_customize->add_setting('zz_ad_prompt_top', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_prompt_top', array(
        'label'           => __('Before Terminal Ad Code', 'zzprompts'),
        'section'         => 'zz_ads_prompts',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_prompt_top_enable', false);
        },
    ));
    
    // Prompt Bottom - Enable Toggle
    $wp_customize->add_setting('zz_ad_prompt_bottom_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_prompt_bottom_enable', array(
        'label'       => __('Enable "After Prompt Terminal" Ad', 'zzprompts'),
        'description' => __('📌 Location: Below the prompt code box on single prompt pages.', 'zzprompts'),
        'section'     => 'zz_ads_prompts',
        'type'        => 'checkbox',
    ));
    
    // Prompt Bottom - Code
    $wp_customize->add_setting('zz_ad_prompt_bottom', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_prompt_bottom', array(
        'label'           => __('After Terminal Ad Code', 'zzprompts'),
        'section'         => 'zz_ads_prompts',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_prompt_bottom_enable', false);
        },
    ));
    
    // Archive Inline - Enable Toggle
    $wp_customize->add_setting('zz_ad_archive_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_archive_enable', array(
        'label'       => __('Enable "Archive Grid Inline" Ad', 'zzprompts'),
        'description' => __('📌 Location: Appears between prompt cards on archive page (every 6 items).', 'zzprompts'),
        'section'     => 'zz_ads_prompts',
        'type'        => 'checkbox',
    ));
    
    // Archive Inline - Code
    $wp_customize->add_setting('zz_ad_archive_content', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_archive_content', array(
        'label'           => __('Archive Inline Ad Code', 'zzprompts'),
        'section'         => 'zz_ads_prompts',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_archive_enable', false);
        },
    ));
    
    // =========================================================================
    // 📰 SECTION 3: BLOG POST ADS
    // =========================================================================
    $wp_customize->add_section('zz_ads_blog', array(
        'title'       => '📰 ' . __('Blog Post Ads', 'zzprompts'),
        'description' => __('Ads for single blog posts.', 'zzprompts'),
        'panel'       => 'zz_ad_panel',
        'priority'    => 30,
    ));
    
    // Blog Top - Enable Toggle
    $wp_customize->add_setting('zz_ad_blog_top_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_blog_top_enable', array(
        'label'       => __('Enable "Before Post Content" Ad', 'zzprompts'),
        'description' => __('📌 Location: Top of blog post content (after title).', 'zzprompts'),
        'section'     => 'zz_ads_blog',
        'type'        => 'checkbox',
    ));
    
    // Blog Top - Code
    $wp_customize->add_setting('zz_ad_blog_top', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_blog_top', array(
        'label'           => __('Before Content Ad Code', 'zzprompts'),
        'section'         => 'zz_ads_blog',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_blog_top_enable', false);
        },
    ));
    
    // Blog Bottom - Enable Toggle
    $wp_customize->add_setting('zz_ad_blog_bottom_enable', array(
        'default'           => false,
        'type'              => 'option',
        'sanitize_callback' => 'zz_sanitize_checkbox',
    ));
    $wp_customize->add_control('zz_ad_blog_bottom_enable', array(
        'label'       => __('Enable "After Post Content" Ad', 'zzprompts'),
        'description' => __('📌 Location: Bottom of blog post content.', 'zzprompts'),
        'section'     => 'zz_ads_blog',
        'type'        => 'checkbox',
    ));
    
    // Blog Bottom - Code
    $wp_customize->add_setting('zz_ad_blog_bottom', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'unfiltered_html',
        'sanitize_callback' => 'zz_sanitize_ad_code',
    ));
    $wp_customize->add_control('zz_ad_blog_bottom', array(
        'label'           => __('After Content Ad Code', 'zzprompts'),
        'section'         => 'zz_ads_blog',
        'type'            => 'textarea',
        'input_attrs'     => array(
            'placeholder' => __('Paste ad code here...', 'zzprompts'),
            'rows'        => 4,
        ),
        'active_callback' => function() {
            return (bool) get_option('zz_ad_blog_bottom_enable', false);
        },
    ));
    
    // =========================================================================
    // 📌 SECTION 4: SIDEBAR AD INFO
    // =========================================================================
    $wp_customize->add_section('zz_ads_sidebar', array(
        'title'       => '📌 ' . __('Sidebar Ad', 'zzprompts'),
        'description' => sprintf(
            /* translators: %s: Link to widgets page */
            __('Sidebar ads are managed via widgets. Go to %s and add the "ZZ: Ad Banner" widget to any sidebar.', 'zzprompts'),
            '<a href="' . esc_url(admin_url('widgets.php')) . '">' . __('Appearance → Widgets', 'zzprompts') . '</a>'
        ),
        'panel'       => 'zz_ad_panel',
        'priority'    => 40,
    ));
    
    // Info-only setting (no actual control needed, just for section to appear)
    $wp_customize->add_setting('zz_ad_sidebar_info', array(
        'default'           => '',
        'sanitize_callback' => '__return_empty_string',
    ));
    $wp_customize->add_control('zz_ad_sidebar_info', array(
        'label'       => __('ℹ️ Widget-Based Ad', 'zzprompts'),
        'description' => __('Use the ZZ: Ad Banner widget in any sidebar or footer column. Code pasted there will render with proper styling.', 'zzprompts'),
        'section'     => 'zz_ads_sidebar',
        'type'        => 'hidden',
    ));
}
add_action('customize_register', 'zz_ads_customizer_register', 20);

/* =========================================
   2. SANITIZATION FUNCTIONS
   ========================================= */

/**
 * Sanitize checkbox
 */
function zz_sanitize_checkbox($input) {
    return (bool) $input;
}

/**
 * Sanitize Ad Code
 * Allow unfiltered HTML for administrators only
 */
function zz_sanitize_ad_code($input) {
    if (current_user_can('unfiltered_html')) {
        return $input;
    }
    return wp_strip_all_tags($input);
}

/* =========================================
   3. RENDER AD HELPER FUNCTIONS
   ========================================= */

/**
 * Render Ad Slot (With Enable Check)
 * 
 * Usage: <?php zz_render_ad('prompt_top'); ?>
 * 
 * @param string $location Ad slot location key
 * @return void
 */
function zz_render_ad($location) {
    // Check if ad is enabled
    $enable_key = 'zz_ad_' . $location . '_enable';
    $is_enabled = (bool) get_option($enable_key, false);
    
    // Special handling for legacy keys (header uses different naming)
    if ($location === 'header_code') {
        $is_enabled = (bool) get_option('zz_ad_header_enable', false);
        $ad_code = get_option('zz_ad_header_code', '');
    } else {
        $ad_code = get_option('zz_ad_' . $location, '');
    }
    
    if (!$is_enabled || empty($ad_code)) {
        return;
    }
    
    $safe_location = sanitize_html_class($location);
    ?>
    <div class="zz-ad-slot zz-ad-slot--<?php echo esc_attr($safe_location); ?>">
        <span class="zz-ad-slot__label"><?php esc_html_e('Advertisement', 'zzprompts'); ?></span>
        <?php 
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $ad_code; 
        ?>
    </div>
    <?php
}

/**
 * Get Ad Code (Without Rendering)
 * 
 * @param string $location Ad slot location key
 * @return string Ad code or empty string
 */
function zz_get_ad($location) {
    $enable_key = 'zz_ad_' . $location . '_enable';
    $is_enabled = (bool) get_option($enable_key, false);
    
    if (!$is_enabled) {
        return '';
    }
    
    return get_option('zz_ad_' . $location, '');
}

/**
 * Check if ad is enabled
 * 
 * @param string $location Ad slot location key
 * @return bool
 */
function zz_is_ad_enabled($location) {
    return (bool) get_option('zz_ad_' . $location . '_enable', false);
}

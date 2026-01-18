<?php
/**
 * Ad Infrastructure - Customizer Integration
 * 
 * Centralized ad management in Theme Options (Customizer).
 * Supports AdSense, custom HTML, JavaScript, and iframe embeds.
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/* =========================================
   1. REGISTER CUSTOMIZER SECTION & CONTROLS
   ========================================= */

function zz_ads_customizer_register($wp_customize) {
    
    // Add Ads Section under Theme Options Panel
    $wp_customize->add_section('zz_ads_section', array(
        'title'       => __('Ad Management', 'zzprompts'),
        'description' => __('Paste your ad codes (AdSense, HTML, JavaScript). Admins can use script tags.', 'zzprompts'),
        'panel'       => 'zzprompts_options',
        'priority'    => 200,
    ));
    
    // Ad Slots Configuration
    $ad_slots = array(
        'header_code'   => array(
            'label' => __('Header Ad (Below Navigation)', 'zzprompts'),
            'desc'  => __('Displays below nav bar on all pages except Homepage.', 'zzprompts'),
        ),
        'prompt_top'    => array(
            'label' => __('Prompt: Before Terminal', 'zzprompts'),
            'desc'  => __('Above the prompt box on single prompt pages.', 'zzprompts'),
        ),
        'prompt_bottom' => array(
            'label' => __('Prompt: After Terminal', 'zzprompts'),
            'desc'  => __('Below the prompt box on single prompt pages.', 'zzprompts'),
        ),
        'blog_top'      => array(
            'label' => __('Blog: Before Content', 'zzprompts'),
            'desc'  => __('Top of blog single posts.', 'zzprompts'),
        ),
        'blog_bottom'   => array(
            'label' => __('Blog: After Content', 'zzprompts'),
            'desc'  => __('Bottom of blog single posts.', 'zzprompts'),
        ),
        'sidebar'       => array(
            'label' => __('Sidebar Ad', 'zzprompts'),
            'desc'  => __('Use ZZ: Ad Banner widget to display this.', 'zzprompts'),
        ),
        'archive_content' => array(
            'label' => __('Prompt Archive: Inline Ad', 'zzprompts'),
            'desc'  => __('Appears after a specific number of cards on the archive page.', 'zzprompts'),
        ),
    );
    
    foreach ($ad_slots as $key => $slot) {
        $setting_id = 'zz_ad_' . $key;
        
        // Setting
        $wp_customize->add_setting($setting_id, array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'unfiltered_html',
            'sanitize_callback' => 'zz_sanitize_ad_code',
        ));
        
        // Control
        $wp_customize->add_control($setting_id, array(
            'label'       => $slot['label'],
            'description' => $slot['desc'],
            'section'     => 'zz_ads_section',
            'type'        => 'textarea',
            'input_attrs' => array(
                'placeholder' => __('Paste ad code here...', 'zzprompts'),
                'rows'        => 4,
                'style'       => 'font-family: monospace; font-size: 12px;',
            ),
        ));
    }
}
add_action('customize_register', 'zz_ads_customizer_register', 20);

/* =========================================
   2. SANITIZE AD CODE
   ========================================= */

function zz_sanitize_ad_code($input) {
    // Allow unfiltered HTML for administrators only
    if (current_user_can('unfiltered_html')) {
        return $input;
    }
    return wp_strip_all_tags($input);
}

/* =========================================
   3. RENDER AD HELPER FUNCTION
   ========================================= */

/**
 * Render Ad Slot
 * 
 * Usage: <?php zz_render_ad('prompt_top'); ?>
 * 
 * @param string $location Ad slot location key
 * @return void
 */
function zz_render_ad($location) {
    $ad_code = get_option('zz_ad_' . $location, '');
    
    if (empty($ad_code)) {
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
    return get_option('zz_ad_' . $location, '');
}

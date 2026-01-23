<?php
/**
 * One Click Demo Import Configuration
 * 
 * Configures the One Click Demo Import plugin for ZZ Prompts theme.
 * Handles demo content, widget settings, and customizer options.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Define demo import files.
 * 
 * @return array Array of demo import configurations.
 */
function zzprompts_ocdi_import_files() {
    return array(
        array(
            'import_file_name'           => 'ZZ Prompts Demo',
            'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-content.xml',
            'import_widget_file_url'     => get_template_directory_uri() . '/demo-content/widgets.wie',
            'import_customizer_file_url' => get_template_directory_uri() . '/demo-content/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/screenshot.png',
            'preview_url'                => 'https://demo.zztechlabs.com/zzprompts/',
            'import_notice'              => esc_html__('After import, go to Settings > Reading to set your Homepage and Posts page. Then go to Appearance > Menus to set up your navigation menus.', 'zzprompts'),
        ),
    );
}
add_filter('ocdi/import_files', 'zzprompts_ocdi_import_files');

/**
 * After import setup - set front page, blog page, menus, etc.
 *
 * @param array $selected_import Array with selected import data.
 */
function zzprompts_ocdi_after_import_setup($selected_import) {
    
    // Set Homepage (front page).
    $homepage = get_page_by_title('Home');
    if (!$homepage) {
        $homepage = get_page_by_title('Homepage');
    }
    
    if ($homepage) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID);
    }

    // Set Blog Page.
    $blog_page = get_page_by_title('Blog');
    if ($blog_page) {
        update_option('page_for_posts', $blog_page->ID);
    }

    // Assign menus to locations.
    $main_menu = get_term_by('name', 'Main Menu', 'nav_menu');
    if (!$main_menu) {
        $main_menu = get_term_by('name', 'Primary Menu', 'nav_menu');
    }
    
    $footer_menu = get_term_by('name', 'Footer Menu', 'nav_menu');
    if (!$footer_menu) {
        $footer_menu = get_term_by('name', 'Footer', 'nav_menu');
    }

    $locations = get_theme_mod('nav_menu_locations');
    
    if ($main_menu) {
        $locations['primary'] = $main_menu->term_id;
    }
    
    if ($footer_menu) {
        $locations['footer'] = $footer_menu->term_id;
    }
    
    set_theme_mod('nav_menu_locations', $locations);

    // Flush rewrite rules to prevent 404s.
    flush_rewrite_rules();
}
add_action('ocdi/after_import', 'zzprompts_ocdi_after_import_setup');

/**
 * Disable generation of smaller images (thumbnails) during import.
 * This speeds up the import process significantly.
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array.
 */
function zzprompts_ocdi_disable_regenerate_thumbnails($sizes) {
    // Only during demo import.
    if (defined('DOING_OCDI_IMPORT') && DOING_OCDI_IMPORT) {
        return array();
    }
    return $sizes;
}

/**
 * Before widgets import: make a backup of current widgets.
 */
function zzprompts_ocdi_before_widgets_import() {
    // Optionally backup current widgets before import.
    $sidebars_widgets = get_option('sidebars_widgets');
    update_option('zzprompts_widgets_backup', $sidebars_widgets);
}
add_action('ocdi/before_widgets_import', 'zzprompts_ocdi_before_widgets_import');

/**
 * Display intro text in demo import admin page.
 *
 * @param string $default_text Default intro text.
 * @return string Modified intro text.
 */
function zzprompts_ocdi_intro_text($default_text) {
    $message = '<div class="ocdi-intro-notice" style="background:#fff;padding:20px;border-left:4px solid #6366F1;margin-bottom:20px;">';
    $message .= '<h3 style="margin-top:0;">' . esc_html__('Welcome to ZZ Prompts Demo Import', 'zzprompts') . '</h3>';
    $message .= '<p>' . esc_html__('Click the "Import Demo Data" button below to import the demo content, widgets, and Customizer settings.', 'zzprompts') . '</p>';
    $message .= '<p><strong>' . esc_html__('Note:', 'zzprompts') . '</strong> ' . esc_html__('This may take a few minutes. Please be patient and do not close this page until the import is complete.', 'zzprompts') . '</p>';
    $message .= '<p style="color:#64748B;font-size:0.9em;">' . esc_html__('Images used in the demo are for preview purposes and may be replaced with placeholders.', 'zzprompts') . '</p>';
    $message .= '</div>';
    return $message;
}
add_filter('ocdi/plugin_intro_text', 'zzprompts_ocdi_intro_text');

/**
 * Disable the branding notice in demo import.
 */
add_filter('ocdi/disable_pt_branding', '__return_true');

/**
 * Set permalink structure after import.
 */
function zzprompts_ocdi_set_permalinks() {
    // Set pretty permalinks if not already set.
    $permalink_structure = get_option('permalink_structure');
    if (empty($permalink_structure)) {
        update_option('permalink_structure', '/%postname%/');
        flush_rewrite_rules();
    }
}
add_action('ocdi/after_import', 'zzprompts_ocdi_set_permalinks', 20);

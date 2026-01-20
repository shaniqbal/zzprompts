<?php
/**
 * Dynamic Customizer CSS
 * 
 * Outputs CSS variables that match the theme's variable system:
 * - --zz-color-primary: Buttons, links, active states
 * - --zz-color-accent: Badges, tags, success states
 * 
 * NOTE: Text colors are NOT customizable because they need to be
 * different for light/dark modes. Text colors are controlled by
 * _variables.css, _light.css, and _dark.css.
 *
 * @package zzprompts
 */

defined('ABSPATH') || exit;

/**
 * Output Customizer CSS Variables
 */
function zzprompts_customizer_css() {
    // Get colors with defaults
    $primary_color = zzprompts_get_option('primary_color', '#6366F1');
    $accent_color  = zzprompts_get_option('accent_color', '#10b981');
    
    // Generate shades
    $primary_hover = zzprompts_adjust_color_brightness($primary_color, -15);
    $primary_light = zzprompts_adjust_color_brightness($primary_color, 90);
    $primary_glow  = zzprompts_hex_to_rgba($primary_color, 0.15);
    ?>
    <style type="text/css" id="zzprompts-customizer-css">
        :root {
            /* Primary Color System - Matching theme variables */
            --zz-color-primary: <?php echo esc_attr($primary_color); ?>;
            --zz-color-primary-hover: <?php echo esc_attr($primary_hover); ?>;
            --zz-color-primary-dark: <?php echo esc_attr($primary_hover); ?>;
            --zz-color-primary-light: <?php echo esc_attr($primary_light); ?>;
            --zz-color-primary-glow: <?php echo esc_attr($primary_glow); ?>;
            
            /* Accent/Success Color */
            --zz-color-accent: <?php echo esc_attr($accent_color); ?>;
            --zz-color-success: <?php echo esc_attr($accent_color); ?>;
            
            /* Gradient with primary color */
            --zz-gradient-text: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?> 0%, #8B5CF6 50%, #EC4899 100%);
        }
    </style>
    <?php
}
add_action('wp_head', 'zzprompts_customizer_css', 20);

/**
 * Convert hex to rgba
 */
function zzprompts_hex_to_rgba($hex, $alpha = 1) {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba({$r}, {$g}, {$b}, {$alpha})";
}

/**
 * Adjust Color Brightness
 * 
 * @param string $hex Hex color code
 * @param int $percent Positive = lighten, Negative = darken
 * @return string Adjusted hex color
 */
function zzprompts_adjust_color_brightness($hex, $percent) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Adjust brightness
    if ($percent > 0) {
        // Lighten: blend with white
        $r = $r + (255 - $r) * ($percent / 100);
        $g = $g + (255 - $g) * ($percent / 100);
        $b = $b + (255 - $b) * ($percent / 100);
    } else {
        // Darken: reduce values
        $factor = (100 + $percent) / 100;
        $r = $r * $factor;
        $g = $g * $factor;
        $b = $b * $factor;
    }
    
    // Clamp and convert back to hex
    $r = max(0, min(255, round($r)));
    $g = max(0, min(255, round($g)));
    $b = max(0, min(255, round($b)));
    
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

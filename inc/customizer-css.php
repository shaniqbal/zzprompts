<?php
/**
 * Dynamic Customizer CSS
 * 
 * Outputs CSS variables for the V1 color system:
 * - Primary: Buttons, links, active states
 * - Accent: Badges, tags, meta highlights  
 * - Text: Body and heading text
 *
 * @package zzprompts
 */

defined('ABSPATH') || exit;

/**
 * Output Customizer CSS Variables
 */
function zzprompts_customizer_css() {
    // Get colors with defaults
    $primary_color = zzprompts_get_option('primary_color', '#5c6ac4');
    $accent_color  = zzprompts_get_option('accent_color', '#10b981');
    $text_color    = zzprompts_get_option('text_color', '#1f2937');
    
    // Generate hover shade (darken by ~10%)
    $primary_hover = zzprompts_adjust_color_brightness($primary_color, -15);
    $primary_light = zzprompts_adjust_color_brightness($primary_color, 85);
    ?>
    <style type="text/css" id="zzprompts-customizer-css">
        :root {
            /* Primary Color System */
            --color-primary: <?php echo esc_attr($primary_color); ?>;
            --color-primary-hover: <?php echo esc_attr($primary_hover); ?>;
            --color-primary-light: <?php echo esc_attr($primary_light); ?>;
            
            /* Accent Color */
            --color-accent: <?php echo esc_attr($accent_color); ?>;
            
            /* Text Color */
            --color-text: <?php echo esc_attr($text_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'zzprompts_customizer_css');

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

<?php
defined('ABSPATH') || exit;

/**
 * Helper Functions
 * 
 * @package zzprompts
 */

// Sanitize Text Field
function zzprompts_sanitize_text($text) {
    return sanitize_text_field($text);
}

// Sanitize Textarea
function zzprompts_sanitize_textarea($text) {
    return sanitize_textarea_field($text);
}

// Get Post Excerpt with Custom Length
function zzprompts_get_excerpt($length = 20, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $excerpt = get_the_excerpt($post_id);
    $excerpt = wp_trim_words($excerpt, $length, '...');
    
    return esc_html($excerpt);
}

// Format Number with K/M suffix
function zzprompts_format_number($number) {
    $number = absint($number);
    
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }
    
    return number_format($number);
}

// Get Reading Time
function zzprompts_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    
    return sprintf(
        esc_html(_n('%d min read', '%d mins read', $reading_time, 'zzprompts')),
        $reading_time
    );
}

// Get Post Meta with Default
function zzprompts_get_meta($meta_key, $post_id = null, $default = '') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $value = get_post_meta($post_id, $meta_key, true);
    
    return $value ? $value : $default;
}

// Check if Prompt is Premium (Future-proof)
function zzprompts_is_premium($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $is_premium = get_post_meta($post_id, '_is_premium', true);
    
    return (bool) $is_premium;
}

// Display Premium Badge
function zzprompts_premium_badge() {
    $output = '<span class="zz-premium-badge" aria-label="' . esc_attr__('Premium Content', 'zzprompts') . '">';
    $output .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">';
    $output .= '<path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>';
    $output .= '</svg>';
    $output .= esc_html__('Premium', 'zzprompts');
    $output .= '</span>';
    
    return $output;
}

// Breadcrumbs
function zzprompts_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $output = '<nav class="zz-breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'zzprompts') . '">';
    $output .= '<ol>';
    $output .= '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'zzprompts') . '</a></li>';
    
    if (is_singular('prompt')) {
        $output .= '<li><a href="' . esc_url(get_post_type_archive_link('prompt')) . '">' . esc_html__('Prompts', 'zzprompts') . '</a></li>';
        $output .= '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_post_type_archive('prompt')) {
        $output .= '<li aria-current="page">' . esc_html__('Prompts', 'zzprompts') . '</li>';
    } elseif (is_tax('prompt_category')) {
        $output .= '<li><a href="' . esc_url(get_post_type_archive_link('prompt')) . '">' . esc_html__('Prompts', 'zzprompts') . '</a></li>';
        $output .= '<li aria-current="page">' . esc_html(single_term_title('', false)) . '</li>';
    } elseif (is_tax('ai_tool')) {
        $output .= '<li><a href="' . esc_url(get_post_type_archive_link('prompt')) . '">' . esc_html__('Prompts', 'zzprompts') . '</a></li>';
        $output .= '<li aria-current="page">' . esc_html(single_term_title('', false)) . '</li>';
    } elseif (is_singular('post')) {
        $output .= '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('Blog', 'zzprompts') . '</a></li>';
        $output .= '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_page()) {
        $output .= '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    
    return $output;
}

// Social Share Buttons
function zzprompts_social_share() {
    $post_url = esc_url(get_permalink());
    $post_title = esc_attr(get_the_title());
    
    $output = '<div class="zz-social-share">';
    $output .= '<span class="zz-share-label">' . esc_html__('Share:', 'zzprompts') . '</span>';
    
    // Twitter
    $output .= '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Twitter', 'zzprompts') . '">';
    $output .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>';
    $output .= '</a>';
    
    // Facebook
    $output .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Facebook', 'zzprompts') . '">';
    $output .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>';
    $output .= '</a>';
    
    // LinkedIn
    $output .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on LinkedIn', 'zzprompts') . '">';
    $output .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>';
    $output .= '</a>';
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Sanitize Title/Slug to remove Emojis and Special Characters
 * Ensures clean URLs for SEO and SaaS Quality Standards.
 *
 * @param string $title The title to sanitize.
 * @return string Sanitized title.
 */
function zzprompts_sanitize_emoji_slug($title) {
    // Regex to strip emojis (ranges for various unicode sets)
    $clean_title = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1F018}-\x{1F270}]/u', '', $title);
    
    return $clean_title;
}
add_filter('sanitize_title', 'zzprompts_sanitize_emoji_slug', 9);

/**
 * Add prompt-specific body classes.
 * 
 * @param array $classes Existing body classes
 * @return array Modified body classes
 */
function zzprompts_add_prompt_body_class($classes) {
    // Add specific class for prompt single pages
    if (is_singular('prompt')) {
        $classes[] = 'singular-prompt';
        $classes[] = 'single-prompt';
    }

    return $classes;
}
add_filter('body_class', 'zzprompts_add_prompt_body_class');

/**
 * Get total copies across all prompts
 * Used for homepage stats
 * 
 * @return int Total copy count
 */
function zzprompts_get_total_copies() {
    global $wpdb;
    
    $total = wp_cache_get('zzprompts_total_copies', 'zzprompts');
    
    if (false === $total) {
        $total = $wpdb->get_var(
            "SELECT SUM(meta_value) FROM {$wpdb->postmeta} 
             WHERE meta_key = '_copy_count' 
             AND meta_value > 0"
        );
        
        $total = absint($total);
        wp_cache_set('zzprompts_total_copies', $total, 'zzprompts', HOUR_IN_SECONDS);
    }
    
    return $total;
}

/**
 * 💡 Smart Related Prompts (Fallback Logic)
 * 
 * Priority 1: Same Category
 * Priority 2: Same AI Tool (Fallback)
 * Priority 3: Latest Prompts (Last Resort)
 * 
 * @param int $post_id Current Post ID
 * @param int $limit Number of posts to return
 * @return WP_Query
 */
function zzprompts_get_smart_related_prompts($post_id = null, $limit = 4) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $collected_ids = array();
    
    // 1. Get Categories
    $categories = get_the_terms($post_id, 'prompt_category');
    $cat_ids = ($categories && !is_wp_error($categories)) ? wp_list_pluck($categories, 'term_id') : array();
    
    if (!empty($cat_ids)) {
        $args_cat = array(
            'post_type'      => 'prompt',
            'posts_per_page' => $limit,
            'post__not_in'   => array($post_id),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'prompt_category',
                    'field'    => 'term_id',
                    'terms'    => $cat_ids,
                ),
            ),
            'fields' => 'ids', // Only fetch IDs for speed
        );
        
        $query_cat = new WP_Query($args_cat);
        if ($query_cat->have_posts()) {
            $collected_ids = array_merge($collected_ids, $query_cat->posts);
        }
    }
    
    // 2. Fallback: AI Tool (if we need more posts)
    if (count($collected_ids) < $limit) {
        $remaining = $limit - count($collected_ids);
        $tools = get_the_terms($post_id, 'ai_tool');
        $tool_ids = ($tools && !is_wp_error($tools)) ? wp_list_pluck($tools, 'term_id') : array();
        
        if (!empty($tool_ids)) {
            $args_tool = array(
                'post_type'      => 'prompt',
                'posts_per_page' => $remaining,
                'post__not_in'   => array_merge(array($post_id), $collected_ids),
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'ai_tool',
                        'field'    => 'term_id',
                        'terms'    => $tool_ids,
                    ),
                ),
                'fields' => 'ids',
            );
            
            $query_tool = new WP_Query($args_tool);
            if ($query_tool->have_posts()) {
                $collected_ids = array_merge($collected_ids, $query_tool->posts);
            }
        }
    }
    
    // 3. Last Resort: Latest Prompts (if still not enough)
    if (count($collected_ids) < $limit) {
        $remaining = $limit - count($collected_ids);
        
        $args_latest = array(
            'post_type'      => 'prompt',
            'posts_per_page' => $remaining,
            'post__not_in'   => array_merge(array($post_id), $collected_ids),
            'fields'         => 'ids',
        );
        
        $query_latest = new WP_Query($args_latest);
        if ($query_latest->have_posts()) {
            $collected_ids = array_merge($collected_ids, $query_latest->posts);
        }
    }
    
    // 4. Return Final WP_Query with preserved order
    if (empty($collected_ids)) {
        return new WP_Query(); // Empty
    }
    
    $final_args = array(
        'post_type'      => 'prompt',
        'post__in'       => $collected_ids,
        'posts_per_page' => $limit,
        'orderby'        => 'post__in', // Preserve our priority order
    );
    
    return new WP_Query($final_args);
}

/**
 * 💡 Premium Content Refinement Filter
 * Automatically wraps standard code blocks into a "Terminal" style frame
 * with dots and a copy button for Blog Posts (Modern V1).
 */
function zzprompts_refine_blog_content($content) {
    if (!is_singular('post')) {
        return $content;
    }

    // Pattern for Gutenberg Code Blocks: <pre class="wp-block-code"><code>...</code></pre>
    // or standard <pre><code>...</code></pre>
    // We target <pre> tags and wrap them if they contain <code>
    $pattern = '/<pre([^>]*)>(.*?)<code([^>]*)>(.*?)<\/code>(.*?)<\/pre>/is';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $pre_attrs = $matches[1];
        $before_code = $matches[2];
        $code_attrs = $matches[3];
        $code_body = $matches[4];
        $after_code = $matches[5];
        
        // Generate a random ID for the copy target
        $target_id = 'zz-code-' . wp_generate_password(6, false);
        
        // Build the Terminal Wrapper
        $output = '<div class="zz-blog-terminal">';
        $output .= '  <div class="zz-blog-terminal__header">';
        $output .= '    <div class="zz-blog-terminal__dots">';
        $output .= '      <span class="zz-blog-terminal__dot zz-blog-terminal__dot--red"></span>';
        $output .= '      <span class="zz-blog-terminal__dot zz-blog-terminal__dot--yellow"></span>';
        $output .= '      <span class="zz-blog-terminal__dot zz-blog-terminal__dot--green"></span>';
        $output .= '    </div>';
        $output .= '    <button class="zz-blog-terminal__copy zz-copy-btn btn-text-only" data-post-id="' . get_the_ID() . '" data-clipboard-target="#' . $target_id . '" aria-label="Copy code">';
        $output .= '      <i class="fa-regular fa-copy"></i> <span class="btn-text">Copy</span>';
        $output .= '    </button>';
        $output .= '  </div>';
        $output .= '  <div class="zz-blog-terminal__body">';
        $output .= '    <pre' . $pre_attrs . '>' . $before_code . '<code id="' . $target_id . '"' . $code_attrs . '>' . $code_body . '</code>' . $after_code . '</pre>';
        $output .= '  </div>';
        $output .= '</div>';
        
        return $output;
    }, $content);

    return $content;
}
add_filter('the_content', 'zzprompts_refine_blog_content', 20);

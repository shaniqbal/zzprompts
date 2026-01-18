<?php
defined('ABSPATH') || exit;

/**
 * Theme Features: Likes & Copy System
 * 
 * @package zzprompts
 */

/**
 * Helper: Get User IP
 */
function zzprompts_get_ip() {
    $ip = '0.0.0.0';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return apply_filters('zzprompts_get_ip', $ip);
}

// AJAX Handler: Like Prompt
function zzprompts_like_prompt() {
    // 1. Nonce verification
    check_ajax_referer('zzprompts_nonce', 'nonce');
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error(array('message' => esc_html__('Invalid post ID', 'zzprompts')));
    }

    $user_ip = zzprompts_get_ip();
    $transient_key = 'zz_lock_like_' . md5($user_ip . $post_id);

    // 2. IP-based Transient Check (1 hour cooldown)
    if (get_transient($transient_key)) {
        wp_send_json_error(array('message' => esc_html__('Please wait a bit before liking again.', 'zzprompts')));
    }

    // 3. Cookie-based prevention (30 days)
    $cookie_name = 'zz_liked_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error(array('message' => esc_html__('You already liked this prompt!', 'zzprompts')));
    }
    
    // Process increment
    $current_likes = get_post_meta($post_id, '_prompt_likes', true);
    $current_likes = $current_likes ? absint($current_likes) : 0;
    $new_likes = $current_likes + 1;
    update_post_meta($post_id, '_prompt_likes', $new_likes);

    // 4. Set Protection Layers
    set_transient($transient_key, '1', HOUR_IN_SECONDS);
    setcookie($cookie_name, '1', time() + (30 * DAY_IN_SECONDS), '/');

    // 5. Clear Widget Caches (Popular Prompts)
    if (function_exists('zz_clear_sidebar_caches')) {
        zz_clear_sidebar_caches();
    }
    
    wp_send_json_success(array(
        'likes'   => $new_likes,
        'message' => esc_html__('Thank you for liking!', 'zzprompts')
    ));
}
add_action('wp_ajax_zzprompts_like_prompt', 'zzprompts_like_prompt');
add_action('wp_ajax_nopriv_zzprompts_like_prompt', 'zzprompts_like_prompt');

// AJAX Handler: Track Copy
function zzprompts_track_copy() {
    // 1. Nonce verification
    check_ajax_referer('zzprompts_nonce', 'nonce');
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error(array('message' => esc_html__('Invalid post ID', 'zzprompts')));
    }

    $user_ip = zzprompts_get_ip();
    $transient_key = 'zz_lock_copy_' . md5($user_ip . $post_id);

    // 2. IP-based Transient Check (1 hour cooldown)
    if (get_transient($transient_key)) {
        wp_send_json_error(array('message' => esc_html__('Cooldown active.', 'zzprompts')));
    }

    // 3. Cookie-based prevention (30 days)
    $cookie_name = 'zz_copied_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error(array('message' => esc_html__('Already tracked.', 'zzprompts')));
    }
    
    // Process increment
    $current_copies = get_post_meta($post_id, '_prompt_copy_count', true);
    $current_copies = $current_copies ? absint($current_copies) : 0;
    $new_copies = $current_copies + 1;
    update_post_meta($post_id, '_prompt_copy_count', $new_copies);

    // 4. Set Protection Layers
    set_transient($transient_key, '1', HOUR_IN_SECONDS);
    setcookie($cookie_name, '1', time() + (30 * DAY_IN_SECONDS), '/');
    
    wp_send_json_success(array(
        'copies'  => $new_copies,
        'message' => esc_html__('Copied to clipboard!', 'zzprompts')
    ));
}
add_action('wp_ajax_zzprompts_track_copy', 'zzprompts_track_copy');
add_action('wp_ajax_nopriv_zzprompts_track_copy', 'zzprompts_track_copy');

// Get Like Count
function zzprompts_get_likes($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $likes = get_post_meta($post_id, '_prompt_likes', true);
    return $likes ? absint($likes) : 0;
}

// Get Copy Count
function zzprompts_get_copy_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $copies = get_post_meta($post_id, '_prompt_copy_count', true);
    return $copies ? absint($copies) : 0;
}

// Track Post Views
function zzprompts_track_post_views() {
    if (!is_singular(array('prompt', 'post'))) {
        return;
    }
    
    $post_id = get_the_ID();
    
    // Check if it's a preview or bot (Simple bot check)
    if (is_preview() || is_robots()) {
        return;
    }
    
    $meta_key = 'zzprompts_post_views';
    $cookie_name = 'zzprompts_viewed_' . $post_id;
    
    if (!isset($_COOKIE[$cookie_name])) {
        $views = get_post_meta($post_id, $meta_key, true);
        $views = $views ? absint($views) : 0;
        $new_views = $views + 1;
        
        update_post_meta($post_id, $meta_key, $new_views);
        setcookie($cookie_name, '1', time() + 86400, '/');
    }
}
// Track views on 'wp' hook
add_action('wp', 'zzprompts_track_post_views');

// Get Post Views
function zzprompts_get_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $views = get_post_meta($post_id, 'zzprompts_post_views', true);
    return $views ? absint($views) : 0;
}

// Display Like Button
function zzprompts_like_button($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check if likes enabled
    $likes_enabled = get_theme_mod('zz_enable_likes', true);
    if (!$likes_enabled) {
        return;
    }
    
    $likes = zzprompts_get_likes($post_id);
    
    $output = sprintf(
        '<button class="zz-like-btn" data-post-id="%d" aria-label="%s">
            <svg class="zz-heart-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <span class="zz-like-count">%d</span>
        </button>',
        esc_attr($post_id),
        esc_attr__('Like this prompt', 'zzprompts'),
        absint($likes)
    );
    
    return $output;
}

// Display Copy Button
function zzprompts_copy_button($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $output = sprintf(
        '<button class="zz-copy-btn" data-post-id="%d" aria-label="%s">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
            <span>%s</span>
        </button>',
        esc_attr($post_id),
        esc_attr__('Copy prompt to clipboard', 'zzprompts'),
        esc_html__('Copy Prompt', 'zzprompts')
    );
    
    return $output;
}

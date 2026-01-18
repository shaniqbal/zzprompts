<?php
/**
 * The template for displaying search forms in zzprompts
 * 
 * Context-aware search:
 * - On blog pages: searches blog posts only
 * - On prompt pages: searches prompts only
 * - Default: searches prompts (header search priority)
 *
 * @package zzprompts
 */

defined('ABSPATH') || exit;

// Determine search context
$is_blog_context = is_home() || is_category() || is_tag() || is_singular('post') || is_author();
$search_type = $is_blog_context ? 'post' : 'prompt';
$placeholder = $is_blog_context 
    ? esc_attr_x('Search articles...', 'placeholder', 'zzprompts')
    : esc_attr_x('Search prompts...', 'placeholder', 'zzprompts');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <!-- Context-aware post type -->
    <input type="hidden" name="post_type" value="<?php echo esc_attr($search_type); ?>">
    
    <label for="search-field-<?php echo esc_attr(uniqid()); ?>" class="screen-reader-text">
        <?php echo _x('Search for:', 'label', 'zzprompts'); ?>
    </label>
    
    <input type="search" 
           id="search-field-<?php echo esc_attr(uniqid()); ?>" 
           class="search-field" 
           placeholder="<?php echo $placeholder; ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" />
           
    <button type="submit" class="search-submit">
        <?php echo esc_html_x('Search', 'submit button', 'zzprompts'); ?>
    </button>
</form>

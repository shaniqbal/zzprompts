<?php
/**
 * Blog Archive Sidebar - Clean Widget-Based
 * 
 * All sidebar content is now managed via:
 * Appearance → Widgets → Blog Sidebar
 * 
 * Removed hardcoded sections per ThemeForest best practices.
 * Users should add ZZ widgets manually for full control.
 * 
 * @package zzprompts
 * @version 1.0.0 - ThemeForest Ready
 */

defined('ABSPATH') || exit;

// Check if sticky sidebar is enabled
$sidebar_sticky = (bool) get_theme_mod('sidebar_sticky', true);
$sidebar_class = 'zz-blog-sidebar';
if ($sidebar_sticky) {
    $sidebar_class .= ' sticky-sidebar';
}
?>

<aside class="<?php echo esc_attr($sidebar_class); ?>">
    
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <!-- Fallback: Show default widgets if sidebar is empty -->
        <div class="zz-sidebar-widget zz-search-widget">
            <h3 class="zz-widget-title"><?php esc_html_e('Search Articles', 'zzprompts'); ?></h3>
            <?php get_search_form(); ?>
        </div>
        
        <div class="zz-sidebar-widget zz-categories-widget">
            <h3 class="zz-widget-title"><?php esc_html_e('Categories', 'zzprompts'); ?></h3>
            <ul class="zz-category-list">
                <?php wp_list_categories(array(
                    'title_li' => '',
                    'number'   => 10,
                    'orderby'  => 'count',
                    'order'    => 'DESC',
                )); ?>
            </ul>
        </div>
        
        <div class="zz-sidebar-widget zz-recent-widget">
            <h3 class="zz-widget-title"><?php esc_html_e('Recent Posts', 'zzprompts'); ?></h3>
            <ul class="zz-recent-posts-list">
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish',
                ));
                foreach ($recent_posts as $post) :
                ?>
                    <li class="zz-recent-post-item">
                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                            <?php echo esc_html($post['post_title']); ?>
                        </a>
                    </li>
                <?php endforeach; wp_reset_postdata(); ?>
            </ul>
        </div>
    <?php endif; ?>
    
</aside>

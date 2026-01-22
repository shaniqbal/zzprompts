<?php
/**
 * Blog Archive Sidebar - Customizer-Driven
 * 
 * Features:
 * - Search Widget (Instant AJAX)
 * - Categories Widget (Pills style with post count)
 * - Popular Posts Widget (With thumbnails)
 * - Recent Posts Widget
 * - Manual Widgets (via dynamic_sidebar)
 * 
 * All widgets are customizer-controlled: enable/disable, set titles, limit items
 * 
 * @package zzprompts
 * @version 2.0.0 - Clean Launch
 */

defined('ABSPATH') || exit;

// Get customizer settings with defaults - Use proper boolean casting
$search_enabled = (bool) intval(get_option('zzprompts_sidebar_search_enabled', 1));
$search_title = sanitize_text_field(get_option('zzprompts_sidebar_search_title', esc_html__('Search Articles', 'zzprompts')));

$categories_enabled = (bool) intval(get_option('zzprompts_sidebar_categories_enabled', 1));
$categories_title = sanitize_text_field(get_option('zzprompts_sidebar_categories_title', esc_html__('Explore Topics', 'zzprompts')));
$categories_limit = (int) get_option('zzprompts_sidebar_categories_limit', 10);
$categories_count = (bool) intval(get_option('zzprompts_sidebar_categories_count', 1));

$popular_enabled = (bool) intval(get_option('zzprompts_sidebar_popular_enabled', 1));
$popular_title = sanitize_text_field(get_option('zzprompts_sidebar_popular_title', esc_html__('Trending Now', 'zzprompts')));
$popular_limit = (int) get_option('zzprompts_sidebar_popular_limit', 5);
$popular_date = (bool) intval(get_option('zzprompts_sidebar_popular_date', 1));
$popular_thumbnail = (bool) intval(get_option('zzprompts_sidebar_popular_thumbnail', 1));

$recent_enabled = (bool) intval(get_option('zzprompts_sidebar_recent_enabled', 1));
$recent_title = sanitize_text_field(get_option('zzprompts_sidebar_recent_title', esc_html__('Latest Posts', 'zzprompts')));
$recent_limit = (int) get_option('zzprompts_sidebar_recent_limit', 5);

$sidebar_sticky = (bool) intval(get_option('zzprompts_sidebar_sticky', 1));

// Add sticky class if enabled
$sidebar_class = 'zz-blog-sidebar';
if ($sidebar_sticky) {
    $sidebar_class .= ' sticky-sidebar';
}
?>

<aside class="<?php echo esc_attr($sidebar_class); ?>">
    
    <!-- 1️⃣ SEARCH WIDGET (Instant AJAX) -->
    <?php if ($search_enabled) : ?>
    <div class="zz-sidebar-widget zz-search-widget">
        <h3 class="zz-widget-title"><?php echo esc_html($search_title); ?></h3>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="zz-blog-search-form" id="zz-blog-search">
            <!-- Restrict search to blog posts only -->
            <input type="hidden" name="post_type" value="post">
            <input 
                type="search" 
                class="zz-blog-search-input" 
                placeholder="<?php esc_attr_e('Type to search...', 'zzprompts'); ?>" 
                value="<?php echo esc_attr(get_search_query()); ?>" 
                name="s"
                id="zz-blog-search-input"
                autocomplete="off"
            >
            <button type="submit" class="zz-blog-search-submit" aria-label="<?php esc_attr_e('Search', 'zzprompts'); ?>"></button>
            <div class="zz-blog-search-results" id="zz-blog-search-results"></div>
        </form>
    </div>
    <?php endif; ?>

    <!-- 2️⃣ CATEGORIES WIDGET (Pills Style) -->
    <?php if ($categories_enabled) : 
        $categories = zzprompts_get_blog_categories($categories_limit, $categories_count);
        if (!empty($categories)) :
    ?>
    <div class="zz-sidebar-widget zz-categories-widget">
        <h3 class="zz-widget-title"><?php echo esc_html($categories_title); ?></h3>
        <div class="zz-categories-pills">
            <?php foreach ($categories as $cat) : ?>
                <a href="<?php echo esc_url($cat['url']); ?>" class="zz-category-pill">
                    <span class="zz-category-name"><?php echo esc_html($cat['name']); ?></span>
                    <?php if ($categories_count && $cat['count'] > 0) : ?>
                        <span class="zz-category-count"><?php echo esc_html($cat['count']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; endif; ?>

    <!-- 3️⃣ POPULAR POSTS WIDGET -->
    <?php if ($popular_enabled) : 
        $popular_posts = zzprompts_get_popular_posts($popular_limit);
        if (!empty($popular_posts)) :
    ?>
    <div class="zz-sidebar-widget zz-popular-widget">
        <h3 class="zz-widget-title"><?php echo esc_html($popular_title); ?></h3>
        <div class="zz-popular-posts">
            <?php foreach ($popular_posts as $post) : ?>
                <div class="zz-popular-post-item">
                    <div class="zz-popular-post-image">
                        <a href="<?php echo esc_url($post['url']); ?>">
                            <?php if ($popular_thumbnail && !empty($post['image'])) : ?>
                                <img src="<?php echo esc_url($post['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>">
                            <?php else : 
                                // Get first category for color
                                $item_cats = get_the_category($post['id']);
                                $item_cat_name = !empty($item_cats) ? $item_cats[0]->name : 'Blog';
                                $colors = ['#EEF2FF', '#ECFDF5', '#F0F9FF', '#FEF2F2', '#F5F3FF'];
                                $bg_color = $colors[abs(crc32($item_cat_name)) % count($colors)];
                            ?>
                                <div style="width:100%;height:100%;background-color:<?php echo esc_attr($bg_color); ?>;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:rgba(0,0,0,0.3);text-transform:uppercase;">
                                    <?php echo esc_html(substr($item_cat_name, 0, 3)); ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="zz-popular-post-content">
                        <h4 class="zz-popular-post-title">
                            <a href="<?php echo esc_url($post['url']); ?>">
                                <?php echo esc_html($post['title']); ?>
                            </a>
                        </h4>
                        <?php if ($popular_date) : ?>
                            <div class="zz-popular-post-date">
                                <?php echo esc_html($post['date']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; endif; ?>

    <!-- 4️⃣ RECENT POSTS WIDGET -->
    <?php if ($recent_enabled) : 
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => $recent_limit,
            'post_status' => 'publish',
        ));
        if (!empty($recent_posts)) :
    ?>
    <div class="zz-sidebar-widget zz-recent-widget">
        <h3 class="zz-widget-title"><?php echo esc_html($recent_title); ?></h3>
        <ul class="zz-recent-posts-list">
            <?php foreach ($recent_posts as $post) : ?>
                <li class="zz-recent-post-item">
                    <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                        <?php echo esc_html($post['post_title']); ?>
                    </a>
                    <span class="zz-recent-post-date">
                        <?php echo esc_html(get_the_date('M d, Y', $post['ID'])); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; endif; ?>

    <!-- 5️⃣ CONSTANT WP WIDGETS (Standard Sidebar) -->
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <div class="zz-sidebar-standard-widgets">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div>
    <?php endif; ?>
    
</aside>

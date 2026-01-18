<?php
/**
 * The main template file (Blog Router)
 *
 * Loads blog layout variation (V1 or V2) based on Customizer settings.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

// Get blog layout from Customizer
$layout = zzprompts_get_option('blog_layout_select', 'v1');
?>

<!-- Blog Hero Section -->
<section class="blog-hero-section">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="blog-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <?php esc_html_e('Home', 'zzprompts'); ?>
            </a>
            <span class="breadcrumb-separator">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <span class="breadcrumb-current"><?php esc_html_e('Blog', 'zzprompts'); ?></span>
        </nav>
        
        <h1 class="blog-hero-title"><?php esc_html_e('Latest News & Updates', 'zzprompts'); ?></h1>
        <p class="blog-hero-subtitle"><?php esc_html_e('Discover insights, tips, and the latest trends in AI prompts and automation.', 'zzprompts'); ?></p>
    </div>
</section>
</section>

<div class="container section-padding">
    <?php if ('v2' === $layout) : ?>
        
        <!-- V2 Layout: Magazine with Right Sidebar -->
        <div class="zz-blog-layout content-sidebar-wrap sidebar-right">
            <div class="zz-blog-main-content">
                <?php get_template_part('template-parts/blog/card', 'blog'); ?>
            </div>
            <?php get_template_part('template-parts/sidebar', 'blog'); ?>
        </div>
        
    <?php else : ?>
        
        <!-- V1 Layout: Standard Blog with Sidebar -->
        <?php
        $sidebar_enabled = zzprompts_get_option('blog_sidebar_enabled', true);
        $layout_class = $sidebar_enabled ? 'blog-with-sidebar' : 'blog-full-width';
        ?>
        
        <div class="<?php echo esc_attr($layout_class); ?>">
            <main id="primary" class="site-main">
                <?php if (have_posts()) : ?>
                    <div class="blog-posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('template-parts/content'); ?>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    the_posts_pagination(array(
                        'prev_text' => '&larr;',
                        'next_text' => '&rarr;',
                    ));
                    ?>

                <?php else : ?>
                    <div class="no-results">
                        <p><?php esc_html_e('No posts found.', 'zzprompts'); ?></p>
                    </div>
                <?php endif; ?>
            </main>

            <?php
            if ($sidebar_enabled) {
                get_sidebar();
            }
            ?>
        </div>
        
    <?php endif; ?>
</div>

<?php get_footer(); ?>


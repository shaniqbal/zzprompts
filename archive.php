<?php
/**
 * The template for displaying Archive pages (Category, Tag, Author, Date)
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

$blog_layout = zzprompts_get_option('blog_layout_select', 'v1');

$is_blog_archive = is_category() || is_tag() || is_author() || is_date();

if ('v2' === $blog_layout && $is_blog_archive) :
?>

<div class="container section-padding">
    <div class="zz-blog-layout content-sidebar-wrap sidebar-right">
        <div class="zz-blog-main-content">
            <header class="page-header mb-8 pb-8 border-b border-gray-100">
                <?php
                the_archive_title('<h1 class="page-title text-3xl font-bold mb-2 text-heading">', '</h1>');
                the_archive_description('<div class="archive-description text-muted text-lg">', '</div>');
                ?>
            </header>

            <?php get_template_part('template-parts/blog/card', 'blog'); ?>
        </div>

        <?php get_template_part('template-parts/sidebar', 'blog'); ?>
    </div>
</div>

<?php
get_footer();
return;
endif;

$sidebar_enabled = zzprompts_get_option('blog_sidebar_enabled', true);
$layout_class = $sidebar_enabled ? 'content-sidebar-wrap' : 'single-centered-wrap';
?>

<div class="container section-padding">
    <div class="<?php echo esc_attr($layout_class); ?>">
        
        <main id="primary" class="site-main">
            <header class="page-header mb-8 pb-8 border-b border-gray-100">
                <?php
                the_archive_title('<h1 class="page-title text-3xl font-bold mb-2 text-heading">', '</h1>');
                the_archive_description('<div class="archive-description text-muted text-lg">', '</div>');
                ?>
            </header>

            <?php if (have_posts()) : ?>
                <div class="zz-archive-list">
                    <?php 
                    while (have_posts()) : 
                        the_post(); 
                        get_template_part('template-parts/content'); 
                    endwhile; 
                    ?>
                </div>
                
                <div class="mt-8">
                    <?php 
                    the_posts_pagination(array(
                        'prev_text' => '<span class="icon-arrow-left"></span>', 
                        'next_text' => '<span class="icon-arrow-right"></span>'
                    )); 
                    ?>
                </div>
            <?php else : ?>
                <div class="no-results">
                    <h2 class="text-xl font-bold"><?php esc_html_e('Nothing Found', 'zzprompts'); ?></h2>
                    <p class="text-muted"><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'zzprompts'); ?></p>
                </div>
            <?php endif; ?>
        </main>

        <?php if ($sidebar_enabled) get_sidebar(); ?>

    </div>
</div>

<?php get_footer(); ?>

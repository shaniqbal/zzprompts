<?php
/**
 * The template for displaying Archive pages (Category, Tag, Author, Date)
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

$is_blog_archive = is_category() || is_tag() || is_author() || is_date();

// Blog Hero Section for Archives
?>
<section class="zz-blog-hero">
    <div class="zz-container">
        <!-- Breadcrumbs -->
        <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link"><?php esc_html_e('Home', 'zzprompts'); ?></a>
            <span class="zz-breadcrumbs__separator">&rsaquo;</span>
            <span class="zz-breadcrumbs__current"><?php echo get_the_archive_title(); ?></span>
        </nav>
        
        <h1 class="zz-blog-hero__title"><?php the_archive_title(); ?></h1>
        <?php the_archive_description('<p class="zz-blog-hero__subtitle">', '</p>'); ?>
    </div>
</section>

<div class="zz-container u-py-10">
    <?php
    $sidebar_enabled = zzprompts_get_option('blog_show_sidebar', true);
    $layout_class = $sidebar_enabled ? 'zz-blog-layout' : 'zz-blog-layout--full-width';
    ?>
    
    <div class="<?php echo esc_attr($layout_class); ?>">
        <main id="primary" class="zz-blog-main-content">
            <?php get_template_part('template-parts/blog/card', 'blog'); ?>
        </main>

        <?php
        if ($sidebar_enabled) {
            get_template_part('template-parts/sidebar', 'blog');
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>

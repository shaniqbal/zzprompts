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
    $sidebar_enabled = zzprompts_get_option('blog_sidebar_enabled', true);
    $layout_class = $sidebar_enabled ? 'zz-blog-layout' : 'zz-blog-full';
    ?>
    
    <div class="<?php echo esc_attr($layout_class); ?>">
        <main id="primary" class="zz-blog-main-content">
            <?php if (have_posts()) : ?>
                <div class="zz-blog-section">
                    <div class="zz-blog-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('template-parts/blog/card', 'blog'); ?>
                        <?php endwhile; ?>
                    </div>
                </div>

                <?php
                the_posts_pagination(array(
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ));
                ?>

            <?php else : ?>
                <div class="zz-no-results">
                    <div class="zz-no-results__content">
                        <div class="zz-no-results__icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3><?php esc_html_e('No Posts Found', 'zzprompts'); ?></h3>
                        <p><?php esc_html_e('We couldn\'t find any articles matching your request. Try a different search or browse all our articles.', 'zzprompts'); ?></p>
                        <div class="zz-no-results__cta">
                            <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="zz-btn zz-btn--primary">
                                <?php esc_html_e('Browse All Articles', 'zzprompts'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <?php
        if ($sidebar_enabled) {
            get_template_part('template-parts/sidebar', 'blog');
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>

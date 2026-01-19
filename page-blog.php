<?php
defined('ABSPATH') || exit;

/**
 * Template Name: Blog Page
 * Description: Blog archive listing with sidebar
 * 
 * @package zzprompts
 * @version 2.0.0
 */

get_header();

$posts_per_page = intval(get_option('zzprompts_blog_posts_per_page', 9));
$paged = max(1, (int) get_query_var('paged'));

$blog_query = new WP_Query(array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
));
?>

<!-- Blog Hero Section -->
<section class="zz-blog-hero">
    <div class="zz-container">
        <!-- Breadcrumbs -->
        <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link"><?php esc_html_e('Home', 'zzprompts'); ?></a>
            <span class="zz-breadcrumbs__separator">&rsaquo;</span>
            <span class="zz-breadcrumbs__current"><?php esc_html_e('Blog', 'zzprompts'); ?></span>
        </nav>
        
        <h1 class="zz-blog-hero__title"><?php esc_html_e('Latest News & Updates', 'zzprompts'); ?></h1>
        <p class="zz-blog-hero__subtitle"><?php esc_html_e('Discover insights, tips, and the latest trends in AI prompts and automation.', 'zzprompts'); ?></p>
    </div>
</section>

<div class="zz-container u-py-10">
    <div class="zz-blog-layout">
        <div class="zz-blog-main-content">
            <?php 
            // Get Customizer settings
            $show_image    = zzprompts_get_option('blog_show_image', true);
            $show_date     = zzprompts_get_option('blog_show_date', true);
            $show_category = zzprompts_get_option('blog_show_category', true);
            $excerpt_length = zzprompts_get_option('blog_excerpt_length', 20);
            $read_more_text = zzprompts_get_option('blog_read_more_text', __('Read Article', 'zzprompts'));
            
            if ($blog_query->have_posts()) : ?>
                <div class="zz-blog-section">
                    <div class="zz-blog-grid">
                        <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                            <article class="zz-blog-card">
                                <?php if ($show_image) : ?>
                                <div class="zz-blog-card__image-wrapper">
                                    <a href="<?php the_permalink(); ?>" class="zz-blog-card__image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        <?php else : ?>
                                            <div style="width:100%;height:100%;background:linear-gradient(135deg, var(--zz-color-primary) 0%, #8B5CF6 100%);"></div>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($show_date) : ?>
                                    <span class="zz-blog-card__date-badge"><?php echo esc_html(get_the_date('M d, Y')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <div class="zz-blog-card__content">
                                    <div class="zz-blog-card__meta">
                                        <span class="zz-blog-card__read-time">
                                            <i class="far fa-clock"></i> <?php echo esc_html(zzprompts_reading_time()); ?>
                                        </span>
                                        <?php 
                                        $categories = get_the_category();
                                        if (!empty($categories) && $show_category) : ?>
                                            &bull; <span class="zz-blog-card__category"><?php echo esc_html($categories[0]->name); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="zz-blog-card__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>

                                    <div class="zz-blog-card__excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), $excerpt_length, '...'); ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="zz-blog-card__read-link">
                                        <?php echo esc_html($read_more_text); ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="navigation pagination">
                        <?php
                        echo paginate_links(array(
                            'total'     => (int) $blog_query->max_num_pages,
                            'current'   => $paged,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                        ));
                        ?>
                    </div>
                </div>
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
            <?php wp_reset_postdata(); ?>
        </div>

        <?php get_template_part('template-parts/sidebar', 'blog'); ?>
    </div>
</div>

<?php get_footer(); ?>

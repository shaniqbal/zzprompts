<?php
defined('ABSPATH') || exit;

/**
 * Template Name: Blog Page
 * Description: Default blog listing template
 * 
 * @package zzprompts
 */

get_header();

$posts_per_page = intval(get_option('zzprompts_blog_posts_per_page', 10));
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
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <?php esc_html_e('Home', 'zzprompts'); ?>
            </a>
            <span class="zz-breadcrumbs__separator">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <span class="zz-breadcrumbs__current"><?php esc_html_e('Blog', 'zzprompts'); ?></span>
        </nav>
        
        <h1 class="zz-blog-hero__title"><?php esc_html_e('Latest News & Updates', 'zzprompts'); ?></h1>
        <p class="zz-blog-hero__subtitle"><?php esc_html_e('Discover insights, tips, and the latest trends in AI prompts and automation.', 'zzprompts'); ?></p>
    </div>
</section>

<div class="container">
    <div class="section-padding">
        <div class="zz-blog-layout content-sidebar-wrap sidebar-right">
            <div class="zz-blog-main-content">
                <?php if ($blog_query->have_posts()) : ?>
                    <div class="zz-blog-section">
                        <div class="zz-blog-grid">
                            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                                <article class="zz-blog-card">
                                    <div class="zz-blog-card__image-wrapper">
                                        <div class="zz-blog-card__image">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('medium_large'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <span class="zz-blog-card__date-badge"><?php echo esc_html(get_the_date('M d, Y')); ?></span>

                                    <div class="zz-blog-card__content">
                                        <div class="zz-blog-card__meta">
                                            <span class="zz-blog-card__read-time"><?php echo esc_html(zzprompts_reading_time()); ?></span>
                                        </div>

                                        <h3 class="zz-blog-card__title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>

                                        <div class="zz-blog-card__excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 26); ?>
                                        </div>

                                        <div class="zz-blog-card__author">
                                            <span class="zz-blog-card__author-name"><?php the_author(); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <div class="navigation pagination">
                            <?php
                            echo paginate_links(array(
                                'total'     => (int) $blog_query->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '&larr;',
                                'next_text' => '&rarr;',
                            ));
                            ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="zz-no-results">
                        <div class="zz-no-results__icon">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/><line x1="9" y1="9" x2="13" y2="9"/><line x1="9" y1="13" x2="13" y2="13"/></svg>
                        </div>
                        <h3><?php esc_html_e('No Posts Found', 'zzprompts'); ?></h3>
                        <p><?php esc_html_e('Check back later for new blog posts.', 'zzprompts'); ?></p>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <?php get_template_part('template-parts/sidebar', 'blog'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

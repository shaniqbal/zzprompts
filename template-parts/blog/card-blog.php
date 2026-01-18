<?php
/**
 * Blog Archive Grid - Magazine Style Cards
 * Two-column rows, left square image, right meta
 * 
 * @package zzprompts
 * @version 1.1.0
 */

defined('ABSPATH') || exit;
?>

<div class="zz-blog-section">
    <?php if (have_posts()) : ?>
        <div class="zz-blog-grid">
            <?php while (have_posts()) : the_post(); ?>
<article class="zz-blog-card">
    <div class="zz-blog-card__image-wrapper">
        <a href="<?php the_permalink(); ?>" class="zz-blog-card__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large'); ?>
            <?php else : 
                $categories = get_the_category();
                $cat_name = !empty($categories) ? $categories[0]->name : get_bloginfo('name');
                // Generate a consistent color based on category name
                $colors = ['#EEF2FF', '#ECFDF5', '#F0F9FF', '#FEF2F2', '#F5F3FF'];
                $bg_color = $colors[abs(crc32($cat_name)) % count($colors)];
            ?>
                <div class="zz-blog-card__placeholder" style="background-color: <?php echo esc_attr($bg_color); ?>;">
                    <span><?php echo esc_html($cat_name); ?></span>
                </div>
            <?php endif; ?>
        </a>
        <span class="zz-blog-card__date-badge"><?php echo esc_html(get_the_date('M d, Y')); ?></span>
    </div>

    <div class="zz-blog-card__content">
        <div class="zz-blog-card__meta">
            <span class="zz-blog-card__read-time">
                <i class="far fa-clock"></i> <?php echo esc_html(zzprompts_reading_time()); ?>
            </span>
            <?php 
            $categories = get_the_category();
            if (!empty($categories)) : ?>
                &bull; <span class="zz-blog-card__category"><?php echo esc_html($categories[0]->name); ?></span>
            <?php endif; ?>
        </div>

        <h3 class="zz-blog-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="zz-blog-card__excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="zz-blog-card__read-link">
            <?php esc_html_e('Read Article', 'zzprompts'); ?> <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</article>
            <?php endwhile; ?>
        </div><!-- .zz-blog-grid -->

        <!-- Pagination -->
        <div class="navigation pagination">
            <?php
            the_posts_pagination(array(
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
            ));
            ?>
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
</div>

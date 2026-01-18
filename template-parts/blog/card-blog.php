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
            <p><?php esc_html_e('No posts found.', 'zzprompts'); ?></p>
        </div>
    <?php endif; ?>
</div>

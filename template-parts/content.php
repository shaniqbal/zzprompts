<?php
/**
 * Template part for displaying posts in a modern SaaS card style
 *
 * @package zzprompts
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zz-blog-card'); ?>>
    
    <?php if (has_post_thumbnail() && zzprompts_get_option('blog_show_image', true)) : ?>
    <a href="<?php the_permalink(); ?>" class="zz-card-image">
        <?php the_post_thumbnail('large'); ?>
    </a>
    <?php endif; ?>

    <div class="zz-card-content">
        <header class="entry-header">
            <?php if (zzprompts_get_option('blog_show_date', true) || zzprompts_get_option('blog_show_category', true)) : ?>
            <div class="entry-meta">
                <?php if (zzprompts_get_option('blog_show_date', true)) : ?>
                <span class="meta-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?php echo get_the_date(); ?>
                </span>
                <?php endif; ?>
                
                <?php 
                $categories = get_the_category();
                if ( ! empty( $categories ) && zzprompts_get_option('blog_show_category', true) ) {
                    if (zzprompts_get_option('blog_show_date', true)) echo '<span class="meta-sep">&bull;</span>';
                    echo '<span class="meta-cat">' . esc_html( $categories[0]->name ) . '</span>';
                }
                ?>
            </div>
            <?php endif; ?>
            
            <?php 
            if ( is_sticky() && is_home() && ! is_paged() ) {
                printf( '<span class="sticky-label">%s</span>', esc_html__( 'Featured', 'zzprompts' ) );
            }
            ?>

            <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
        </header>

        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>

        <footer class="entry-footer mt-auto">
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php echo esc_html(zzprompts_get_option('blog_read_more_text', __('Read Article', 'zzprompts'))) ; ?>
                <svg class="icon-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </footer>
    </div>
</article>

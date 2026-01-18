<?php
/**
 * Blog Single Content - Reader Focus Layout
 * Clean, centered reading experience
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zz-blog-single'); ?>>
    
    <!-- Breadcrumbs -->
    <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <?php esc_html_e('Home', 'zzprompts'); ?>
        </a>
        <span class="zz-breadcrumbs__separator">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="zz-breadcrumbs__link">
            <?php esc_html_e('Blog', 'zzprompts'); ?>
        </a>
        <span class="zz-breadcrumbs__separator">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="zz-breadcrumbs__current"><?php echo wp_trim_words(get_the_title(), 6, '...'); ?></span>
    </nav>
    
    <!-- Article Header -->
    <header class="zz-article-header">
        <div class="zz-article-header__meta">
            <?php
            $categories = get_the_category();
            if ($categories) :
            ?>
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="zz-article-category">
                    <?php echo esc_html($categories[0]->name); ?>
                </a>
            <?php endif; ?>
            <span class="zz-article-read-time"><?php echo esc_html(zzprompts_reading_time()); ?></span>
        </div>
        
        <h1 class="zz-article-title"><?php the_title(); ?></h1>
        
        <div class="zz-article-meta">
            <span class="zz-article-meta__item">
                <?php echo get_avatar(get_the_author_meta('ID'), 28); ?>
                <?php the_author(); ?>
            </span>
            <span class="zz-article-meta__item">
                <i class="fa-regular fa-calendar"></i>
                <?php echo get_the_date(); ?>
            </span>
            <span class="zz-article-meta__item">
                <i class="fa-regular fa-clock"></i>
                <?php echo esc_html(zzprompts_reading_time()); ?>
            </span>
        </div>
    </header>
    
    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <figure class="zz-article-featured-image">
            <?php the_post_thumbnail('full'); ?>
        </figure>
    <?php endif; ?>
    
    <!-- Article Content -->
    <div class="zz-article-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'zzprompts'),
            'after'  => '</div>',
        ));
        ?>
    </div>
    
    <!-- Article Footer -->
    <footer class="zz-article-footer">
        <!-- Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
            <div class="zz-article-tags">
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="zz-tag-pill">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Share -->
        <div class="zz-article-share">
            <span class="zz-share-label"><?php esc_html_e('Share this article', 'zzprompts'); ?></span>
            <div class="zz-share-buttons">
                <!-- X (Twitter) -->
                <a href="https://x.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="zz-share-btn zz-share-btn--x" aria-label="<?php esc_attr_e('Share on X', 'zzprompts'); ?>">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="zz-share-btn zz-share-btn--facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'zzprompts'); ?>">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="zz-share-btn zz-share-btn--whatsapp" aria-label="<?php esc_attr_e('Share on WhatsApp', 'zzprompts'); ?>">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <!-- LinkedIn -->
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="zz-share-btn zz-share-btn--linkedin" aria-label="<?php esc_attr_e('Share on LinkedIn', 'zzprompts'); ?>">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <!-- Copy Link -->
                <button type="button" class="zz-share-btn zz-share-btn--copy" onclick="navigator.clipboard.writeText('<?php echo esc_url(get_permalink()); ?>').then(function(){this.classList.add('copied');var btn=this;setTimeout(function(){btn.classList.remove('copied')},2000)}.bind(this))" aria-label="<?php esc_attr_e('Copy link', 'zzprompts'); ?>">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <svg class="zz-share-check" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
            </div>
        </div>
    </footer>
    
</article>

<!-- Related Posts -->
<?php
$categories = get_the_category();
$related_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post__not_in'   => array(get_the_ID()),
    'post_status'    => 'publish',
);

if ($categories) {
    $related_args['category__in'] = wp_list_pluck($categories, 'term_id');
}

$related_query = new WP_Query($related_args);

if ($related_query->have_posts()) :
?>
    <section class="zz-related-posts">
        <h3 class="zz-related-posts__title"><?php esc_html_e('More Articles', 'zzprompts'); ?></h3>
        
        <div class="zz-related-posts__grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <article class="zz-related-post-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="zz-related-post-card__image">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="zz-related-post-card__content">
                        <span class="zz-related-post-card__date"><?php echo get_the_date(); ?></span>
                        <h4 class="zz-related-post-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>

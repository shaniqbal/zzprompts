<?php
/**
 * Template Part: Blog Single - Modern V1
 * Glass UI Design with Progress Bar
 * 
 * @package zzprompts
 * @version 2.0.0 - Modern V1 Launch
 */

defined('ABSPATH') || exit;

// Get post data
$categories = get_the_category();
$cat_name = !empty($categories) ? $categories[0]->name : '';
$tags = get_the_tags();
$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$read_time = zzprompts_reading_time(get_the_ID());

// Customizer options
$show_image = zzprompts_get_option('single_show_image', true);
$show_author = zzprompts_get_option('single_show_author', true);
$show_date = zzprompts_get_option('single_show_date', true);
$show_reading_time = zzprompts_get_option('single_show_reading_time', true);
$show_share = zzprompts_get_option('single_show_share_buttons', true);
$show_tags = zzprompts_get_option('single_show_tags', true);

// Check for AI generated content (custom field)
$is_ai_generated = get_post_meta(get_the_ID(), '_is_ai_generated', true);
?>

<!-- Reading Progress Bar -->
<div class="zz-read-progress" aria-hidden="true">
    <div class="zz-read-progress__bar" id="zzReadProgressBar"></div>
</div>

<div class="zz-blog-single-container">
    <div class="zz-blog-single-layout">
        
        <!-- Main Content -->
        <div class="zz-blog-main">
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('zz-blog-article'); ?>>
                
                <!-- Post Header -->
                <header class="zz-blog-header">
                    <div class="zz-blog-badges">
                        <?php if ($cat_name) : ?>
                            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="zz-blog-badge zz-blog-badge--primary">
                                <?php echo esc_html($cat_name); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (has_tag('tutorial')) : ?>
                            <span class="zz-blog-badge zz-blog-badge--success"><?php esc_html_e('Tutorial', 'zzprompts'); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="zz-blog-title"><?php the_title(); ?></h1>
                    
                    <div class="zz-blog-meta">
                        <?php if ($show_author) : ?>
                        <span class="zz-blog-meta__item">
                            <i class="fa-regular fa-user-circle"></i>
                            <?php echo esc_html($author_name); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($show_date) : ?>
                        <span class="zz-blog-meta__item">
                            <i class="fa-regular fa-calendar"></i>
                            <?php echo esc_html(get_the_date()); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($show_reading_time) : ?>
                        <span class="zz-blog-meta__item">
                            <i class="fa-regular fa-clock"></i>
                            <?php echo esc_html($read_time); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <!-- Featured Image or Placeholder -->
                <div class="zz-blog-featured<?php echo !has_post_thumbnail() ? ' zz-blog-featured--placeholder' : ''; ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'zz-blog-featured__img')); ?>
                    <?php else : 
                        // Enhanced Placeholder if no image
                        $colors = ['#EEF2FF', '#ECFDF5', '#F0F9FF', '#FEF2F2', '#F5F3FF'];
                        $bg_color = $colors[abs(crc32($cat_name)) % count($colors)];
                    ?>
                        <div class="zz-blog-featured__placeholder" style="background-color: <?php echo esc_attr($bg_color); ?>;">
                            <span class="zz-blog-featured__placeholder-text"><?php echo esc_html($cat_name); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($is_ai_generated) : ?>
                        <div class="zz-blog-featured__badge">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <?php esc_html_e('AI Generated', 'zzprompts'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Ad: Before Content -->
                <?php zz_render_ad('blog_top'); ?>
                
                <!-- Article Body -->
                <div class="zz-blog-content">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="zz-page-links">' . esc_html__('Pages:', 'zzprompts'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <!-- Ad: After Content -->
                <?php zz_render_ad('blog_bottom'); ?>
                
                <!-- Post Footer: Tags + Share -->
                <footer class="zz-blog-footer">
                    <?php if ($tags && $show_tags) : ?>
                    <div class="zz-blog-tags">
                        <strong><?php esc_html_e('Tags:', 'zzprompts'); ?></strong>
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="zz-blog-tag">
                                #<?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($show_share) : ?>
                    <div class="zz-blog-share">
                        <span class="zz-blog-share__label"><?php esc_html_e('Share:', 'zzprompts'); ?></span>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" rel="noopener" class="zz-blog-share__btn" aria-label="<?php esc_attr_e('Share on Twitter', 'zzprompts'); ?>">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="zz-blog-share__btn" aria-label="<?php esc_attr_e('Share on LinkedIn', 'zzprompts'); ?>">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="zz-blog-share__btn" aria-label="<?php esc_attr_e('Share on Facebook', 'zzprompts'); ?>">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </footer>
                
            </article>
            
            <?php
            // Comments Section - Customizer controlled
            $comments_enabled = (bool) intval(get_option('zzprompts_blog_comments_enabled', 0));
            
            if ($comments_enabled && (comments_open() || get_comments_number())) :
                comments_template();
            endif;
            ?>
            
        </div><!-- .zz-blog-main -->

        <!-- Sidebar -->
        <aside class="zz-blog-sidebar">
            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <?php dynamic_sidebar('sidebar-1'); ?>
            <?php else : ?>
                <!-- Default Sidebar Widgets -->
                <div class="zz-widget">
                    <h4 class="zz-widget__title"><?php esc_html_e('Search Articles', 'zzprompts'); ?></h4>
                    <div class="zz-widget__search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>

    </div><!-- .zz-blog-single-layout -->

    <?php
    /**
     * Related Posts Section
     */
    $categories = get_the_category();
    if ($categories) {
        $related_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => array(get_the_ID()),
            'category__in'   => wp_list_pluck($categories, 'term_id'),
        );
        $related_query = new WP_Query($related_args);
        
        if ($related_query->have_posts()) :
        ?>
        <section class="zz-blog-related">
            <h3 class="zz-blog-related__title"><?php esc_html_e('More Articles', 'zzprompts'); ?></h3>
            <div class="zz-blog-related__grid">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="zz-blog-related__card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="zz-blog-related__image">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                        <div class="zz-blog-related__content">
                            <span class="zz-blog-related__date"><?php echo get_the_date(); ?></span>
                            <h4 class="zz-blog-related__card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
        <?php 
        wp_reset_postdata();
        endif;
    }
    ?>
</div><!-- .zz-blog-single-container -->
<?php

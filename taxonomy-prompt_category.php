<?php
/**
 * Taxonomy Template: Prompt Category
 * 
 * Modern V1 Glassmorphism design for category archive pages.
 * Based on: ZZ Designs Ready/Modern Layout/Category Page/final v1.html
 * 
 * @package zzprompts
 * @version 2.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;

get_header();

// Current term data
$current_term = get_queried_object();
$term_id      = $current_term->term_id;
$term_count   = $current_term->count;
$term_desc    = term_description($term_id);

// Get related categories (exclude current)
$related_cats = get_terms(array(
    'taxonomy'   => 'prompt_category',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
    'hide_empty' => true,
    'exclude'    => $term_id,
));

// Get AI Tools for filter pills
$ai_tools = get_terms(array(
    'taxonomy'   => 'ai_tool',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 6,
    'hide_empty' => true,
));

// Get popular prompts in this category
$popular_args = array(
    'post_type'      => 'prompt',
    'posts_per_page' => 3,
    'tax_query'      => array(
        array(
            'taxonomy' => 'prompt_category',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
    ),
    'meta_key'       => '_prompt_likes',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
);
$popular_prompts = new WP_Query($popular_args);

// Sorting
$current_sort = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'newest';
$current_tool = isset($_GET['ai_tool']) ? sanitize_text_field($_GET['ai_tool']) : '';

// Calculate total likes in this category
$total_likes = 0;
$prompts_in_cat = get_posts(array(
    'post_type'      => 'prompt',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'prompt_category',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
    ),
    'fields' => 'ids',
));
foreach ($prompts_in_cat as $p_id) {
    $total_likes += absint(get_post_meta($p_id, '_prompt_likes', true));
}
?>

<div class="zz-cat-main">
    <div class="zz-cat-container">

        <!-- Main Content Area -->
        <div class="zz-cat-content">

            <!-- Category Hero Section -->
            <section class="zz-cat-hero">
                <span class="zz-cat-hero__badge">
                    <i class="fa-solid fa-folder-open"></i>
                    <?php esc_html_e('Category', 'zzprompts'); ?>
                </span>
                
                <h1 class="zz-cat-hero__title"><?php single_term_title(); ?></h1>
                
                <p class="zz-cat-hero__desc">
                    <?php
                    if ($term_desc) {
                        echo wp_kses_post($term_desc);
                    } else {
                        printf(
                            /* translators: 1: prompt count, 2: category name */
                            esc_html__('Explore our curated collection of %1$s high-quality prompts in the %2$s category.', 'zzprompts'),
                            '<strong>' . number_format_i18n($term_count) . '</strong>',
                            '<strong>' . esc_html(single_term_title('', false)) . '</strong>'
                        );
                    }
                    ?>
                </p>

                <div class="zz-cat-hero__stats">
                    <div class="zz-cat-stat">
                        <i class="fa-solid fa-layer-group"></i>
                        <?php 
                        /* translators: %s: number of prompts */
                        printf(esc_html__('%s Prompts', 'zzprompts'), number_format_i18n($term_count)); 
                        ?>
                    </div>
                    <div class="zz-cat-stat">
                        <i class="fa-solid fa-heart"></i>
                        <?php 
                        /* translators: %s: number of likes */
                        printf(esc_html__('%s Likes', 'zzprompts'), number_format_i18n($total_likes)); 
                        ?>
                    </div>
                    <?php if ($term_count > 50) : ?>
                    <div class="zz-cat-stat">
                        <i class="fa-solid fa-fire"></i>
                        <?php esc_html_e('Trending', 'zzprompts'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Sticky Filter Bar -->
            <div class="zz-cat-filter-bar">
                <div class="zz-cat-filter-pills">
                    <a href="<?php echo esc_url(remove_query_arg('ai_tool')); ?>" 
                       class="zz-cat-pill <?php echo empty($current_tool) ? 'zz-cat-pill--active' : ''; ?>">
                        <?php esc_html_e('All', 'zzprompts'); ?>
                    </a>
                    <?php if (!empty($ai_tools) && !is_wp_error($ai_tools)) : ?>
                        <?php foreach ($ai_tools as $tool) : ?>
                            <a href="<?php echo esc_url(add_query_arg('ai_tool', $tool->slug)); ?>" 
                               class="zz-cat-pill <?php echo ($current_tool === $tool->slug) ? 'zz-cat-pill--active' : ''; ?>">
                                <?php echo esc_html($tool->name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <select class="zz-cat-sort" onchange="window.location.href=this.value;">
                    <option value="<?php echo esc_url(add_query_arg('orderby', 'newest')); ?>" <?php selected($current_sort, 'newest'); ?>>
                        <?php esc_html_e('Sort: Newest', 'zzprompts'); ?>
                    </option>
                    <option value="<?php echo esc_url(add_query_arg('orderby', 'popular')); ?>" <?php selected($current_sort, 'popular'); ?>>
                        <?php esc_html_e('Most Popular', 'zzprompts'); ?>
                    </option>
                    <option value="<?php echo esc_url(add_query_arg('orderby', 'copies')); ?>" <?php selected($current_sort, 'copies'); ?>>
                        <?php esc_html_e('Most Copied', 'zzprompts'); ?>
                    </option>
                </select>
            </div>

            <!-- Prompt Grid -->
            <?php if (have_posts()) : ?>
            <div class="zz-cat-grid">
                <?php
                $card_count = 0;
                while (have_posts()) : the_post();
                    $card_count++;
                    
                    // Get prompt data
                    $prompt_id    = get_the_ID();
                    $likes        = absint(get_post_meta($prompt_id, '_prompt_likes', true));
                    $copies       = absint(get_post_meta($prompt_id, '_prompt_copies', true));
                    $ai_tool      = get_the_terms($prompt_id, 'ai_tool');
                    $tool_name    = (!empty($ai_tool) && !is_wp_error($ai_tool)) ? $ai_tool[0]->name : '';
                    $is_featured  = get_post_meta($prompt_id, '_zz_featured', true);
                    $excerpt      = wp_trim_words(get_the_excerpt(), 18, '...');
                    ?>

                    <article class="zz-cat-card">
                        <div class="zz-cat-card__header">
                            <?php if ($tool_name) : ?>
                                <span class="zz-cat-card__tool"><?php echo esc_html($tool_name); ?></span>
                            <?php else : ?>
                                <span class="zz-cat-card__tool"><?php esc_html_e('AI Prompt', 'zzprompts'); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($is_featured) : ?>
                                <i class="fa-solid fa-star zz-cat-card__featured" title="<?php esc_attr_e('Featured', 'zzprompts'); ?>"></i>
                            <?php endif; ?>
                        </div>

                        <div class="zz-cat-card__body">
                            <h3 class="zz-cat-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="zz-cat-card__preview"><?php echo esc_html($excerpt); ?></p>
                        </div>

                        <div class="zz-cat-card__footer">
                            <div class="zz-cat-card__stats">
                                <span><i class="fa-solid fa-heart"></i> <?php echo esc_html(zzprompts_format_number($likes)); ?></span>
                                <span><i class="fa-regular fa-copy"></i> <?php echo esc_html(zzprompts_format_number($copies)); ?></span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="zz-cat-card__btn">
                                <?php esc_html_e('View', 'zzprompts'); ?>
                            </a>
                        </div>
                    </article>

                    <?php
                    // Insert ad after 6th card
                    if ($card_count === 6 && function_exists('zz_get_ad') && zz_get_ad('archive_content')) :
                    ?>
                        <div class="zz-cat-card zz-cat-ad-slot">
                            <?php zz_render_ad('archive_content'); ?>
                        </div>
                    <?php endif; ?>

                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php
            $pagination = paginate_links(array(
                'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
                'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
                'type'      => 'array',
            ));

            if ($pagination) : ?>
            <nav class="zz-cat-pagination" aria-label="<?php esc_attr_e('Category pagination', 'zzprompts'); ?>">
                <?php foreach ($pagination as $page_link) : ?>
                    <?php echo wp_kses_post($page_link); ?>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>

            <?php else : ?>

            <!-- No Results -->
            <div class="zz-cat-no-results">
                <i class="fa-solid fa-folder-open"></i>
                <h2><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                <p><?php esc_html_e('No prompts found in this category yet. Check back soon!', 'zzprompts'); ?></p>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                    <?php esc_html_e('Browse All Prompts', 'zzprompts'); ?>
                </a>
            </div>

            <?php endif; ?>

        </div>

        <!-- Sidebar -->
        <aside class="zz-cat-sidebar">

            <!-- Search Widget -->
            <div class="zz-cat-widget">
                <h4 class="zz-cat-widget__title"><?php esc_html_e('Search Category', 'zzprompts'); ?></h4>
                <div class="zz-cat-search-wrap">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="text" 
                               class="zz-cat-search-input" 
                               name="s"
                               placeholder="<?php esc_attr_e('Keywords...', 'zzprompts'); ?>"
                               autocomplete="off">
                        <button type="submit" class="zz-cat-search-submit" aria-label="<?php esc_attr_e('Search', 'zzprompts'); ?>">
                            <i class="fa-solid fa-search zz-cat-search-icon"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Popular This Week Widget -->
            <?php if ($popular_prompts->have_posts()) : ?>
            <div class="zz-cat-widget">
                <h4 class="zz-cat-widget__title"><?php esc_html_e('Popular This Week', 'zzprompts'); ?></h4>
                <div class="zz-cat-popular-list">
                    <?php 
                    $icon_classes = array('fire', 'trending', 'views');
                    $counter = 0;
                    while ($popular_prompts->have_posts()) : $popular_prompts->the_post();
                        $p_likes = absint(get_post_meta(get_the_ID(), '_prompt_likes', true));
                        $icon = $icon_classes[$counter % 3];
                    ?>
                    <a href="<?php the_permalink(); ?>" class="zz-cat-popular-item">
                        <div class="zz-cat-popular-icon zz-cat-popular-icon--<?php echo esc_attr($icon); ?>">
                            <?php if ($icon === 'fire') : ?>
                                <i class="fa-solid fa-fire"></i>
                            <?php elseif ($icon === 'trending') : ?>
                                <i class="fa-solid fa-arrow-trend-up"></i>
                            <?php else : ?>
                                <i class="fa-solid fa-eye"></i>
                            <?php endif; ?>
                        </div>
                        <div class="zz-cat-popular-content">
                            <h5 class="zz-cat-popular-title"><?php the_title(); ?></h5>
                            <span class="zz-cat-popular-meta">
                                <?php 
                                /* translators: %s: number of likes */
                                printf(esc_html__('%s Likes', 'zzprompts'), number_format_i18n($p_likes)); 
                                ?>
                            </span>
                        </div>
                    </a>
                    <?php 
                        $counter++;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Categories Widget -->
            <?php if (!empty($related_cats) && !is_wp_error($related_cats)) : ?>
            <div class="zz-cat-widget">
                <h4 class="zz-cat-widget__title"><?php esc_html_e('Other Categories', 'zzprompts'); ?></h4>
                <div class="zz-cat-tags">
                    <?php foreach ($related_cats as $cat) : ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="zz-cat-tag">
                            <?php echo esc_html($cat->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ad Widget -->
            <?php if (function_exists('zz_get_ad') && zz_get_ad('sidebar')) : ?>
            <div class="zz-cat-widget">
                <?php zz_render_ad('sidebar'); ?>
            </div>
            <?php endif; ?>

        </aside>

    </div>
</div>


<?php get_footer(); ?>

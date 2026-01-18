<?php
/**
 * Taxonomy Template: AI Tool
 * 
 * Modern V1 Glassmorphism design for AI tool archive pages.
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
$term_slug    = $current_term->slug;

// AI Tool icons mapping
$tool_icons = array(
    'chatgpt'    => '<i class="fa-solid fa-comment-dots"></i>',
    'midjourney' => '<i class="fa-solid fa-paintbrush"></i>',
    'dall-e'     => '<i class="fa-solid fa-image"></i>',
    'gemini'     => '<i class="fa-solid fa-gem"></i>',
    'claude'     => '<i class="fa-solid fa-robot"></i>',
    'copilot'    => '<i class="fa-solid fa-code"></i>',
    'stable'     => '<i class="fa-solid fa-wand-magic-sparkles"></i>',
    'grok'       => '<i class="fa-solid fa-bolt"></i>',
    'default'    => '<i class="fa-solid fa-microchip"></i>',
);
$tool_icon = isset($tool_icons[$term_slug]) ? $tool_icons[$term_slug] : $tool_icons['default'];

// Get other AI tools (exclude current)
$other_tools = get_terms(array(
    'taxonomy'   => 'ai_tool',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
    'hide_empty' => true,
    'exclude'    => $term_id,
));

// Get prompt categories for filter pills
$prompt_cats = get_terms(array(
    'taxonomy'   => 'prompt_category',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 6,
    'hide_empty' => true,
));

// Get popular prompts for this tool
$popular_args = array(
    'post_type'      => 'prompt',
    'posts_per_page' => 3,
    'tax_query'      => array(
        array(
            'taxonomy' => 'ai_tool',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
    ),
    'meta_key'       => '_prompt_likes',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
);
$popular_prompts = new WP_Query($popular_args);

// Sorting & filters
$current_sort = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'newest';
$current_cat  = isset($_GET['prompt_category']) ? sanitize_text_field($_GET['prompt_category']) : '';

// Calculate total likes
$total_likes = 0;
$prompts_for_tool = get_posts(array(
    'post_type'      => 'prompt',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'ai_tool',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
    ),
    'fields' => 'ids',
));
foreach ($prompts_for_tool as $p_id) {
    $total_likes += absint(get_post_meta($p_id, '_prompt_likes', true));
}
?>

<div class="zz-cat-main">
    <div class="zz-cat-container">

        <!-- Main Content Area -->
        <div class="zz-cat-content">

            <!-- AI Tool Hero Section -->
            <section class="zz-cat-hero">
                <span class="zz-cat-hero__badge">
                    <?php echo $tool_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php esc_html_e('AI Tool', 'zzprompts'); ?>
                </span>
                
                <h1 class="zz-cat-hero__title">
                    <?php single_term_title(); ?> <?php esc_html_e('Prompts', 'zzprompts'); ?>
                </h1>
                
                <p class="zz-cat-hero__desc">
                    <?php
                    if ($term_desc) {
                        echo wp_kses_post($term_desc);
                    } else {
                        printf(
                            /* translators: 1: prompt count, 2: tool name */
                            esc_html__('Discover %1$s powerful prompts optimized for %2$s.', 'zzprompts'),
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
                    <div class="zz-cat-stat">
                        <i class="fa-solid fa-bolt"></i>
                        <?php esc_html_e('Instant Copy', 'zzprompts'); ?>
                    </div>
                </div>
            </section>

            <!-- Sticky Filter Bar -->
            <div class="zz-cat-filter-bar">
                <div class="zz-cat-filter-pills">
                    <a href="<?php echo esc_url(remove_query_arg('prompt_category')); ?>" 
                       class="zz-cat-pill <?php echo empty($current_cat) ? 'zz-cat-pill--active' : ''; ?>">
                        <?php esc_html_e('All', 'zzprompts'); ?>
                    </a>
                    <?php if (!empty($prompt_cats) && !is_wp_error($prompt_cats)) : ?>
                        <?php foreach ($prompt_cats as $cat) : ?>
                            <a href="<?php echo esc_url(add_query_arg('prompt_category', $cat->slug)); ?>" 
                               class="zz-cat-pill <?php echo ($current_cat === $cat->slug) ? 'zz-cat-pill--active' : ''; ?>">
                                <?php echo esc_html($cat->name); ?>
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
                    $categories   = get_the_terms($prompt_id, 'prompt_category');
                    $cat_name     = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
                    $is_featured  = get_post_meta($prompt_id, '_zz_featured', true);
                    $excerpt      = wp_trim_words(get_the_excerpt(), 18, '...');
                    ?>

                    <article class="zz-cat-card">
                        <div class="zz-cat-card__header">
                            <span class="zz-cat-card__tool"><?php echo esc_html(single_term_title('', false)); ?></span>
                            
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
            <nav class="zz-cat-pagination" aria-label="<?php esc_attr_e('AI Tool pagination', 'zzprompts'); ?>">
                <?php foreach ($pagination as $page_link) : ?>
                    <?php echo wp_kses_post($page_link); ?>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>

            <?php else : ?>

            <!-- No Results -->
            <div class="zz-cat-no-results">
                <i class="fa-solid fa-robot"></i>
                <h2><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                <p>
                    <?php 
                    printf(
                        /* translators: %s: tool name */
                        esc_html__('No prompts found for %s yet. Check back soon!', 'zzprompts'),
                        esc_html(single_term_title('', false))
                    ); 
                    ?>
                </p>
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
                <h4 class="zz-cat-widget__title">
                    <?php 
                    /* translators: %s: tool name */
                    printf(esc_html__('Search %s', 'zzprompts'), single_term_title('', false)); 
                    ?>
                </h4>
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

            <!-- Other AI Tools Widget -->
            <?php if (!empty($other_tools) && !is_wp_error($other_tools)) : ?>
            <div class="zz-cat-widget">
                <h4 class="zz-cat-widget__title"><?php esc_html_e('Other AI Tools', 'zzprompts'); ?></h4>
                <div class="zz-cat-tags">
                    <?php foreach ($other_tools as $tool) : ?>
                        <a href="<?php echo esc_url(get_term_link($tool)); ?>" class="zz-cat-tag">
                            <?php echo esc_html($tool->name); ?>
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

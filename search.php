<?php
/**
 * Search Results Page - Premium Modern Design
 * Features: Hero search, type tabs, keyword highlighting, sidebar
 *
 * @package zzprompts
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

// Get search query and results count
$search_query  = get_search_query();
$total_results = $wp_query->found_posts;

// Count by post type
$prompt_count = 0;
$blog_count   = 0;

if ($search_query) {
    // Count prompts
    $prompt_query = new WP_Query(array(
        's'              => $search_query,
        'post_type'      => 'prompt',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));
    $prompt_count = $prompt_query->found_posts;
    wp_reset_postdata();

    // Count blog posts
    $blog_query = new WP_Query(array(
        's'              => $search_query,
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));
    $blog_count = $blog_query->found_posts;
    wp_reset_postdata();
}

// Get active tab from URL parameter
$active_tab = isset($_GET['type']) ? sanitize_key($_GET['type']) : 'all';
if (!in_array($active_tab, array('all', 'prompt', 'post'), true)) {
    $active_tab = 'all';
}

/**
 * Highlight search keywords in text
 *
 * @param string $text Text to highlight
 * @param string $query Search query
 * @return string
 */
function zz_highlight_search_term($text, $query) {
    if (empty($query)) {
        return $text;
    }
    // Escape special regex characters
    $query_escaped = preg_quote($query, '/');
    // Replace with highlighted version (case-insensitive)
    return preg_replace('/(' . $query_escaped . ')/i', '<mark class="zz-highlight">$1</mark>', $text);
}
?>

<div class="zz-search-page">
    <div class="zz-search-container">

        <!-- Main Content Area -->
        <main class="zz-search-main">

            <!-- Hero Section -->
            <section class="zz-search-hero">
                <h1 class="zz-search-hero__title">
                    <?php esc_html_e('Results for', 'zzprompts'); ?>
                    <span class="zz-search-hero__query">"<?php echo esc_html($search_query); ?>"</span>
                </h1>

                <!-- Search Form -->
                <form class="zz-search-hero__form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="zz-search-hero__input-wrap">
                        <input type="search" 
                               name="s" 
                               class="zz-search-hero__input" 
                               value="<?php echo esc_attr($search_query); ?>" 
                               placeholder="<?php esc_attr_e('Refine your search...', 'zzprompts'); ?>">
                        <button type="submit" class="zz-search-hero__btn" aria-label="<?php esc_attr_e('Search', 'zzprompts'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <p class="zz-search-hero__meta">
                    <?php
                    printf(
                        /* translators: %1$s: prompt count, %2$s: blog post count */
                        esc_html__('Found %1$s prompts • %2$s blog posts', 'zzprompts'),
                        '<strong>' . number_format_i18n($prompt_count) . '</strong>',
                        '<strong>' . number_format_i18n($blog_count) . '</strong>'
                    );
                    ?>
                </p>
            </section>

            <!-- Filter Toolbar -->
            <div class="zz-search-toolbar">
                <div class="zz-search-tabs" role="tablist">
                    <a href="<?php echo esc_url(add_query_arg('type', 'all', remove_query_arg('paged'))); ?>" 
                       class="zz-search-tab <?php echo ('all' === $active_tab) ? 'zz-search-tab--active' : ''; ?>"
                       role="tab"
                       aria-selected="<?php echo ('all' === $active_tab) ? 'true' : 'false'; ?>">
                        <?php esc_html_e('All', 'zzprompts'); ?>
                        <span class="zz-search-tab__count"><?php echo esc_html(number_format_i18n($total_results)); ?></span>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg('type', 'prompt', remove_query_arg('paged'))); ?>" 
                       class="zz-search-tab <?php echo ('prompt' === $active_tab) ? 'zz-search-tab--active' : ''; ?>"
                       role="tab"
                       aria-selected="<?php echo ('prompt' === $active_tab) ? 'true' : 'false'; ?>">
                        <?php esc_html_e('Prompts', 'zzprompts'); ?>
                        <span class="zz-search-tab__count"><?php echo esc_html(number_format_i18n($prompt_count)); ?></span>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg('type', 'post', remove_query_arg('paged'))); ?>" 
                       class="zz-search-tab <?php echo ('post' === $active_tab) ? 'zz-search-tab--active' : ''; ?>"
                       role="tab"
                       aria-selected="<?php echo ('post' === $active_tab) ? 'true' : 'false'; ?>">
                        <?php esc_html_e('Blog Posts', 'zzprompts'); ?>
                        <span class="zz-search-tab__count"><?php echo esc_html(number_format_i18n($blog_count)); ?></span>
                    </a>
                </div>
            </div>

            <!-- Results Grid -->
            <?php
            // Modified query for filtered results
            if ('all' !== $active_tab) {
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $filtered_query = new WP_Query(array(
                    's'              => $search_query,
                    'post_type'      => $active_tab,
                    'posts_per_page' => 12,
                    'paged'          => $paged,
                ));
            } else {
                $filtered_query = $wp_query;
            }

            if ($filtered_query->have_posts()) : ?>

                <div class="zz-search-grid">
                    <?php while ($filtered_query->have_posts()) : $filtered_query->the_post(); ?>
                        <?php
                        $post_type  = get_post_type();
                        $is_prompt  = ('prompt' === $post_type);
                        $title      = get_the_title();
                        $excerpt    = wp_strip_all_tags(get_the_excerpt());
                        
                        // Highlight search terms
                        $title_highlighted   = zz_highlight_search_term($title, $search_query);
                        $excerpt_highlighted = zz_highlight_search_term(wp_trim_words($excerpt, 20, '...'), $search_query);
                        ?>

                        <article class="zz-res-card <?php echo $is_prompt ? 'zz-res-card--prompt' : 'zz-res-card--blog'; ?>">
                            
                            <?php if (!$is_prompt && has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="zz-res-card__thumb">
                                    <?php the_post_thumbnail('medium', array('class' => 'zz-res-card__thumb-img')); ?>
                                </a>
                            <?php endif; ?>

                            <div class="zz-res-card__header">
                                <span class="zz-res-card__badge <?php echo $is_prompt ? 'zz-res-card__badge--prompt' : 'zz-res-card__badge--blog'; ?>">
                                    <?php echo $is_prompt ? esc_html__('Prompt', 'zzprompts') : esc_html__('Blog Post', 'zzprompts'); ?>
                                </span>
                                <span class="zz-res-card__meta-label">
                                    <?php
                                    if ($is_prompt) {
                                        $tools = get_the_terms(get_the_ID(), 'ai_tool');
                                        if ($tools && !is_wp_error($tools)) {
                                            echo esc_html($tools[0]->name);
                                        }
                                    } else {
                                        echo esc_html(zzprompts_reading_time());
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="zz-res-card__body">
                                <h3 class="zz-res-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php echo wp_kses($title_highlighted, array('mark' => array('class' => array()))); ?></a>
                                </h3>
                                <p class="zz-res-card__excerpt"><?php echo wp_kses($excerpt_highlighted, array('mark' => array('class' => array()))); ?></p>
                            </div>

                            <div class="zz-res-card__footer">
                                <?php if ($is_prompt) : ?>
                                    <div class="zz-res-card__stats">
                                        <span><i class="fas fa-heart"></i> <?php echo esc_html(zzprompts_format_number(zzprompts_get_likes(get_the_ID()))); ?></span>
                                        <span><i class="far fa-copy"></i> <?php echo esc_html(zzprompts_format_number(zzprompts_get_copy_count(get_the_ID()))); ?></span>
                                    </div>
                                <?php else : ?>
                                    <span class="zz-res-card__date"><?php echo esc_html(get_the_date('M j, Y')); ?></span>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="zz-res-card__link">
                                    <?php echo $is_prompt ? esc_html__('View', 'zzprompts') : esc_html__('Read', 'zzprompts'); ?>
                                </a>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php
                if ('all' !== $active_tab) {
                    // Custom pagination for filtered query
                    $big = 999999999;
                    echo '<nav class="zz-search-pagination" aria-label="' . esc_attr__('Search results pagination', 'zzprompts') . '">';
                    echo paginate_links(array(
                        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format'    => '?paged=%#%',
                        'current'   => max(1, get_query_var('paged')),
                        'total'     => $filtered_query->max_num_pages,
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                    ));
                    echo '</nav>';
                } else {
                    // the_posts_pagination() outputs its own <nav> container
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                        'class'     => 'zz-search-pagination',
                    ));
                }
                ?>

            <?php else : ?>

                <!-- No Results -->
                <div class="zz-search-empty">
                    <div class="zz-search-empty__icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2 class="zz-search-empty__title"><?php esc_html_e('No Results Found', 'zzprompts'); ?></h2>
                    <p class="zz-search-empty__text">
                        <?php esc_html_e('Sorry, nothing matched your search. Try different keywords or browse our content.', 'zzprompts'); ?>
                    </p>
                    <div class="zz-search-empty__actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-btn zz-btn--primary">
                            <i class="fas fa-home"></i> <?php esc_html_e('Go Home', 'zzprompts'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn zz-btn--outline">
                            <i class="fas fa-layer-group"></i> <?php esc_html_e('Browse Prompts', 'zzprompts'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="zz-btn zz-btn--outline">
                            <i class="fas fa-newspaper"></i> <?php esc_html_e('Browse Articles', 'zzprompts'); ?>
                        </a>
                    </div>
                </div>

            <?php endif; ?>

            <?php
            if ('all' !== $active_tab) {
                wp_reset_postdata();
            }
            ?>

        </main>

        <!-- Sidebar -->
        <aside class="zz-search-sidebar">

            <!-- Related Keywords Widget -->
            <?php
            // Get related terms from prompt taxonomies
            $related_terms = array();
            if ($search_query) {
                $term_args = array(
                    'taxonomy'   => array('prompt_category', 'ai_tool'),
                    'name__like' => $search_query,
                    'number'     => 8,
                    'hide_empty' => true,
                );
                $found_terms = get_terms($term_args);
                if (!is_wp_error($found_terms)) {
                    $related_terms = $found_terms;
                }
            }
            if (!empty($related_terms)) : ?>
                <div class="zz-search-widget">
                    <h4 class="zz-search-widget__title"><?php esc_html_e('Related Keywords', 'zzprompts'); ?></h4>
                    <div class="zz-search-widget__tags">
                        <?php foreach ($related_terms as $term) : ?>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="zz-search-widget__tag">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Popular Searches Widget -->
            <div class="zz-search-widget">
                <h4 class="zz-search-widget__title"><?php esc_html_e('Popular Searches', 'zzprompts'); ?></h4>
                <ul class="zz-search-widget__list">
                    <?php
                    // Get popular prompt categories
                    $popular_cats = get_terms(array(
                        'taxonomy'   => 'prompt_category',
                        'number'     => 5,
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'hide_empty' => true,
                    ));
                    if (!is_wp_error($popular_cats) && !empty($popular_cats)) :
                        foreach ($popular_cats as $cat) : ?>
                            <li>
                                <a href="<?php echo esc_url(add_query_arg('s', urlencode($cat->name), home_url('/'))); ?>">
                                    <i class="fas fa-search"></i>
                                    <?php echo esc_html($cat->name); ?>
                                </a>
                            </li>
                        <?php endforeach;
                    endif;
                    ?>
                </ul>
            </div>

            <!-- Ad Slot Widget -->
            <div class="zz-search-widget zz-search-widget--ad">
                <span class="zz-search-widget__ad-label"><?php esc_html_e('Advertisement', 'zzprompts'); ?></span>
                <div class="zz-search-widget__ad-slot">
                    <?php
                    /**
                     * Hook for ad placement on search results sidebar
                     * Use: add_action('zzprompts_search_sidebar_ad', 'your_ad_function');
                     */
                    do_action('zzprompts_search_sidebar_ad');
                    ?>
                    <span><?php esc_html_e('Ad Space', 'zzprompts'); ?></span>
                </div>
            </div>

        </aside>

    </div>
</div>

<?php get_footer(); ?>

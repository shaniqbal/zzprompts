<?php
/**
 * The template for displaying Prompt Category pages
 * Premium V1 Classic Design - Light & Clean
 *
 * @package zzprompts
 * @version 3.0.0
 */

defined('ABSPATH') || exit;

get_header();

$current_term = get_queried_object();
$term_count = $current_term->count;

// Get related categories
$related_cats = get_terms(array(
    'taxonomy'   => 'prompt_category',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 6,
    'hide_empty' => true,
    'exclude'    => $current_term->term_id,
));

// Get popular prompts in this category
$popular_args = array(
    'post_type'      => 'prompt',
    'posts_per_page' => 3,
    'tax_query'      => array(
        array(
            'taxonomy' => 'prompt_category',
            'field'    => 'term_id',
            'terms'    => $current_term->term_id,
        ),
    ),
    'meta_key'       => '_prompt_likes',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
);
$popular_prompts = new WP_Query($popular_args);
?>

<!-- Premium Light Header with Breadcrumbs -->
<div class="zz-prm-header">
    <div class="container">
        <div class="zz-prm-header-inner">
            <!-- Breadcrumbs -->
            <nav class="zz-prm-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'zzprompts'); ?></a>
                <i class="fas fa-chevron-right"></i>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>"><?php esc_html_e('Categories', 'zzprompts'); ?></a>
                <i class="fas fa-chevron-right"></i>
                <span class="current"><?php single_term_title(); ?></span>
            </nav>
        </div>
    </div>
</div>

<!-- Hero Section with Search -->
<section class="zz-prm-hero">
    <div class="container">
        <h1 class="zz-prm-hero-title"><?php single_term_title(); ?></h1>
        <p class="zz-prm-hero-desc">
            <?php 
            if (term_description()) {
                echo wp_kses_post(term_description());
            } else {
                printf(
                    esc_html__('Explore %s ready-to-use prompts in the %s category.', 'zzprompts'), 
                    '<strong>' . number_format_i18n($term_count) . '</strong>',
                    '<strong>' . esc_html(single_term_title('', false)) . '</strong>'
                );
            }
            ?>
        </p>

        <!-- Category Search Bar -->
        <div class="zz-prm-cat-search">
            <input type="text" 
                   class="zz-prm-cat-input" 
                   placeholder="<?php printf(esc_attr__('Search in %s...', 'zzprompts'), single_term_title('', false)); ?>"
                   id="zzCatSearch"
                   autocomplete="off">
            <i class="fas fa-search zz-prm-cat-icon"></i>
        </div>
    </div>
</section>

<!-- Search Functionality Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('zzCatSearch');
    const cardGrid = document.querySelector('.zz-prm-card-grid');
    const cards = document.querySelectorAll('.zz-prm-card');
    
    // Create no results message
    const noResultsDiv = document.createElement('div');
    noResultsDiv.className = 'zz-prm-no-search-results';
    noResultsDiv.style.display = 'none';
    noResultsDiv.innerHTML = `
        <i class="fas fa-search"></i>
        <h3>No Results Found</h3>
        <p>Try adjusting your search terms</p>
    `;
    
    if (cardGrid) {
        cardGrid.parentNode.insertBefore(noResultsDiv, cardGrid);
    }
    
    if (searchInput && cards.length > 0) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            cards.forEach(function(card) {
                const title = card.querySelector('.zz-prm-card-title')?.textContent.toLowerCase() || '';
                const desc = card.querySelector('.zz-prm-card-desc')?.textContent.toLowerCase() || '';
                const chip = card.querySelector('.zz-prm-chip')?.textContent.toLowerCase() || '';
                
                const matches = title.includes(searchTerm) || 
                               desc.includes(searchTerm) || 
                               chip.includes(searchTerm);
                
                if (matches || searchTerm === '') {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                cardGrid.style.display = 'none';
                noResultsDiv.style.display = 'block';
            } else {
                cardGrid.style.display = '';
                noResultsDiv.style.display = 'none';
            }
        });
    }
});
</script>

<!-- Main Layout: Content + Sidebar -->
<div class="container">
    <div class="zz-prm-layout">
        
        <!-- Main Content Area -->
        <main class="zz-prm-content">
            
            <?php if (have_posts()) : ?>
            
            <!-- Prompt Cards Grid -->
            <div class="zz-prm-card-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    // Get prompt metadata
                    $likes = get_post_meta(get_the_ID(), '_prompt_likes', true);
                    $likes = $likes ? intval($likes) : 0;
                    $ai_tools = get_the_terms(get_the_ID(), 'ai_tool');
                    $ai_tool_name = (!empty($ai_tools) && !is_wp_error($ai_tools)) ? $ai_tools[0]->name : 'AI Prompt';
                    ?>
                    
                    <article class="zz-prm-card">
                        <div class="zz-prm-card-inner">
                            
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="zz-prm-img-wrapper">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <h3 class="zz-prm-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <p class="zz-prm-card-desc">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            
                            <div class="zz-prm-card-footer">
                                <span class="zz-prm-chip"><?php echo esc_html($ai_tool_name); ?></span>
                                <div class="zz-prm-likes">
                                    <i class="fas fa-heart"></i>
                                    <?php echo number_format_i18n($likes); ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php 
            $pagination = paginate_links(array(
                'prev_text' => '<i class="fas fa-arrow-left"></i>',
                'next_text' => '<i class="fas fa-arrow-right"></i>',
            ));
            
            if ($pagination) : ?>
            <div class="zz-prm-pagination">
                <?php echo wp_kses_post($pagination); ?>
            </div>
            <?php endif; ?>

            <?php else : ?>

            <!-- No Results -->
            <div class="zz-prm-no-results">
                <i class="fas fa-folder-open"></i>
                <h2><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                <p><?php esc_html_e('No prompts found in this category yet. Check back soon!', 'zzprompts'); ?></p>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-prm-browse-btn">
                    <?php esc_html_e('Browse All Prompts', 'zzprompts'); ?>
                </a>
            </div>

            <?php endif; ?>
            
        </main>

        <!-- Sidebar Widgets -->
        <aside class="zz-prm-sidebar">
            
            <!-- Top Categories Widget with Total Likes -->
            <?php 
            // Get all categories
            $top_categories = get_terms(array(
                'taxonomy'   => 'prompt_category',
                'orderby'    => 'count',
                'order'      => 'DESC',
                'number'     => 3,
                'hide_empty' => true,
            ));
            
            if (!empty($top_categories) && !is_wp_error($top_categories)) :
            ?>
            <div class="zz-prm-widget">
                <div class="zz-prm-widget-head"><?php esc_html_e('Top This Week', 'zzprompts'); ?></div>
                <div class="zz-prm-top-list">
                    <?php 
                    foreach ($top_categories as $cat_term) :
                        // Get all prompts in this category and sum their likes
                        $cat_prompts = get_posts(array(
                            'post_type'      => 'prompt',
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'prompt_category',
                                    'field'    => 'term_id',
                                    'terms'    => $cat_term->term_id,
                                ),
                            ),
                            'fields'         => 'ids',
                        ));
                        
                        $total_likes = 0;
                        if (!empty($cat_prompts)) {
                            foreach ($cat_prompts as $prompt_id) {
                                $prompt_likes = get_post_meta($prompt_id, '_prompt_likes', true);
                                if ($prompt_likes && is_numeric($prompt_likes)) {
                                    $total_likes += intval($prompt_likes);
                                }
                            }
                        }
                        
                        // Get category image (first prompt's thumbnail)
                        $cat_thumb = '';
                        if (!empty($cat_prompts)) {
                            $first_prompt = $cat_prompts[0];
                            if (has_post_thumbnail($first_prompt)) {
                                $cat_thumb = get_the_post_thumbnail_url($first_prompt, 'thumbnail');
                            }
                        }
                    ?>
                    <a href="<?php echo esc_url(get_term_link($cat_term)); ?>" class="zz-prm-top-item">
                        <?php if ($cat_thumb) : ?>
                        <img src="<?php echo esc_url($cat_thumb); ?>" 
                             class="zz-prm-top-thumb" 
                             alt="<?php echo esc_attr($cat_term->name); ?>">
                        <?php else : ?>
                        <div class="zz-prm-top-thumb zz-prm-thumb-placeholder">
                            <i class="fas fa-folder"></i>
                        </div>
                        <?php endif; ?>
                        <div class="zz-prm-top-content">
                            <h5><?php echo esc_html($cat_term->name); ?></h5>
                            <span>
                                <i class="fas fa-heart" style="color:var(--prm-heart)"></i>
                                <?php echo number_format_i18n($total_likes); ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Categories Widget -->
            <?php if (!empty($related_cats) && !is_wp_error($related_cats)) : ?>
            <div class="zz-prm-widget">
                <div class="zz-prm-widget-head"><?php esc_html_e('Related Categories', 'zzprompts'); ?></div>
                <div class="zz-prm-tax-cloud">
                    <?php foreach ($related_cats as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="zz-prm-tag">
                        <?php echo esc_html($term->name); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </aside>

    </div>
</div>

<?php
get_footer();

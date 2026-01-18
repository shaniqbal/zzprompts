<?php
/**
 * The template for displaying AI Tool pages
 *
 * @package zzprompts
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

$current_term = get_queried_object();
$term_count = $current_term->count;

// Get other AI tools
$other_tools = get_terms(array(
    'taxonomy'   => 'ai_tool',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
    'hide_empty' => true,
    'exclude'    => $current_term->term_id,
));

// AI Tool icons mapping
$tool_icons = array(
    'chatgpt' => '💬',
    'midjourney' => '🎨',
    'dalle' => '🖼️',
    'gemini' => '✨',
    'claude' => '🤖',
    'copilot' => '👨‍💻',
    'default' => '🤖',
);
$tool_slug = sanitize_title($current_term->name);
$tool_icon = isset($tool_icons[$tool_slug]) ? $tool_icons[$tool_slug] : $tool_icons['default'];
?>

<main id="primary" class="site-main">
    
    <!-- Hero Section -->
    <section class="zz-tax-hero zz-tax-hero--tool">
        <div class="zz-tax-hero__bg">
            <div class="zz-tax-float zz-tax-float--1"></div>
            <div class="zz-tax-float zz-tax-float--2"></div>
        </div>
        
        <div class="container">
            <div class="zz-tax-hero__content">
                <!-- Breadcrumbs -->
                <nav class="zz-tax-breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'zzprompts'); ?></a>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>"><?php esc_html_e('Prompts', 'zzprompts'); ?></a>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    <span><?php single_term_title(); ?></span>
                </nav>
                
                <!-- Tool Icon -->
                <div class="zz-tax-tool-icon">
                    <?php echo $tool_icon; ?>
                </div>
                
                <!-- Tool Badge -->
                <div class="zz-tax-badge zz-tax-badge--tool">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <?php esc_html_e('AI Tool', 'zzprompts'); ?>
                </div>
                
                <!-- Title -->
                <h1 class="zz-tax-title"><?php single_term_title(); ?> <?php esc_html_e('Prompts', 'zzprompts'); ?></h1>
                
                <!-- Description -->
                <p class="zz-tax-desc">
                    <?php 
                    if (term_description()) {
                        echo wp_kses_post(term_description());
                    } else {
                        printf(esc_html__('Discover %s powerful prompts optimized for %s.', 'zzprompts'), 
                            '<strong>' . number_format_i18n($term_count) . '</strong>',
                            '<strong>' . esc_html(single_term_title('', false)) . '</strong>'
                        );
                    }
                    ?>
                </p>
                
                <!-- Stats -->
                <div class="zz-tax-stats">
                    <div class="zz-tax-stat">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span><?php echo esc_html(number_format_i18n($term_count)); ?> <?php esc_html_e('Prompts', 'zzprompts'); ?></span>
                    </div>
                    <div class="zz-tax-stat">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span><?php esc_html_e('Instant Copy', 'zzprompts'); ?></span>
                    </div>
                </div>
                
                <!-- Search Box -->
                <div class="zz-tax-search">
                    <form role="search" method="get" class="zz-tax-search__form" action="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>">
                        <svg class="zz-tax-search__icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="search" 
                               class="zz-tax-search__input" 
                               name="s" 
                               placeholder="<?php printf(esc_attr__('Search %s prompts...', 'zzprompts'), single_term_title('', false)); ?>"
                               value="<?php echo esc_attr(get_search_query()); ?>">
                        <button type="submit" class="zz-tax-search__btn">
                            <?php esc_html_e('Search', 'zzprompts'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Scalable Dropdown Filters -->
    <section class="zz-filter-bar">
        <div class="container">
            <form class="zz-filters-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>">
                
                <div class="zz-select-wrapper">
                    <select name="prompt_category" class="zz-select" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e('All Categories', 'zzprompts'); ?></option>
                        <?php
                        $cat_args = array(
                            'taxonomy'   => 'prompt_category',
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'number'     => 50,
                            'hide_empty' => true
                        );
                        $cats = get_terms($cat_args);
                        
                        $current_cat = get_query_var('prompt_category');
                        
                        if (!empty($cats) && !is_wp_error($cats)) {
                            foreach ($cats as $cat) {
                                $is_selected = ($current_cat === $cat->slug || (isset($_GET['prompt_category']) && $_GET['prompt_category'] === $cat->slug));
                                $selected = $is_selected ? 'selected' : '';
                                echo '<option value="' . esc_attr($cat->slug) . '" ' . $selected . '>' . esc_html($cat->name) . ' (' . $cat->count . ')</option>';
                            }
                        }
                        ?>
                    </select>
                    <svg class="zz-select-arrow" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                </div>

                <div class="zz-select-wrapper">
                    <select name="ai_tool" class="zz-select" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e('All AI Models', 'zzprompts'); ?></option>
                        <?php
                        $tool_args = array(
                            'taxonomy'   => 'ai_tool',
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'number'     => 50,
                            'hide_empty' => true
                        );
                        $tools = get_terms($tool_args);
                        
                        $current_tool = get_query_var('ai_tool'); 

                        if (!empty($tools) && !is_wp_error($tools)) {
                            foreach ($tools as $tool) {
                                $is_selected = ($current_tool === $tool->slug || (isset($_GET['ai_tool']) && $_GET['ai_tool'] === $tool->slug));
                                $selected = $is_selected ? 'selected' : '';
                                echo '<option value="' . esc_attr($tool->slug) . '" ' . $selected . '>' . esc_html($tool->name) . ' (' . $tool->count . ')</option>';
                            }
                        }
                        ?>
                    </select>
                    <svg class="zz-select-arrow" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                </div>

                <div class="zz-select-wrapper">
                    <select name="orderby" class="zz-select" onchange="this.form.submit()">
                        <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : 'date', 'date'); ?>><?php esc_html_e('Newest First', 'zzprompts'); ?></option>
                        <option value="meta_value_num" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'meta_value_num'); ?>><?php esc_html_e('Most Popular', 'zzprompts'); ?></option>
                        <option value="title" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title'); ?>><?php esc_html_e('Alphabetical (A-Z)', 'zzprompts'); ?></option>
                    </select>
                    <svg class="zz-select-arrow" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                </div>

            </form>
        </div>
    </section>
    
    <!-- Other AI Tools -->
    <?php if (!empty($other_tools) && !is_wp_error($other_tools)) : ?>
    <section class="zz-tax-related">
        <div class="container">
            <div class="zz-tax-related__header">
                <span class="zz-tax-related__label"><?php esc_html_e('Other AI Tools:', 'zzprompts'); ?></span>
                <div class="zz-tax-related__pills">
                    <?php foreach ($other_tools as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="zz-tax-pill zz-tax-pill--tool">
                        <?php echo esc_html($term->name); ?>
                        <span class="zz-tax-pill__count"><?php echo esc_html($term->count); ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Prompts Grid -->
    <section class="zz-tax-prompts">
        <div class="container">
            <?php if (have_posts()) : ?>
            
            <!-- Section Header -->
            <div class="zz-tax-section__header">
                <h2 class="zz-tax-section__title">
                    <?php printf(esc_html__('All %s Prompts', 'zzprompts'), single_term_title('', false)); ?>
                </h2>
                <span class="zz-tax-results-count">
                    <?php printf(esc_html(_n('%s prompt', '%s prompts', $term_count, 'zzprompts')), number_format_i18n($term_count)); ?>
                </span>
            </div>
            
            <div class="zz-prompts-grid zz-tax-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/prompt/content', 'grid');
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <?php 
            $pagination = paginate_links(array(
                'prev_text' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>',
                'next_text' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>',
            ));
            
            if ($pagination) : ?>
            <div class="zz-tax-pagination">
                <?php echo wp_kses_post($pagination); ?>
            </div>
            <?php endif; ?>

            <?php else : ?>

            <div class="zz-tax-no-results">
                <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h2><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                <p><?php printf(esc_html__('No prompts found for %s yet. Check back soon!', 'zzprompts'), single_term_title('', false)); ?></p>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-tax-browse-btn">
                    <?php esc_html_e('Browse All Prompts', 'zzprompts'); ?>
                </a>
            </div>

            <?php endif; ?>
        </div>
    </section>
    
</main>

<?php
get_footer();

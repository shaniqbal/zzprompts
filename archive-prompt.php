<?php
/**
 * The template for displaying Prompt Archive pages
 * Modern V1 Layout - Glassmorphism Design
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

// Get total prompts count
$counts = wp_count_posts('prompt');
$total_prompts = isset($counts->publish) ? $counts->publish : 0;

// Check if expanded view requested
$view_mode = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : '';
$show_all_tools = ($view_mode === 'ai_tools');
$show_all_cats = ($view_mode === 'categories');

// Get filter terms - show all if expanded, otherwise limit to 8
$ai_tools = get_terms(array(
    'taxonomy'   => 'ai_tool',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => $show_all_tools ? 0 : 8,
    'hide_empty' => true
));

$prompt_cats = get_terms(array(
    'taxonomy'   => 'prompt_category',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => $show_all_cats ? 0 : 8,
    'hide_empty' => true
));

// Get total counts for "Show More" logic
$total_tools = wp_count_terms(array('taxonomy' => 'ai_tool', 'hide_empty' => true));
$total_cats = wp_count_terms(array('taxonomy' => 'prompt_category', 'hide_empty' => true));

// Current filters
$current_tool = isset($_GET['ai_tool']) ? sanitize_text_field($_GET['ai_tool']) : '';
$current_cat = isset($_GET['prompt_category']) ? sanitize_text_field($_GET['prompt_category']) : '';
$current_search = isset($_GET['s_prompt']) ? sanitize_text_field($_GET['s_prompt']) : '';
?>

<main id="primary" class="site-main zz-archive-main">
    <div class="zz-archive-container">
        
        <!-- Sidebar Filters -->
        <?php get_template_part('template-parts/sidebar', 'prompts'); ?>
        
        <!-- Main Content Grid -->
        <div class="zz-archive-content">
            
            <?php 
            // SEO Intro Section (only on first page, no filters)
            $show_intro = !is_paged() && empty($current_tool) && empty($current_cat) && empty($current_search);
            $archive_title = zzprompts_get_option('archive_seo_title', __('AI Prompt Library', 'zzprompts'));
            $archive_desc = zzprompts_get_option('archive_seo_description', '');
            
            if ($show_intro && (!empty($archive_title) || !empty($archive_desc))) : ?>
            <header class="zz-archive-header">
                <?php if (!empty($archive_title)) : ?>
                    <h1 class="zz-archive-title"><?php echo esc_html($archive_title); ?></h1>
                <?php endif; ?>
                <?php if (!empty($archive_desc)) : ?>
                    <p class="zz-archive-intro"><?php echo esc_html($archive_desc); ?></p>
                <?php endif; ?>
            </header>
            <?php endif; ?>
            
            <?php if (have_posts()) : ?>
            
            <div class="zz-prompts-grid">
                <?php 
                $card_count = 0;
                while (have_posts()) : the_post();
                    $card_count++;
                    
                    get_template_part('template-parts/prompt/card', 'prompt');

                    // Ad slot after 6th card (full width) to prevent grid gaps
                    if ($card_count === 6 && zz_get_ad('archive_content')) :
                        ?>
                        <div class="zz-archive-ad-slot">
                            <?php zz_render_ad('archive_content'); ?>
                        </div>
                        <?php
                    endif;
                endwhile;
                ?>
            </div>
            
            <!-- Pagination -->
            <?php
            $pag_args = array(
                'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
                'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
                'type'      => 'array',
            );
            
            // Preserve filters in pagination
            $filter_params = array();
            foreach (array('ai_tool', 'prompt_category', 's_prompt') as $param) {
                if (!empty($_GET[$param])) {
                    $filter_params[$param] = sanitize_text_field($_GET[$param]);
                }
            }
            if (!empty($filter_params)) {
                $pag_args['add_args'] = $filter_params;
            }
            
            $pagination = paginate_links($pag_args);
            
            if ($pagination) : ?>
            <nav class="zz-pagination" aria-label="<?php esc_attr_e('Prompt navigation', 'zzprompts'); ?>">
                <?php foreach ($pagination as $page_link) : ?>
                    <?php echo wp_kses_post($page_link); ?>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>
            
            <?php else : ?>
            
            <!-- No Results -->
            <div class="zz-archive-empty">
                <i class="fa-regular fa-folder-open"></i>
                <h2><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                <p><?php esc_html_e('We couldn\'t find any prompts matching your criteria. Try a different search or browse all prompts.', 'zzprompts'); ?></p>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn zz-btn--primary">
                    <?php esc_html_e('Browse All Prompts', 'zzprompts'); ?>
                </a>
            </div>
            
            <?php endif; ?>
            
        </div>
        
    </div>
</main>

<?php get_footer(); ?>


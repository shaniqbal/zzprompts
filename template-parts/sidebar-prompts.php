<?php
/**
 * Prompt Archive Sidebar - AJAX Filters (Chip Design)
 *
 * @package zzprompts
 * @version 2.0.0 - Clean Launch
 */

defined('ABSPATH') || exit;

// Check Customizer Setting for showing counts
$show_counts = zzprompts_get_option('sidebar_filter_show_counts', false);
?>

<aside class="zz-archive-sidebar">
    <form id="zz-filter-form" class="zz-filter-form" onsubmit="return false;">
        
        <?php if (is_author()) : ?>
            <input type="hidden" name="post_type" value="prompt">
            <input type="hidden" name="author_name" value="<?php echo esc_attr(get_the_author_meta('user_nicename', get_queried_object_id())); ?>">
        <?php endif; ?>

        <div class="zz-filter-section zz-search-section">
            <div class="zz-search-wrapper">
                <input type="text" name="s_prompt" id="zz-filter-search" class="zz-filter-search" placeholder="<?php esc_attr_e('Search prompts...', 'zzprompts'); ?>" autocomplete="off">
                <span class="zz-search-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
        </div>

        <div class="zz-filter-section">
            <h3 class="zz-filter-title"><?php esc_html_e('AI Model', 'zzprompts'); ?></h3>
            <div class="zz-chips-container" data-show-more="6">
                <?php
                $tools = get_terms(array('taxonomy' => 'ai_tool', 'hide_empty' => true));
                if (!empty($tools) && !is_wp_error($tools)) {
                    $tool_count = count($tools);
                    foreach ($tools as $index => $term) {
                        $is_active = (isset($_GET['ai_tool']) && in_array($term->slug, (array)$_GET['ai_tool']));
                        $is_hidden = ($index >= 6) ? ' zz-chip-hidden' : '';
                        ?>
                        <label class="zz-chip <?php echo $is_active ? 'active' : ''; ?><?php echo esc_attr($is_hidden); ?>">
                            <input type="checkbox" name="ai_tool[]" value="<?php echo esc_attr($term->slug); ?>" <?php checked($is_active); ?>>
                            <span class="zz-chip__label"><?php echo esc_html($term->name); ?></span>
                            <?php if ( $show_counts ) : ?>
                                <span class="zz-chip__count"><?php echo esc_html($term->count); ?></span>
                            <?php endif; ?>
                        </label>
                        <?php
                    }
                    if ($tool_count > 6) : ?>
                        <button type="button" class="zz-show-more-btn" data-section="ai-model">
                            <span class="show-more-text"><?php esc_html_e('Show More', 'zzprompts'); ?></span>
                            <span class="show-less-text" style="display: none;"><?php esc_html_e('Show Less', 'zzprompts'); ?></span>
                        </button>
                    <?php endif;
                }
                ?>
            </div>
        </div>

        <div class="zz-filter-section">
            <h3 class="zz-filter-title"><?php esc_html_e('Category', 'zzprompts'); ?></h3>
            <div class="zz-chips-container" data-show-more="6">
                <?php
                $cats = get_terms(array('taxonomy' => 'prompt_category', 'hide_empty' => true));
                if (!empty($cats) && !is_wp_error($cats)) {
                    $cat_count = count($cats);
                    foreach ($cats as $index => $term) {
                        $is_active = (isset($_GET['prompt_category']) && in_array($term->slug, (array)$_GET['prompt_category']));
                        $is_hidden = ($index >= 6) ? ' zz-chip-hidden' : '';
                        ?>
                        <label class="zz-chip <?php echo $is_active ? 'active' : ''; ?><?php echo esc_attr($is_hidden); ?>">
                            <input type="checkbox" name="prompt_category[]" value="<?php echo esc_attr($term->slug); ?>" <?php checked($is_active); ?>>
                            <span class="zz-chip__label"><?php echo esc_html($term->name); ?></span>
                            <?php if ( $show_counts ) : ?>
                                <span class="zz-chip__count"><?php echo esc_html($term->count); ?></span>
                            <?php endif; ?>
                        </label>
                        <?php
                    }
                    if ($cat_count > 6) : ?>
                        <button type="button" class="zz-show-more-btn" data-section="category">
                            <span class="show-more-text"><?php esc_html_e('Show More', 'zzprompts'); ?></span>
                            <span class="show-less-text" style="display: none;"><?php esc_html_e('Show Less', 'zzprompts'); ?></span>
                        </button>
                    <?php endif;
                }
                ?>
            </div>
        </div>
        
        <div id="zz-filter-loader" style="display:none;"></div>

        <?php 
        $has_filters = !empty($_GET['ai_tool']) || !empty($_GET['prompt_category']) || !empty($_GET['s_prompt']);
        ?>
        <div class="zz-filter-reset-wrap" <?php echo !$has_filters ? 'style="display:none;"' : ''; ?>>
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-filter-reset">
                <i class="fa-solid fa-times"></i>
                <?php esc_html_e('Clear All Filters', 'zzprompts'); ?>
            </a>
        </div>

    </form>
</aside>

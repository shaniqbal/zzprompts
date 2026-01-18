<?php
/**
 * Template part for displaying single prompt content
 * V1: "SaaS Product" Style (Not Blog Style)
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Get Meta Data
$prompt_id    = get_the_ID();
$views        = zzprompts_get_post_views($prompt_id);
$copies       = zzprompts_get_copy_count($prompt_id);
$tools        = get_the_terms($prompt_id, 'ai_tool');
$tool_name    = ($tools && !is_wp_error($tools)) ? $tools[0]->name : 'General AI';
$categories   = get_the_terms($prompt_id, 'prompt_category');
$cat_name     = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'Uncategorized';

// Get prompt text from meta field, fallback to post content
$prompt_text = get_post_meta($prompt_id, '_prompt_text', true);
$has_prompt_meta = !empty($prompt_text);
if (empty($prompt_text)) {
    $prompt_text = get_the_content();
    $prompt_text = wp_strip_all_tags($prompt_text);
    $prompt_text = trim($prompt_text);
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zz-single-prompt-product'); ?>>

    <!-- V1: Header with Meta Badges Above Title -->
    <header class="prompt-header mb-6">
        <div class="prompt-meta-badges mb-3">
            <span class="badge badge-tool">🤖 <?php echo esc_html($tool_name); ?></span>
            <span class="badge badge-cat">📂 <?php echo esc_html($cat_name); ?></span>
        </div>
        
        <h1 class="entry-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>

        <!-- V1: Meta Stats Row (Customizable) -->
        <?php
        $show_date   = zzprompts_get_option('single_prompt_meta_show_date', false);
        $show_views  = zzprompts_get_option('single_prompt_meta_show_views', false);
        $show_copies = zzprompts_get_option('single_prompt_meta_show_copies', true);
        $show_author = zzprompts_get_option('single_prompt_meta_show_author', true);
        
        // Only show row if at least one meta item is enabled
        if ($show_date || $show_views || $show_copies || $show_author) :
        ?>
        <div class="prompt-stats-row text-muted text-sm flex gap-4 items-center">
            <?php if ($show_date) : ?>
                <span class="prompt-meta-date">📅 <?php echo esc_html(get_the_date()); ?></span>
            <?php endif; ?>
            
            <?php if ($show_views) : ?>
                <span class="prompt-meta-views">👁️ <?php echo esc_html($views); ?> <?php esc_html_e('Views', 'zzprompts'); ?></span>
            <?php endif; ?>
            
            <?php if ($show_copies) : ?>
                <span class="prompt-meta-copies">📋 <?php echo esc_html(zzprompts_format_number($copies)); ?> <?php esc_html_e('Used', 'zzprompts'); ?></span>
            <?php endif; ?>
            
            <?php if ($show_author) : ?>
                <span class="prompt-author" style="display:inline-flex;align-items:center;gap:0.33em;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" style="vertical-align:middle;">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 20c0-2.5 3.5-4.5 8-4.5s8 2 8 4.5" />
                    </svg>
                    <?php esc_html_e('By', 'zzprompts'); ?> <?php the_author(); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (has_post_thumbnail()) : ?>
        <div class="zz-featured-hero">
            <span class="zz-hero-badge">✨ Generated Result</span>
            <?php the_post_thumbnail('large', array(
                'class' => 'zz-hero-img',
                'loading' => 'lazy',
                'decoding' => 'async',
            )); ?>
        </div>
        <?php endif; ?>
    </header>

    <!-- LEGACY: Old Header - Hidden for V1 -->
    <div style="display:none;">
    <header class="prompt-single-header">
        <!-- Title (H1 first) -->
        <h1 class="prompt-single-title"><?php the_title(); ?></h1>
        
        <!-- Meta Information Row (Below Title) -->
        <?php
        // Get customizer settings
        $show_date_legacy   = zzprompts_get_option('single_prompt_meta_show_date', false);
        $show_views_legacy  = zzprompts_get_option('single_prompt_meta_show_views', false);
        $show_copies_legacy = zzprompts_get_option('single_prompt_meta_show_copies', true);
        $show_author_legacy = zzprompts_get_option('single_prompt_meta_show_author', true);
        
        // Only show row if at least one meta item is enabled
        if ($show_date_legacy || $show_views_legacy || $show_copies_legacy || $show_author_legacy) :
        ?>
        <div class="prompt-single-meta">
            <?php
            $post_id_for_meta = get_the_ID();

            // Copies (show/hide via Customizer)
            if ($show_copies_legacy && zzprompts_get_option('enable_copy_counter', true)) {
                $copies_legacy = zzprompts_get_copy_count($post_id_for_meta);
                echo '<span class="prompt-meta-item prompt-meta-pill prompt-meta-copies">';
                echo '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">';
                echo '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>';
                echo '<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>';
                echo '</svg>';
                echo '<span class="copy-count" data-post-id="' . esc_attr($post_id_for_meta) . '">' . esc_html(zzprompts_format_number($copies_legacy)) . '</span> ' . esc_html__('Copies', 'zzprompts');
                echo '</span>';
            }

            // Date
            if ($show_date_legacy) {
                echo '<span class="prompt-meta-item prompt-meta-pill prompt-meta-date">';
                echo '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">';
                echo '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>';
                echo '<line x1="16" y1="2" x2="16" y2="6"></line>';
                echo '<line x1="8" y1="2" x2="8" y2="6"></line>';
                echo '<line x1="3" y1="10" x2="21" y2="10"></line>';
                echo '</svg>';
                echo esc_html(get_the_date('M j, Y'));
                echo '</span>';
            }
            
            // Views
            if ($show_views_legacy) {
                $views_legacy = zzprompts_get_post_views($post_id_for_meta);
                echo '<span class="prompt-meta-item prompt-meta-pill prompt-meta-views">';
                echo '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">';
                echo '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>';
                echo '<circle cx="12" cy="12" r="3"></circle>';
                echo '</svg>';
                echo esc_html($views_legacy) . ' ' . esc_html__('Views', 'zzprompts');
                echo '</span>';
            }
            
            // Author
            if ($show_author_legacy) {
                echo '<span class="prompt-meta-item prompt-meta-pill prompt-meta-author">';
                echo '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">';
                echo '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>';
                echo '<circle cx="12" cy="7" r="4"></circle>';
                echo '</svg>';
                echo esc_html__('By', 'zzprompts') . ' ' . esc_html(get_the_author());
                echo '</span>';
            }
            ?>
        </div>
        <?php endif; ?>
    </header>
    </div>
    <!-- End LEGACY Hidden Header -->

    <!-- Featured Image moved and restyled above -->


    <!-- Ad Before Prompt -->
    <?php
    $ad_before = zzprompts_get_option('ad_before_prompt');
    if ($ad_before) {
        echo '<div class="zz-ad-spot zz-ad-before-prompt mb-4">' . $ad_before . '</div>';
    }
    ?>

    <!-- V1: Terminal Box for Prompt -->
    <?php if ($prompt_text) : ?>
        <div class="prompt-terminal-box mb-8" id="zz-prompt-box-<?php the_ID(); ?>">
                <div class="terminal-header">
                    <div class="terminal-dots" aria-hidden="true">
                        <span class="terminal-dot terminal-dot-red"></span>
                        <span class="terminal-dot terminal-dot-yellow"></span>
                        <span class="terminal-dot terminal-dot-green"></span>
                    </div>
                    <span class="terminal-label"><?php esc_html_e('Prompt', 'zzprompts'); ?></span>
                    <div class="terminal-actions">
                        <div class="terminal-actions-group">
                            <?php if (zzprompts_get_option('enable_likes', true)) : ?>
                                <button type="button" class="zz-like-btn zz-like-btn-terminal" data-post-id="<?php the_ID(); ?>" aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>" style="padding:0 0.25em 0 0;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.01 4.01 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44h0.74C14.09 5.01 15.76 4 17.5 4 19.99 4 22 6.01 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span class="like-count" style="font-size:0.95em;line-height:1;vertical-align:middle;"><?php echo absint(zzprompts_get_likes(get_the_ID())); ?></span>
                                </button>
                            <?php endif; ?>
                            <button class="btn-copy-prompt zz-copy-btn" 
                                    data-post-id="<?php the_ID(); ?>" 
                                    data-prompt-id="<?php the_ID(); ?>"
                                    data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                                    aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span class="btn-text"><?php echo esc_html(zzprompts_get_option('copy_btn_text', __('Copy', 'zzprompts'))); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="terminal-body">
                    <div class="prompt-text-wrapper zz-prompt-restrict" id="prompt-wrapper-<?php the_ID(); ?>">
                        <pre class="prompt-code-block" id="zz-prompt-raw-<?php the_ID(); ?>"><code><?php echo esc_html($prompt_text); ?></code></pre>
                        <div class="prompt-fade-overlay"></div>
                    </div>

                    <button class="zz-expand-btn hidden" data-target="prompt-wrapper-<?php the_ID(); ?>">
                        <span class="expand-text"><?php esc_html_e('Show Full Prompt', 'zzprompts'); ?> 👇</span>
                        <span class="collapse-text hidden"><?php esc_html_e('Collapse', 'zzprompts'); ?> ☝️</span>
                    </button>
                </div>
            </div>
    <?php endif; ?>

    <!-- LEGACY: Old Prompt Box - Hidden -->
    <div style="display:none;">
        <?php if ($prompt_text) : ?>
            <?php $likes_enabled = zzprompts_get_option('enable_likes', true); ?>
            <div class="zz-prompt-box" id="zz-prompt-box-legacy-<?php the_ID(); ?>">
                <div class="prompt-box-layout">
                    <div class="prompt-text-area">
                        <div class="prompt-text-wrapper" id="prompt-text-wrapper-<?php the_ID(); ?>">
                            <pre class="prompt-text" id="prompt-text-<?php the_ID(); ?>"><?php echo esc_html($prompt_text); ?></pre>
                        </div>
                    </div>
                </div>

                <div class="zz-prompt-actions-bar">
                    <?php if ($likes_enabled) : ?>
                        <button type="button" class="zz-like-btn zz-like-btn-bar" data-post-id="<?php the_ID(); ?>" aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="like-count"><?php echo absint(zzprompts_get_likes(get_the_ID())); ?></span>
                            <span class="zz-like-label"><?php esc_html_e('Likes', 'zzprompts'); ?></span>
                        </button>
                    <?php endif; ?>

                    <div class="zz-prompt-actions-right">
                        <button class="zz-copy-btn zz-copy-btn-side" 
                                data-post-id="<?php the_ID(); ?>" 
                                data-prompt-id="<?php the_ID(); ?>"
                                data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                                aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            <span class="btn-text"><?php echo esc_html(zzprompts_get_option('copy_btn_text', __('Copy', 'zzprompts'))); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tags / Categories (below prompt box) -->
            <div class="zz-prompt-badges zz-prompt-badges-below">
                <?php
                // AI Tools
                $tools = get_the_terms(get_the_ID(), 'ai_tool');
                if ($tools && !is_wp_error($tools)) {
                    foreach ($tools as $tool) {
                        echo '<a href="' . esc_url(get_term_link($tool)) . '" class="zz-badge-pill zz-badge-tool">';
                        echo '<span>' . esc_html($tool->name) . '</span>';
                        echo '</a>';
                    }
                }

                // Categories
                $categories = get_the_terms(get_the_ID(), 'prompt_category');
                if ($categories && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        echo '<a href="' . esc_url(get_term_link($category)) . '" class="zz-badge-pill zz-badge-category">';
                        echo '<span>' . esc_html($category->name) . '</span>';
                        echo '</a>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- End LEGACY Hidden Prompt Box -->

    <!-- V1: Description Section -->
        <div class="prompt-description-content prose max-w-none">
            <h3 class="text-xl font-bold mb-3"><?php esc_html_e('About this Prompt', 'zzprompts'); ?></h3>
            <?php 
            // Get description content
            $description_content = get_the_content();
            $description_stripped = wp_strip_all_tags($description_content);
            $description_stripped = trim($description_stripped);
            
            // Show description if it exists and is different from prompt text
            if ($has_prompt_meta || ($description_stripped !== $prompt_text && !empty($description_stripped))) {
                the_content();
                
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'zzprompts'),
                    'after'  => '</div>',
                ));
            } else {
                // If no description, show default text
                if ( has_excerpt() ) {
                    the_excerpt(); 
                } else {
                    echo '<p>' . sprintf(
                        esc_html__('Use this prompt to generate high-quality results with %s. Adjust the parameters in square brackets [] to fit your specific needs.', 'zzprompts'),
                        esc_html($tool_name)
                    ) . '</p>';
                }
            }
            ?>
        </div>

    <!-- Ad After Prompt (if enabled) -->
    <?php
    $ad_after = zzprompts_get_option('ad_after_prompt');
    if ($ad_after) {
        echo '<div class="zz-ad-spot zz-ad-after-prompt mt-4 mb-4">' . $ad_after . '</div>';
    }
    ?>
        
    <!-- How to Use This Prompt Section -->
    <?php
        $how_to_use = get_post_meta(get_the_ID(), '_prompt_how_to_use', true);
        if (!empty($how_to_use)) :
        ?>
            <div class="prompt-how-to-use">
                <h2 class="prompt-section-title"><?php esc_html_e('How to Use This Prompt', 'zzprompts'); ?></h2>
                <div class="prompt-how-to-content">
                    <?php echo wp_kses_post(wpautop($how_to_use)); ?>
                </div>
            </div>
    <?php endif; ?>
    
    <!-- Example Outputs Section -->
    <?php
        $example_outputs = get_post_meta(get_the_ID(), '_prompt_example_outputs', true);
        if (!empty($example_outputs)) :
        ?>
            <div class="prompt-example-outputs">
                <h2 class="prompt-section-title"><?php esc_html_e('Example Outputs', 'zzprompts'); ?></h2>
                <div class="prompt-examples-grid">
                    <?php echo wp_kses_post($example_outputs); ?>
                </div>
            </div>
    <?php endif; ?>

    <!-- Prompt Footer - Related Posts (Only for V1, V2 shows in sidebar) -->
    <?php
    $layout = zzprompts_get_option('prompt_single_layout_mode', 'v1');
    $show_related = ($layout !== 'v2'); // Hide related in footer for V2, show in sidebar instead
    
    if ($show_related) :
        // Smart Related Query
        $related_query = zzprompts_get_smart_related_prompts(get_the_ID(), 3);

        if ($related_query->have_posts()) :
    ?>
        <footer class="prompt-related-section">
            <h2 class="section-title"><?php echo esc_html(zzprompts_get_option('single_related_title', __('Related Prompts', 'zzprompts'))); ?></h2>
            
            <div class="prompts-grid">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <?php get_template_part('template-parts/prompt/content', 'grid'); ?>
                <?php endwhile; ?>
            </div>
        </footer>
        <?php
            wp_reset_postdata();
        endif;
    endif;
    ?>
    
    <!-- Mobile Sticky Action Buttons (Copy + Like) -->
    <?php if ($prompt_text) : ?>
        <div class="mobile-sticky-copy">
            <div class="mobile-sticky-actions">
                <?php if (zzprompts_get_option('enable_likes', true)) : ?>
                    <button class="zz-like-btn zz-like-btn-sticky" 
                            data-post-id="<?php the_ID(); ?>" 
                            aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#f87171" stroke="#f87171" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="like-count" style="font-size:1em;line-height:1;vertical-align:middle;"><?php echo absint(zzprompts_get_likes(get_the_ID())); ?></span>
                    </button>
                <?php endif; ?>
                
                <button class="zz-copy-btn zz-copy-btn-sticky" 
                        data-post-id="<?php the_ID(); ?>" 
                        data-prompt-id="<?php the_ID(); ?>"
                        data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                        aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span class="btn-text"><?php echo esc_html(zzprompts_get_option('copy_btn_text', __('Copy Prompt', 'zzprompts'))); ?></span>
                </button>
            </div>
        </div>
    <?php endif; ?>

</article>

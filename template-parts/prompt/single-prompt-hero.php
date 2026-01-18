<?php
/**
 * Template Part: Single Prompt Content - Modern V1
 * 
 * Glassmorphism terminal-style prompt display.
 * BEM naming: .zz-prompt-* and .zz-terminal-*
 *
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;

// Get Meta Data
$prompt_id    = get_the_ID();
$views        = zzprompts_get_post_views($prompt_id);
$copies       = zzprompts_get_copy_count($prompt_id);
$likes        = zzprompts_get_likes($prompt_id);
$tools        = get_the_terms($prompt_id, 'ai_tool');
$tool_name    = ($tools && !is_wp_error($tools)) ? $tools[0]->name : __('General AI', 'zzprompts');
$tool_slug    = ($tools && !is_wp_error($tools)) ? $tools[0]->slug : 'general';
$categories   = get_the_terms($prompt_id, 'prompt_category');
$cat_name     = ($categories && !is_wp_error($categories)) ? $categories[0]->name : __('Prompt', 'zzprompts');

/* 
 * CONTENT LOGIC:
 * 1. Check New Meta Box (_zz_prompt_text)
 * 2. Fallback to Legacy Meta (_prompt_text)
 * 3. Fallback to Content (Legacy Mode)
 */
$prompt_text = get_post_meta($prompt_id, '_zz_prompt_text', true);
$is_isolated_prompt = !empty($prompt_text);

if (!$is_isolated_prompt) {
    $prompt_text = get_post_meta($prompt_id, '_prompt_text', true);
    if (!empty($prompt_text)) {
        $is_isolated_prompt = true;
    } else {
        $raw_content = get_the_content();
        $str_content = wp_strip_all_tags($raw_content);
        $prompt_text = trim($str_content);
        $is_isolated_prompt = false; 
    }
}

// Customizer options
$show_date     = zzprompts_get_option('single_prompt_meta_show_date', false);
$show_views    = zzprompts_get_option('single_prompt_meta_show_views', false);
$show_copies   = zzprompts_get_option('single_prompt_meta_show_copies', true);
$show_author   = zzprompts_get_option('single_prompt_meta_show_author', true);
$likes_enabled = zzprompts_get_option('enable_likes', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zz-prompt-wrapper'); ?>>
    
    <div class="zz-prompt-container">

        <!-- Main Content Area -->
        <div class="zz-prompt-content">

            <!-- Header Area -->
            <header class="zz-prompt-header">
                <span class="zz-prompt-badge"><?php echo esc_html($tool_name); ?></span>
                <h1 class="zz-prompt-title"><?php the_title(); ?></h1>
                
                <?php if ($show_date || $show_views || $show_copies || $show_author || $likes_enabled) : ?>
                <div class="zz-prompt-meta">
                    <?php if ($likes_enabled) : ?>
                        <span class="zz-prompt-meta__item">
                            <i class="fa-solid fa-heart"></i>
                            <span class="zz-prompt-likes-count"><?php echo esc_html(zzprompts_format_number($likes)); ?></span> 
                            <?php esc_html_e('Likes', 'zzprompts'); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($show_copies) : ?>
                        <span class="zz-prompt-meta__item">
                            <i class="fa-regular fa-copy"></i>
                            <?php echo esc_html(zzprompts_format_number($copies)); ?> 
                            <?php esc_html_e('Copies', 'zzprompts'); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($show_author) : ?>
                        <span class="zz-prompt-meta__item">
                            <i class="fa-regular fa-user"></i>
                            <?php the_author(); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($show_date) : ?>
                        <span class="zz-prompt-meta__item">
                            <i class="fa-regular fa-clock"></i>
                            <?php echo esc_html(get_the_modified_date('M j, Y')); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </header>

            <hr class="zz-prompt-separator">

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
            <div class="zz-prompt-featured">
                <?php the_post_thumbnail('large', array(
                    'alt' => get_the_title(),
                    'class' => 'zz-prompt-featured__img'
                )); ?>
            </div>
            <?php endif; ?>

            <!-- Description / Gutenberg Content -->
            <?php if ($is_isolated_prompt) : ?>
            <div class="zz-prompt-description">
                <div class="zz-prompt-description__content">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php elseif (has_excerpt()) : ?>
            <div class="zz-prompt-description">
                <div class="zz-prompt-description__content">
                    <?php the_excerpt(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ad: Before Terminal -->
            <?php zz_render_ad('prompt_top'); ?>

            <!-- Terminal / Prompt Box -->
            <?php if ($prompt_text) : ?>
            <div class="zz-terminal" id="zz-terminal-<?php the_ID(); ?>">
                <div class="zz-terminal__header">
                    <div class="zz-terminal__dots" aria-hidden="true">
                        <span class="zz-terminal__dot zz-terminal__dot--red"></span>
                        <span class="zz-terminal__dot zz-terminal__dot--yellow"></span>
                        <span class="zz-terminal__dot zz-terminal__dot--green"></span>
                    </div>
                    
                    <!-- Action Buttons (Desktop) -->
                    <div class="zz-terminal__actions">
                        <?php if ($likes_enabled) : ?>
                        <button type="button" 
                                class="zz-terminal__btn zz-terminal__btn--like zz-like-btn" 
                                data-post-id="<?php the_ID(); ?>"
                                aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>">
                            <i class="fa-solid fa-heart"></i>
                            <span class="like-count"><?php echo absint($likes); ?></span>
                        </button>
                        <?php endif; ?>
                        
                        <button type="button" 
                                class="zz-terminal__btn zz-terminal__btn--copy zz-copy-btn"
                                data-post-id="<?php the_ID(); ?>"
                                data-prompt-id="<?php the_ID(); ?>"
                                data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                                aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                            <i class="fa-regular fa-copy"></i>
                            <span class="btn-text"><?php esc_html_e('Copy Prompt', 'zzprompts'); ?></span>
                        </button>
                    </div>
                </div>
                
                <div class="zz-terminal__body" id="zz-terminal-body-<?php the_ID(); ?>">
                    <pre class="zz-terminal__code"><?php echo esc_html($prompt_text); ?></pre>
                    
                    <!-- Fade Overlay with Expand Button -->
                    <div class="zz-terminal__fade">
                        <button type="button" class="zz-terminal__expand" data-target="zz-terminal-body-<?php the_ID(); ?>">
                            <i class="fas fa-chevron-down"></i>
                            <span class="expand-text"><?php esc_html_e('Show Full Prompt', 'zzprompts'); ?></span>
                            <span class="collapse-text" style="display:none;"><?php esc_html_e('Collapse', 'zzprompts'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Mobile Sticky Actions (Only visible on mobile) -->
            <?php if ($prompt_text) : ?>
            <div class="zz-prompt-mobile-actions">
                <div class="zz-prompt-mobile-actions__inner">
                    <?php if ($likes_enabled) : ?>
                    <button type="button" 
                            class="zz-prompt-mobile-actions__btn zz-prompt-mobile-actions__btn--like zz-like-btn"
                            data-post-id="<?php the_ID(); ?>"
                            aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>">
                        <i class="fa-solid fa-heart"></i>
                        <span class="like-count"><?php echo absint($likes); ?></span>
                    </button>
                    <?php endif; ?>
                    
                    <button type="button" 
                            class="zz-prompt-mobile-actions__btn zz-prompt-mobile-actions__btn--copy zz-copy-btn"
                            data-post-id="<?php the_ID(); ?>"
                            data-prompt-id="<?php the_ID(); ?>"
                            data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                            aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                        <i class="fa-regular fa-copy"></i>
                        <span class="btn-text"><?php esc_html_e('Copy Prompt', 'zzprompts'); ?></span>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ad: After Terminal -->
            <?php zz_render_ad('prompt_bottom'); ?>

            <!-- Related Prompts -->
            <?php
            $related_query = zzprompts_get_smart_related_prompts(get_the_ID(), 3);
            if ($related_query->have_posts()) :
            ?>
            <section class="zz-prompt-related">
                <h2 class="zz-prompt-related__title">
                    <i class="fa-solid fa-bolt"></i>
                    <?php echo esc_html(zzprompts_get_option('single_related_title', __('You might also like', 'zzprompts'))); ?>
                </h2>
                <div class="zz-prompt-related__grid">
                    <?php while ($related_query->have_posts()) : $related_query->the_post(); 
                        $rel_tools = get_the_terms(get_the_ID(), 'ai_tool');
                        $rel_tool_name = ($rel_tools && !is_wp_error($rel_tools)) ? $rel_tools[0]->name : '';
                        $rel_cats = get_the_terms(get_the_ID(), 'prompt_category');
                        $rel_cat_name = ($rel_cats && !is_wp_error($rel_cats)) ? $rel_cats[0]->name : __('Prompt', 'zzprompts');
                        $rel_likes = zzprompts_get_likes(get_the_ID());
                    ?>
                    <article class="zz-prompt-card">
                        <a href="<?php the_permalink(); ?>" class="zz-prompt-card__media">
                            <span class="zz-prompt-card__badge"><?php echo esc_html($rel_tool_name ? $rel_tool_name : $rel_cat_name); ?></span>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array('class' => 'zz-prompt-card__img')); ?>
                            <?php else : ?>
                                <div class="zz-prompt-card__placeholder">
                                    <span class="zz-prompt-card__placeholder-text"><?php echo esc_html($rel_cat_name); ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                        
                        <div class="zz-prompt-card__content">
                            <h4 class="zz-prompt-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="zz-prompt-card__meta">
                                <span><i class="fa-solid fa-heart"></i> <?php echo esc_html(zzprompts_format_number($rel_likes)); ?></span>
                                <span><i class="fa-regular fa-folder-open zz-icon-folder"></i> <?php echo esc_html($rel_cat_name); ?></span>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php 
            wp_reset_postdata();
            endif; 
            ?>

        </div><!-- .zz-prompt-content -->

        <!-- Sidebar -->
        <?php if (is_active_sidebar('sidebar-prompt')) : ?>
        <aside class="zz-prompt-sidebar">
            <?php dynamic_sidebar('sidebar-prompt'); ?>
        </aside>
        <?php endif; ?>

    </div><!-- .zz-prompt-container -->

</article>

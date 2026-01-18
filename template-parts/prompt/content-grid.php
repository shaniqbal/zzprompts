<?php
/**
 * Template Part: Premium Prompt Grid Card
 * Matching Category Page Design
 * 
 * @package zzprompts
 * @version 3.0.0
 */

defined('ABSPATH') || exit;

$tools = get_the_terms(get_the_ID(), 'ai_tool');
$categories = get_the_terms(get_the_ID(), 'prompt_category');
$prompt_excerpt = wp_strip_all_tags(get_the_excerpt());
$likes_count = absint(zzprompts_get_likes(get_the_ID()));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('classic-prompt-card'); ?>>
    
    <!-- Tool Badge -->
    <?php if ($tools && !is_wp_error($tools)) : ?>
        <div class="classic-prompt-badge"><?php echo esc_html($tools[0]->name); ?></div>
    <?php endif; ?>
    
    <!-- Featured Image -->
    <div class="classic-prompt-img">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        <?php else : ?>
            <!-- Category Name Fallback -->
            <div class="classic-img-fallback">
                <?php if ($categories && !is_wp_error($categories)) : ?>
                    <span class="fallback-category"><?php echo esc_html($categories[0]->name); ?></span>
                <?php else : ?>
                    <span class="fallback-category"><?php esc_html_e('Prompt', 'zzprompts'); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Card Content -->
    <div class="classic-prompt-content">
        
        <!-- Title -->
        <h3 class="classic-prompt-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <!-- Excerpt Preview -->
        <div class="classic-prompt-excerpt">
            <?php echo esc_html(wp_trim_words($prompt_excerpt, 15, '...')); ?>
        </div>
        
        <!-- Footer -->
        <div class="classic-prompt-footer">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <span class="classic-prompt-meta"><?php echo esc_html($categories[0]->name); ?></span>
            <?php else : ?>
                <span class="classic-prompt-meta">Prompt</span>
            <?php endif; ?>
            
            <div class="classic-prompt-actions">
                <a href="<?php the_permalink(); ?>" class="classic-btn-icon" title="<?php esc_attr_e('View Prompt', 'zzprompts'); ?>">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </a>
                <a href="<?php the_permalink(); ?>" class="classic-btn-icon classic-btn-copy" title="<?php esc_attr_e('Copy Prompt', 'zzprompts'); ?>">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                    </svg>
                </a>
            </div>
        </div>
        
    </div>
    
</article>

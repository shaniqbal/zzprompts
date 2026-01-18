<?php
/**
 * Template part for displaying Classic Prompt Card
 * 
 * ThemeForest Approved Card Style
 * - Title (2 lines max)
 * - Short excerpt (1-2 lines)
 * - Primary action: View Prompt
 * - Optional: category, author, date
 * - NO: like buttons, copy buttons, hover-only actions, badges overload
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Get category
$categories = get_the_terms(get_the_ID(), 'prompt_category');
$category = !empty($categories) && !is_wp_error($categories) ? $categories[0] : null;
?>

<article id="prompt-<?php the_ID(); ?>" <?php post_class('cv1-prompt-card'); ?>>
    <!-- Category Label (Optional) -->
    <?php if ($category) : ?>
    <div class="cv1-card-meta">
        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="cv1-card-category">
            <?php echo esc_html($category->name); ?>
        </a>
    </div>
    <?php endif; ?>
    
    <!-- Title -->
    <h3 class="cv1-card-title">
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h3>
    
    <!-- Excerpt -->
    <p class="cv1-card-excerpt">
        <?php 
        $excerpt = get_the_excerpt();
        echo esc_html(wp_trim_words($excerpt, 15, '...'));
        ?>
    </p>
    
    <!-- Footer with Author & Action -->
    <div class="cv1-card-footer">
        <div class="cv1-card-author">
            <span class="cv1-card-date"><?php echo esc_html(get_the_date('M j, Y')); ?></span>
        </div>
        
        <a href="<?php the_permalink(); ?>" class="cv1-card-btn">
            <?php esc_html_e('View Prompt', 'zzprompts'); ?>
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</article>

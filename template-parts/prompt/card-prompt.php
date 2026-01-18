<?php
/**
 * Template Part: Modern V1 Archive Card
 * Design: Thumbnail + Title + Badges + Excerpt + Copy Button
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$tools = get_the_terms(get_the_ID(), 'ai_tool');
$categories = get_the_terms(get_the_ID(), 'prompt_category');
$prompt_excerpt = wp_strip_all_tags(get_the_excerpt());
$likes_count = absint(zzprompts_get_likes(get_the_ID()));

// Get tool/category names
$tool_name = ($tools && !is_wp_error($tools)) ? $tools[0]->name : '';
$cat_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : __('Prompt', 'zzprompts');

// Generate thumbnail color based on tool
$thumb_colors = array(
    'chatgpt'    => '6366F1',
    'midjourney' => '10B981',
    'claude'     => '8B5CF6',
    'dall-e'     => 'F59E0B',
    'gemini'     => '3B82F6',
    'grok'       => 'EC4899',
);
$tool_slug = ($tools && !is_wp_error($tools)) ? sanitize_title($tools[0]->name) : 'default';
$thumb_bg = isset($thumb_colors[$tool_slug]) ? $thumb_colors[$tool_slug] : '6366F1';
$thumb_text = strtoupper(substr($tool_name ?: $cat_name, 0, 3));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zz-archive-card'); ?>>
    
    <!-- Card Header: Thumbnail + Title/Badges -->
    <div class="zz-archive-card__header">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="zz-archive-card__thumb">
                <?php the_post_thumbnail('thumbnail', array('class' => 'zz-archive-card__thumb-img')); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="zz-archive-card__thumb zz-archive-card__thumb--placeholder" style="background-color: #<?php echo esc_attr($thumb_bg); ?>;">
                <span><?php echo esc_html($thumb_text); ?></span>
            </a>
        <?php endif; ?>
        
        <div class="zz-archive-card__info">
            <h3 class="zz-archive-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="zz-archive-card__badges">
                <?php if ($tool_name) : ?>
                    <span class="zz-archive-badge zz-archive-badge--tool"><?php echo esc_html($tool_name); ?></span>
                <?php endif; ?>
                <span class="zz-archive-badge zz-archive-badge--cat"><?php echo esc_html($cat_name); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Excerpt -->
    <p class="zz-archive-card__excerpt">
        <?php echo esc_html(wp_trim_words($prompt_excerpt, 18, '...')); ?>
    </p>
    
    <!-- Footer: Copy Button + Stats -->
    <div class="zz-archive-card__footer">
        <a href="<?php the_permalink(); ?>" class="zz-archive-card__copy-btn">
            <i class="fa-regular fa-copy"></i>
            <?php esc_html_e('Copy', 'zzprompts'); ?>
        </a>
        <div class="zz-archive-card__stats">
            <i class="fa-solid fa-heart"></i>
            <?php echo esc_html(zzprompts_format_number($likes_count)); ?>
        </div>
    </div>
    
</article>

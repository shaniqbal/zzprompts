<?php
/**
 * Single Prompt Sidebar - Related Prompts Panel
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$post_id = get_the_ID();
?>

<aside class="zz-prompt-sidebar">
    
    <!-- Related Prompts -->
    <?php
    // Smart Related Prompts (Priority: Category -> AI Tool -> Latest)
    $related_query = zzprompts_get_smart_related_prompts($post_id, 5);
    ?>
    
    <?php if ($related_query->have_posts()) : ?>
        <div class="zz-sidebar-section zz-related-section">
            <h3 class="zz-sidebar-section__title"><?php echo esc_html(zzprompts_get_option('single_related_title', __('Related Prompts', 'zzprompts'))); ?></h3>
            
            <ul class="zz-related-list">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <li class="zz-related-item">
                        <a href="<?php the_permalink(); ?>" class="zz-related-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="zz-related-thumb">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'zz-related-img')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="zz-related-content">
                                <h4 class="zz-related-item__title"><?php the_title(); ?></h4>
                                
                                <div class="zz-related-item__meta">
                                    <?php
                                    $cat_terms = get_the_terms(get_the_ID(), 'prompt_category');
                                    if ($cat_terms && !is_wp_error($cat_terms)) :
                                    ?>
                                        <span class="zz-related-category"><?php echo esc_html($cat_terms[0]->name); ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if (zzprompts_get_option('enable_copy_counter', true)) : ?>
                                        <?php $related_copies = zzprompts_get_copy_count(get_the_ID()); ?>
                                        <span class="zz-related-copies">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                            </svg>
                                            <?php echo esc_html(zzprompts_format_number($related_copies)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    
    <!-- Ad Spot in Sidebar (if enabled) -->
    <?php
    $ad_sidebar = zzprompts_get_option('ad_sidebar_sticky');
    if ($ad_sidebar) {
        echo '<div class="zz-sidebar-section zz-ad-section">' . $ad_sidebar . '</div>';
    }
    ?>
    
</aside>

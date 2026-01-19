<?php
/**
 * 404 Error Page Template - Modern V1
 * 
 * Glassmorphism error page with search and shortcuts.
 * Based on: ZZ Designs Ready/Modern Layout/404 Error Page/final v1.html
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="zz-error-container" id="main">
    <div class="zz-error-card">
        
        <!-- Error Badge -->
        <span class="zz-error-badge"><?php esc_html_e('404 Error', 'zzprompts'); ?></span>
        
        <!-- Title -->
        <h1 class="zz-error-title"><?php esc_html_e('Page not found', 'zzprompts'); ?></h1>
        
        <!-- Description -->
        <p class="zz-error-desc">
            <?php esc_html_e("The page you are looking for doesn't exist or has been moved. Don't worry, we can help you find your way back.", 'zzprompts'); ?>
        </p>
        
        <!-- Search Bar -->
        <form role="search" method="get" class="zz-error-search" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="hidden" name="post_type" value="prompt">
            <i class="fas fa-search zz-error-search__icon" aria-hidden="true"></i>
            <input 
                type="search" 
                class="zz-error-search__input" 
                name="s" 
                placeholder="<?php esc_attr_e('Search for prompts...', 'zzprompts'); ?>"
                value="<?php echo get_search_query(); ?>"
                autofocus
            >
        </form>
        
        <!-- Action Buttons -->
        <div class="zz-error-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-error-btn zz-error-btn--primary">
                <i class="fas fa-home" aria-hidden="true"></i>
                <?php esc_html_e('Go to Homepage', 'zzprompts'); ?>
            </a>
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-error-btn zz-error-btn--outline">
                <i class="fas fa-layer-group" aria-hidden="true"></i>
                <?php esc_html_e('Browse Prompts', 'zzprompts'); ?>
            </a>
        </div>
        
        <!-- Shortcuts Grid -->
        <div class="zz-error-shortcuts">
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>?orderby=popular" class="zz-error-shortcut">
                <i class="fas fa-fire zz-error-shortcut__icon" aria-hidden="true"></i>
                <span class="zz-error-shortcut__text"><?php esc_html_e('Popular Prompts', 'zzprompts'); ?></span>
            </a>
            
            <?php 
            // Get first prompt category
            $categories = get_terms(array(
                'taxonomy'   => 'prompt_category',
                'number'     => 1,
                'hide_empty' => true,
            ));
            $cat_url = (!empty($categories) && !is_wp_error($categories)) 
                ? get_term_link($categories[0]) 
                : get_post_type_archive_link('prompt');
            ?>
            <a href="<?php echo esc_url($cat_url); ?>" class="zz-error-shortcut">
                <i class="fas fa-layer-group zz-error-shortcut__icon" aria-hidden="true"></i>
                <span class="zz-error-shortcut__text"><?php esc_html_e('Categories', 'zzprompts'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="zz-error-shortcut">
                <i class="fas fa-pen-nib zz-error-shortcut__icon" aria-hidden="true"></i>
                <span class="zz-error-shortcut__text"><?php esc_html_e('Read Blog', 'zzprompts'); ?></span>
            </a>
        </div>
        
    </div>
</main>

<?php get_footer(); ?>

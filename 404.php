<?php
defined('ABSPATH') || exit;

get_header();
?>

    <div class="container">
        
        <div class="zz-404-content">
            <h1 class="zz-404-title"><?php esc_html_e('404', 'zzprompts'); ?></h1>
            <h2 class="zz-404-subtitle"><?php esc_html_e('Page Not Found', 'zzprompts'); ?></h2>
            <p class="zz-404-text">
                <?php esc_html_e('Oops! The page you are looking for does not exist. It might have been moved or deleted.', 'zzprompts'); ?>
            </p>
            
            <div class="zz-404-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-btn zz-btn-primary">
                    <?php esc_html_e('Back to Home', 'zzprompts'); ?>
                </a>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn zz-btn-secondary">
                    <?php esc_html_e('Browse Prompts', 'zzprompts'); ?>
                </a>
            </div>
            
            <div class="zz-404-search">
                <h3><?php esc_html_e('Try Searching:', 'zzprompts'); ?></h3>
                <?php get_search_form(); ?>
            </div>
        </div>
        
    </div>

<?php
get_footer();

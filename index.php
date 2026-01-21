<?php
/**
 * The main template file (Blog Router)
 *
 * Loads blog layout variation (V1 or V2) based on Customizer settings.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

// Get blog layout from Customizer
$layout = zzprompts_get_option('blog_layout_select', 'v1');
?>

<!-- Blog Hero Section -->
<section class="zz-blog-hero">
    <div class="zz-container">
        <!-- Breadcrumbs -->
        <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link"><?php esc_html_e('Home', 'zzprompts'); ?></a>
            <span class="zz-breadcrumbs__separator">&rsaquo;</span>
            <span class="zz-breadcrumbs__current"><?php esc_html_e('Blog', 'zzprompts'); ?></span>
        </nav>
        
        <h1 class="zz-blog-hero__title"><?php esc_html_e('Latest News & Updates', 'zzprompts'); ?></h1>
        <p class="zz-blog-hero__subtitle"><?php esc_html_e('Discover insights, tips, and the latest trends in AI prompts and automation.', 'zzprompts'); ?></p>
    </div>
</section>

<div class="zz-container u-py-10">
    <!-- Modern V1 Layout -->
    <?php
    $sidebar_enabled = zzprompts_get_option('blog_show_sidebar', true);
    $layout_class = $sidebar_enabled ? 'zz-blog-layout' : 'zz-blog-layout--full-width';
    ?>
    
    <div class="<?php echo esc_attr($layout_class); ?>">
        <main id="primary" class="zz-blog-main-content">
            <?php get_template_part('template-parts/blog/card', 'blog'); ?>
        </main>

        <?php
        if ($sidebar_enabled) {
            get_template_part('template-parts/sidebar', 'blog');
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>


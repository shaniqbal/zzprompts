<?php
/**
 * Main Footer Template
 * ============================
 * Glassmorphism Design with 4 Column Layout
 * Styled by: assets/css/skins/modern/footer.css
 *
 * @package zzprompts
 * @version 1.0.0 - ThemeForest Ready
 */

defined('ABSPATH') || exit;

// Check if ANY footer widget area has content
$has_footer_widgets = is_active_sidebar('footer-1') 
    || is_active_sidebar('footer-2') 
    || is_active_sidebar('footer-3') 
    || is_active_sidebar('footer-4');
?>

<!-- Footer Widget Area -->
<div class="container">
    <?php if ($has_footer_widgets) : ?>
        <div class="zz-footer-grid">
            
            <!-- Column 1: About -->
            <div class="zz-footer-col">
                <?php if (is_active_sidebar('footer-1')) {
                    dynamic_sidebar('footer-1');
                } ?>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div class="zz-footer-col">
                <?php if (is_active_sidebar('footer-2')) {
                    dynamic_sidebar('footer-2');
                } ?>
            </div>
            
            <!-- Column 3: Categories -->
            <div class="zz-footer-col">
                <?php if (is_active_sidebar('footer-3')) {
                    dynamic_sidebar('footer-3');
                } ?>
            </div>
            
            <!-- Column 4: Connect -->
            <div class="zz-footer-col">
                <?php if (is_active_sidebar('footer-4')) {
                    dynamic_sidebar('footer-4');
                } ?>
            </div>
            
        </div>
    <?php else : ?>
        <!-- Default Footer Content (when no widgets configured) -->
        <div class="zz-footer-grid zz-footer-grid--default">
            
            <!-- Column 1: Brand -->
            <div class="zz-footer-col">
                <div class="zz-footer-widget">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h4 class="zz-footer-widget__title"><?php bloginfo('name'); ?></h4>
                    <?php endif; ?>
                    <p class="zz-footer-widget__desc"><?php bloginfo('description'); ?></p>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div class="zz-footer-col">
                <div class="zz-footer-widget">
                    <h4 class="zz-footer-widget__title"><?php esc_html_e('Quick Links', 'zzprompts'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'zz-footer-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => function() {
                            echo '<ul class="zz-footer-menu">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'zzprompts') . '</a></li>';
                            if (post_type_exists('prompt')) {
                                echo '<li><a href="' . esc_url(get_post_type_archive_link('prompt')) . '">' . esc_html__('Prompts', 'zzprompts') . '</a></li>';
                            }
                            echo '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('Blog', 'zzprompts') . '</a></li>';
                            echo '</ul>';
                        },
                    ));
                    ?>
                </div>
            </div>
            
            <!-- Column 3: Categories -->
            <div class="zz-footer-col">
                <div class="zz-footer-widget">
                    <h4 class="zz-footer-widget__title"><?php esc_html_e('Categories', 'zzprompts'); ?></h4>
                    <ul class="zz-footer-menu">
                        <?php
                        $categories = get_categories(array('number' => 5, 'orderby' => 'count', 'order' => 'DESC'));
                        foreach ($categories as $cat) {
                            echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
                        }
                        if (empty($categories)) {
                            echo '<li>' . esc_html__('No categories yet', 'zzprompts') . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            
            <!-- Column 4: Contact -->
            <div class="zz-footer-col">
                <div class="zz-footer-widget">
                    <h4 class="zz-footer-widget__title"><?php esc_html_e('Get in Touch', 'zzprompts'); ?></h4>
                    <p class="zz-footer-widget__desc">
                        <?php esc_html_e('Have questions? We\'d love to hear from you.', 'zzprompts'); ?>
                    </p>
                    <p class="zz-footer-widget__email">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>">
                            <?php echo esc_html(get_option('admin_email')); ?>
                        </a>
                    </p>
                </div>
            </div>
            
        </div>
    <?php endif; ?>
</div>

<!-- Footer Bottom / Copyright -->
<div class="zz-footer-bottom">
    <div class="container">
        <p>
            <?php 
            // Get footer copyright with proper default
            $default_copyright = sprintf(
                /* translators: %1$s: current year, %2$s: site name */
                esc_html__('© %1$s %2$s. All rights reserved.', 'zzprompts'), 
                date('Y'),
                get_bloginfo('name')
            );
            $footer_copyright = get_theme_mod('footer_copyright', $default_copyright);
            echo wp_kses_post($footer_copyright);
            ?>
        </p>
    </div>
</div>

<?php
/**
 * Footer Template - Modern V1
 * 
 * Clean footer with BEM naming.
 * CRITICAL: wp_footer() MUST remain before </body>
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;
?>

    </main><!-- .zz-main -->

    <!-- =========================================
         FOOTER - Modern V1
         ========================================= -->
    <footer id="colophon" class="zz-footer">
        <div class="zz-container">
            
            <?php 
            // Check if ANY footer widget area has content
            $has_footer_widgets = is_active_sidebar('footer-1') 
                || is_active_sidebar('footer-2') 
                || is_active_sidebar('footer-3') 
                || is_active_sidebar('footer-4');
            ?>
            
            <?php if ($has_footer_widgets) : ?>
            <div class="zz-footer__grid">
                
                <!-- Footer Column 1 -->
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div class="zz-footer__col zz-footer__col--1">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>
                <?php endif; ?>
                
                <!-- Footer Column 2 -->
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <div class="zz-footer__col zz-footer__col--2">
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                </div>
                <?php endif; ?>
                
                <!-- Footer Column 3 -->
                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="zz-footer__col zz-footer__col--3">
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                </div>
                <?php endif; ?>
                
                <!-- Footer Column 4 -->
                <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                <div class="zz-footer__col zz-footer__col--4">
                    <?php dynamic_sidebar( 'footer-4' ); ?>
                </div>
                <?php endif; ?>

            </div>
            <?php else : ?>
            <!-- Default Footer Content (when no widgets configured) -->
            <div class="zz-footer__grid zz-footer__grid--default">
                
                <!-- Column 1: Brand -->
                <div class="zz-footer__col zz-footer__col--1">
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
                <div class="zz-footer__col zz-footer__col--2">
                    <div class="zz-footer-widget">
                        <h4 class="zz-footer-widget__title"><?php esc_html_e('Quick Links', 'zzprompts'); ?></h4>
                        <ul class="zz-footer-menu">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'zzprompts'); ?></a></li>
                            <?php if (post_type_exists('prompt')) : ?>
                            <li><a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>"><?php esc_html_e('Prompts', 'zzprompts'); ?></a></li>
                            <?php endif; ?>
                            <?php 
                            $blog_page = get_option('page_for_posts');
                            if ($blog_page) : ?>
                            <li><a href="<?php echo esc_url(get_permalink($blog_page)); ?>"><?php esc_html_e('Blog', 'zzprompts'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Column 3: Categories -->
                <div class="zz-footer__col zz-footer__col--3">
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
                <div class="zz-footer__col zz-footer__col--4">
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

            
            <!-- Footer Bottom: Contact Info & Copyright -->
            <div class="zz-footer__bottom">
                <?php 
                $email = zzprompts_get_option('footer_email', '');
                $location = zzprompts_get_option('footer_location', '');

                /* Footer Menu */
                if ( has_nav_menu( 'footer' ) ) : ?>
                    <div class="zz-footer__menu">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'zz-footer-menu-list',
                            'depth'          => 1,
                            'container'      => false,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </div>
                <?php endif; 
                
                if (!empty($email) || !empty($location)) : ?>
                    <div class="zz-footer__contact-info">
                        <?php if ($email) : ?>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="zz-footer__contact-item">
                                <i class="fas fa-envelope"></i> <?php echo esc_html($email); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($location) : ?>
                            <span class="zz-footer__contact-item">
                                <i class="fas fa-location-dot"></i> <?php echo esc_html($location); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <p class="zz-footer__copyright">
                    <?php 
                    $copyright = zzprompts_get_option('footer_copyright', sprintf(esc_html__('© %s Prompts Library. All rights reserved.', 'zzprompts'), date('Y')));
                    // Replace {year} placeholder if user used it
                    $copyright = str_replace('{year}', date('Y'), $copyright);
                    echo wp_kses_post($copyright); 
                    ?>
                </p>
            </div>
            
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button 
        class="zz-back-to-top" 
        id="zz-back-to-top"
        aria-label="<?php esc_attr_e('Back to top', 'zzprompts'); ?>"
        title="<?php esc_attr_e('Back to top', 'zzprompts'); ?>"
    >
        <i class="fas fa-arrow-up zz-back-to-top__icon"></i>
    </button>

</div><!-- .zz-site -->

<?php wp_footer(); ?>

</body>
</html>

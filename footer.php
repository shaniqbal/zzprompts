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
            
            <!-- Footer Bottom: Contact Info & Copyright -->
            <div class="zz-footer__bottom">
                <?php 
                $email = zzprompts_get_option('footer_email', '');
                $location = zzprompts_get_option('footer_location', '');
                
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

<?php
/**
 * Main Footer Template
 * ============================
 * Glassmorphism Design with 6 Independent Widgets
 * Styled by: assets/css/skins/modern/footer.css
 *
 * @package zzprompts
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Footer Widget Area -->
<div class="container">
    <div class="zz-footer-grid">
        
        <!-- Widget 1: About Us / Brand -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-1' ) ) {
                dynamic_sidebar( 'footer-modern-1' );
            } ?>
        </div>
        
        <!-- Widget 2: Social Links -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-2' ) ) {
                dynamic_sidebar( 'footer-modern-2' );
            } ?>
        </div>
        
        <!-- Widget 3: Cloud Tags / Taxonomy -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-3' ) ) {
                dynamic_sidebar( 'footer-modern-3' );
            } ?>
        </div>
        
        <!-- Widget 4: CTA / Promo -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-4' ) ) {
                dynamic_sidebar( 'footer-modern-4' );
            } ?>
        </div>
        
        <!-- Widget 5: Newsletter Signup -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-5' ) ) {
                dynamic_sidebar( 'footer-modern-5' );
            } ?>
        </div>
        
        <!-- Widget 6: Advertisement / Extra Links -->
        <div class="zz-footer-col">
            <?php if ( is_active_sidebar( 'footer-modern-6' ) ) {
                dynamic_sidebar( 'footer-modern-6' );
            } ?>
        </div>
        
    </div>
</div>

<!-- Footer Bottom / Copyright -->
<div class="zz-footer-bottom">
    <div class="container">
        <p>
            &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'zzprompts' ); ?>
        </p>
    </div>
</div>

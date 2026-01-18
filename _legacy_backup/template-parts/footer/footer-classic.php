<?php
/**
 * Classic Footer Template (V1)
 * =============================
 * Standard Widget Grid Layout with 6 Independent Widgets
 * 
 * This template is loaded when layout_style is set to 'v1' (Classic)
 * Styled by: assets/css/footer-classic.css
 *
 * @package zzprompts
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Classic Footer Widget Area -->
<div class="container">
    <div class="zz-footer-classic-grid">
        
        <!-- Widget 1: About Us / Brand -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-1' ) ) {
                dynamic_sidebar( 'footer-classic-1' );
            } ?>
        </div>
        
        <!-- Widget 2: Social Links -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-2' ) ) {
                dynamic_sidebar( 'footer-classic-2' );
            } ?>
        </div>
        
        <!-- Widget 3: Cloud Tags / Taxonomy -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-3' ) ) {
                dynamic_sidebar( 'footer-classic-3' );
            } ?>
        </div>
        
        <!-- Widget 4: CTA / Promo -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-4' ) ) {
                dynamic_sidebar( 'footer-classic-4' );
            } ?>
        </div>
        
        <!-- Widget 5: Newsletter Signup -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-5' ) ) {
                dynamic_sidebar( 'footer-classic-5' );
            } ?>
        </div>
        
        <!-- Widget 6: Advertisement / Extra Links -->
        <div class="zz-footer-classic-col">
            <?php if ( is_active_sidebar( 'footer-classic-6' ) ) {
                dynamic_sidebar( 'footer-classic-6' );
            } ?>
        </div>
        
    </div>
</div>

<!-- Classic Footer Bottom / Copyright -->
<div class="zz-footer-classic-bottom">
    <div class="container">
        <div class="zz-footer-classic-bottom-inner">
            <div class="zz-footer-classic-copyright">
                &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'zzprompts' ); ?>
            </div>
            <div class="zz-footer-classic-links">
                <a href="#"><?php esc_html_e( 'Privacy Policy', 'zzprompts' ); ?></a>
                <a href="#"><?php esc_html_e( 'Terms of Service', 'zzprompts' ); ?></a>
            </div>
        </div>
    </div>
</div>

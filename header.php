<?php
/**
 * Header Template - Modern V1
 * 
 * Glass morphism sticky header with BEM naming.
 * CRITICAL: wp_head(), body_class(), wp_body_open() MUST remain.
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <script>
        (function() {
            // 1. LocalStorage check karo (User ki manual setting)
            var savedTheme = localStorage.getItem('zz-theme');
            
            // 2. System Preference check karo (Agar user ne save nahi kiya)
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // 3. Final Theme decide karo
            var theme = savedTheme ? savedTheme : (prefersDark ? 'dark' : 'light');
            
            // 4. HTML tag par foran laga do (Page render hone se PEHLE)
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="zz-site">
    <a class="zz-skip-link" href="#main">
        <?php esc_html_e('Skip to content', 'zzprompts'); ?>
    </a>

    <!-- =========================================
         HEADER - Modern V1 (Glass Morphism)
         ========================================= -->
    <header id="masthead" class="zz-header">
        <div class="zz-header__inner">
            
            <!-- Logo / Site Branding -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-header__logo" rel="home">
                <?php
                if (has_custom_logo()) {
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    if ($logo) {
                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                    }
                } else {
                    $site_title = get_bloginfo('name');
                    $words = explode(' ', $site_title);
                    if (count($words) > 1) {
                        $last_word = array_pop($words);
                        echo esc_html(implode(' ', $words)) . ' <span class="zz-text-accent">' . esc_html($last_word) . '</span>';
                    } else {
                        echo esc_html($site_title);
                    }
                }
                ?>
            </a>

            <!-- Primary Navigation -->
            <nav class="zz-header__nav" aria-label="<?php esc_attr_e('Primary Menu', 'zzprompts'); ?>">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'zz-header__nav-list',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'link_before'    => '<span class="zz-header__nav-link">',
                        'link_after'     => '</span>',
                        'walker'         => null, // Can add custom walker later
                    ));
                } else {
                    // Default menu items for demo
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-header__nav-link <?php echo is_front_page() ? 'zz-header__nav-link--active' : ''; ?>"><?php esc_html_e('Home', 'zzprompts'); ?></a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-header__nav-link <?php echo (is_post_type_archive('prompt') || is_tax(array('prompt_category', 'ai_tool')) || is_singular('prompt')) ? 'zz-header__nav-link--active' : ''; ?>"><?php esc_html_e('Browse Prompts', 'zzprompts'); ?></a>
                    <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="zz-header__nav-link"><?php esc_html_e('Blog', 'zzprompts'); ?></a>
                    <a href="<?php echo esc_url(home_url('/about/')); ?>" class="zz-header__nav-link"><?php esc_html_e('About Us', 'zzprompts'); ?></a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="zz-header__nav-link"><?php esc_html_e('Contact', 'zzprompts'); ?></a>
                    <?php
                }
                ?>
            </nav>

            <!-- Header Search Bar -->
            <div class="zz-header__search">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="hidden" name="post_type" value="prompt">
                    <svg class="zz-header__search-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input 
                        type="search" 
                        class="zz-header__search-input" 
                        placeholder="<?php esc_attr_e('Search AI Prompts...', 'zzprompts'); ?>" 
                        value="<?php echo get_search_query(); ?>" 
                        name="s"
                    >
                </form>
            </div>

            <!-- Theme Toggle (Dark/Light) -->
            <button 
                class="zz-theme-toggle" 
                id="zz-theme-toggle"
                aria-label="<?php esc_attr_e('Toggle dark mode', 'zzprompts'); ?>"
                title="<?php esc_attr_e('Toggle dark mode', 'zzprompts'); ?>"
            >
                <i class="fas fa-sun zz-theme-toggle__icon zz-theme-toggle__sun"></i>
                <i class="fas fa-moon zz-theme-toggle__icon zz-theme-toggle__moon"></i>
            </button>

            <!-- Mobile Menu Toggle -->
            <button 
                class="zz-header__toggle" 
                id="zz-mobile-toggle"
                aria-controls="zz-mobile-menu" 
                aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle navigation menu', 'zzprompts'); ?>"
            >
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </header>

    <?php if (!is_front_page() && function_exists('zz_render_ad')) : ?>
        <div class="zz-header-ad-wrap">
            <?php zz_render_ad('header_code'); ?>
        </div>
    <?php endif; ?>

    <!-- =========================================
         MOBILE MENU OVERLAY
         ========================================= -->
    <div class="zz-mobile-menu" id="zz-mobile-menu" aria-hidden="true">
        <div class="zz-mobile-menu__header">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-header__logo">
                <?php bloginfo('name'); ?>
            </a>
            <button class="zz-mobile-menu__close" id="zz-mobile-close" aria-label="<?php esc_attr_e('Close menu', 'zzprompts'); ?>">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="zz-mobile-menu__nav">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'zz-mobile-menu__list',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
            } else {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-mobile-menu__nav-link"><?php esc_html_e('Home', 'zzprompts'); ?></a>
                <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-mobile-menu__nav-link"><?php esc_html_e('Browse Prompts', 'zzprompts'); ?></a>
                <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="zz-mobile-menu__nav-link"><?php esc_html_e('Blog', 'zzprompts'); ?></a>
                <a href="<?php echo esc_url(home_url('/about/')); ?>" class="zz-mobile-menu__nav-link"><?php esc_html_e('About Us', 'zzprompts'); ?></a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="zz-mobile-menu__nav-link"><?php esc_html_e('Contact', 'zzprompts'); ?></a>
                <?php
            }
            ?>
        </nav>
    </div>

    <!-- =========================================
         MAIN CONTENT AREA
         ========================================= -->
    <main id="main" class="zz-main">

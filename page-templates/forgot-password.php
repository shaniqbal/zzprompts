<?php
/**
 * Template Name: Forgot Password
 * Description: Modern glassmorphism Forgot Password page template.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Redirect if already logged in
if (is_user_logged_in()) {
    wp_safe_redirect(home_url());
    exit;
}

// Get login page URL (fallback to wp-login.php)
$login_page = get_permalink(get_page_by_path('login'));
if (!$login_page) {
    $login_page = wp_login_url();
}

// Handle messages
$message = '';
$message_type = '';

if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm') {
    $message = __('Check your email for a link to reset your password.', 'zzprompts');
    $message_type = 'success';
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalidcombo':
            $message = __('There is no account with that username or email address.', 'zzprompts');
            break;
        case 'invalidkey':
        case 'expiredkey':
            $message = __('This password reset link is invalid or has expired.', 'zzprompts');
            break;
        default:
            $message = __('An error occurred. Please try again.', 'zzprompts');
    }
    $message_type = 'error';
}

get_header();
?>

<main class="zz-auth-page">
    <div class="zz-auth-card">
        <a href="<?php echo esc_url($login_page); ?>" class="zz-auth-back">
            <i class="fa-solid fa-arrow-left"></i>
            <?php esc_html_e('Back to Login', 'zzprompts'); ?>
        </a>

        <header class="zz-auth-card__header">
            <?php 
            $logo = get_theme_mod('custom_logo');
            if ($logo) {
                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'zz-auth-card__logo', 'alt' => get_bloginfo('name')));
            }
            ?>
            <h1 class="zz-auth-card__title"><?php esc_html_e('Reset Password', 'zzprompts'); ?></h1>
            <p class="zz-auth-card__subtitle">
                <?php esc_html_e('Enter your email address and we\'ll send you instructions to reset your password.', 'zzprompts'); ?>
            </p>
        </header>

        <?php if ($message) : ?>
            <div class="zz-auth-message zz-auth-message--<?php echo esc_attr($message_type); ?>">
                <?php echo esc_html($message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo esc_url(wp_lostpassword_url()); ?>" method="post" class="zz-auth-form">
            <div class="zz-auth-field">
                <label class="zz-auth-field__label" for="user_login"><?php esc_html_e('Email Address', 'zzprompts'); ?></label>
                <div class="zz-auth-field__input-wrap">
                    <i class="fa-regular fa-envelope zz-auth-field__icon"></i>
                    <input 
                        type="email" 
                        id="user_login" 
                        name="user_login" 
                        class="zz-auth-field__input" 
                        placeholder="<?php esc_attr_e('Enter your email address', 'zzprompts'); ?>" 
                        required 
                        autocomplete="email"
                    >
                </div>
            </div>

            <?php wp_nonce_field('zz_forgot_form', 'zz_forgot_nonce'); ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url(add_query_arg('checkemail', 'confirm', get_permalink())); ?>">

            <button type="submit" class="zz-auth-submit">
                <?php esc_html_e('Send Reset Link', 'zzprompts'); ?>
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>

        <div class="zz-auth-divider">
            <span class="zz-auth-divider__line"></span>
            <span class="zz-auth-divider__text"><?php esc_html_e('or', 'zzprompts'); ?></span>
            <span class="zz-auth-divider__line"></span>
        </div>

        <p class="zz-auth-footer">
            <?php esc_html_e('Remember your password?', 'zzprompts'); ?>
            <a href="<?php echo esc_url($login_page); ?>" class="zz-auth-footer__link">
                <?php esc_html_e('Sign in', 'zzprompts'); ?>
            </a>
        </p>
    </div>
</main>

<?php
get_footer();

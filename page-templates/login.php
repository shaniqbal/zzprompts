<?php
/**
 * Template Name: Login
 * Description: Modern glassmorphism Login page template.
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

// Handle login errors
$login_error = '';
if (isset($_GET['login']) && $_GET['login'] === 'failed') {
    $login_error = __('Invalid username or password. Please try again.', 'zzprompts');
}

get_header();
?>

<main class="zz-auth-page">
    <div class="zz-auth-card">
        <header class="zz-auth-card__header">
            <?php 
            $logo = get_theme_mod('custom_logo');
            if ($logo) {
                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'zz-auth-card__logo', 'alt' => get_bloginfo('name')));
            }
            ?>
            <h1 class="zz-auth-card__title"><?php esc_html_e('Welcome Back', 'zzprompts'); ?></h1>
            <p class="zz-auth-card__subtitle"><?php esc_html_e('Sign in to access your account', 'zzprompts'); ?></p>
        </header>

        <?php if ($login_error) : ?>
            <div class="zz-auth-message zz-auth-message--error">
                <?php echo esc_html($login_error); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo esc_url(wp_login_url()); ?>" method="post" class="zz-auth-form">
            <div class="zz-auth-field">
                <label class="zz-auth-field__label" for="user_login"><?php esc_html_e('Username or Email', 'zzprompts'); ?></label>
                <div class="zz-auth-field__input-wrap">
                    <i class="fa-regular fa-user zz-auth-field__icon"></i>
                    <input 
                        type="text" 
                        id="user_login" 
                        name="log" 
                        class="zz-auth-field__input" 
                        placeholder="<?php esc_attr_e('Enter your username or email', 'zzprompts'); ?>" 
                        required 
                        autocomplete="username"
                    >
                </div>
            </div>

            <div class="zz-auth-field">
                <label class="zz-auth-field__label" for="user_pass"><?php esc_html_e('Password', 'zzprompts'); ?></label>
                <div class="zz-auth-field__input-wrap">
                    <i class="fa-solid fa-lock zz-auth-field__icon"></i>
                    <input 
                        type="password" 
                        id="user_pass" 
                        name="pwd" 
                        class="zz-auth-field__input" 
                        placeholder="<?php esc_attr_e('Enter your password', 'zzprompts'); ?>" 
                        required 
                        autocomplete="current-password"
                    >
                    <button type="button" class="zz-auth-field__toggle" aria-label="<?php esc_attr_e('Toggle password visibility', 'zzprompts'); ?>">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="zz-auth-extras">
                <label class="zz-auth-remember">
                    <input type="checkbox" name="rememberme" value="forever">
                    <?php esc_html_e('Remember me', 'zzprompts'); ?>
                </label>
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="zz-auth-forgot">
                    <?php esc_html_e('Forgot password?', 'zzprompts'); ?>
                </a>
            </div>

            <?php wp_nonce_field('zz_login_form', 'zz_login_nonce'); ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>">

            <button type="submit" class="zz-auth-submit">
                <?php esc_html_e('Sign In', 'zzprompts'); ?>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <?php if (get_option('users_can_register')) : ?>
            <div class="zz-auth-divider">
                <span class="zz-auth-divider__line"></span>
                <span class="zz-auth-divider__text"><?php esc_html_e('or', 'zzprompts'); ?></span>
                <span class="zz-auth-divider__line"></span>
            </div>

            <p class="zz-auth-footer">
                <?php esc_html_e("Don't have an account?", 'zzprompts'); ?>
                <a href="<?php echo esc_url(wp_registration_url()); ?>" class="zz-auth-footer__link">
                    <?php esc_html_e('Create one', 'zzprompts'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
</main>

<script>
(function() {
    // Password Toggle
    var toggleBtn = document.querySelector('.zz-auth-field__toggle');
    var passInput = document.getElementById('user_pass');
    
    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', function() {
            var icon = this.querySelector('i');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
})();
</script>

<?php
get_footer();

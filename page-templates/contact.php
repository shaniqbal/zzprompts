<?php
/**
 * Template Name: Contact Us
 * Description: Modern glassmorphism Contact Us page template.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="zz-contact-container">

    <!-- Hero Section -->
    <section class="zz-contact-hero">
        <span class="zz-contact-hero__label"><?php esc_html_e('Contact Us', 'zzprompts'); ?></span>
        <h1 class="zz-contact-hero__title"><?php esc_html_e('Get in Touch', 'zzprompts'); ?></h1>
        <p class="zz-contact-hero__desc">
            <?php esc_html_e('Have a question, feedback, or partnership inquiry? We\'d love to hear from you.', 'zzprompts'); ?>
        </p>
    </section>

    <!-- Contact Options -->
    <section class="zz-contact-options">
        <div class="zz-contact-option">
            <div class="zz-contact-option__icon">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <h2 class="zz-contact-option__title"><?php esc_html_e('Email Us', 'zzprompts'); ?></h2>
            <p class="zz-contact-option__text">
                <?php esc_html_e('For general inquiries and support questions.', 'zzprompts'); ?>
            </p>
            <?php $contact_email = zzprompts_get_option('footer_email', 'support@zzprompts.com'); ?>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="zz-contact-option__link">
                <?php echo esc_html($contact_email); ?>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="zz-contact-option">
            <div class="zz-contact-option__icon">
                <i class="fa-brands fa-discord"></i>
            </div>
            <h2 class="zz-contact-option__title"><?php esc_html_e('Join Community', 'zzprompts'); ?></h2>
            <p class="zz-contact-option__text">
                <?php esc_html_e('Chat with other users and get instant help.', 'zzprompts'); ?>
            </p>
            <?php $discord_url = zzprompts_get_option('social_discord', 'https://discord.gg/zzprompts'); ?>
            <a href="<?php echo esc_url($discord_url); ?>" class="zz-contact-option__link" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('Discord Server', 'zzprompts'); ?>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="zz-contact-option">
            <div class="zz-contact-option__icon">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <h2 class="zz-contact-option__title"><?php esc_html_e('Partnerships', 'zzprompts'); ?></h2>
            <p class="zz-contact-option__text">
                <?php esc_html_e('Interested in collaborating or advertising?', 'zzprompts'); ?>
            </p>
            <?php $partner_email = zzprompts_get_option('footer_email', 'partners@zzprompts.com'); ?>
            <a href="mailto:<?php echo esc_attr($partner_email); ?>" class="zz-contact-option__link">
                <?php echo esc_html($partner_email); ?>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- Contact Form Card -->
    <section class="zz-contact-form-card">
        <h2 class="zz-contact-form-card__title"><?php esc_html_e('Send Us a Message', 'zzprompts'); ?></h2>
        
        <?php 
        // Display success/error messages for fallback form
        if (isset($_GET['contact'])) :
            $contact_status = sanitize_key($_GET['contact']);
            if ($contact_status === 'success') : ?>
                <div class="zz-auth-message zz-auth-message--success" style="max-width: 600px; margin: 0 auto var(--zz-space-5, 20px);">
                    <?php esc_html_e('Thank you! Your message has been sent successfully.', 'zzprompts'); ?>
                </div>
            <?php elseif ($contact_status === 'error') : ?>
                <div class="zz-auth-message zz-auth-message--error" style="max-width: 600px; margin: 0 auto var(--zz-space-5, 20px);">
                    <?php esc_html_e('Sorry, there was an error sending your message. Please try again.', 'zzprompts'); ?>
                </div>
            <?php endif;
        endif;
        ?>
        
        <div class="zz-contact-form">
            <?php
            // Check for Contact Form 7
            $cf7_shortcode = get_theme_mod('zz_contact_form_shortcode', '');
            
            if ($cf7_shortcode && shortcode_exists('contact-form-7')) {
                echo do_shortcode($cf7_shortcode);
            } else {
                // Fallback form (sends to admin email)
                ?>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="zz-contact-fallback-form">
                    <?php wp_nonce_field('zz_contact_form', 'zz_contact_nonce'); ?>
                    <input type="hidden" name="action" value="zz_contact_form">
                    
                    <div class="zz-contact-form-row">
                        <div class="zz-contact-field">
                            <label class="zz-contact-field__label" for="contact-name"><?php esc_html_e('Full Name', 'zzprompts'); ?></label>
                            <input type="text" id="contact-name" name="name" class="zz-contact-field__input" placeholder="<?php esc_attr_e('John Doe', 'zzprompts'); ?>" required>
                        </div>
                        <div class="zz-contact-field">
                            <label class="zz-contact-field__label" for="contact-email"><?php esc_html_e('Email Address', 'zzprompts'); ?></label>
                            <input type="email" id="contact-email" name="email" class="zz-contact-field__input" placeholder="<?php esc_attr_e('john@example.com', 'zzprompts'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="zz-contact-field">
                        <label class="zz-contact-field__label" for="contact-subject"><?php esc_html_e('Subject', 'zzprompts'); ?></label>
                        <input type="text" id="contact-subject" name="subject" class="zz-contact-field__input" placeholder="<?php esc_attr_e('How can we help?', 'zzprompts'); ?>" required>
                    </div>
                    
                    <div class="zz-contact-field">
                        <label class="zz-contact-field__label" for="contact-message"><?php esc_html_e('Message', 'zzprompts'); ?></label>
                        <textarea id="contact-message" name="message" class="zz-contact-field__textarea" placeholder="<?php esc_attr_e('Tell us what\'s on your mind...', 'zzprompts'); ?>" required></textarea>
                    </div>
                    
                    <button type="submit" class="zz-contact-form__submit">
                        <?php esc_html_e('Send Message', 'zzprompts'); ?>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="zz-contact-faq">
        <h2 class="zz-contact-faq__title"><?php esc_html_e('Frequently Asked Questions', 'zzprompts'); ?></h2>
        <div class="zz-contact-faq__list">
            <div class="zz-contact-faq-item">
                <button class="zz-contact-faq-item__q" type="button" aria-expanded="false">
                    <?php esc_html_e('How long does it take to get a response?', 'zzprompts'); ?>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="zz-contact-faq-item__a">
                    <?php esc_html_e('We typically respond within 24-48 hours during business days. For urgent matters, join our Discord community for faster assistance.', 'zzprompts'); ?>
                </div>
            </div>
            
            <div class="zz-contact-faq-item">
                <button class="zz-contact-faq-item__q" type="button" aria-expanded="false">
                    <?php esc_html_e('Can I request a custom prompt?', 'zzprompts'); ?>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="zz-contact-faq-item__a">
                    <?php esc_html_e('Absolutely! Use the contact form above to describe your needs and we\'ll work with you to create a tailored prompt for your use case.', 'zzprompts'); ?>
                </div>
            </div>
            
            <div class="zz-contact-faq-item">
                <button class="zz-contact-faq-item__q" type="button" aria-expanded="false">
                    <?php esc_html_e('Do you offer bulk licensing for teams?', 'zzprompts'); ?>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="zz-contact-faq-item__a">
                    <?php esc_html_e('Yes! We offer team and enterprise plans with bulk access and custom pricing. Contact our partnerships team for more information.', 'zzprompts'); ?>
                </div>
            </div>
            
            <div class="zz-contact-faq-item">
                <button class="zz-contact-faq-item__q" type="button" aria-expanded="false">
                    <?php esc_html_e('How can I become a contributor?', 'zzprompts'); ?>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="zz-contact-faq-item__a">
                    <?php esc_html_e('We\'re always looking for talented prompt engineers! Send us your best prompts and a brief introduction via the contact form.', 'zzprompts'); ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Buyer Custom Content Area (Gutenberg Blocks)
    if (have_posts()) :
        while (have_posts()) : the_post();
            $content = get_the_content();
            if (!empty(trim($content))) : ?>
                <section class="zz-page-content">
                    <div class="zz-container">
                        <?php the_content(); ?>
                    </div>
                </section>
            <?php endif;
        endwhile;
    endif;
    ?>

</main>

<script>
(function() {
    // FAQ Toggle
    document.querySelectorAll('.zz-contact-faq-item__q').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var item = this.parentElement;
            var isOpen = item.classList.contains('is-open');
            
            // Close all
            document.querySelectorAll('.zz-contact-faq-item').forEach(function(faq) {
                faq.classList.remove('is-open');
                faq.querySelector('.zz-contact-faq-item__q').setAttribute('aria-expanded', 'false');
            });
            
            // Open clicked if it was closed
            if (!isOpen) {
                item.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });
})();
</script>

<?php
get_footer();

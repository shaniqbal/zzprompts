<?php
/**
 * Template Name: About Us
 * Description: Modern glassmorphism About Us page template.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="zz-about-container">

    <!-- Hero Section -->
    <section class="zz-about-hero">
        <span class="zz-about-hero__label"><?php esc_html_e('About Us', 'zzprompts'); ?></span>
        <h1 class="zz-about-hero__title"><?php esc_html_e('Premium AI Prompts for Creators & Professionals', 'zzprompts'); ?></h1>
        <p class="zz-about-hero__desc">
            <?php esc_html_e('We curate high-quality, tested prompts that help you get better results from ChatGPT, Midjourney, DALL·E and other AI tools.', 'zzprompts'); ?>
        </p>
    </section>

    <!-- Split Section: What Is This? -->
    <section class="zz-about-split">
        <div class="zz-about-split__content">
            <h2 class="zz-about-split__title"><?php esc_html_e('What Is This Platform?', 'zzprompts'); ?></h2>
            <p class="zz-about-split__text">
                <?php esc_html_e('ZZ Prompts is a curated library of premium AI prompts designed to help creators, marketers, developers, and professionals unlock the full potential of AI tools.', 'zzprompts'); ?>
            </p>
            <p class="zz-about-split__text">
                <?php esc_html_e('Every prompt is carefully crafted and tested to ensure consistent, high-quality outputs across various AI platforms.', 'zzprompts'); ?>
            </p>
            <ul class="zz-about-bullets">
                <li class="zz-about-bullet">
                    <span class="zz-about-bullet__icon"><i class="fa-solid fa-check"></i></span>
                    <span class="zz-about-bullet__text"><?php esc_html_e('Hand-curated by AI experts', 'zzprompts'); ?></span>
                </li>
                <li class="zz-about-bullet">
                    <span class="zz-about-bullet__icon"><i class="fa-solid fa-check"></i></span>
                    <span class="zz-about-bullet__text"><?php esc_html_e('Tested on real AI tools', 'zzprompts'); ?></span>
                </li>
                <li class="zz-about-bullet">
                    <span class="zz-about-bullet__icon"><i class="fa-solid fa-check"></i></span>
                    <span class="zz-about-bullet__text"><?php esc_html_e('New prompts added weekly', 'zzprompts'); ?></span>
                </li>
            </ul>
        </div>
        <div class="zz-about-split__card">
            <div class="zz-about-info-card">
                <div class="zz-about-info-row">
                    <span class="zz-about-info-label"><?php esc_html_e('Founded', 'zzprompts'); ?></span>
                    <span class="zz-about-info-val">2024</span>
                </div>
                <div class="zz-about-info-row">
                    <span class="zz-about-info-label"><?php esc_html_e('Total Prompts', 'zzprompts'); ?></span>
                    <?php 
                    $p_counts = wp_count_posts('prompt');
                    $p_publish = isset($p_counts->publish) ? $p_counts->publish : 0;
                    ?>
                    <span class="zz-about-info-val"><?php echo esc_html($p_publish > 0 ? $p_publish : '500'); ?>+</span>
                </div>
                <div class="zz-about-info-row">
                    <span class="zz-about-info-label"><?php esc_html_e('Active Users', 'zzprompts'); ?></span>
                    <span class="zz-about-info-val">10,000+</span>
                </div>
                <div class="zz-about-info-row">
                    <span class="zz-about-info-label"><?php esc_html_e('AI Tools Supported', 'zzprompts'); ?></span>
                    <span class="zz-about-info-val">8+</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="zz-about-vision">
        <h2 class="zz-about-vision__title"><?php esc_html_e('Our Vision', 'zzprompts'); ?></h2>
        <p class="zz-about-vision__text">
            <?php esc_html_e('We believe AI should be accessible to everyone. Our mission is to democratize AI creativity by providing ready-to-use prompts that deliver professional results — no expertise required.', 'zzprompts'); ?>
        </p>
    </section>

    <!-- How It Works -->
    <section class="zz-about-steps">
        <header class="zz-about-section-head">
            <h2 class="zz-about-section-head__title"><?php esc_html_e('How It Works', 'zzprompts'); ?></h2>
            <p class="zz-about-section-head__desc"><?php esc_html_e('Get started in three simple steps', 'zzprompts'); ?></p>
        </header>
        <div class="zz-about-steps-grid">
            <div class="zz-about-step">
                <div class="zz-about-step__icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <h3 class="zz-about-step__title"><?php esc_html_e('Browse Prompts', 'zzprompts'); ?></h3>
                <p class="zz-about-step__desc"><?php esc_html_e('Explore our curated library of prompts organized by category and AI tool.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-step">
                <div class="zz-about-step__icon"><i class="fa-solid fa-copy"></i></div>
                <h3 class="zz-about-step__title"><?php esc_html_e('Copy & Paste', 'zzprompts'); ?></h3>
                <p class="zz-about-step__desc"><?php esc_html_e('Click to copy any prompt instantly. Paste it into your favorite AI tool.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-step">
                <div class="zz-about-step__icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <h3 class="zz-about-step__title"><?php esc_html_e('Get Results', 'zzprompts'); ?></h3>
                <p class="zz-about-step__desc"><?php esc_html_e('Watch as AI generates amazing content tailored to your needs.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-step">
                <div class="zz-about-step__icon"><i class="fa-solid fa-heart"></i></div>
                <h3 class="zz-about-step__title"><?php esc_html_e('Save Favorites', 'zzprompts'); ?></h3>
                <p class="zz-about-step__desc"><?php esc_html_e('Like your favorite prompts and build your personal collection.', 'zzprompts'); ?></p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="zz-about-values-section">
        <header class="zz-about-section-head">
            <h2 class="zz-about-section-head__title"><?php esc_html_e('Our Values', 'zzprompts'); ?></h2>
            <p class="zz-about-section-head__desc"><?php esc_html_e('Principles that guide everything we do', 'zzprompts'); ?></p>
        </header>
        <div class="zz-about-values">
            <div class="zz-about-value">
                <div class="zz-about-value__icon"><i class="fa-solid fa-gem"></i></div>
                <h3 class="zz-about-value__title"><?php esc_html_e('Quality First', 'zzprompts'); ?></h3>
                <p class="zz-about-value__text"><?php esc_html_e('Every prompt is tested and refined for consistent, high-quality outputs.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-value">
                <div class="zz-about-value__icon"><i class="fa-solid fa-users"></i></div>
                <h3 class="zz-about-value__title"><?php esc_html_e('Community Driven', 'zzprompts'); ?></h3>
                <p class="zz-about-value__text"><?php esc_html_e('Built by creators, for creators. Your feedback shapes our library.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-value">
                <div class="zz-about-value__icon"><i class="fa-solid fa-rocket"></i></div>
                <h3 class="zz-about-value__title"><?php esc_html_e('Always Evolving', 'zzprompts'); ?></h3>
                <p class="zz-about-value__text"><?php esc_html_e('We stay ahead of AI trends and continuously update our library.', 'zzprompts'); ?></p>
            </div>
            <div class="zz-about-value">
                <div class="zz-about-value__icon"><i class="fa-solid fa-lock"></i></div>
                <h3 class="zz-about-value__title"><?php esc_html_e('Privacy Focused', 'zzprompts'); ?></h3>
                <p class="zz-about-value__text"><?php esc_html_e('Your data stays private. We never track or sell your information.', 'zzprompts'); ?></p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="zz-about-stats">
        <div class="zz-about-stat">
            <?php 
            $p_counts = wp_count_posts('prompt');
            $p_publish = isset($p_counts->publish) ? $p_counts->publish : 0;
            ?>
            <span class="zz-about-stat__num"><?php echo esc_html($p_publish > 0 ? $p_publish : '500'); ?>+</span>
            <span class="zz-about-stat__label"><?php esc_html_e('Prompts', 'zzprompts'); ?></span>
        </div>
        <div class="zz-about-stat">
            <span class="zz-about-stat__num">10K+</span>
            <span class="zz-about-stat__label"><?php esc_html_e('Users', 'zzprompts'); ?></span>
        </div>
        <div class="zz-about-stat">
            <span class="zz-about-stat__num">8+</span>
            <span class="zz-about-stat__label"><?php esc_html_e('AI Tools', 'zzprompts'); ?></span>
        </div>
        <div class="zz-about-stat">
            <span class="zz-about-stat__num">50K+</span>
            <span class="zz-about-stat__label"><?php esc_html_e('Copies', 'zzprompts'); ?></span>
        </div>
    </section>

    <!-- Team Section (Optional - Comment out if not needed) -->
    <!--
    <section class="zz-about-team">
        <header class="zz-about-section-head">
            <h2 class="zz-about-section-head__title"><?php esc_html_e('Meet the Team', 'zzprompts'); ?></h2>
            <p class="zz-about-section-head__desc"><?php esc_html_e('The people behind ZZ Prompts', 'zzprompts'); ?></p>
        </header>
        <div class="zz-about-team-grid">
            <article class="zz-about-team-card">
                <img class="zz-about-team-card__avatar" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/team-1.jpg" alt="<?php esc_attr_e('Team Member', 'zzprompts'); ?>">
                <h3 class="zz-about-team-card__name">John Doe</h3>
                <span class="zz-about-team-card__role"><?php esc_html_e('Founder & CEO', 'zzprompts'); ?></span>
                <div class="zz-about-team-socials">
                    <a href="#" class="zz-about-team-socials__link" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="zz-about-team-socials__link" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </article>
        </div>
    </section>
    -->

    <!-- CTA Section -->
    <section class="zz-about-cta">
        <div class="zz-about-cta__card">
            <h2 class="zz-about-cta__title"><?php esc_html_e('Ready to Explore?', 'zzprompts'); ?></h2>
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-about-cta__btn">
                <?php esc_html_e('Browse Prompts', 'zzprompts'); ?>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
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

<?php
get_footer();

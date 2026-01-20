<?php
/**
 * Block Patterns Registration
 * 
 * Registers reusable Gutenberg block patterns for buyers.
 * Patterns use core blocks with ZZ Prompts styling classes.
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Register Block Pattern Category
 */
function zzprompts_register_pattern_category() {
    register_block_pattern_category(
        'zzprompts',
        array(
            'label'       => esc_html__('ZZ Prompts', 'zzprompts'),
            'description' => esc_html__('Custom patterns for ZZ Prompts theme.', 'zzprompts'),
        )
    );
}
add_action('init', 'zzprompts_register_pattern_category');

/**
 * Register Block Patterns
 */
function zzprompts_register_block_patterns() {
    
    // =========================================
    // PRICING TABLE PATTERN
    // =========================================
    register_block_pattern(
        'zzprompts/pricing-table',
        array(
            'title'       => esc_html__('Pricing Table', 'zzprompts'),
            'description' => esc_html__('A 3-column pricing table with Free, Pro, and Enterprise plans.', 'zzprompts'),
            'categories'  => array('zzprompts'),
            'keywords'    => array('pricing', 'plans', 'table', 'subscription'),
            'content'     => '<!-- wp:group {"className":"zz-pricing-section","layout":{"type":"default"}} -->
<div class="wp-block-group zz-pricing-section">

<!-- wp:heading {"textAlign":"center","level":2,"className":"zz-pricing-title"} -->
<h2 class="wp-block-heading has-text-align-center zz-pricing-title">' . esc_html__('Choose Your Plan', 'zzprompts') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"zz-pricing-subtitle"} -->
<p class="has-text-align-center zz-pricing-subtitle">' . esc_html__('Simple, transparent pricing for everyone.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"className":"zz-pricing-grid"} -->
<div class="wp-block-columns zz-pricing-grid">

<!-- wp:column {"className":"zz-pricing-card"} -->
<div class="wp-block-column zz-pricing-card">

<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-pricing-card__name"} -->
<h3 class="wp-block-heading has-text-align-center zz-pricing-card__name">' . esc_html__('Free', 'zzprompts') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"zz-pricing-card__price"} -->
<p class="has-text-align-center zz-pricing-card__price"><strong>$0</strong> / ' . esc_html__('month', 'zzprompts') . '</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zz-pricing-card__features"} -->
<ul class="wp-block-list zz-pricing-card__features">
<li>' . esc_html__('Access to 50 prompts', 'zzprompts') . '</li>
<li>' . esc_html__('Basic categories', 'zzprompts') . '</li>
<li>' . esc_html__('Community support', 'zzprompts') . '</li>
</ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"zz-pricing-card__btn"} -->
<div class="wp-block-button zz-pricing-card__btn"><a class="wp-block-button__link wp-element-button">' . esc_html__('Get Started', 'zzprompts') . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-pricing-card zz-pricing-card--featured"} -->
<div class="wp-block-column zz-pricing-card zz-pricing-card--featured">

<!-- wp:paragraph {"align":"center","className":"zz-pricing-card__badge"} -->
<p class="has-text-align-center zz-pricing-card__badge">' . esc_html__('Most Popular', 'zzprompts') . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-pricing-card__name"} -->
<h3 class="wp-block-heading has-text-align-center zz-pricing-card__name">' . esc_html__('Pro', 'zzprompts') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"zz-pricing-card__price"} -->
<p class="has-text-align-center zz-pricing-card__price"><strong>$19</strong> / ' . esc_html__('month', 'zzprompts') . '</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zz-pricing-card__features"} -->
<ul class="wp-block-list zz-pricing-card__features">
<li>' . esc_html__('Unlimited prompts', 'zzprompts') . '</li>
<li>' . esc_html__('All categories + new releases', 'zzprompts') . '</li>
<li>' . esc_html__('Priority support', 'zzprompts') . '</li>
<li>' . esc_html__('Early access to features', 'zzprompts') . '</li>
</ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"zz-pricing-card__btn zz-pricing-card__btn--primary"} -->
<div class="wp-block-button zz-pricing-card__btn zz-pricing-card__btn--primary"><a class="wp-block-button__link wp-element-button">' . esc_html__('Start Free Trial', 'zzprompts') . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-pricing-card"} -->
<div class="wp-block-column zz-pricing-card">

<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-pricing-card__name"} -->
<h3 class="wp-block-heading has-text-align-center zz-pricing-card__name">' . esc_html__('Enterprise', 'zzprompts') . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"zz-pricing-card__price"} -->
<p class="has-text-align-center zz-pricing-card__price"><strong>' . esc_html__('Custom', 'zzprompts') . '</strong></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zz-pricing-card__features"} -->
<ul class="wp-block-list zz-pricing-card__features">
<li>' . esc_html__('Everything in Pro', 'zzprompts') . '</li>
<li>' . esc_html__('Custom prompt creation', 'zzprompts') . '</li>
<li>' . esc_html__('Team management', 'zzprompts') . '</li>
<li>' . esc_html__('Dedicated account manager', 'zzprompts') . '</li>
<li>' . esc_html__('API access', 'zzprompts') . '</li>
</ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"zz-pricing-card__btn"} -->
<div class="wp-block-button zz-pricing-card__btn"><a class="wp-block-button__link wp-element-button">' . esc_html__('Contact Sales', 'zzprompts') . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->',
        )
    );

    // =========================================
    // FEATURES GRID PATTERN
    // =========================================
    register_block_pattern(
        'zzprompts/features-grid',
        array(
            'title'       => esc_html__('Features Grid', 'zzprompts'),
            'description' => esc_html__('A 3-column grid showcasing key features with icons.', 'zzprompts'),
            'categories'  => array('zzprompts'),
            'keywords'    => array('features', 'grid', 'icons', 'benefits'),
            'content'     => '<!-- wp:group {"className":"zz-features-section","layout":{"type":"default"}} -->
<div class="wp-block-group zz-features-section">

<!-- wp:heading {"textAlign":"center","level":2,"className":"zz-features-title"} -->
<h2 class="wp-block-heading has-text-align-center zz-features-title">' . esc_html__('Why Choose Us', 'zzprompts') . '</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"zz-features-grid"} -->
<div class="wp-block-columns zz-features-grid">

<!-- wp:column {"className":"zz-feature-card"} -->
<div class="wp-block-column zz-feature-card">
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__icon","fontSize":"large"} -->
<p class="has-text-align-center zz-feature-card__icon has-large-font-size">⚡</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-feature-card__title"} -->
<h3 class="wp-block-heading has-text-align-center zz-feature-card__title">' . esc_html__('Lightning Fast', 'zzprompts') . '</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__desc"} -->
<p class="has-text-align-center zz-feature-card__desc">' . esc_html__('Copy any prompt with a single click. No signup required to browse.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-feature-card"} -->
<div class="wp-block-column zz-feature-card">
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__icon","fontSize":"large"} -->
<p class="has-text-align-center zz-feature-card__icon has-large-font-size">🎯</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-feature-card__title"} -->
<h3 class="wp-block-heading has-text-align-center zz-feature-card__title">' . esc_html__('Expert Curated', 'zzprompts') . '</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__desc"} -->
<p class="has-text-align-center zz-feature-card__desc">' . esc_html__('Every prompt is tested and refined by AI professionals for best results.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-feature-card"} -->
<div class="wp-block-column zz-feature-card">
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__icon","fontSize":"large"} -->
<p class="has-text-align-center zz-feature-card__icon has-large-font-size">🔄</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3,"className":"zz-feature-card__title"} -->
<h3 class="wp-block-heading has-text-align-center zz-feature-card__title">' . esc_html__('Always Updated', 'zzprompts') . '</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"zz-feature-card__desc"} -->
<p class="has-text-align-center zz-feature-card__desc">' . esc_html__('New prompts added weekly to keep up with the latest AI capabilities.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->',
        )
    );

    // =========================================
    // FAQ ACCORDION PATTERN
    // =========================================
    register_block_pattern(
        'zzprompts/faq-accordion',
        array(
            'title'       => esc_html__('FAQ Accordion', 'zzprompts'),
            'description' => esc_html__('Frequently asked questions in an expandable accordion format.', 'zzprompts'),
            'categories'  => array('zzprompts'),
            'keywords'    => array('faq', 'accordion', 'questions', 'answers'),
            'content'     => '<!-- wp:group {"className":"zz-faq-section","layout":{"type":"default"}} -->
<div class="wp-block-group zz-faq-section">

<!-- wp:heading {"textAlign":"center","level":2,"className":"zz-faq-title"} -->
<h2 class="wp-block-heading has-text-align-center zz-faq-title">' . esc_html__('Frequently Asked Questions', 'zzprompts') . '</h2>
<!-- /wp:heading -->

<!-- wp:details {"className":"zz-faq-item"} -->
<details class="wp-block-details zz-faq-item">
<summary>' . esc_html__('What is ZZ Prompts?', 'zzprompts') . '</summary>
<!-- wp:paragraph -->
<p>' . esc_html__('ZZ Prompts is a curated library of high-quality AI prompts designed to help you get better results from ChatGPT, Midjourney, DALL·E, and other AI tools.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->

<!-- wp:details {"className":"zz-faq-item"} -->
<details class="wp-block-details zz-faq-item">
<summary>' . esc_html__('Are the prompts free to use?', 'zzprompts') . '</summary>
<!-- wp:paragraph -->
<p>' . esc_html__('We offer both free and premium prompts. Free users can access a selection of prompts, while premium members get unlimited access to our entire library.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->

<!-- wp:details {"className":"zz-faq-item"} -->
<details class="wp-block-details zz-faq-item">
<summary>' . esc_html__('Which AI tools are supported?', 'zzprompts') . '</summary>
<!-- wp:paragraph -->
<p>' . esc_html__('Our prompts work with ChatGPT, Claude, Gemini, Midjourney, DALL·E, Stable Diffusion, and many other popular AI platforms.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->

<!-- wp:details {"className":"zz-faq-item"} -->
<details class="wp-block-details zz-faq-item">
<summary>' . esc_html__('Can I request custom prompts?', 'zzprompts') . '</summary>
<!-- wp:paragraph -->
<p>' . esc_html__('Absolutely! Contact us with your specific needs and our team will create tailored prompts for your use case.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->

</div>
<!-- /wp:group -->',
        )
    );

    // =========================================
    // CTA BANNER PATTERN
    // =========================================
    register_block_pattern(
        'zzprompts/cta-banner',
        array(
            'title'       => esc_html__('Call to Action Banner', 'zzprompts'),
            'description' => esc_html__('A prominent call-to-action section with heading and button.', 'zzprompts'),
            'categories'  => array('zzprompts'),
            'keywords'    => array('cta', 'banner', 'action', 'button'),
            'content'     => '<!-- wp:group {"className":"zz-cta-banner","layout":{"type":"default"}} -->
<div class="wp-block-group zz-cta-banner">

<!-- wp:heading {"textAlign":"center","level":2,"className":"zz-cta-banner__title"} -->
<h2 class="wp-block-heading has-text-align-center zz-cta-banner__title">' . esc_html__('Ready to Supercharge Your AI Workflow?', 'zzprompts') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"zz-cta-banner__desc"} -->
<p class="has-text-align-center zz-cta-banner__desc">' . esc_html__('Join thousands of creators and professionals using our curated prompts to save time and boost productivity.', 'zzprompts') . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"zz-cta-banner__btn"} -->
<div class="wp-block-button zz-cta-banner__btn"><a class="wp-block-button__link wp-element-button">' . esc_html__('Browse Prompts', 'zzprompts') . '</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"zz-cta-banner__btn zz-cta-banner__btn--secondary"} -->
<div class="wp-block-button zz-cta-banner__btn zz-cta-banner__btn--secondary"><a class="wp-block-button__link wp-element-button">' . esc_html__('Learn More', 'zzprompts') . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->',
        )
    );

    // =========================================
    // TESTIMONIALS PATTERN
    // =========================================
    register_block_pattern(
        'zzprompts/testimonials',
        array(
            'title'       => esc_html__('Testimonials', 'zzprompts'),
            'description' => esc_html__('Customer testimonials in a 3-column grid layout.', 'zzprompts'),
            'categories'  => array('zzprompts'),
            'keywords'    => array('testimonials', 'reviews', 'quotes', 'customers'),
            'content'     => '<!-- wp:group {"className":"zz-testimonials-section","layout":{"type":"default"}} -->
<div class="wp-block-group zz-testimonials-section">

<!-- wp:heading {"textAlign":"center","level":2,"className":"zz-testimonials-title"} -->
<h2 class="wp-block-heading has-text-align-center zz-testimonials-title">' . esc_html__('What Our Users Say', 'zzprompts') . '</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"zz-testimonials-grid"} -->
<div class="wp-block-columns zz-testimonials-grid">

<!-- wp:column {"className":"zz-testimonial-card"} -->
<div class="wp-block-column zz-testimonial-card">
<!-- wp:paragraph {"className":"zz-testimonial-card__stars"} -->
<p class="zz-testimonial-card__stars">⭐⭐⭐⭐⭐</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__quote"} -->
<p class="zz-testimonial-card__quote">"' . esc_html__('These prompts have completely transformed my content creation workflow. I save hours every week!', 'zzprompts') . '"</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__author"} -->
<p class="zz-testimonial-card__author"><strong>Sarah M.</strong> — ' . esc_html__('Content Creator', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-testimonial-card"} -->
<div class="wp-block-column zz-testimonial-card">
<!-- wp:paragraph {"className":"zz-testimonial-card__stars"} -->
<p class="zz-testimonial-card__stars">⭐⭐⭐⭐⭐</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__quote"} -->
<p class="zz-testimonial-card__quote">"' . esc_html__('The quality of prompts here is unmatched. Every single one delivers exactly what I need.', 'zzprompts') . '"</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__author"} -->
<p class="zz-testimonial-card__author"><strong>Alex K.</strong> — ' . esc_html__('Marketing Manager', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"zz-testimonial-card"} -->
<div class="wp-block-column zz-testimonial-card">
<!-- wp:paragraph {"className":"zz-testimonial-card__stars"} -->
<p class="zz-testimonial-card__stars">⭐⭐⭐⭐⭐</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__quote"} -->
<p class="zz-testimonial-card__quote">"' . esc_html__('As a developer, these coding prompts have been a game-changer for my productivity.', 'zzprompts') . '"</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"zz-testimonial-card__author"} -->
<p class="zz-testimonial-card__author"><strong>James T.</strong> — ' . esc_html__('Software Developer', 'zzprompts') . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->',
        )
    );

}
add_action('init', 'zzprompts_register_block_patterns');

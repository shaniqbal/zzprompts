<?php
/**
 * Front Page Template - Modern V1
 * 
 * Loads the Modern V1 homepage layout.
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;

get_header();

// Load Homepage Template
get_template_part('template-parts/home/hero', 'home');

// Reset post data after hero-home.php queries
wp_reset_postdata();

// Buyer Custom Content Area (Gutenberg Blocks)
// Get the front page content if it exists
$front_page_id = get_option('page_on_front');
if ($front_page_id) :
    $front_page = get_post($front_page_id);
    $content = $front_page->post_content;
    if (!empty(trim($content))) : ?>
        <section class="zz-page-content">
            <div class="zz-container">
                <?php echo apply_filters('the_content', $content); ?>
            </div>
        </section>
    <?php endif;
endif;

get_footer();

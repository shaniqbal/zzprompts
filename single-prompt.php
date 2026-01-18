<?php
/**
 * Single Prompt Template
 * Modern V1 Layout - Glass UI with Sidebar
 * 
 * @package zzprompts
 * @version 4.0.0 - Modern V1 Launch
 */

defined('ABSPATH') || exit;

get_header();
?>

<!-- Modern V1 Layout: Glass UI with integrated sidebar -->
<?php
while (have_posts()) :
    the_post();
    get_template_part('template-parts/prompt/single-prompt', 'hero');
endwhile;
?>

<?php get_footer(); ?>

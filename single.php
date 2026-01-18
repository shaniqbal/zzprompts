<?php
/**
 * The template for displaying all single posts (Blog)
 * Modern V1 Layout - Glass UI with Progress Bar
 *
 * @package zzprompts
 * @version 2.0.0 - Modern V1 Launch
 */

defined('ABSPATH') || exit;

get_header();
?>

<!-- Modern V1 Layout: Glass UI with Progress Bar -->
<?php
while (have_posts()) : the_post();
    get_template_part('template-parts/blog/single', 'blog');
endwhile;
?>

<?php get_footer(); ?>


<?php
/**
 * The template for displaying all pages
 * 
 * Modern glassmorphism page template with hero section.
 * Consistent design for all custom pages (Terms, Privacy, Pricing, etc.)
 *
 * @package zzprompts
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();
?>

<main class="zz-page-container">

    <!-- Hero Section -->
    <section class="zz-page-hero">
        <div class="zz-container">
            <!-- Breadcrumbs -->
            <nav class="zz-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'zzprompts'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-breadcrumbs__link"><?php esc_html_e('Home', 'zzprompts'); ?></a>
                <span class="zz-breadcrumbs__separator">&rsaquo;</span>
                <span class="zz-breadcrumbs__current"><?php the_title(); ?></span>
            </nav>
            
            <h1 class="zz-page-hero__title"><?php the_title(); ?></h1>
            
            <?php if (has_excerpt()) : ?>
                <p class="zz-page-hero__desc"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Page Content -->
    <section class="zz-page-content">
        <div class="zz-container">
            <article id="post-<?php the_ID(); ?>" <?php post_class('zz-page-article'); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="zz-page-article__thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="zz-page-article__content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="zz-page-links">' . esc_html__('Pages:', 'zzprompts'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

            </article>

            <?php
            if (comments_open() || get_comments_number()) :
            ?>
                <div class="zz-page-comments">
                    <?php comments_template(); ?>
                </div>
            <?php
            endif;
            ?>
        </div>
    </section>

</main>

<?php
endwhile;

get_footer();

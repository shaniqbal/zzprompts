<?php
/**
 * The template for displaying all pages
 * Displays a premium centered layout geared for readability (SaaS Standard).
 *
 * @package zzprompts
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main section-padding">
    <div class="container">
        
        <!-- Premium Focused Reading Layout (Center Column) -->
        <div class="zz-readable-container">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <header class="entry-header mb-10 text-center">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail mb-8 rounded-xl overflow-hidden">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'zzprompts'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                </article>

                <?php
                if (comments_open() || get_comments_number()) :
                    ?>
                    <div class="page-comments mt-12 pt-12 border-t">
                        <?php comments_template(); ?>
                    </div>
                    <?php
                endif;
                ?>

            <?php endwhile; ?>
        </div>
        
    </div>
</main>

<?php
get_footer();

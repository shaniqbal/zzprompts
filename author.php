<?php
/**
 * The template for displaying Author Archive pages
 *
 * Supports two views:
 * - Default: Blog posts (existing V1 archive layout)
 * - Prompts: V2 prompt archive layout when ?post_type=prompt
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

$requested_post_type = get_query_var('post_type');
if (empty($requested_post_type) && isset($_GET['post_type'])) {
    $requested_post_type = sanitize_key(wp_unslash($_GET['post_type']));
}

$is_prompt_view = ($requested_post_type === 'prompt');
$author = get_queried_object();
$author_id = isset($author->ID) ? (int) $author->ID : 0;

if ($is_prompt_view && $author_id) :

    ?>

    <main id="primary" class="site-main">
        <div class="container section-padding">
            <header class="archive-hero text-center">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: author name */
                    printf(esc_html__('Prompts by %s', 'zzprompts'), esc_html(get_the_author_meta('display_name', $author_id)));
                    ?>
                </h1>
            </header>

            <div class="content-sidebar-wrap sidebar-left">
                <?php get_template_part('template-parts/sidebar', 'prompts'); ?>

                <div class="main-content">
                    <?php if (have_posts()) : ?>
                        <div class="zz-prompts-grid">
                            <?php while (have_posts()) : the_post(); ?>
                                <?php
                                $categories = get_the_terms(get_the_ID(), 'prompt_category');
                                $tools = get_the_terms(get_the_ID(), 'ai_tool');
                                ?>

                                <article class="zz-archive-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="zz-archive-card-image">
                                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                                        </div>
                                    <?php else : ?>
                                        <div class="zz-archive-card-image"></div>
                                    <?php endif; ?>

                                    <div class="zz-archive-card-content">
                                        <h3 class="zz-archive-card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>

                                        <div class="zz-archive-card-desc">
                                            <?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 18)); ?>
                                        </div>

                                        <div class="zz-archive-card-footer">
                                            <div class="zz-archive-card-badges">
                                                <?php if ($tools && !is_wp_error($tools)) : ?>
                                                    <span class="zz-archive-badge zz-badge-blue"><?php echo esc_html($tools[0]->name); ?></span>
                                                <?php endif; ?>

                                                <?php if ($categories && !is_wp_error($categories)) : ?>
                                                    <span class="zz-archive-badge zz-badge-green"><?php echo esc_html($categories[0]->name); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <button class="zz-archive-copy-btn" data-prompt-id="<?php the_ID(); ?>">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                <?php esc_html_e('Copy', 'zzprompts'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <div class="navigation pagination">
                            <?php
                            $pag_args = array(
                                'prev_text' => '&larr;',
                                'next_text' => '&rarr;',
                                'add_args'  => array('post_type' => 'prompt'),
                            );

                            // Preserve filter query parameters in pagination links
                            $filter_params = array('post_type' => 'prompt');
                            foreach ( array( 'ai_tool', 'prompt_category', 's_prompt' ) as $param ) {
                                if ( ! empty( $_GET[ $param ] ) ) {
                                    $filter_params[ $param ] = wp_unslash( $_GET[ $param ] );
                                }
                            }
                            $pag_args['add_args'] = $filter_params;

                            echo wp_kses_post( paginate_links( $pag_args ) );
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="no-results text-center py-5">
                            <h2 class="text-xl font-bold mb-2"><?php esc_html_e('No Prompts Found', 'zzprompts'); ?></h2>
                            <p class="text-muted mb-4"><?php esc_html_e('This author has not submitted any prompts yet.', 'zzprompts'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php

else :

    // Default blog author archive = existing archive layout
    $sidebar_enabled = zzprompts_get_option('blog_sidebar_enabled', true);
    $layout_class = $sidebar_enabled ? 'content-sidebar-wrap' : 'single-centered-wrap';
    ?>

    <div class="container section-padding">
        <div class="<?php echo esc_attr($layout_class); ?>">
            <main id="primary" class="site-main">
                <header class="page-header mb-8 pb-8 border-b border-gray-100">
                    <?php
                    the_archive_title('<h1 class="page-title text-3xl font-bold mb-2 text-heading">', '</h1>');
                    the_archive_description('<div class="archive-description text-muted text-lg">', '</div>');
                    ?>
                </header>

                <?php if (have_posts()) : ?>
                    <div class="zz-archive-list">
                        <?php
                        while (have_posts()) :
                            the_post();
                            get_template_part('template-parts/content');
                        endwhile;
                        ?>
                    </div>

                    <div class="mt-8">
                        <?php
                        the_posts_pagination(array(
                            'prev_text' => '<span class="icon-arrow-left"></span>',
                            'next_text' => '<span class="icon-arrow-right"></span>',
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <div class="no-results">
                        <h2 class="text-xl font-bold"><?php esc_html_e('Nothing Found', 'zzprompts'); ?></h2>
                        <p class="text-muted"><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'zzprompts'); ?></p>
                    </div>
                <?php endif; ?>
            </main>

            <?php if ($sidebar_enabled) get_sidebar(); ?>
        </div>
    </div>

<?php
endif;

get_footer();

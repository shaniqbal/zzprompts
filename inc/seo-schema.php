<?php
/**
 * SEO & Schema Markup Generator
 * 
 * Plugin-Agnostic SEO Fallbacks:
 * - Meta description (if no SEO plugin)
 * - Canonical URLs (if no SEO plugin)
 * - JSON-LD Schema (CreativeWork for prompts, Article for blog)
 * - Open Graph basics (if no SEO plugin)
 * 
 * PHILOSOPHY: Theme provides clean HTML + fallbacks.
 * SEO plugins (Yoast/RankMath) take over when active.
 * 
 * @package zzprompts
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

/* ============================================================
   1. META DESCRIPTION FALLBACK
   ============================================================ */

/**
 * Output meta description if no SEO plugin is handling it
 */
function zzprompts_meta_description() {
    // Skip if popular SEO plugins are active
    if (
        defined('WPSEO_VERSION') ||           // Yoast
        defined('RANK_MATH_VERSION') ||       // RankMath
        defined('AIOSEO_VERSION') ||          // All in One SEO
        class_exists('The_SEO_Framework')     // SEO Framework
    ) {
        return;
    }
    
    $description = '';
    
    if (is_front_page()) {
        $description = get_bloginfo('description');
        if (empty($description)) {
            $description = sprintf(
                /* translators: %s: site name */
                esc_html__('Welcome to %s - Your source for curated AI prompts.', 'zzprompts'),
                get_bloginfo('name')
            );
        }
    } elseif (is_singular('prompt')) {
        $description = get_the_excerpt();
        if (empty($description)) {
            $description = wp_trim_words(get_the_content(), 25, '...');
        }
    } elseif (is_singular('post')) {
        $description = get_the_excerpt();
        if (empty($description)) {
            $description = wp_trim_words(get_the_content(), 25, '...');
        }
    } elseif (is_post_type_archive('prompt')) {
        $description = zzprompts_get_option('archive_seo_description', '');
        if (empty($description)) {
            $description = sprintf(
                /* translators: %s: site name */
                esc_html__('Browse our collection of curated AI prompts at %s.', 'zzprompts'),
                get_bloginfo('name')
            );
        }
    } elseif (is_tax('prompt_category') || is_tax('ai_tool')) {
        $term = get_queried_object();
        $description = $term->description;
        if (empty($description)) {
            $description = sprintf(
                /* translators: %s: term name */
                esc_html__('Explore AI prompts in %s category.', 'zzprompts'),
                $term->name
            );
        }
    } elseif (is_category()) {
        $term = get_queried_object();
        $description = $term->description;
        if (empty($description)) {
            $description = sprintf(
                /* translators: %s: category name */
                esc_html__('Articles about %s.', 'zzprompts'),
                $term->name
            );
        }
    }
    
    // Sanitize and trim to 160 chars
    if (!empty($description)) {
        $description = wp_strip_all_tags($description);
        $description = mb_substr($description, 0, 160);
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}
add_action('wp_head', 'zzprompts_meta_description', 1);


/* ============================================================
   2. CANONICAL URL FALLBACK
   ============================================================ */

/**
 * Output canonical URL if no SEO plugin is handling it
 */
function zzprompts_canonical_url() {
    // Skip if SEO plugins active
    if (
        defined('WPSEO_VERSION') ||
        defined('RANK_MATH_VERSION') ||
        defined('AIOSEO_VERSION') ||
        class_exists('The_SEO_Framework')
    ) {
        return;
    }
    
    $canonical = '';
    
    if (is_singular()) {
        $canonical = get_permalink();
    } elseif (is_front_page()) {
        $canonical = home_url('/');
    } elseif (is_post_type_archive('prompt')) {
        $canonical = get_post_type_archive_link('prompt');
    } elseif (is_tax() || is_category() || is_tag()) {
        $canonical = get_term_link(get_queried_object());
    } elseif (is_home()) {
        $canonical = get_permalink(get_option('page_for_posts'));
    }
    
    // For paginated archives, canonical to page 1
    if (is_paged() && !is_singular()) {
        if (is_post_type_archive('prompt')) {
            $canonical = get_post_type_archive_link('prompt');
        } elseif (is_tax() || is_category()) {
            $canonical = get_term_link(get_queried_object());
        }
    }
    
    if (!empty($canonical) && !is_wp_error($canonical)) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }
}
add_action('wp_head', 'zzprompts_canonical_url', 1);


/* ============================================================
   3. SEARCH RESULTS: NOINDEX
   ============================================================ */

/**
 * Add noindex to search results pages
 */
function zzprompts_search_noindex() {
    if (is_search()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
}
add_action('wp_head', 'zzprompts_search_noindex', 1);


/* ============================================================
   4. JSON-LD SCHEMA: PROMPT (CreativeWork)
   ============================================================ */

function zzprompts_output_prompt_schema() {
    if (!is_singular('prompt')) {
        return;
    }

    global $post;

    // Get meta data safely
    $likes = function_exists('zzprompts_get_likes') ? zzprompts_get_likes($post->ID) : 0;
    $copies = function_exists('zzprompts_get_copy_count') ? zzprompts_get_copy_count($post->ID) : 0;
    $author_name = get_the_author_meta('display_name', $post->post_author);
    
    // Get prompt text
    $prompt_text = get_post_meta($post->ID, '_zz_prompt_text', true);
    if (empty($prompt_text)) {
        $prompt_text = get_post_meta($post->ID, '_prompt_text', true);
    }
    if (empty($prompt_text)) {
        $prompt_text = wp_strip_all_tags(get_the_content());
    }
    
    // Get categories
    $categories = get_the_terms($post->ID, 'prompt_category');
    $keywords = array();
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $keywords[] = $cat->name;
        }
    }
    
    // Get AI Tool
    $ai_tools = get_the_terms($post->ID, 'ai_tool');
    $ai_tool = ($ai_tools && !is_wp_error($ai_tools)) ? $ai_tools[0]->name : '';

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => get_the_title(),
        'headline' => get_the_title(),
        'description' => wp_trim_words($prompt_text, 50, '...'),
        'text' => $prompt_text,
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/')
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        ),
        'interactionStatistic' => array(
            array(
                '@type' => 'InteractionCounter',
                'interactionType' => 'https://schema.org/LikeAction',
                'userInteractionCount' => (int) $likes
            ),
            array(
                '@type' => 'InteractionCounter',
                'interactionType' => 'https://schema.org/ShareAction',
                'userInteractionCount' => (int) $copies
            )
        )
    );
    
    // Add keywords if available
    if (!empty($keywords)) {
        $schema['keywords'] = implode(', ', $keywords);
    }
    
    // Add AI tool as about
    if (!empty($ai_tool)) {
        $schema['about'] = array(
            '@type' => 'SoftwareApplication',
            'name' => $ai_tool
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'zzprompts_output_prompt_schema', 10);


/* ============================================================
   5. JSON-LD SCHEMA: BLOG POST (Article)
   ============================================================ */

function zzprompts_output_blog_schema() {
    if (!is_singular('post')) {
        return;
    }

    global $post;
    
    $author_name = get_the_author_meta('display_name', $post->post_author);
    $excerpt = get_the_excerpt();
    if (empty($excerpt)) {
        $excerpt = wp_trim_words(get_the_content(), 25, '...');
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => wp_strip_all_tags($excerpt),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/')
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        )
    );
    
    // Add featured image if available
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image = wp_get_attachment_image_src($image_id, 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2]
            );
        }
    }
    
    // Add word count for articles
    $word_count = str_word_count(wp_strip_all_tags(get_the_content()));
    if ($word_count > 0) {
        $schema['wordCount'] = $word_count;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'zzprompts_output_blog_schema', 10);


/* ============================================================
   6. JSON-LD SCHEMA: BREADCRUMB
   ============================================================ */

function zzprompts_output_breadcrumb_schema() {
    // Only on single pages
    if (!is_singular()) {
        return;
    }
    
    $items = array();
    $position = 1;
    
    // Home
    $items[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => esc_html__('Home', 'zzprompts'),
        'item' => home_url('/')
    );
    
    if (is_singular('prompt')) {
        // Prompts archive
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => esc_html__('Prompts', 'zzprompts'),
            'item' => get_post_type_archive_link('prompt')
        );
        
        // Current prompt
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_the_title()
        );
    } elseif (is_singular('post')) {
        // Blog
        $blog_page = get_option('page_for_posts');
        if ($blog_page) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title($blog_page),
                'item' => get_permalink($blog_page)
            );
        }
        
        // Current post
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_the_title()
        );
    }
    
    if (count($items) > 1) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'zzprompts_output_breadcrumb_schema', 10);


/* ============================================================
   7. OPEN GRAPH FALLBACK (Basic)
   ============================================================ */

function zzprompts_open_graph_fallback() {
    // Skip if SEO plugins active
    if (
        defined('WPSEO_VERSION') ||
        defined('RANK_MATH_VERSION') ||
        defined('AIOSEO_VERSION') ||
        class_exists('The_SEO_Framework')
    ) {
        return;
    }
    
    $og_title = '';
    $og_description = '';
    $og_type = 'website';
    $og_url = '';
    $og_image = '';
    
    if (is_singular()) {
        $og_title = get_the_title();
        $og_description = get_the_excerpt();
        $og_type = is_singular('post') ? 'article' : 'website';
        $og_url = get_permalink();
        
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(null, 'large');
        }
    } elseif (is_front_page()) {
        $og_title = get_bloginfo('name');
        $og_description = get_bloginfo('description');
        $og_url = home_url('/');
    }
    
    if (!empty($og_title)) {
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        
        if (!empty($og_description)) {
            echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(wp_strip_all_tags($og_description), 25, '...')) . '">' . "\n";
        }
        
        if (!empty($og_image)) {
            echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'zzprompts_open_graph_fallback', 2);

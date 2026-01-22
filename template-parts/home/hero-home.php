<?php
/**
 * Homepage Content - Modern V1
 * 
 * Modern glass morphism homepage design.
 * Uses BEM naming convention (.zz-*).
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;
?>

<!-- =========================================
     HERO SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_hero_section', true)) : ?>
<section class="zz-hero">
    <div class="zz-container">
        
        <?php
        $hero_title = zzprompts_get_option('hero_title', __('Instant AI Prompts for ChatGPT, Midjourney & More', 'zzprompts'));
        $hero_subtitle = zzprompts_get_option('hero_subtitle', __('Copy & paste production-ready prompts to speed up your workflow.', 'zzprompts'));
        $search_placeholder = zzprompts_get_option('hero_search_placeholder', __('Search prompts...', 'zzprompts'));

        // Multi-keyword highlighting system
        $keywords = ['ChatGPT', 'Gemini', 'Grok', 'Midjourney', 'AI'];
        $temp_title = $hero_title;
        
        foreach ($keywords as $kw) {
            $temp_title = str_ireplace($kw, '<span>' . $kw . '</span>', $temp_title);
        }
        
        $highlighted_title = $temp_title;

        // Force a line break after "For" or "for"
        if (stripos($highlighted_title, 'for ') !== false) {
            $highlighted_title = preg_replace('/(for\s+)/i', '$1<br>', $highlighted_title, 1);
        }
        ?>
        
        <div class="zz-hero__glow"></div>
        
        <h1 class="zz-hero__title">
            <?php echo $highlighted_title; ?>
        </h1>
        
        <p class="zz-hero__subtitle">
            <span class="zz-hero__subtitle-inner"><?php echo esc_html($hero_subtitle); ?></span>
        </p>
        
        <!-- Hero Search -->
        <div class="zz-hero__search">
            <div class="zz-hero-search">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="hidden" name="post_type" value="prompt">
                    <svg class="zz-hero-search__icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input 
                        type="search" 
                        class="zz-hero-search__input" 
                        placeholder="<?php echo esc_attr($search_placeholder); ?>" 
                        name="s"
                    >
                </form>
            </div>
        </div>
        
        <!-- Category & AI Tool Pills -->
        <div class="zz-hero__pills zz-filter-pills">
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-filter-pill zz-filter-pill--active">
                <i class="fas fa-th-large"></i> <?php esc_html_e('All Prompts', 'zzprompts'); ?>
            </a>
            <?php
            // Get Top AI Tools
            $tools = get_terms(array(
                'taxonomy'   => 'ai_tool',
                'hide_empty' => true,
                'number'     => 3,
            ));
            
            if (!is_wp_error($tools) && !empty($tools)) {
                foreach ($tools as $tool) {
                    echo '<a href="' . esc_url(get_term_link($tool)) . '" class="zz-filter-pill"><i class="fas fa-robot"></i> ' . esc_html($tool->name) . '</a>';
                }
            }

            // Get Top Categories
            $categories = get_terms(array(
                'taxonomy'   => 'prompt_category',
                'hide_empty' => true,
                'number'     => 3,
            ));
            
            if (!is_wp_error($categories) && !empty($categories)) {
                foreach ($categories as $cat) {
                    echo '<a href="' . esc_url(get_term_link($cat)) . '" class="zz-filter-pill"><i class="fas fa-tag"></i> ' . esc_html($cat->name) . '</a>';
                }
            }
            ?>
        </div>
        
    </div>
</section>
<?php endif; ?>

<!-- =========================================
     PROMPTS GRID SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_home_prompts', true)) : ?>
<section class="zz-home-prompts">
    <div class="zz-container">
        
        <div class="zz-prompt-grid">
            <?php
            if ( ! post_type_exists('prompt') ) :
                ?>
                <div class="zz-notice">
                    <?php esc_html_e( 'Install and activate the Prompts Core plugin to enable demo content.', 'zzprompts' ); ?>
                </div>
                <?php
            else :
                $prompts_count = zzprompts_get_option('home_prompts_count', 8);
                $prompts = new WP_Query(array(
                    'post_type'      => 'prompt',
                    'posts_per_page' => $prompts_count,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));
                
                if ($prompts->have_posts()) :
                    while ($prompts->have_posts()) : $prompts->the_post();
                        
                        // Get AI Tool
                        $ai_tools = get_the_terms(get_the_ID(), 'ai_tool');
                        $ai_tool = $ai_tools && !is_wp_error($ai_tools) ? $ai_tools[0] : null;
                        $ai_tool_slug = $ai_tool ? sanitize_title($ai_tool->name) : 'chatgpt';
                        
                        // Get like count
                        $likes = get_post_meta(get_the_ID(), 'prompt_likes', true);
                        $likes = $likes ? intval($likes) : 0;
                        ?>
                        
                        <div class="zz-prompt-card">
                            <?php if ($ai_tool) : ?>
                                <span class="zz-prompt-card__badge zz-prompt-card__badge--<?php echo esc_attr($ai_tool_slug); ?>">
                                    <?php echo esc_html($ai_tool->name); ?>
                                </span>
                            <?php endif; ?>
                            
                            <h3 class="zz-prompt-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <p class="zz-prompt-card__excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                            </p>
                            
                            <div class="zz-prompt-card__footer">
                                <a href="<?php the_permalink(); ?>" class="zz-btn-copy">
                                    <i class="far fa-copy"></i>
                                    <?php echo esc_html(zzprompts_get_option('copy_btn_text', __('Copy Prompt', 'zzprompts'))); ?>
                                </a>
                                
                                <span class="zz-prompt-card__likes">
                                    <i class="fas fa-heart"></i>
                                    <?php echo esc_html($likes); ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p class="zz-text-muted"><?php esc_html_e('No prompts found.', 'zzprompts'); ?></p>
                    <?php
                endif;
            endif; // post_type_exists check
            ?>
        </div>
        
        <!-- Browse All Button -->
        <div class="zz-home-prompts__cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn-browse">
                <?php esc_html_e('Browse All Prompts', 'zzprompts'); ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
    </div>
</section>
<?php endif; ?>

<!-- =========================================
     HOW IT WORKS SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_home_how', true)) : ?>
<section class="zz-home-how zz-section">
    <div class="zz-container">
        
        <div class="zz-section-header">
            <h2 class="zz-section-title"><?php echo esc_html(zzprompts_get_option('home_how_title', __('How It Works', 'zzprompts'))); ?></h2>
            <p class="zz-section-subtitle"><?php echo esc_html(zzprompts_get_option('home_how_subtitle', __('Get started in three simple steps', 'zzprompts'))); ?></p>
        </div>
        
        <div class="zz-steps-grid">
            
            <div class="zz-step-card">
                <div class="zz-step-card__icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="zz-step-card__title"><?php esc_html_e('Browse & Search', 'zzprompts'); ?></h3>
                <p class="zz-step-card__desc"><?php esc_html_e('Explore our curated library of AI prompts. Filter by category, AI model, or use case.', 'zzprompts'); ?></p>
            </div>
            
            <div class="zz-step-card">
                <div class="zz-step-card__icon">
                    <i class="fas fa-copy"></i>
                </div>
                <h3 class="zz-step-card__title"><?php esc_html_e('Copy with One Click', 'zzprompts'); ?></h3>
                <p class="zz-step-card__desc"><?php esc_html_e('Found what you need? Copy the prompt instantly to your clipboard with a single click.', 'zzprompts'); ?></p>
            </div>
            
            <div class="zz-step-card">
                <div class="zz-step-card__icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3 class="zz-step-card__title"><?php esc_html_e('Use & Create', 'zzprompts'); ?></h3>
                <p class="zz-step-card__desc"><?php esc_html_e('Paste directly into ChatGPT, Midjourney, Claude, or any AI tool. Start creating instantly.', 'zzprompts'); ?></p>
            </div>
            
        </div>
        
    </div>
</section>
<?php endif; ?>

<!-- =========================================
     FEATURES SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_home_features', true)) : ?>
<section class="zz-home-features zz-section">
    <div class="zz-container">
        
        <div class="zz-features-grid">
            
            <!-- Content Side -->
            <div class="zz-home-features__content">
                <h2 class="zz-home-features__title"><?php echo esc_html(zzprompts_get_option('home_features_title', __('Why Choose Our Prompts?', 'zzprompts'))); ?></h2>
                
                <div class="zz-home-features__list">
                    
                    <div class="zz-home-features__item">
                        <div class="zz-home-features__check">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h4 class="zz-home-features__item-title"><?php esc_html_e('Expert Curated', 'zzprompts'); ?></h4>
                            <p class="zz-home-features__item-desc"><?php esc_html_e('Each prompt is tested and refined by AI professionals.', 'zzprompts'); ?></p>
                        </div>
                    </div>
                    
                    <div class="zz-home-features__item">
                        <div class="zz-home-features__check">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h4 class="zz-home-features__item-title"><?php esc_html_e('Always Updated', 'zzprompts'); ?></h4>
                            <p class="zz-home-features__item-desc"><?php esc_html_e('New prompts added weekly to keep up with AI evolution.', 'zzprompts'); ?></p>
                        </div>
                    </div>
                    
                    <div class="zz-home-features__item">
                        <div class="zz-home-features__check">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h4 class="zz-home-features__item-title"><?php esc_html_e('Multi-Platform', 'zzprompts'); ?></h4>
                            <p class="zz-home-features__item-desc"><?php esc_html_e('Works with ChatGPT, Claude, Gemini, Midjourney & more.', 'zzprompts'); ?></p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Stats Side -->
            <div class="zz-stats-grid">
                <?php
                // Get dynamic stats
                $counts = wp_count_posts('prompt');
                $prompt_count = isset($counts->publish) ? $counts->publish : 0;
                ?>
                
                <div class="zz-stat-card">
                    <div class="zz-stat-card__value"><?php echo number_format($prompt_count); ?>+</div>
                    <h3 class="zz-stat-card__title"><?php esc_html_e('AI Prompts', 'zzprompts'); ?></h3>
                    <p class="zz-stat-card__desc"><?php esc_html_e('Ready to use', 'zzprompts'); ?></p>
                </div>
                
                <div class="zz-stat-card">
                    <div class="zz-stat-card__icon">⚡</div>
                    <h3 class="zz-stat-card__title"><?php esc_html_e('One-Click Copy', 'zzprompts'); ?></h3>
                    <p class="zz-stat-card__desc"><?php esc_html_e('Instant productivity', 'zzprompts'); ?></p>
                </div>
                
                <div class="zz-stat-card">
                    <div class="zz-stat-card__icon">🎯</div>
                    <h3 class="zz-stat-card__title"><?php esc_html_e('10+ Categories', 'zzprompts'); ?></h3>
                    <p class="zz-stat-card__desc"><?php esc_html_e('Every use case', 'zzprompts'); ?></p>
                </div>
                
                <div class="zz-stat-card">
                    <div class="zz-stat-card__icon">🤖</div>
                    <h3 class="zz-stat-card__title"><?php esc_html_e('5+ AI Models', 'zzprompts'); ?></h3>
                    <p class="zz-stat-card__desc"><?php esc_html_e('Universal compatibility', 'zzprompts'); ?></p>
                </div>
                
            </div>
            
        </div>
        
    </div>
</section>
<?php endif; ?>

<!-- =========================================
     LATEST ARTICLES SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_home_blog', true)) : ?>
<section class="zz-home-blog zz-section">
    <div class="zz-container">
        
        <div class="zz-section-header">
            <h2 class="zz-section-title"><?php echo esc_html(zzprompts_get_option('home_blog_title', __('Latest Articles', 'zzprompts'))); ?></h2>
            <p class="zz-section-subtitle"><?php echo esc_html(zzprompts_get_option('home_blog_subtitle', __('Tips, tutorials, and AI insights', 'zzprompts'))); ?></p>
        </div>
        
        <div class="zz-blog-grid zz-blog-grid--home">
            <?php
            $show_image = zzprompts_get_option('blog_show_image', true);
            $posts = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 3, 
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));
            
            if ($posts->have_posts()) :
                while ($posts->have_posts()) : $posts->the_post();
                    $categories = get_the_category();
                    $card_class = 'zz-blog-card zz-blog-card--home' . (!$show_image ? ' zz-blog-card--no-image' : '');
                    ?>
                    
                    <article class="<?php echo esc_attr($card_class); ?>">
                        <div class="zz-blog-card__inner">
                            <?php if ($show_image) : ?>
                                <div class="zz-blog-card__image-wrapper">
                                    <a href="<?php the_permalink(); ?>" class="zz-blog-card__image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php else : 
                                            $cat_name = !empty($categories) ? $categories[0]->name : get_bloginfo('name');
                                            $colors = ['#EEF2FF', '#ECFDF5', '#F0F9FF', '#FEF2F2', '#F5F3FF'];
                                            $bg_color = $colors[abs(crc32($cat_name)) % count($colors)];
                                        ?>
                                            <div class="zz-blog-card__placeholder" style="background-color: <?php echo esc_attr($bg_color); ?>;">
                                                <span><?php echo esc_html($cat_name); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="zz-blog-card__content">
                                <div class="zz-blog-card__meta">
                                    <span class="zz-blog-card__meta-item zz-blog-card__meta-item--date">
                                        <i class="far fa-calendar-alt"></i> <?php echo esc_html(get_the_date('M d, Y')); ?>
                                    </span>
                                    <span class="zz-blog-card__meta-divider"></span>
                                    <span class="zz-blog-card__meta-item zz-blog-card__meta-item--read">
                                        <i class="far fa-clock"></i> <?php echo esc_html(zzprompts_reading_time()); ?>
                                    </span>
                                </div>
                                
                                <h3 class="zz-blog-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
 
                                <div class="zz-blog-card__excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </div>
                                
                                <div class="zz-blog-card__footer">
                                    <a href="<?php the_permalink(); ?>" class="zz-blog-card__read-link">
                                        <?php echo esc_html(zzprompts_get_option('blog_read_more_text', __('Read Article', 'zzprompts'))); ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        
        <div class="zz-home-blog__cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="zz-btn zz-btn--primary zz-btn--pill">
                <?php esc_html_e('View All Articles', 'zzprompts'); ?>
            </a>
        </div>
        
    </div>
</section>
<?php endif; ?>

<!-- =========================================
     BOTTOM CTA SECTION
     ========================================= -->
<?php if (zzprompts_get_option('show_home_cta', true)) : ?>
<section class="zz-home-cta">
    <div class="zz-container">
        
        <h2 class="zz-home-cta__title"><?php echo esc_html(zzprompts_get_option('home_cta_title', __('Ready to Supercharge Your AI Workflow?', 'zzprompts'))); ?></h2>
        <p class="zz-home-cta__desc"><?php echo esc_html(zzprompts_get_option('home_cta_subtitle', __('Join thousands of professionals using our curated prompts to save time and boost productivity.', 'zzprompts'))); ?></p>
        
        <div class="zz-home-cta__buttons">
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-btn zz-btn--primary zz-btn--pill">
                <?php esc_html_e('Browse Prompts', 'zzprompts'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>" class="zz-btn zz-btn--white zz-btn--pill">
                <?php esc_html_e('Learn More', 'zzprompts'); ?>
            </a>
        </div>
        
    </div>
</section>
<?php endif; ?>

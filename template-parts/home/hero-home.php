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
<section class="zz-hero">
    <div class="zz-container">
        
        <?php
        // Get Customizer settings
        $hero_title = zzprompts_get_option('hero_title', __('Instant AI Prompts for ChatGPT, Midjourney & More', 'zzprompts'));
        $hero_subtitle = zzprompts_get_option('hero_subtitle', __('Copy & paste production-ready prompts to speed up your workflow.', 'zzprompts'));
        $search_placeholder = zzprompts_get_option('hero_search_placeholder', __('Search prompts...', 'zzprompts'));
        ?>
        
        <h1 class="zz-hero__title">
            <?php echo esc_html($hero_title); ?>
        </h1>
        
        <p class="zz-hero__subtitle">
            <?php echo esc_html($hero_subtitle); ?>
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
        
        <!-- Category Pills -->
        <div class="zz-hero__pills zz-filter-pills">
            <a href="<?php echo esc_url(get_post_type_archive_link('prompt')); ?>" class="zz-filter-pill zz-filter-pill--active">
                <?php esc_html_e('All Prompts', 'zzprompts'); ?>
            </a>
            <?php
            $categories = get_terms(array(
                'taxonomy'   => 'prompt_category',
                'hide_empty' => true,
                'number'     => 5,
            ));
            
            if (!is_wp_error($categories) && !empty($categories)) {
                foreach ($categories as $cat) {
                    echo '<a href="' . esc_url(get_term_link($cat)) . '" class="zz-filter-pill">' . esc_html($cat->name) . '</a>';
                }
            }
            ?>
        </div>
        
    </div>
</section>

<!-- =========================================
     PROMPTS GRID SECTION
     ========================================= -->
<section class="zz-home-prompts">
    <div class="zz-container">
        
        <div class="zz-prompt-grid">
            <?php
            $prompts = new WP_Query(array(
                'post_type'      => 'prompt',
                'posts_per_page' => 8,
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
                                <?php esc_html_e('Copy Prompt', 'zzprompts'); ?>
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

<!-- =========================================
     HOW IT WORKS SECTION
     ========================================= -->
<section class="zz-home-how zz-section">
    <div class="zz-container">
        
        <div class="zz-section-header">
            <h2 class="zz-section-title"><?php esc_html_e('How It Works', 'zzprompts'); ?></h2>
            <p class="zz-section-subtitle"><?php esc_html_e('Get started in three simple steps', 'zzprompts'); ?></p>
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

<!-- =========================================
     FEATURES SECTION
     ========================================= -->
<section class="zz-home-features zz-section">
    <div class="zz-container">
        
        <div class="zz-features-grid">
            
            <!-- Content Side -->
            <div class="zz-home-features__content">
                <h2 class="zz-home-features__title"><?php esc_html_e('Why Choose Our Prompts?', 'zzprompts'); ?></h2>
                
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
                $prompt_count = wp_count_posts('prompt')->publish;
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

<!-- =========================================
     LATEST ARTICLES SECTION
     ========================================= -->
<section class="zz-home-blog zz-section">
    <div class="zz-container">
        
        <div class="zz-section-header">
            <h2 class="zz-section-title"><?php esc_html_e('Latest Articles', 'zzprompts'); ?></h2>
            <p class="zz-section-subtitle"><?php esc_html_e('Tips, tutorials, and AI insights', 'zzprompts'); ?></p>
        </div>
        
        <div class="zz-blog-grid">
            <?php
            $posts = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));
            
            if ($posts->have_posts()) :
                while ($posts->have_posts()) : $posts->the_post();
                    $categories = get_the_category();
                    ?>
                    
                    <div class="zz-blog-card">
                        <a href="<?php the_permalink(); ?>" class="zz-blog-card__image-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php the_title_attribute(); ?>" class="zz-blog-card__image">
                            <?php else : ?>
                                <div class="zz-blog-card__image zz-blog-card__image--placeholder"></div>
                            <?php endif; ?>
                        </a>
                        
                        <?php if ($categories) : ?>
                            <span class="zz-blog-card__category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        
                        <h3 class="zz-blog-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <a href="<?php the_permalink(); ?>" class="zz-blog-card__read">
                            <?php esc_html_e('Read More →', 'zzprompts'); ?>
                        </a>
                    </div>
                    
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        
        <div class="zz-home-prompts__cta">
            <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="zz-btn zz-btn--primary">
                <?php esc_html_e('View All Articles', 'zzprompts'); ?>
            </a>
        </div>
        
    </div>
</section>

<!-- =========================================
     BOTTOM CTA SECTION
     ========================================= -->
<section class="zz-home-cta">
    <div class="zz-container">
        
        <h2 class="zz-home-cta__title"><?php esc_html_e('Ready to Supercharge Your AI Workflow?', 'zzprompts'); ?></h2>
        <p class="zz-home-cta__desc"><?php esc_html_e('Join thousands of professionals using our curated prompts to save time and boost productivity.', 'zzprompts'); ?></p>
        
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

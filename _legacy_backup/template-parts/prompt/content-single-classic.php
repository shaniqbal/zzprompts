<?php
/**
 * Template part for displaying Classic Single Prompt
 * 
 * ThemeForest Approved - Clean, Structured, Trustworthy
 * Classic ≠ old, Classic = clean + structured + neutral
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Get Meta Data
$prompt_id    = get_the_ID();
$views        = zzprompts_get_post_views($prompt_id);
$copies       = zzprompts_get_copy_count($prompt_id);
$likes        = zzprompts_get_likes($prompt_id);
$tools        = get_the_terms($prompt_id, 'ai_tool');
$tool         = ($tools && !is_wp_error($tools)) ? $tools[0] : null;
$categories   = get_the_terms($prompt_id, 'prompt_category');
$category     = ($categories && !is_wp_error($categories)) ? $categories[0] : null;

// Get prompt text from meta field, fallback to post content
$prompt_text = get_post_meta($prompt_id, '_prompt_text', true);
$has_prompt_meta = !empty($prompt_text);
if (empty($prompt_text)) {
    $prompt_text = get_the_content();
    $prompt_text = wp_strip_all_tags($prompt_text);
    $prompt_text = trim($prompt_text);
}

// Get description content
$description_content = get_the_content();
$description_stripped = wp_strip_all_tags($description_content);
$description_stripped = trim($description_stripped);
$has_description = $has_prompt_meta || ($description_stripped !== $prompt_text && !empty($description_stripped));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('csp-article'); ?>>

    <!-- 1️⃣ Title Area -->
    <header class="csp-header">
        <h1 class="csp-title"><?php the_title(); ?></h1>
        
        <div class="csp-meta-row">
            <span class="csp-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <?php echo esc_html(zzprompts_format_number($views)); ?> <?php esc_html_e('views', 'zzprompts'); ?>
            </span>
            <span class="csp-meta-sep">•</span>
            <span class="csp-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                </svg>
                <span class="csp-copy-count" data-post-id="<?php the_ID(); ?>"><?php echo esc_html(zzprompts_format_number($copies)); ?></span> <?php esc_html_e('copies', 'zzprompts'); ?>
            </span>
            <?php if ($tool) : ?>
            <span class="csp-meta-sep">•</span>
            <a href="<?php echo esc_url(get_term_link($tool)); ?>" class="csp-meta-item csp-meta-link">
                <?php echo esc_html($tool->name); ?>
            </a>
            <?php endif; ?>
            <?php if ($category) : ?>
            <span class="csp-meta-sep">•</span>
            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="csp-meta-item csp-meta-link">
                <?php echo esc_html($category->name); ?>
            </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- 2️⃣ Example Output (Demo Image) -->
    <?php if (has_post_thumbnail()) : ?>
    <section class="csp-example-output">
        <h2 class="csp-section-label"><?php esc_html_e('Example Output', 'zzprompts'); ?></h2>
        <?php $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>
        <div class="csp-image-wrapper">
            <button type="button" class="csp-image-btn zz-lightbox-trigger" 
                    data-full-src="<?php echo esc_url($full_image_url); ?>"
                    aria-label="<?php esc_attr_e('Click to expand image', 'zzprompts'); ?>">
                <?php the_post_thumbnail('large', array(
                    'class'    => 'csp-image',
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                )); ?>
                <span class="csp-image-expand">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                    </svg>
                </span>
            </button>
        </div>
    </section>
    
    <!-- Lightbox Modal -->
    <div class="zz-lightbox" id="zz-lightbox" aria-hidden="true" role="dialog" aria-modal="true">
        <button class="zz-lightbox-close" aria-label="<?php esc_attr_e('Close image', 'zzprompts'); ?>">&times;</button>
        <img class="zz-lightbox-image" src="" alt="" loading="lazy">
    </div>
    <?php endif; ?>

    <!-- 3️⃣ AdSense Block (After Example Output) -->
    <?php
    $ad_before = zzprompts_get_option('ad_before_prompt');
    ?>
    <div class="csp-ad-block csp-ad-separated" role="region" aria-label="Advertisement">
        <div class="csp-ad-label">Advertisement</div>
        <div class="csp-ad-inner">
            <?php
            if ($ad_before) {
                echo $ad_before;
            } else {
                echo '<div class="csp-ad-demo">[Demo Ad Slot]</div>';
            }
            ?>
        </div>
    </div>

    <!-- 4️⃣ Prompt Box (Main Focus Area) -->
    <?php if ($prompt_text) : ?>
    <section class="csp-prompt-section">
        <div class="csp-prompt-box">
            <!-- Prompt Header -->
            <div class="csp-prompt-header">
                <span class="csp-prompt-label">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="4 17 10 11 4 5"/>
                        <line x1="12" y1="19" x2="20" y2="19"/>
                    </svg>
                    <?php esc_html_e('Prompt', 'zzprompts'); ?>
                </span>
            </div>
            
            <!-- Prompt Content with Fade for Long Prompts -->
            <div class="csp-prompt-content">
                <div class="csp-prompt-wrapper csp-prompt-restrict" id="csp-prompt-wrapper-<?php the_ID(); ?>">
                    <pre class="csp-prompt-text" id="csp-prompt-<?php the_ID(); ?>"><?php echo esc_html($prompt_text); ?></pre>
                    <div class="csp-prompt-fade"></div>
                </div>
                
                <button class="csp-expand-btn csp-expand-hidden" data-target="csp-prompt-wrapper-<?php the_ID(); ?>">
                    <span class="csp-expand-text"><?php esc_html_e('Show Full Prompt', 'zzprompts'); ?></span>
                    <span class="csp-collapse-text"><?php esc_html_e('Collapse', 'zzprompts'); ?></span>
                    <svg class="csp-expand-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
            </div>
            
            <!-- Prompt Actions (Inside Box) -->
            <div class="csp-prompt-actions">
                <!-- Copy Button (Primary) -->
                <button class="csp-btn csp-btn-copy zz-copy-btn" 
                        data-post-id="<?php the_ID(); ?>" 
                        data-prompt-id="<?php the_ID(); ?>"
                        data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
                        aria-label="<?php esc_attr_e('Copy prompt to clipboard', 'zzprompts'); ?>">
                    <svg class="csp-icon-copy" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                    </svg>
                    <svg class="csp-icon-check" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span class="csp-btn-text"><?php esc_html_e('Copy Prompt', 'zzprompts'); ?></span>
                </button>
                
                <!-- Like Button (Secondary) -->
                <?php if (zzprompts_get_option('enable_likes', true)) : ?>
                <button class="csp-btn csp-btn-like zz-like-btn" 
                        data-post-id="<?php the_ID(); ?>" 
                        aria-label="<?php esc_attr_e('Like this prompt', 'zzprompts'); ?>">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                    </svg>
                    <span class="like-count"><?php echo absint($likes); ?></span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 5️⃣ Optional Text Section (Description/Notes) -->
    <?php if ($has_description || has_excerpt()) : ?>
    <section class="csp-description">
        <h2 class="csp-section-title"><?php esc_html_e('About This Prompt', 'zzprompts'); ?></h2>
        <div class="csp-description-content">
            <?php 
            if ($has_description) {
                the_content();
            } elseif (has_excerpt()) {
                the_excerpt();
            }
            ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- How to Use Section (If exists) -->
    <?php
    $how_to_use = get_post_meta(get_the_ID(), '_prompt_how_to_use', true);
    if (!empty($how_to_use)) :
    ?>
    <section class="csp-how-to-use">
        <h2 class="csp-section-title"><?php esc_html_e('How to Use This Prompt', 'zzprompts'); ?></h2>
        <div class="csp-how-content">
            <?php echo wp_kses_post(wpautop($how_to_use)); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- 6️⃣ Second AdSense Block -->
    <?php
    $ad_after = zzprompts_get_option('ad_after_prompt');
    ?>
    <div class="csp-ad-block csp-ad-separated" role="region" aria-label="Advertisement">
        <div class="csp-ad-label">Advertisement</div>
        <div class="csp-ad-inner">
            <?php
            if ($ad_after) {
                echo $ad_after;
            } else {
                echo '<div class="csp-ad-demo">[Demo Ad Slot]</div>';
            }
            ?>
        </div>
    </div>

    <!-- 7️⃣ Share This Prompt (Same style as Blog V2) -->
    <section class="csp-share">
        <h2 class="csp-share-label"><?php esc_html_e('Share this prompt', 'zzprompts'); ?></h2>
        <div class="csp-share-buttons">
            <!-- X (Twitter) -->
            <a href="https://x.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
               target="_blank" rel="noopener noreferrer" class="csp-share-btn csp-share-x" 
               aria-label="<?php esc_attr_e('Share on X', 'zzprompts'); ?>">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
               target="_blank" rel="noopener noreferrer" class="csp-share-btn csp-share-facebook" 
               aria-label="<?php esc_attr_e('Share on Facebook', 'zzprompts'); ?>">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" 
               target="_blank" rel="noopener noreferrer" class="csp-share-btn csp-share-whatsapp" 
               aria-label="<?php esc_attr_e('Share on WhatsApp', 'zzprompts'); ?>">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
               target="_blank" rel="noopener noreferrer" class="csp-share-btn csp-share-linkedin" 
               aria-label="<?php esc_attr_e('Share on LinkedIn', 'zzprompts'); ?>">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <!-- Copy Link -->
            <button type="button" class="csp-share-btn csp-share-copy" 
                    onclick="navigator.clipboard.writeText('<?php echo esc_url(get_permalink()); ?>').then(function(){this.classList.add('copied');var btn=this;setTimeout(function(){btn.classList.remove('copied')},2000)}.bind(this))" 
                    aria-label="<?php esc_attr_e('Copy link', 'zzprompts'); ?>">
                <svg class="csp-link-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
                </svg>
                <svg class="csp-check-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </button>
        </div>
    </section>

    <!-- 8️⃣ Related Prompts (Simple Grid - No Copy/Like) -->
    <?php
    // Smart Related Prompts (Priority: Category -> AI Tool -> Latest)
    $related_query = zzprompts_get_smart_related_prompts(get_the_ID(), 3);

    if ($related_query->have_posts()) :
    ?>
    <section class="csp-related">
        <h2 class="csp-section-title"><?php esc_html_e('Related Prompts', 'zzprompts'); ?></h2>
        
        <div class="csp-related-grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); 
                $rel_tool = get_the_terms(get_the_ID(), 'ai_tool');
                $rel_cat  = get_the_terms(get_the_ID(), 'prompt_category');
            ?>
            <a href="<?php the_permalink(); ?>" class="csp-related-card">
                <h3 class="csp-related-title"><?php the_title(); ?></h3>
                <div class="csp-related-meta">
                    <?php if ($rel_cat && !is_wp_error($rel_cat)) : ?>
                    <span class="csp-related-cat"><?php echo esc_html($rel_cat[0]->name); ?></span>
                    <?php endif; ?>
                    <?php if ($rel_tool && !is_wp_error($rel_tool)) : ?>
                    <span class="csp-related-tool"><?php echo esc_html($rel_tool[0]->name); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php endif; ?>


    <!-- Next/Previous Prompt Navigation -->
    <?php
    // Get next and previous posts for custom post type 'prompt'
    // get_previous_post() and get_next_post() work within same post type automatically
    // First try within same category (true = same taxonomy term)
    $prev_post = get_previous_post(true, '', 'prompt_category');
    $next_post = get_next_post(true, '', 'prompt_category');
    
    // If no posts in same category, get from all prompts (false = any taxonomy)
    if (!$prev_post) {
        $prev_post = get_previous_post(false);
    }
    if (!$next_post) {
        $next_post = get_next_post(false);
    }
    
    // Only show navigation if at least one link exists
    if ($prev_post || $next_post) :
    ?>
    <nav class="csp-prompt-nav" aria-label="<?php esc_attr_e('Prompt Navigation', 'zzprompts'); ?>">
        <div class="csp-prompt-nav-inner">
            <?php if ($prev_post): ?>
                <a class="csp-prompt-nav-link prev" href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                    <div class="csp-prompt-nav-content">
                        <span class="csp-prompt-nav-label"><?php esc_html_e('Previous Prompt', 'zzprompts'); ?></span>
                        <span class="csp-prompt-nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                    </div>
                    <svg class="csp-prompt-nav-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            <?php else: ?>
                <div class="csp-prompt-nav-link prev disabled"></div>
            <?php endif; ?>
            
            <?php if ($next_post): ?>
                <a class="csp-prompt-nav-link next" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                    <svg class="csp-prompt-nav-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                    <div class="csp-prompt-nav-content">
                        <span class="csp-prompt-nav-label"><?php esc_html_e('Next Prompt', 'zzprompts'); ?></span>
                        <span class="csp-prompt-nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    </div>
                </a>
            <?php else: ?>
                <div class="csp-prompt-nav-link next disabled"></div>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>
</article>

<!-- Mobile Sticky Copy Button -->
<?php if ($prompt_text) : ?>
<div class="csp-mobile-sticky">
    <button class="csp-mobile-copy zz-copy-btn" 
            data-post-id="<?php echo esc_attr($prompt_id); ?>" 
            data-prompt-id="<?php echo esc_attr($prompt_id); ?>"
            data-prompt-text="<?php echo esc_attr($prompt_text); ?>"
            aria-label="<?php esc_attr_e('Copy prompt', 'zzprompts'); ?>">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
            <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
        </svg>
        <span><?php esc_html_e('Copy Prompt', 'zzprompts'); ?></span>
    </button>
</div>
<?php endif; ?>

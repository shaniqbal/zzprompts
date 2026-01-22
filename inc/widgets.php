<?php
/**
 * ZZ Custom Widgets - Modern V1
 * 
 * Fresh, clean widgets for zzprompts theme.
 * Matches Modern V1 design with BEM classes.
 *
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// ==========================================================================
// 1. ZZ: BRAND & SOCIAL WIDGET
// ==========================================================================
// Purpose: Footer/Sidebar - Logo, description, social icons
// ==========================================================================

class ZZ_Widget_Brand extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'zz_brand',
            '🏷️ ' . esc_html__('ZZ: Brand & Social', 'zzprompts'),
            array('description' => esc_html__('Display logo, site description, and social media icons.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $logo_url = !empty($instance['logo_url']) ? $instance['logo_url'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : get_bloginfo('description');
        $show_social = !empty($instance['show_social']) ? $instance['show_social'] : false;
        ?>
        <div class="zz-widget zz-widget--brand">
            <?php if ($logo_url) : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-widget__logo">
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
            </a>
            <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="zz-widget__site-name">
                <?php bloginfo('name'); ?>
            </a>
            <?php endif; ?>
            
            <?php if ($description) : ?>
            <p class="zz-widget__desc"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            
            <?php if ($show_social) : ?>
            <div class="zz-widget__socials">
                <?php
                $networks = array(
                    'facebook'  => 'facebook-f',
                    'twitter'   => 'x-twitter',
                    'instagram' => 'instagram',
                    'linkedin'  => 'linkedin-in',
                    'youtube'   => 'youtube',
                    'github'    => 'github',
                    'discord'   => 'discord',
                );
                foreach ($networks as $network => $icon) {
                    $url = zzprompts_get_option('social_' . $network, '');
                    if ($url) {
                        echo '<a href="' . esc_url($url) . '" class="zz-widget__social" target="_blank" rel="noopener" aria-label="' . esc_attr(ucfirst($network)) . '">';
                        echo '<i class="fa-brands fa-' . esc_attr($icon) . '"></i>';
                        echo '</a>';
                    }
                }
                ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $logo_url = isset($instance['logo_url']) ? $instance['logo_url'] : '';
        $description = isset($instance['description']) ? $instance['description'] : '';
        $show_social = isset($instance['show_social']) ? (bool) $instance['show_social'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('logo_url')); ?>"><?php esc_html_e('Logo URL:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('logo_url')); ?>" name="<?php echo esc_attr($this->get_field_name('logo_url')); ?>" type="text" value="<?php echo esc_url($logo_url); ?>">
            <small><?php esc_html_e('Leave empty to show site name.', 'zzprompts'); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'zzprompts'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_social')); ?>" name="<?php echo esc_attr($this->get_field_name('show_social')); ?>" <?php checked($show_social); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_social')); ?>"><?php esc_html_e('Show social icons (configured in Theme Settings)', 'zzprompts'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['logo_url'] = esc_url_raw($new_instance['logo_url']);
        $instance['description'] = sanitize_textarea_field($new_instance['description']);
        $instance['show_social'] = !empty($new_instance['show_social']);
        return $instance;
    }
}


// ==========================================================================
// 2. [REMOVED] ZZ: PROMPT SEARCH WIDGET
// ==========================================================================
// Note: Removed in v1.0.0 - Use WordPress default search or searchform.php
// ==========================================================================


// ==========================================================================
// 3. ZZ: POPULAR PROMPTS WIDGET
// ==========================================================================
// Purpose: Sidebar - Top prompts by likes/views
// ==========================================================================

class ZZ_Widget_Popular_Prompts extends WP_Widget {
    
    private $cache_key = 'zz_widget_popular_prompts';
    
    public function __construct() {
        parent::__construct(
            'zz_popular_prompts',
            '🔥 ' . esc_html__('ZZ: Popular Prompts', 'zzprompts'),
            array('description' => esc_html__('Display popular prompts by likes or views. Cached for performance.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Popular Prompts', 'zzprompts');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $order_by = !empty($instance['order_by']) ? $instance['order_by'] : 'likes';
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        // Cache key unique to settings
        $cache_key = $this->cache_key . '_' . md5($number . $order_by);
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            echo $cached;
        } else {
            $query_args = array(
                'post_type' => 'prompt',
                'posts_per_page' => $number,
                'post_status' => 'publish',
            );
            
            if ($order_by === 'views') {
                $query_args['meta_key'] = 'zzprompts_post_views';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'DESC';
            } else {
                $query_args['meta_key'] = '_prompt_likes';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'DESC';
            }
            
            $query = new WP_Query($query_args);
            
            ob_start();
            if ($query->have_posts()) :
            ?>
            <div class="zz-widget zz-widget--popular">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $likes = zzprompts_get_likes(get_the_ID());
                    $tools = get_the_terms(get_the_ID(), 'ai_tool');
                    $tool_name = ($tools && !is_wp_error($tools)) ? $tools[0]->name : '';
                ?>
                <a href="<?php the_permalink(); ?>" class="zz-widget__popular-item" data-post-id="<?php the_ID(); ?>">
                    <div class="zz-widget__popular-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="zz-widget__popular-info">
                        <h5 class="zz-widget__popular-title"><?php the_title(); ?></h5>
                        <span class="zz-widget__popular-meta">
                            <?php echo esc_html(zzprompts_format_number($likes)); ?> <?php esc_html_e('Likes', 'zzprompts'); ?>
                            <?php if ($tool_name) : ?> · <?php echo esc_html($tool_name); ?><?php endif; ?>
                        </span>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
            <?php
            endif;
            wp_reset_postdata();
            
            $output = ob_get_clean();
            echo $output;
            
            // Cache for 4 hours
            set_transient($cache_key, $output, 4 * HOUR_IN_SECONDS);
        }
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Popular Prompts', 'zzprompts');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $order_by = isset($instance['order_by']) ? $instance['order_by'] : 'likes';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number to show:', 'zzprompts'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" min="1" max="10" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order_by')); ?>"><?php esc_html_e('Order by:', 'zzprompts'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order_by')); ?>" name="<?php echo esc_attr($this->get_field_name('order_by')); ?>">
                <option value="likes" <?php selected($order_by, 'likes'); ?>><?php esc_html_e('Most Likes', 'zzprompts'); ?></option>
                <option value="views" <?php selected($order_by, 'views'); ?>><?php esc_html_e('Most Views', 'zzprompts'); ?></option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['order_by'] = sanitize_key($new_instance['order_by']);
        
        // Clear cache
        delete_transient($this->cache_key . '_' . md5($old_instance['number'] . $old_instance['order_by']));
        delete_transient($this->cache_key . '_' . md5($instance['number'] . $instance['order_by']));
        
        return $instance;
    }
}


// ==========================================================================
// 4. ZZ: CATEGORY TAGS WIDGET
// ==========================================================================
// Purpose: Sidebar/Footer - Taxonomy cloud (Categories, AI Tools)
// ==========================================================================

class ZZ_Widget_Category_Tags extends WP_Widget {
    
    private $cache_key = 'zz_widget_category_tags';
    
    public function __construct() {
        parent::__construct(
            'zz_category_tags',
            '🎯 ' . esc_html__('ZZ: Category Filter', 'zzprompts'),
            array('description' => esc_html__('Display prompt categories or AI tools as filter buttons.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Categories', 'zzprompts');
        $taxonomy = !empty($instance['taxonomy']) ? $instance['taxonomy'] : 'prompt_category';
        $limit = !empty($instance['limit']) ? absint($instance['limit']) : 10;
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $cache_key = $this->cache_key . '_' . md5($taxonomy . $limit);
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            echo $cached;
        } else {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'number' => $limit,
                'hide_empty' => true,
                'orderby' => 'count',
                'order' => 'DESC',
            ));
            
            ob_start();
            if (!empty($terms) && !is_wp_error($terms)) :
            ?>
            <div class="zz-widget zz-widget--tags">
                <?php foreach ($terms as $term) : ?>
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="zz-widget__tag">
                    <?php echo esc_html($term->name); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php
            endif;
            
            $output = ob_get_clean();
            echo $output;
            
            set_transient($cache_key, $output, 4 * HOUR_IN_SECONDS);
        }
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Categories', 'zzprompts');
        $taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : 'prompt_category';
        $limit = isset($instance['limit']) ? absint($instance['limit']) : 10;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('taxonomy')); ?>"><?php esc_html_e('Taxonomy:', 'zzprompts'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('taxonomy')); ?>" name="<?php echo esc_attr($this->get_field_name('taxonomy')); ?>">
                <option value="prompt_category" <?php selected($taxonomy, 'prompt_category'); ?>><?php esc_html_e('Prompt Categories', 'zzprompts'); ?></option>
                <option value="ai_tool" <?php selected($taxonomy, 'ai_tool'); ?>><?php esc_html_e('AI Tools', 'zzprompts'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php esc_html_e('Limit:', 'zzprompts'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" name="<?php echo esc_attr($this->get_field_name('limit')); ?>" type="number" min="1" max="30" value="<?php echo esc_attr($limit); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['taxonomy'] = sanitize_key($new_instance['taxonomy']);
        $instance['limit'] = absint($new_instance['limit']);
        
        // Clear cache
        delete_transient($this->cache_key . '_' . md5($old_instance['taxonomy'] . $old_instance['limit']));
        delete_transient($this->cache_key . '_' . md5($instance['taxonomy'] . $instance['limit']));
        
        return $instance;
    }
}


// ==========================================================================
// 5. ZZ: NEWSLETTER WIDGET
// ==========================================================================
// Purpose: Footer - Email signup form
// ==========================================================================

class ZZ_Widget_Newsletter extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'zz_newsletter',
            '📧 ' . esc_html__('ZZ: Newsletter', 'zzprompts'),
            array('description' => esc_html__('Email newsletter signup form. Connect to Mailchimp, MailerLite, etc.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Newsletter', 'zzprompts');
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $form_action = !empty($instance['form_action']) ? $instance['form_action'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : esc_html__('Subscribe', 'zzprompts');
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        ?>
        <div class="zz-widget zz-widget--newsletter">
            <?php if ($description) : ?>
            <p class="zz-widget__newsletter-desc"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            
            <form class="zz-widget__newsletter-form" action="<?php echo esc_url($form_action); ?>" method="post">
                <input type="email" 
                       class="zz-widget__newsletter-input" 
                       name="email" 
                       placeholder="<?php esc_attr_e('Enter your email', 'zzprompts'); ?>" 
                       required>
                <button type="submit" class="zz-widget__newsletter-btn">
                    <?php echo esc_html($button_text); ?>
                </button>
            </form>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Newsletter', 'zzprompts');
        $description = isset($instance['description']) ? $instance['description'] : '';
        $form_action = isset($instance['form_action']) ? $instance['form_action'] : '';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : esc_html__('Subscribe', 'zzprompts');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'zzprompts'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" rows="2"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('form_action')); ?>"><?php esc_html_e('Form Action URL (e.g., Mailchimp):', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('form_action')); ?>" name="<?php echo esc_attr($this->get_field_name('form_action')); ?>" type="url" value="<?php echo esc_url($form_action); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['description'] = sanitize_textarea_field($new_instance['description']);
        $instance['form_action'] = esc_url_raw($new_instance['form_action']);
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        return $instance;
    }
}


// ==========================================================================
// 6. ZZ: AD BANNER WIDGET
// ==========================================================================
// Purpose: Sidebar/Footer - Custom ad banner
// ==========================================================================

class ZZ_Widget_Ad_Banner extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'zz_ad_banner',
            '💰 ' . esc_html__('ZZ: Ad Banner', 'zzprompts'),
            array('description' => esc_html__('Display advertisements. Use global sidebar ad or custom HTML/JavaScript code.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        $use_global = !empty($instance['use_global']) ? $instance['use_global'] : false;
        $ad_code = '';
        
        // Use global sidebar ad from Ad Management page
        if ($use_global && function_exists('zz_get_ad')) {
            $ad_code = zz_get_ad('sidebar');
        }
        
        // Fallback to widget-specific code
        if (empty($ad_code)) {
            $ad_code = !empty($instance['ad_code']) ? $instance['ad_code'] : '';
        }
        
        $label = !empty($instance['show_label']) ? $instance['show_label'] : false;
        
        if (empty($ad_code)) return; // Don't render if no ad
        
        echo $args['before_widget'];
        ?>
        <div class="zz-widget zz-widget--ad">
            <?php if ($label) : ?>
            <span class="zz-widget__ad-label"><?php esc_html_e('Advertisement', 'zzprompts'); ?></span>
            <?php endif; ?>
            <div class="zz-widget__ad-content">
                <?php 
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $ad_code; 
                ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $use_global = isset($instance['use_global']) ? (bool) $instance['use_global'] : true;
        $ad_code = isset($instance['ad_code']) ? $instance['ad_code'] : '';
        $show_label = isset($instance['show_label']) ? (bool) $instance['show_label'] : true;
        ?>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('use_global')); ?>" name="<?php echo esc_attr($this->get_field_name('use_global')); ?>" <?php checked($use_global); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('use_global')); ?>">
                <strong><?php esc_html_e('Use Global Sidebar Ad', 'zzprompts'); ?></strong>
            </label>
            <br><small><?php esc_html_e('Uses ad from Appearance → Ad Management (recommended)', 'zzprompts'); ?></small>
        </p>
        <hr>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('ad_code')); ?>"><?php esc_html_e('Or Custom Ad Code (HTML/JavaScript):', 'zzprompts'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('ad_code')); ?>" name="<?php echo esc_attr($this->get_field_name('ad_code')); ?>" rows="6"><?php echo esc_textarea($ad_code); ?></textarea>
            <small><?php esc_html_e('Only used if "Use Global Sidebar Ad" is unchecked or global ad is empty.', 'zzprompts'); ?></small>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_label')); ?>" name="<?php echo esc_attr($this->get_field_name('show_label')); ?>" <?php checked($show_label); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_label')); ?>"><?php esc_html_e('Show "Advertisement" label', 'zzprompts'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['use_global'] = !empty($new_instance['use_global']);
        // Allow HTML/Scripts for ad code (admin only)
        $instance['ad_code'] = current_user_can('unfiltered_html') ? $new_instance['ad_code'] : wp_kses_post($new_instance['ad_code']);
        $instance['show_label'] = !empty($new_instance['show_label']);
        return $instance;
    }
}


// ==========================================================================
// 7. ZZ: AUTHOR BIO WIDGET
// ==========================================================================
// Purpose: Sidebar - Author/Editor profile for trust building
// ==========================================================================

class ZZ_Widget_Author_Bio extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'zz_author_bio',
            '👤 ' . esc_html__('ZZ: Author Box', 'zzprompts'),
            array('description' => esc_html__('Display author/editor profile card with avatar, bio, and social links.', 'zzprompts'))
        );
        
        // Enqueue media uploader on widgets page
        add_action('admin_enqueue_scripts', array($this, 'enqueue_media_uploader'));
    }
    
    /**
     * Enqueue media uploader scripts on widgets page
     */
    public function enqueue_media_uploader($hook) {
        if ('widgets.php' !== $hook && 'customize.php' !== $hook) {
            return;
        }
        wp_enqueue_media();
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $avatar_id = !empty($instance['avatar_id']) ? absint($instance['avatar_id']) : 0;
        $avatar_url = $avatar_id ? wp_get_attachment_image_url($avatar_id, 'thumbnail') : '';
        $name = !empty($instance['name']) ? $instance['name'] : '';
        $role = !empty($instance['role']) ? $instance['role'] : '';
        $bio = !empty($instance['bio']) ? $instance['bio'] : '';
        $show_social = !empty($instance['show_social']) ? $instance['show_social'] : false;
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        ?>
        <div class="zz-widget zz-widget--author">
            <?php if ($avatar_url) : ?>
            <div class="zz-widget__author-avatar">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($name); ?>">
            </div>
            <?php endif; ?>
            
            <div class="zz-widget__author-info">
                <?php if ($name) : ?>
                <h5 class="zz-widget__author-name"><?php echo esc_html($name); ?></h5>
                <?php endif; ?>
                
                <?php if ($role) : ?>
                <span class="zz-widget__author-role"><?php echo esc_html($role); ?></span>
                <?php endif; ?>
                
                <?php if ($bio) : ?>
                <p class="zz-widget__author-bio"><?php echo esc_html($bio); ?></p>
                <?php endif; ?>
                
                <?php if ($show_social) : ?>
                <div class="zz-widget__author-social">
                    <?php
                    $networks = array(
                        'facebook'  => 'facebook-f',
                        'twitter'   => 'x-twitter',
                        'instagram' => 'instagram',
                        'linkedin'  => 'linkedin-in',
                        'github'    => 'github',
                        'discord'   => 'discord',
                    );
                    foreach ($networks as $network => $icon) {
                        $url = zzprompts_get_option('social_' . $network, '');
                        if ($url) {
                            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener" aria-label="' . esc_attr(ucfirst($network)) . '">';
                            echo '<i class="fa-brands fa-' . esc_attr($icon) . '"></i>';
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $avatar_id = isset($instance['avatar_id']) ? absint($instance['avatar_id']) : 0;
        $avatar_url = $avatar_id ? wp_get_attachment_image_url($avatar_id, 'thumbnail') : '';
        $name = isset($instance['name']) ? $instance['name'] : '';
        $role = isset($instance['role']) ? $instance['role'] : '';
        $bio = isset($instance['bio']) ? $instance['bio'] : '';
        $show_social = isset($instance['show_social']) ? (bool) $instance['show_social'] : false;
        
        $unique_id = uniqid('zz_author_avatar_');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <!-- Media Library Avatar Picker -->
        <p>
            <label><?php esc_html_e('Avatar Image:', 'zzprompts'); ?></label>
            <br>
            <span class="zz-avatar-preview" id="<?php echo esc_attr($unique_id); ?>_preview" style="display: inline-block; margin: 5px 0;">
                <?php if ($avatar_url) : ?>
                    <img src="<?php echo esc_url($avatar_url); ?>" style="max-width: 80px; height: auto; border-radius: 50%;">
                <?php endif; ?>
            </span>
            <br>
            <input type="hidden" 
                   class="zz-avatar-id" 
                   id="<?php echo esc_attr($this->get_field_id('avatar_id')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('avatar_id')); ?>" 
                   value="<?php echo esc_attr($avatar_id); ?>">
            <button type="button" class="button zz-avatar-upload" data-preview="#<?php echo esc_attr($unique_id); ?>_preview" data-input="#<?php echo esc_attr($this->get_field_id('avatar_id')); ?>">
                <?php esc_html_e('Select Image', 'zzprompts'); ?>
            </button>
            <?php if ($avatar_id) : ?>
            <button type="button" class="button zz-avatar-remove" data-preview="#<?php echo esc_attr($unique_id); ?>_preview" data-input="#<?php echo esc_attr($this->get_field_id('avatar_id')); ?>">
                <?php esc_html_e('Remove', 'zzprompts'); ?>
            </button>
            <?php endif; ?>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('name')); ?>"><?php esc_html_e('Name:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('name')); ?>" name="<?php echo esc_attr($this->get_field_name('name')); ?>" type="text" value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('role')); ?>"><?php esc_html_e('Role/Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('role')); ?>" name="<?php echo esc_attr($this->get_field_name('role')); ?>" type="text" value="<?php echo esc_attr($role); ?>" placeholder="<?php esc_attr_e('e.g., Prompt Curator', 'zzprompts'); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('bio')); ?>"><?php esc_html_e('Short Bio:', 'zzprompts'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('bio')); ?>" name="<?php echo esc_attr($this->get_field_name('bio')); ?>" rows="3"><?php echo esc_textarea($bio); ?></textarea>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_social')); ?>" name="<?php echo esc_attr($this->get_field_name('show_social')); ?>" <?php checked($show_social); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_social')); ?>"><?php esc_html_e('Show social icons', 'zzprompts'); ?></label>
        </p>
        
        <script>
        jQuery(document).ready(function($) {
            // Only bind once per widget
            if (typeof window.zzAuthorAvatarBound === 'undefined') {
                window.zzAuthorAvatarBound = true;
                
                $(document).on('click', '.zz-avatar-upload', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var previewSelector = button.data('preview');
                    var inputSelector = button.data('input');
                    
                    var frame = wp.media({
                        title: '<?php echo esc_js(__('Select Avatar Image', 'zzprompts')); ?>',
                        button: { text: '<?php echo esc_js(__('Use this image', 'zzprompts')); ?>' },
                        multiple: false,
                        library: { type: 'image' }
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        var imgUrl = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                        $(previewSelector).html('<img src="' + imgUrl + '" style="max-width: 80px; height: auto; border-radius: 50%;">');
                        $(inputSelector).val(attachment.id).trigger('change');
                    });
                    
                    frame.open();
                });
                
                $(document).on('click', '.zz-avatar-remove', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var previewSelector = button.data('preview');
                    var inputSelector = button.data('input');
                    $(previewSelector).html('');
                    $(inputSelector).val('').trigger('change');
                });
            }
        });
        </script>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['avatar_id'] = absint($new_instance['avatar_id']);
        $instance['name'] = sanitize_text_field($new_instance['name']);
        $instance['role'] = sanitize_text_field($new_instance['role']);
        $instance['bio'] = sanitize_textarea_field($new_instance['bio']);
        $instance['show_social'] = !empty($new_instance['show_social']);
        return $instance;
    }
}


// ==========================================================================
// 8. ZZ: POPULAR POSTS WIDGET (Performance Optimized)
// ==========================================================================
// Purpose: Sidebar - Trending posts by views/comments
// ==========================================================================

class ZZ_Widget_Popular_Posts extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'zz_popular_posts',
            '📰 ' . esc_html__('ZZ: Popular Posts', 'zzprompts'),
            array('description' => esc_html__('Display trending blog posts by views. Multiple display styles available.', 'zzprompts'))
        );

        // Hook to clear transient cache on post update
        add_action('save_post', array($this, 'clear_transient'));
    }

    public function clear_transient() {
        delete_transient('zz_popular_posts_query');
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Trending Topics';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $style = !empty($instance['style']) ? $instance['style'] : 'style-4';
        $show_views = !empty($instance['show_views']) ? $instance['show_views'] : false;

        echo $args['before_widget'];

        // Premium Title Output
        if ($title) {
            echo $args['before_title'];
            echo '<i class="fa-solid fa-fire-flame-curved" style="color: #6366F1; margin-right: 8px;"></i> ' . esc_html($title);
            echo $args['after_title'];
        }

        // Performance Check: Use Transients
        $popular_posts = get_transient('zz_popular_posts_query');

        if (false === $popular_posts) {
            $query_args = array(
                'post_type'      => 'post',
                'posts_per_page' => $number,
                'no_found_rows'  => true,
                'ignore_sticky_posts' => true
            );

            // Smart Ordering Logic
            $meta_key = '';
            // 1. Theme Native Views
            if (metadata_exists('post', 0, 'zzprompts_post_views')) {
                $meta_key = 'zzprompts_post_views';
            } 
            // 2. Post Views Counter Plugin
            elseif (metadata_exists('post', 0, 'post_views_count')) {
                $meta_key = 'post_views_count';
            }
            // 3. Generic Views Key
            elseif (metadata_exists('post', 0, 'views')) {
                $meta_key = 'views';
            }

            if ($meta_key) {
                $query_args['meta_key'] = $meta_key;
                $query_args['orderby']  = 'meta_value_num';
            } else {
                $query_args['orderby'] = 'comment_count';
            }

            $popular_posts = new WP_Query($query_args);
            set_transient('zz_popular_posts_query', $popular_posts, 4 * HOUR_IN_SECONDS);
        }

        if ($popular_posts->have_posts()) :
            echo '<div class="zz-popular-list">'; // Neutral class
            
            $count = 1;
            while ($popular_posts->have_posts()) : $popular_posts->the_post();
                
                // Post Views Logic
                $views_output = '';
                if ($show_views) {
                    $views = get_post_meta(get_the_ID(), 'zzprompts_post_views', true);
                    if (!$views) $views = get_post_meta(get_the_ID(), 'post_views_count', true);
                    if (!$views) $views = get_post_meta(get_the_ID(), 'views', true);
                    $views = $views ? $views : 0;
                    
                    $formatted_views = function_exists('zzprompts_format_number') ? zzprompts_format_number($views) : $views;
                    $views_output = '<span class="s-views-pill"><i class="fa-regular fa-eye"></i> ' . $formatted_views . '</span>';
                }

                switch ($style) {
                    case 'style-1': // Numbered
                        ?>
                        <a href="<?php the_permalink(); ?>" class="style-1-item">
                            <span class="s1-index"><?php echo esc_html($count); ?></span>
                            <div class="s1-content">
                                <span class="s1-title"><?php the_title(); ?></span>
                                <?php echo $views_output; ?>
                            </div>
                        </a>
                        <?php
                        break;

                    case 'style-2': // Icons
                        ?>
                        <a href="<?php the_permalink(); ?>" class="style-2-item">
                            <span class="s2-icon"><i class="fa-solid fa-bolt"></i></span>
                            <div class="s2-content">
                                <span class="s2-title"><?php the_title(); ?></span>
                                <?php echo $views_output; ?>
                            </div>
                        </a>
                        <?php
                        break;

                    default: // Style 4: Glass Strips (Premium Default)
                        $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : 'https://placehold.co/40x40/6366F1/FFF?text=ZZ';
                        ?>
                        <a href="<?php the_permalink(); ?>" class="style-4-item">
                            <img src="<?php echo esc_url($thumb_url); ?>" class="s4-thumb" alt="<?php echo esc_attr(get_the_title()); ?>">
                            <div class="s4-content">
                                <h4 class="s4-title"><?php the_title(); ?></h4>
                                <?php echo $views_output; ?>
                            </div>
                        </a>
                        <?php
                        break;
                }
                
                $count++;
            endwhile;
            wp_reset_postdata();

            echo '</div>';
        endif;

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Trending Topics', 'zzprompts');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $style = isset($instance['style']) ? $instance['style'] : 'style-4';
        $show_views = isset($instance['show_views']) ? (bool) $instance['show_views'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts:', 'zzprompts'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Style Layout:', 'zzprompts'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="style-1" <?php selected($style, 'style-1'); ?>><?php esc_html_e('Style 1 (Numbered)', 'zzprompts'); ?></option>
                <option value="style-2" <?php selected($style, 'style-2'); ?>><?php esc_html_e('Style 2 (Icons)', 'zzprompts'); ?></option>
                <option value="style-4" <?php selected($style, 'style-4'); ?>><?php esc_html_e('Style 4 (Glass Strips)', 'zzprompts'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_views); ?> id="<?php echo esc_attr($this->get_field_id('show_views')); ?>" name="<?php echo esc_attr($this->get_field_name('show_views')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_views')); ?>"><?php esc_html_e('Show post views count', 'zzprompts'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['style'] = sanitize_text_field($new_instance['style']);
        $instance['show_views'] = !empty($new_instance['show_views']);
        
        // Clear transient on widget settings update
        delete_transient('zz_popular_posts_query');
        
        return $instance;
    }
}


// ==========================================================================
// 8. ZZ: FOOTER CONTACT INFO WIDGET
// ==========================================================================
// Purpose: Display email and location from Customizer settings
// ==========================================================================

class ZZ_Widget_Footer_Contact extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'zz_footer_contact',
            '📞 ' . esc_html__('ZZ: Contact Info', 'zzprompts'),
            array('description' => esc_html__('Display email and location from Theme Settings. Perfect for footer.', 'zzprompts'))
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Contact Us', 'zzprompts');
        $email = get_theme_mod('footer_email', '');
        $location = get_theme_mod('footer_location', '');
        
        // Don't display if no contact info
        if (empty($email) && empty($location)) {
            return;
        }
        
        echo $args['before_widget'];
        ?>
        <div class="zz-widget zz-widget--contact">
            <?php if ($title) : ?>
            <h4 class="zz-widget__title"><?php echo esc_html($title); ?></h4>
            <?php endif; ?>
            
            <ul class="zz-widget__contact-list">
                <?php if ($email) : ?>
                <li class="zz-widget__contact-item">
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                </li>
                <?php endif; ?>
                
                <?php if ($location) : ?>
                <li class="zz-widget__contact-item">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    <span><?php echo esc_html($location); ?></span>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Contact Us', 'zzprompts');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'zzprompts'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p class="description">
            <?php esc_html_e('Email and Location are configured in Customizer → ZZ Theme Options → Footer.', 'zzprompts'); ?>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}


// ==========================================================================
// REGISTER ALL WIDGETS
// ==========================================================================

function zzprompts_register_widgets() {
    register_widget('ZZ_Widget_Brand');
    register_widget('ZZ_Widget_Popular_Prompts');
    register_widget('ZZ_Widget_Category_Tags');
    register_widget('ZZ_Widget_Newsletter');
    register_widget('ZZ_Widget_Ad_Banner');
    register_widget('ZZ_Widget_Author_Bio');
    register_widget('ZZ_Widget_Popular_Posts');
    register_widget('ZZ_Widget_Footer_Contact');
}
add_action('widgets_init', 'zzprompts_register_widgets');

// ==========================================================================
// CLEAR ALL SIDEBAR CACHES ON WIDGET SAVE/UPDATE
// ==========================================================================

/**
 * Clear all ZZ widget transient caches when sidebars are updated.
 * This ensures fresh data when buyer makes ANY changes to widgets.
 */
function zz_clear_sidebar_caches() {
    global $wpdb;
    
    // Delete all ZZ widget transients
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_zz_widget_%' 
         OR option_name LIKE '_transient_timeout_zz_widget_%'"
    );
}
add_action('sidebar_admin_setup', 'zz_clear_sidebar_caches');
add_action('update_option_sidebars_widgets', 'zz_clear_sidebar_caches');

<?php
/**
 * The Sidebar containing the main widget area.
 * 
 * Enhanced with Search Widget
 * - On Blog Archive: AJAX instant search
 * - On Single Post: Normal form submit to search page
 *
 * @package zzprompts
 */

defined('ABSPATH') || exit;

// Get customizer settings for search
$search_enabled = (bool) intval(get_option('zzprompts_sidebar_search_enabled', 1));
$search_title = sanitize_text_field(get_option('zzprompts_sidebar_search_title', esc_html__('Search Articles', 'zzprompts')));

$is_sticky = zzprompts_get_option('sidebar_sticky_enabled', true);
$sticky_class = $is_sticky ? 'zz-sticky-sidebar' : '';

// Determine if we're on blog archive (for AJAX search) or single post (for normal search)
$is_blog_archive = is_home() || (is_archive() && !is_post_type_archive('prompt'));
?>

<aside id="secondary" class="widget-area zz-blog-sidebar <?php echo esc_attr($sticky_class); ?>" role="complementary">
	
	<!-- Search Widget -->
	<?php if ($search_enabled) : ?>
	<div class="zz-sidebar-widget zz-search-widget">
		<h3 class="zz-widget-title"><?php echo esc_html($search_title); ?></h3>
		
		<?php if ($is_blog_archive) : ?>
			<!-- AJAX Instant Search (Blog Archive) -->
			<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="zz-blog-search-form" id="zz-blog-search">
				<!-- Restrict search to blog posts only -->
				<input type="hidden" name="post_type" value="post">
				<input 
					type="search" 
					class="zz-blog-search-input" 
					placeholder="<?php esc_attr_e('Type to search...', 'zzprompts'); ?>" 
					value="<?php echo esc_attr(get_search_query()); ?>" 
					name="s"
					id="zz-blog-search-input"
					autocomplete="off"
				>
				<div class="zz-blog-search-results" id="zz-blog-search-results"></div>
			</form>
		<?php else : ?>
			<!-- Normal Search (Single Post - redirects to search page) -->
			<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="zz-blog-search-form">
				<!-- Restrict search to blog posts only -->
				<input type="hidden" name="post_type" value="post">
				<input 
					type="search" 
					class="zz-blog-search-input" 
					placeholder="<?php esc_attr_e('Type to search...', 'zzprompts'); ?>" 
					value="<?php echo esc_attr(get_search_query()); ?>" 
					name="s"
					autocomplete="off"
				>
				<button type="submit" class="zz-search-submit-btn">
					<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
				</button>
			</form>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	
	<?php
	// Sidebar Ad
	$sticky_ad = zzprompts_get_option('ad_sidebar_sticky');
	if ($sticky_ad) {
		echo '<div class="zz-ad-spot zz-ad-sidebar mb-4 text-center">' . $sticky_ad . '</div>';
	}
	?>
	
	<?php 
	// WordPress Widgets
	if (is_active_sidebar('sidebar-1')) {
		dynamic_sidebar('sidebar-1');
	}
	?>
</aside>

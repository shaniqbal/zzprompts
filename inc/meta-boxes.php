<?php
/**
 * Meta Boxes for Prompt Post Type
 * 
 * Handles custom fields for prompts (e.g. Prompt Text/Code).
 * Kept separate from Gutenberg content for clean architecture.
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Register Meta Box
 */
function zzprompts_add_prompt_meta_box() {
    add_meta_box(
        'zz_prompt_data_box',                 // ID
        esc_html__('Prompt Data', 'zzprompts'), // Title
        'zzprompts_render_prompt_meta_box',    // Callback
        'prompt',                             // Post Type
        'normal',                             // Context
        'high'                                // Priority
    );
}
add_action('add_meta_boxes', 'zzprompts_add_prompt_meta_box');

/**
 * Render Meta Box Content
 */
function zzprompts_render_prompt_meta_box($post) {
    // Add nonce for verification
    wp_nonce_field('zzprompts_save_prompt_meta', 'zzprompts_prompt_nonce');

    // Retrieve existing value
    $prompt_text = get_post_meta($post->ID, '_zz_prompt_text', true);
    ?>
    <div class="zz-meta-box-wrapper" style="margin-top: 10px;">
        <label for="zz_prompt_text" style="display:block; font-weight:600; margin-bottom: 8px;">
            <?php esc_html_e('Prompt Code / Text', 'zzprompts'); ?>
        </label>
        <p class="description" style="margin-bottom: 10px;">
            <?php esc_html_e('Paste the actual AI prompt here. This will be displayed in the Terminal/Code Box. Use the main editor above for the description/blog post.', 'zzprompts'); ?>
        </p>
        <textarea 
            id="zz_prompt_text" 
            name="zz_prompt_text" 
            rows="8" 
            style="width: 100%; font-family: monospace; background: #f0f0f1; padding: 10px; border: 1px solid #c3c4c7;"
            placeholder="<?php esc_attr_e('Enter your prompt here...', 'zzprompts'); ?>"
        ><?php echo esc_textarea($prompt_text); ?></textarea>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function zzprompts_save_prompt_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['zzprompts_prompt_nonce']) || !wp_verify_nonce($_POST['zzprompts_prompt_nonce'], 'zzprompts_save_prompt_meta')) {
        return;
    }

    // Autosave check
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Permission check
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save or Delete Data
    if (isset($_POST['zz_prompt_text'])) {
        $prompt_text = sanitize_textarea_field($_POST['zz_prompt_text']);
        update_post_meta($post_id, '_zz_prompt_text', $prompt_text);
    } else {
        delete_post_meta($post_id, '_zz_prompt_text');
    }
}
add_action('save_post', 'zzprompts_save_prompt_meta_box');

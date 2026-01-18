<?php
/**
 * Comments Template
 * 
 * Native WordPress comments with clean, minimal styling.
 * Fully Customizer controlled - optional feature.
 * 
 * @package zzprompts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Password protected posts - no comments
if (post_password_required()) {
    return;
}

// Get Customizer settings
$show_count = (bool) intval(get_option('zzprompts_comments_show_count', 1));
$show_avatars = (bool) intval(get_option('zzprompts_comments_show_avatars', 1));
$show_website = (bool) intval(get_option('zzprompts_comments_website_field', 0));

// Add body class for avatar control
$comments_class = 'zz-comments-section';
if (!$show_avatars) {
    $comments_class .= ' hide-avatars';
}
?>

<section id="comments" class="<?php echo esc_attr($comments_class); ?>">
    
    <?php if (have_comments()) : ?>
        
        <!-- Comments Header -->
        <header class="zz-comments-header">
            <h3 class="zz-comments-title">
                <?php esc_html_e('Discussion', 'zzprompts'); ?>
                <?php if ($show_count && get_comments_number() > 0) : ?>
                    <span class="zz-comments-count"><?php echo esc_html(get_comments_number()); ?></span>
                <?php endif; ?>
            </h3>
        </header>
        
        <!-- Comments List -->
        <ol class="zz-comments-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => $show_avatars ? 48 : 0,
                'callback'    => 'zzprompts_comment_callback',
                'max_depth'   => 2, // Max 2 levels of replies
            ));
            ?>
        </ol>
        
        <?php
        // Comments Navigation (if paginated)
        the_comments_navigation(array(
            'prev_text' => esc_html__('&larr; Older Comments', 'zzprompts'),
            'next_text' => esc_html__('Newer Comments &rarr;', 'zzprompts'),
        ));
        ?>
        
    <?php endif; ?>
    
    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="zz-comments-closed">
            <?php esc_html_e('Comments are closed.', 'zzprompts'); ?>
        </p>
    <?php endif; ?>
    
    <?php
    // Comment Form
    if (comments_open()) :
        
        // Build form fields
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $required_attr = ($req ? ' required' : '');
        $required_text = ($req ? ' *' : '');
        
        $fields = array(
            'author' => sprintf(
                '<div class="zz-comment-field zz-field-author">
                    <input id="author" name="author" type="text" value="%s" placeholder="%s%s"%s />
                </div>',
                esc_attr($commenter['comment_author']),
                esc_attr__('Your Name', 'zzprompts'),
                $required_text,
                $required_attr
            ),
            'email' => sprintf(
                '<div class="zz-comment-field zz-field-email">
                    <input id="email" name="email" type="email" value="%s" placeholder="%s%s"%s />
                </div>',
                esc_attr($commenter['comment_author_email']),
                esc_attr__('Email Address', 'zzprompts'),
                $required_text,
                $required_attr
            ),
        );
        
        // Website field - optional via Customizer
        if ($show_website) {
            $fields['url'] = sprintf(
                '<div class="zz-comment-field zz-field-url">
                    <input id="url" name="url" type="url" value="%s" placeholder="%s" />
                </div>',
                esc_attr($commenter['comment_author_url']),
                esc_attr__('Website (optional)', 'zzprompts')
            );
        }
        
        // Comment form args
        $comment_form_args = array(
            'class_form'           => 'zz-comment-form',
            'title_reply'          => esc_html__('Leave a Comment', 'zzprompts'),
            'title_reply_to'       => esc_html__('Reply to %s', 'zzprompts'),
            'title_reply_before'   => '<h3 id="reply-title" class="zz-comment-reply-title">',
            'title_reply_after'    => '</h3>',
            'cancel_reply_before'  => ' <small class="zz-cancel-reply">',
            'cancel_reply_after'   => '</small>',
            'cancel_reply_link'    => esc_html__('Cancel', 'zzprompts'),
            'label_submit'         => esc_html__('Post Comment', 'zzprompts'),
            'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
            'submit_field'         => '<div class="zz-form-submit">%1$s %2$s</div>',
            'comment_notes_before' => '<p class="zz-comment-notes">' . esc_html__('Your email address will not be published.', 'zzprompts') . '</p>',
            'comment_notes_after'  => '<p class="zz-moderation-note">' . esc_html__('Your comment may be held for moderation.', 'zzprompts') . '</p>',
            'fields'               => $fields,
            'comment_field'        => sprintf(
                '<div class="zz-comment-field zz-field-comment">
                    <textarea id="comment" name="comment" placeholder="%s" required></textarea>
                </div>',
                esc_attr__('Write your comment here...', 'zzprompts')
            ),
        );
        
        comment_form($comment_form_args);
        
    endif;
    ?>
    
</section>

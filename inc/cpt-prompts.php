<?php
defined('ABSPATH') || exit;

/**
 * Custom Post Type: Prompts
 * Register CPT and Taxonomies
 * 
 * @package zzprompts
 */

// Register Custom Post Type: Prompt
function zzprompts_register_prompt_cpt() {
    $labels = array(
        'name'                  => _x('Prompts', 'Post Type General Name', 'zzprompts'),
        'singular_name'         => _x('Prompt', 'Post Type Singular Name', 'zzprompts'),
        'menu_name'             => esc_html__('Prompts', 'zzprompts'),
        'name_admin_bar'        => esc_html__('Prompt', 'zzprompts'),
        'archives'              => esc_html__('Prompt Archives', 'zzprompts'),
        'attributes'            => esc_html__('Prompt Attributes', 'zzprompts'),
        'parent_item_colon'     => esc_html__('Parent Prompt:', 'zzprompts'),
        'all_items'             => esc_html__('All Prompts', 'zzprompts'),
        'add_new_item'          => esc_html__('Add New Prompt', 'zzprompts'),
        'add_new'               => esc_html__('Add New', 'zzprompts'),
        'new_item'              => esc_html__('New Prompt', 'zzprompts'),
        'edit_item'             => esc_html__('Edit Prompt', 'zzprompts'),
        'update_item'           => esc_html__('Update Prompt', 'zzprompts'),
        'view_item'             => esc_html__('View Prompt', 'zzprompts'),
        'view_items'            => esc_html__('View Prompts', 'zzprompts'),
        'search_items'          => esc_html__('Search Prompt', 'zzprompts'),
        'not_found'             => esc_html__('Not found', 'zzprompts'),
        'not_found_in_trash'    => esc_html__('Not found in Trash', 'zzprompts'),
        'featured_image'        => esc_html__('Featured Image', 'zzprompts'),
        'set_featured_image'    => esc_html__('Set featured image', 'zzprompts'),
        'remove_featured_image' => esc_html__('Remove featured image', 'zzprompts'),
        'use_featured_image'    => esc_html__('Use as featured image', 'zzprompts'),
        'insert_into_item'      => esc_html__('Insert into prompt', 'zzprompts'),
        'uploaded_to_this_item' => esc_html__('Uploaded to this prompt', 'zzprompts'),
        'items_list'            => esc_html__('Prompts list', 'zzprompts'),
        'items_list_navigation' => esc_html__('Prompts list navigation', 'zzprompts'),
        'filter_items_list'     => esc_html__('Filter prompts list', 'zzprompts'),
    );
    
    $args = array(
        'label'                 => esc_html__('Prompt', 'zzprompts'),
        'description'           => esc_html__('AI Prompts Library', 'zzprompts'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-chat',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'prompts',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'prompts'),
    );
    
    register_post_type('prompt', $args);
}
add_action('init', 'zzprompts_register_prompt_cpt', 0);

// Register Taxonomy: Prompt Category
function zzprompts_register_prompt_category() {
    $labels = array(
        'name'                       => _x('Prompt Categories', 'Taxonomy General Name', 'zzprompts'),
        'singular_name'              => _x('Prompt Category', 'Taxonomy Singular Name', 'zzprompts'),
        'menu_name'                  => esc_html__('Categories', 'zzprompts'),
        'all_items'                  => esc_html__('All Categories', 'zzprompts'),
        'parent_item'                => esc_html__('Parent Category', 'zzprompts'),
        'parent_item_colon'          => esc_html__('Parent Category:', 'zzprompts'),
        'new_item_name'              => esc_html__('New Category Name', 'zzprompts'),
        'add_new_item'               => esc_html__('Add New Category', 'zzprompts'),
        'edit_item'                  => esc_html__('Edit Category', 'zzprompts'),
        'update_item'                => esc_html__('Update Category', 'zzprompts'),
        'view_item'                  => esc_html__('View Category', 'zzprompts'),
        'separate_items_with_commas' => esc_html__('Separate categories with commas', 'zzprompts'),
        'add_or_remove_items'        => esc_html__('Add or remove categories', 'zzprompts'),
        'choose_from_most_used'      => esc_html__('Choose from the most used', 'zzprompts'),
        'popular_items'              => esc_html__('Popular Categories', 'zzprompts'),
        'search_items'               => esc_html__('Search Categories', 'zzprompts'),
        'not_found'                  => esc_html__('Not Found', 'zzprompts'),
        'no_terms'                   => esc_html__('No categories', 'zzprompts'),
        'items_list'                 => esc_html__('Categories list', 'zzprompts'),
        'items_list_navigation'      => esc_html__('Categories list navigation', 'zzprompts'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'prompt-category'),
    );
    
    register_taxonomy('prompt_category', array('prompt'), $args);
}
add_action('init', 'zzprompts_register_prompt_category', 0);

// Register Taxonomy: AI Tool
function zzprompts_register_ai_tool() {
    $labels = array(
        'name'                       => _x('AI Tools', 'Taxonomy General Name', 'zzprompts'),
        'singular_name'              => _x('AI Tool', 'Taxonomy Singular Name', 'zzprompts'),
        'menu_name'                  => esc_html__('AI Tools', 'zzprompts'),
        'all_items'                  => esc_html__('All AI Tools', 'zzprompts'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => esc_html__('New AI Tool Name', 'zzprompts'),
        'add_new_item'               => esc_html__('Add New AI Tool', 'zzprompts'),
        'edit_item'                  => esc_html__('Edit AI Tool', 'zzprompts'),
        'update_item'                => esc_html__('Update AI Tool', 'zzprompts'),
        'view_item'                  => esc_html__('View AI Tool', 'zzprompts'),
        'separate_items_with_commas' => esc_html__('Separate AI tools with commas', 'zzprompts'),
        'add_or_remove_items'        => esc_html__('Add or remove AI tools', 'zzprompts'),
        'choose_from_most_used'      => esc_html__('Choose from the most used', 'zzprompts'),
        'popular_items'              => esc_html__('Popular AI Tools', 'zzprompts'),
        'search_items'               => esc_html__('Search AI Tools', 'zzprompts'),
        'not_found'                  => esc_html__('Not Found', 'zzprompts'),
        'no_terms'                   => esc_html__('No AI Tools', 'zzprompts'),
        'items_list'                 => esc_html__('AI Tools list', 'zzprompts'),
        'items_list_navigation'      => esc_html__('AI Tools list navigation', 'zzprompts'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'ai-tool'),
    );
    
    register_taxonomy('ai_tool', array('prompt'), $args);
}
add_action('init', 'zzprompts_register_ai_tool', 0);

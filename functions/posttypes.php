<?php

function sgcrackit_quizes_init() {
    if( post_type_exists('quiz') )
    {
        return;
    }
    
    $labels = array(
        'name' => 'Quizes',
        'singular_name' => 'Quiz',
        'add_new' => 'New Quiz',
        'all_items' => 'All Quizes',
        'add_new_item' => 'Add Quiz',
        'edit_item' => 'Edit Quiz',
        'new_item' => 'New Quiz',
        'view_item' => 'View Quiz',
        'search_item' => 'Seach for Quiz',
        'not_fount' => 'No Quiz found',
        'not_found_in_trash' => 'No quiz found in trash',
        'parent_item_colon' => 'Parent Quiz'
    );
    
    $args = array(
        'label' => 'Quizes',
        'description' => 'All quizes',
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'quiz'),
        'query_var' => true,
        'show_in_menu' => true,
        'show_in_nav_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 4,
        'can_export' => true,
        'exclude_from_search' => false,
        'plublicly_queryable' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'author',
            'page-attributes',)
    );
    
    register_post_type('quiz', $args);
}

add_action('init', 'sgcrackit_quizes_init', 0);

function sgcrackit_custom_taxonomies() {
    
    if(taxonomy_exists('level')){
        return;
    }
    
    $labels = array(
        'name' => 'Levels',
        'singular_name' => 'Level',
        'search_items' => 'Search Levels',
        'all_items' => 'All Levels',
		'parent_item' => 'Parent Level',
		'parent_item_colon' => 'Parent Level:',
		'edit_item' => 'Edit Level',
		'update_item' => 'Update Level',
		'add_new_item' => 'Add New Level',
		'new_item_name' => 'New Level Name',
		'menu_name' => 'Levels'
    );
    
    $args = array(
        'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'level' )
    );
    
    register_taxonomy('level', array('quiz'), $args);
    
    if (taxonomy_exists('language')){
        return;
    }
    
    $labels = array(
        'name' => 'Languages',
        'singular_name' => 'Language',
        'search_items' => 'Search Languages',
        'all_items' => 'All Languages',
		'parent_item' => 'Parent Language',
		'parent_item_colon' => 'Parent Language:',
		'edit_item' => 'Edit Language',
		'update_item' => 'Update Language',
		'add_new_item' => 'Add New Language',
		'new_item_name' => 'New Language Name',
		'menu_name' => 'Languages'
    );
    
    $args = array(
        'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'language' )
    );
    
    register_taxonomy('language', array('quiz'), $args);
}

add_action('init', 'sgcrackit_custom_taxonomies');

function sgcrackit_rewrite_flush() {
    sgcrackit_quizes_init();
    sgcrackit_custom_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'sgcrackit_rewrite_flush' );



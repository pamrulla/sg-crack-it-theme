<?php

function sgcrackit_setup() {
	add_editor_style('theme/css/editor-style.css');
	add_theme_support('post-thumbnails');
	update_option('thumbnail_size_w', 170);
	update_option('medium_size_w', 470);
	update_option('large_size_w', 970);
    
    // change the Sample page to the home page
    if (is_admin()){
        $home_page_title = 'Home';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'home'
        );
        if(!the_slug_exists('home')){
            $home_page_id = wp_insert_post($home_page);
        }
    }

    // change the Sample page to the home page
    if (is_admin()){
        $home_page_title = 'All Languages';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'all-languages',
            'meta_input' => array(
                '_wp_page_template' => 'page-all-languages.php'
            )
        );
        if(!the_slug_exists('all-languages')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Quiz App';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'quiz-app',
            'meta_input' => array(
                '_wp_page_template' => 'page-quiz-app.php'
            )
        );
        if(!the_slug_exists('quiz-app')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Dashboard';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'dashboard',
            'meta_input' => array(
                '_wp_page_template' => 'page-dashboard.php'
            )
        );
        if(!the_slug_exists('dashboard')){
            $home_page_id = wp_insert_post($home_page);
        }
    }

    $homepage = get_page_by_title( 'Home' );

    if ( $homepage )
    {
        update_option( 'page_on_front', $homepage->ID );
        update_option( 'show_on_front', 'page' );
    }

}
add_action('init', 'sgcrackit_setup');

if (! isset($content_width))
	$content_width = 600;

function sgcrackit_excerpt_readmore() {
	return '&nbsp; <a href="'. get_permalink() . '">' . '&hellip; ' . __('Read more', 'sgcrackit') . ' <i class="fa fa-arrow-right"></i>' . '</a></p>';
}
add_filter('excerpt_more', 'sgcrackit_excerpt_readmore');

// Add post formats support. See http://codex.wordpress.org/Post_Formats
add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

// setup a function to check if these pages exist
function the_slug_exists($post_name) {
	global $wpdb;
	if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
		return true;
	} else {
		return false;
	}
}


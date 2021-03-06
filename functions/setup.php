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
    
    if (is_admin()){
        $home_page_title = 'Membership Plans';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'membership-plans',
            'meta_input' => array(
                '_wp_page_template' => 'page-memberplan.php'
            )
        );
        if(!the_slug_exists('membership-plans')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Checkout';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'checkout',
            'meta_input' => array(
                '_wp_page_template' => 'page-checkout.php'
            )
        );
        if(!the_slug_exists('checkout')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'LogIn';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'login',
            'meta_input' => array(
                '_wp_page_template' => 'page-login.php'
            )
        );
        if(!the_slug_exists('login')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Register';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'register',
            'meta_input' => array(
                '_wp_page_template' => 'page-register.php'
            )
        );
        if(!the_slug_exists('register')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Lost Password';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'lost-password',
            'meta_input' => array(
                '_wp_page_template' => 'page-lost-password.php'
            )
        );
        if(!the_slug_exists('lost-password')){
            $home_page_id = wp_insert_post($home_page);
        }
    }
    
    if (is_admin()){
        $home_page_title = 'Logout';
        $home_page_content = '';
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_content' => $home_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_slug' => 'logout',
            'meta_input' => array(
                '_wp_page_template' => 'page-logout.php'
            )
        );
        if(!the_slug_exists('logout')){
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

// Login redirects

function custom_login() {
	echo header("Location: " . get_bloginfo( 'url' ) . "/login");
}

add_action('login_head', 'custom_login');

function login_link_url( $url ) {
   $url = get_bloginfo( 'url' ) . "/login";
   return $url;
   }
add_filter( 'login_url', 'login_link_url', 10, 2 );

function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'remove_admin_bar');

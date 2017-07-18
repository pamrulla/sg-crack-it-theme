<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php wp_title('.', true, 'right'); bloginfo('name'); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <?php
                        $locations = get_nav_menu_locations();;
                        $menu = get_term( $locations['primary'], 'nav_menu' );
                        $menu_items = wp_get_nav_menu_items($menu->term_id);
                        echo '<ul class="navbar-nav mr-auto mt-2 mt-lg-0">';
                        foreach( $menu_items as $menu_item ) {
                            if(get_permalink() == $menu_item->url){
                                echo '<li class="nav-item active">';
                            }
                            else {
                                echo '<li class="nav-item">';
                            }
                            echo '<a class="nav-link" href="'.$menu_item->url.'">'.$menu_item->title.'</a>';
                            echo '</li>';
                        }
                        echo '</ul>';
                          /*wp_nav_menu( array(
                              'menu'              => 'Navbar',
                            'theme_location'		=> 'Navbar',
                            'container'         => 'false',
                            'menu_class'				=> '',
                            'fallback_cb'				=> false,
                            'items_wrap'				=> '<ul id="%1$s" class="navbar-nav mr-auto mt-2 mt-lg-0 %2$s">%3$s</ul>',
                            'depth'							=> 2,
                            'walker'            => new sgcrackit_walker_nav_menu()
                          ) );*/
                    ?>
              </div>
        </nav>

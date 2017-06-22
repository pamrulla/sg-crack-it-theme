<?php get_header(); ?>

    <?php
        if( is_front_page()) {
            $sgcratckit_classes = array('container');
        }
        else {
            $sgcratckit_classes = array('card');
        }
    ?>

    <?php if( have_posts() ) {
        
        while( have_posts() ) {
            the_post(); ?>
            <div <?php body_class($sgcratckit_classes); ?>>
                <h3><?php the_title(); ?></h3>
                <small>Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(); ?></small>
                <p><?php the_content(); ?></p>
            </div>
            <hr>
        <?php }
    
    }
    ?>

<?php get_footer(); ?>

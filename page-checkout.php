<?php

/*
    Template Name: Page Checkout
    
*/ ?>
<?php
    if(!is_user_logged_in()) { ?>
     <script>location.href='<?php echo get_permalink(get_page_by_path('log-in')->ID); ?>';</script>   
<?php    } ?>
<?php get_header(); ?>

<div class="container">
  <div class="row">
    
    <div class="col-sm-12">
      <div id="content" role="main">
        <?php get_template_part('loops/content', 'checkout'); ?>
      </div><!-- /#content -->
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>

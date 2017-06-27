<?php 

/*

    Template Name: page-all-languages

*/
?>
<?php get_header(); ?>

<div class="container">
  <div class="row">
    
    <div class="col-sm-12">
      <div id="content" role="main">
        <?php get_template_part('loops/content', 'all-languages'); ?>
      </div><!-- /#content -->
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>

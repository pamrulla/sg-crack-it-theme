<?php

/*
    Template Name: Page Quiz App
*/ ?>
<?php get_header(); ?>

<div class="container">
  <div class="row">
    
    <div class="col-sm-12">
      <div id="content" role="main">
        <?php get_template_part('quizapp/content', 'quiz-app'); ?>
      </div><!-- /#content -->
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>

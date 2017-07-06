<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var quizId = '<?php echo $_GET['id']; ?>';
    var title = '<?php echo get_post($_GET['id'])->post_title; ?>';
    var level = '<?php echo wp_get_post_terms($_GET['id'], 'level')[0]->name; ?>';
    var language = '<?php echo wp_get_post_terms($_GET['id'], 'language')[0]->name; ?>';
    var userId = '<?php echo get_current_user_id(); ?>';
    var isResume = '<?php echo $_GET['isResume']; ?>';
</script>

<br/>
<div class="card">
    <div class="card-header text-center">
        <h4 id="quiz-title"></h4>
    </div>
    <div class="card-block">
        <div class="alert alert-danger text-center" role="alert" id="no-answer">
            Ooops...! forgot to select an answer.
        </div>
        <div id="quiz-content">
        </div>
    </div>
    <div class="card-footer text-center">
        <small class="text-muted">This quiz is presented by <a href="www.smartgnan.com" target="_blank">www.smartgnan.com</a></small>
    </div>
</div>

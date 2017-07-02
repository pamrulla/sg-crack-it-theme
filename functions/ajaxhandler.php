<?php

function sgcrackit_ajax_get_quiz_questions() {
    
    wp_send_json_success( getQuizQuestions($_POST['level'], $_POST['language']) );
    //die();
}

add_action('wp_ajax_sgcrackit_ajax_get_quiz_questions', 'sgcrackit_ajax_get_quiz_questions');

function sgcrackit_ajax_update_quiz_progress() {
    global $questionsTable;
    $resVal = updateProgress($_POST['id'], $_POST['type'], $_POST['userId'], $_POST['answer'], $_POST['isCompleted']);
    wp_send_json_success($resVal);
}

add_action('wp_ajax_sgcrackit_ajax_update_quiz_progress', 'sgcrackit_ajax_update_quiz_progress');

function sgcrackit_ajax_admin_get_questions() {
    wp_send_json_success(getQuestionsList($_POST['level'], $_POST['language']));
}

add_action('wp_ajax_sgcrackit_ajax_admin_get_questions', 'sgcrackit_ajax_admin_get_questions');

function sgcrackit_ajax_admin_import_questions() {
    wp_send_json_success(insertImport($_POST['data'], $_POST['level'], $_POST['language']));
}

add_action('wp_ajax_sgcrackit_ajax_admin_import_questions', 'sgcrackit_ajax_admin_import_questions');
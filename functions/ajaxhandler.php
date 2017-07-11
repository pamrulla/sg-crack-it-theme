<?php

function sgcrackit_ajax_get_quiz_questions() {
    
    wp_send_json_success( getQuizQuestions($_POST['level'], $_POST['language'], $_POST['isResume'], $_POST['userId'], $_POST['quizId']) );
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

function sgcrackit_ajax_admin_get_pending_quiz() {
    wp_send_json_success(getPendingQuiz());
}

add_action('wp_ajax_sgcrackit_ajax_admin_get_pending_quiz', 'sgcrackit_ajax_admin_get_pending_quiz');

function sgcrackit_ajax_admin_get_quiz_for_validation() {
    wp_send_json_success(getQuizForValidation($_POST['quizId'], $_POST['prgId']));
}

add_action('wp_ajax_sgcrackit_ajax_admin_get_quiz_for_validation', 'sgcrackit_ajax_admin_get_quiz_for_validation');

function sgcrackit_ajax_admin_submit_quiz_score() {
    wp_send_json_success(submit_quiz_score($_POST['quizId'], $_POST['score'], $_POST['userId'], $_POST['report']));
}

add_action('wp_ajax_sgcrackit_ajax_admin_submit_quiz_score', 'sgcrackit_ajax_admin_submit_quiz_score');

function sgcrackit_ajax_admin_calculate_quiz_score() {
    $manualRightAnswers = $_POST['manualRightAnswers'];
    $manualWrongAnswers = $_POST['manualWrongAnswers'];
    $autoRightAnswers = $_POST['autoRightAnswers'];
    $autoWrongAnswers = $_POST['autoWrongAnswers'];
    $manualRating = $_POST['rating'];
    
    $totalManual = $manualRightAnswers + $manualWrongAnswers;
    $totalAuto = $autoRightAnswers + $autoWrongAnswers;
    
    $manualPercentage = $totalManual * 100 / ($totalManual + $totalAuto);
    $autoPercentage = 100 - $manualPercentage;
    
    $expectedAutoScore = $autoPercentage / 2;
    $expectedManualScore = 100 - $expectedAutoScore;
    
    $autoActual = $expectedAutoScore * $autoRightAnswers / $totalAuto;
    $manulActual = $expectedManualScore * $manualRating / (10 * $totalManual);
    
    return wp_send_json_success(ceil($autoActual + $manulActual));
}

add_action('wp_ajax_sgcrackit_ajax_admin_calculate_quiz_score', 'sgcrackit_ajax_admin_calculate_quiz_score');

function sgcrackit_ajax_dashboard_get_participated_quiz() {
    return wp_send_json_success(dashboard_get_participated_quiz($_POST['userId']));
}

add_action('wp_ajax_sgcrackit_ajax_dashboard_get_participated_quiz', 'sgcrackit_ajax_dashboard_get_participated_quiz');

function sgcrackit_ajax_dashboard_get_quiz_insights() {
    return wp_send_json_success(dashboard_get_quiz_insights($_POST['userId'], $_POST['language']));
}

add_action('wp_ajax_sgcrackit_ajax_dashboard_get_quiz_insights', 'sgcrackit_ajax_dashboard_get_quiz_insights');

function sgcrackit_ajax_checkout_process_payment() {
    return wp_send_json_success(checkout_process_payment($_POST['userId'], $_POST['memberplan'], $_POST['option'], $_POST['txnId'], $_POST['amount'], $_POST['status']));
}

add_action('wp_ajax_sgcrackit_ajax_checkout_process_payment', 'sgcrackit_ajax_checkout_process_payment');

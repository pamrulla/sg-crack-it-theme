<?php

function sgcrackit_ajax_get_quiz_questions() {
    $sampleData = '{"Title": "Test Quiz 1","ID": "1","Level": "Beginner","Questions": [{"ID": "1","Type": "MC","Question": "This is single choice question","Options": ["option1","option2"],"TimeLimit": "30"},{"ID": "1","Type": "MA","Question": "This is multiple select question","Options": ["choice1","choice2","choice3","choice4"],"TimeLimit": "30"},{"ID": "1","Type": "SORT","Question": "This is sorting question","Options": ["item1","item2","item3","item4","item5","item6","item7","item8"],"TimeLimit": "60"},{"ID": "1","Type": "DESC","Question": "This is descriptive question","TimeLimit": "2"}]}';
    wp_send_json_success( $sampleData );
    //die();
}

add_action('wp_ajax_nopriv_sgcrackit_ajax_get_quiz_questions', 'sgcrackit_ajax_get_quiz_questions');

function sgcrackit_ajax_update_quiz_progress() {
    wp_send_json_success($_POST['data']);
}

add_action('wp_ajax_nopriv_sgcrackit_ajax_update_quiz_progress', 'sgcrackit_ajax_update_quiz_progress');

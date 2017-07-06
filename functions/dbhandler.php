<?php

$questionsTable = $wpdb->prefix . "sgct_questions";
$progressTable = $wpdb->prefix . "sgct_progress";
$scoreTable = $wpdb->prefix . "sgct_score";

function sgcrackit_createTables() {
    global $wpdb;
    global $questionsTable;
    global $progressTable;
    global $scoreTable;
    
	$charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $questionsTable ( 
        id BIGINT NOT NULL AUTO_INCREMENT ,
        question LONGTEXT NOT NULL , 
        options MEDIUMTEXT NOT NULL , 
        answers MEDIUMTEXT NOT NULL , 
        type TINYTEXT NOT NULL , 
        level TINYTEXT NOT NULL , 
        language TINYTEXT NOT NULL ,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
    
    $sql1 = "CREATE TABLE $progressTable ( 
        id BIGINT NOT NULL AUTO_INCREMENT ,
        quizId BIGINT NOT NULL ,
        userId BIGINT NOT NULL ,
        answers LONGTEXT NOT NULL , 
        isCompleted TINYINT NOT NULL , 
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    dbDelta( $sql1 );
    
    $sql2 = "CREATE TABLE $scoreTable ( 
        id BIGINT NOT NULL AUTO_INCREMENT ,
        quizId BIGINT NOT NULL ,
        userId BIGINT NOT NULL ,
        score SMALLINT NOT NULL ,
        report MEDIUMTEXT NOT NULL ,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    dbDelta( $sql2 );
}

add_action('after_switch_theme', 'sgcrackit_createTables');

function updateProgress($id, $type, $userId, $answer, $isCompleted) {
    global $progressTable;
    global $wpdb;
    
    $sqlSelect = "SELECT answers FROM $progressTable WHERE userId = $userId AND quizId = $id ";
    $result = $wpdb->get_results($sqlSelect);
    
    $resVal = 0;
    if(count($result) == 0) {
        $resVal = $wpdb->insert(
            $progressTable,
            array(
                'userId' => $userId,
                'quizId' => $id,
                'isCompleted' => $isCompleted,
                'answers' => $answer
            )
        );
    }
    else {
        $resVal = $wpdb->update(
            $progressTable,
            array(
                'userId' => $userId,
                'quizId' => $id,
                'isCompleted' => $isCompleted,
                'answers' => $answer
            ),
            array(
                'userId' => $userId,
                'quizId' => $id
            )
        );
    }
    return $resVal;
}

function insertImport($data) {
    global $questionsTable;
    global $wpdb;
    
    $data = stripslashes_deep($data);
    $obj = json_decode($data);
    
    if($obj == null)
        return false;
    
    $isError = false;
    
    if(count($obj->Questions)) {
        foreach($obj->Questions as $o) {
            $resVal = $wpdb->insert(
                $questionsTable,
                array(
                    'question' => $o->Question,
                    'options' => json_encode($o->Options),
                    'answers' => json_encode($o->Answers),
                    'type' => $o->Type,
                    'level' => $obj->Level,
                    'language' => $obj->Language
                )
            );
            
            $isError = $isError && ($resVal == false) ? false : true;
        }
    }
    
    return $isError;
}

//admin
function getQuestionsList($level, $language) {
    global $questionsTable;
    global $wpdb;
    
    $sqlSelect = "SELECT * FROM $questionsTable WHERE level = '$level' AND language = '$language' ";
    $result = $wpdb->get_results($sqlSelect);
    return json_encode($result);
}


function getQuizQuestions($level, $language, $isResume, $userId, $quizId) {
    global $questionsTable;
    global $progressTable;
    global $wpdb;
    
    $totalQuestions = 4;
    $result = null;
    $resultAnswers = array();
    
    if($isResume == false){
        $sqlSelect = "SELECT id, question, options, type FROM $questionsTable WHERE level = '$level' AND language = '$language' ORDER BY RAND() LIMIT $totalQuestions ";
        $result = $wpdb->get_results($sqlSelect);
        //return json_encode($result);
    }
    else {
    
        $sql = "SELECT answers FROM $progressTable WHERE userId = $userId AND quizId = $quizId and isCompleted = 0 ";
        $resultAnswers = $wpdb->get_results($sql);
    
        $resultAnswers = json_decode(stripslashes_deep($resultAnswers[0]->answers));
    
        $pendingQuestions = $totalQuestions - count($resultAnswers);
    
        $sqlSelect = "SELECT id, question, options, type FROM $questionsTable WHERE level = '$level' AND language = '$language' ORDER BY RAND() LIMIT $pendingQuestions ";
        $result = $wpdb->get_results($sqlSelect);
    }
    
    $dataToSend = array(
        'Questions' => json_encode($result),
        'Anwers' => json_encode($resultAnswers),
        'AnsweredQuestions' => $isResume ? count($resultAnswers) : 0
    );
    
    return json_encode($dataToSend);
}

function getPendingQuiz() {
    global $progressTable;
    global $wpdb;
    
    $sqlSelect = "SELECT $progressTable.id as id, quizId, userId, post_title from $progressTable, wp_posts where isCompleted = 1 and wp_posts.ID = quizId and wp_posts.post_type = 'quiz'";
    $result = $wpdb->get_results($sqlSelect);
    return json_encode($result);
}

function getQuizForValidation($quizId, $prgId) {
    global $questionsTable;
    global $progressTable;
    global $wpdb;
    
    $sqlSelect = "SELECT answers, userId from $progressTable where isCompleted = 1 and quizId = $quizId and id = $prgId ";
    $result = $wpdb->get_results($sqlSelect);
    $userId = $result[0]->userId;
    $result = json_decode(stripslashes_deep($result[0]->answers));
    
    $numberOfAutoRightAnswers = 0;
    $numberOfAutoWrongAnswers = 0;
    
    $manualQuestions = array();
    
    foreach( $result as $res ) {
        $sql = "SELECT * from $questionsTable where id=$res->qtnId ";
        $qtn = $wpdb->get_results($sql);
        $ansExpected = json_decode($qtn[0]->answers);
        $ansActual = json_decode($res->answer);
        if($qtn[0]->type == 'MC') {
            if( $ansExpected[0] == $res->answer[0]) {
                $numberOfAutoRightAnswers += 1;
            }
            else {
                $numberOfAutoWrongAnswers += 1;
            }
        }
        else if($qtn[0]->type == 'MA') {
            if( count($ansExpected) <> count($res->answer)) {
                $numberOfAutoWrongAnswers += 1;
            }
            else {
                $isFound = false;
                for($i = 0; $i < count($res->answer); $i++) {
                    $isFound = false;
                    for($j = 0; $j < count($ansExpected); $j++) {
                        if($res->answer[$i] == $ansExpected[$j])
                        {
                            $isFound = true;
                        }
                    }
                    if($isFound == false) {
                        $numberOfAutoWrongAnswers += 1;
                        break;
                    }
                }
                if($isFound) {
                    $numberOfAutoRightAnswers += 1;
                }
            }
        }
        else if($qtn[0]->type == 'SORT') {
            $optActual = json_decode($qtn[0]->options);
            for($i = 0; $i < count($optActual); $i++) {
                if($optActual[$i] <> $res->answer[$i]) {
                    $numberOfAutoWrongAnswers += 1;
                    break;
                }
            }
            if($i == count($optActual)) {
                $numberOfAutoRightAnswers += 1;
            }
        }
        else if($qtn[0]->type == 'DESC') {
            array_push($manualQuestions, array(
                'Question' => $qtn[0]->question,
                'Answer' => $res->answer[0]
            ));
        }
    }
    
    $validation = array(
        "numberOfAutoRightAnswers" => $numberOfAutoRightAnswers,
        "numberOfAutoWrongAnswers" => $numberOfAutoWrongAnswers,
        "manualQuestions" => $manualQuestions,
        "userId" => $userId
    );
    
    return json_encode($validation);
}

function submit_quiz_score($quizId, $score, $userId, $report) {
    global $wpdb;
    global $scoreTable;
    global $progressTable;
    
    $resVal = $wpdb->insert(
                $scoreTable,
                array(
                    'quizId' => $quizId,
                    'userId' => $userId,
                    'score' => $score,
                    'report' => $report
                )
            );
    if($resVal == false){
        return $resVal;
    }
    $resVal = $wpdb->delete(
        $progressTable,
        array(
            'quizId' => $quizId,
            'userId' => $userId
        )
    );
    return ($resVal == false) ? false : true;
}

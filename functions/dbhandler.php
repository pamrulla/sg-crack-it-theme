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
                    'level' => $obj->level,
                    'language' => $obj->language
                )
            );
            
            $isError = $isError && ($resVal == false) ? false : true;
        }
    }
    
    return $isError;
}

function getQuestionsList($level, $language) {
    global $questionsTable;
    global $wpdb;
    
    $sqlSelect = "SELECT * FROM $questionsTable WHERE level = '$level' AND language = '$language' ";
    $result = $wpdb->get_results($sqlSelect);
    return json_encode($result);
}

function getQuizQuestions($level, $language) {
    global $questionsTable;
    global $wpdb;
    
    $sqlSelect = "SELECT id, question, options, type FROM $questionsTable WHERE level = '$level' AND language = '$language' ORDER BY RAND() LIMIT 30 ";
    $result = $wpdb->get_results($sqlSelect);
    return json_encode($result);
}

function getPendingQuiz() {
    global $progressTable;
    global $wpdb;
    
    $sqlSelect = "SELECT $progressTable.id as id, quizId, userId, post_title from $progressTable, wp_posts where isCompleted = 1 and wp_posts.ID = quizId ";
    $result = $wpdb->get_results($sqlSelect);
    return json_encode($result);
}
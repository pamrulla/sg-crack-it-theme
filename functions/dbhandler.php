<?php

$questionsTable = $wpdb->prefix . "sgct_questions";
$progressTable = $wpdb->prefix . "sgct_progress";
$scoreTable = $wpdb->prefix . "sgct_score";
$postsTable = $wpdb->prefix . "posts";
$memberplanTable = $wpdb->prefix . "sgct_memberplan";
$ordersTable = $wpdb->prefix . "sgct_orders";

function sgcrackit_createTables() {
    global $wpdb;
    global $questionsTable;
    global $progressTable;
    global $scoreTable;
    global $memberplanTable;
    global $ordersTable;
    
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
        date DATE NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    dbDelta( $sql2 );
    
    $sql3 = "CREATE TABLE $memberplanTable ( 
        id BIGINT NOT NULL AUTO_INCREMENT ,
        userId BIGINT NOT NULL ,
        memberplan SMALLINT NOT NULL ,
        option SMALLINT NOT NULL ,
        startDate DATE NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    dbDelta( $sql3 );
    
    $sql4 = "CREATE TABLE $ordersTable ( 
        id BIGINT NOT NULL AUTO_INCREMENT ,
        userId BIGINT NOT NULL ,
        txnId MEDIUMTEXT NOT NULL , 
        memberplan SMALLINT NOT NULL ,
        option SMALLINT NOT NULL ,
        date DATE NOT NULL,
        amount SMALLINT NOT NULL ,
        status SMALLINT NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    dbDelta( $sql4 );
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
    
    if($isCompleted == 1) {
        sendQuizCompletionMail($userId, $id);
    }
    return $resVal;
}


function sendQuizCompletionMail($userId, $id){
    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    $lang = get_the_terms($id, 'language')[0]->name;
    $lvl = get_the_terms($id, 'level')[0]->name;
    $user = get_user_by( 'id', $userId );
    $name = $user->first_name . ' ' . $user->last_name;
    $email = $user->user_email;
    
    $message = '';
    
    $message .= getMailHeader();
    $message .= getMainSenderName($name);
    
    $message .= 'Congratulations on completion of '.$lang.' language for complexity level of '.$lvl;
    $message .= 'We will validate your quiz in 24 hours and send you the final score.';
    
    $message .= getMailFooter();
    
    wp_mail($email, 'Successfully completion of '.$lang.'-'.$lvl.' quiz', $message);
    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

}

function getMailHeader() {
    $message = '';
    $message .= '<!DOCTYPE html>';
    $message .= '<html>';
    $message .= '<head>';
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    $message .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    $message .= '</head>';
    $message .= '<body style="margin: 0; padding: 0">';
    $message .= '<table border="1" cellpadding="0" cellspacing="0" width="80%" align="center" style="border-collapse: collapse;">';
    $message .= '<tr>';
    $message .= '<td align="center" bgcolor="#5f5f5f" style="padding: 20px 0 30px 0; color:white; font-size:25px">';
    $message .= 'SmartGnan - Crack It';
    $message .= '</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td align="left" style="padding: 20px; color:black; font-size:16px">';
    return $message;
}

function getMainSenderName($name) {
    return '<p>Hi '.$name.',</p>';
}

function getMailFooter() {
    $message = '<p>Good luck!</p>';
    $message .= '<p>Regards,</p>';
    $message .= '<p>Team SmartGnan.</p>';
    $message .= '</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td align="center" bgcolor="#5f5f5f" style="padding: 20px 0 20px 0; color:#bababa; font-size:14px">';
    $message .= '<a href="www.SmartGnan.com/CrackIt" style="text-decoration: none; color:#bababa;">www.SmartGnan.com/CrackIt</a>';
    $message .= '</td>';
    $message .= '</tr>';
    $message .= '</table>';
    $message .= '</body>';
    $message .= '</html>';
    return $message;
}

function wpdocs_set_html_mail_content_type() {
    return 'text/html';
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
    global $postsTable;
    global $wpdb;
    
    $sqlSelect = "SELECT $progressTable.id as id, quizId, userId, post_title from $progressTable, $postsTable where isCompleted = 1 and $postsTable.ID = quizId and $postsTable.post_type = 'quiz'";
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
                    'report' => $report,
                    'date' => date("Y-m-d")
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
    
    sendQuizValidationMail($userId, $quizId, $report, $score);
    
    return ($resVal == false) ? false : true;
}

function sendQuizValidationMail($userId, $id, $report, $score){
    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    $lang = get_the_terms($id, 'language')[0]->name;
    $lvl = get_the_terms($id, 'level')[0]->name;
    $user = get_user_by( 'id', $userId );
    $name = $user->first_name . ' ' . $user->last_name;
    $email = $user->user_email;
    
    global $wpdb;
    global $memberplanTable;
    
    $sql1 = "SELECT * FROM $memberplanTable WHERE userId = $userId";
    $res1 = $wpdb->get_results($sql1);
    
    $startDate = strtotime($res1[0]->startDate);
    $str = '';
    
    if($res1[0]->option == 1) {    
        $str = "+1 month";
    }
    else if($res1[0]->option == 2) {  
        $str = "+6 month";
    }
    else if($res1[0]->option == 3) {   
        $str = "+12 month";
    }
    
    $expiryDate = date( "Y-m-d",strtotime($str, $startDate));
    
    $message = getMailHeader();
    $message .= getMainSenderName($name);
    
    $message .= '<p>Congratulations, we have successfully validated <strong>'.$lang.'-'.$lvl.'</strong> quiz. </p>';
    $message .= '<h3>You Score: '.$score.'</h3>';
    if(count($res1) != 0 && $expiryDate >= date("Y-m-d")) {
        $message .= '<p>Some inputs to you from our expers:</p>';
        $message .= json_decode(stripslashes_deep($report));
    }
    
    $message .= getMailFooter();
    
    wp_mail($email, 'Successful validation of '.$lang.'-'.$lvl.' quiz', $message);
    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

}

function dashboard_get_participated_quiz($userId) {
    global $wpdb;
    global $scoreTable;
    global $progressTable;
    
    
    $validated = array();
    
    $sql = "SELECT quizId, MAX(score) as sc FROM $scoreTable WHERE userId = $userId GROUP BY quizId ";
    $res = $wpdb->get_results($sql);
    
    $all_languages = get_terms('language');
    
    foreach($all_languages as $lang) {
        $total_score = 0;
        
        for($i = 0; $i < count($res); $i++) {
            if(get_the_terms($res[$i]->quizId, 'language')[0]->name == $lang->name ) {
                $total_score += $res[$i]->sc;
            }
        }
        
        if($total_score != 0) {
            array_push($validated, array("language" => $lang->name, "score" => $total_score ));
        }
    }
    
    $inprogress = array();
    
    $sqli = "SELECT quizId from $progressTable WHERE userId = $userId and isCompleted=0 ";
    $ip = $wpdb->get_results($sqli);
    
    foreach($ip as $i) {
        $level = get_the_terms($i->quizId, 'level')[0]->name;
        $language = get_the_terms($i->quizId, 'language')[0]->name;
        array_push($inprogress, array("language" => $language, "level" => $level, "quizId" => $i->quizId ));
    }
    
    $sqlp = "SELECT quizId from $progressTable WHERE userId = $userId and isCompleted=1 ";
    $p = $wpdb->get_results($sqlp);
    
    $pending = array();
    
    foreach($p as $i) {
        $level = get_the_terms($i->quizId, 'level')[0]->name;
        $language = get_the_terms($i->quizId, 'language')[0]->name;
        array_push($pending, array("language" => $language, "level" => $level));
    }
    
    
    
    return json_encode(array("validated" => $validated, "inprogress" => $inprogress, "pending" => $pending));
}

function dashboard_get_quiz_insights($userId, $language) {
    global $wpdb;
    global $scoreTable;
    global $progressTable;
    
    
    $insights = array();
    
    $sql = "SELECT date, quizId, score FROM $scoreTable WHERE userId = $userId ORDER BY date ";
    $res = $wpdb->get_results($sql);
    
    $all_languages = get_terms('language');
    
    $b_s = 0;
    $i_s = 0;
    $a_s = 0;
    $t_s = 0;
        
    
    for($i = 0; $i < count($res); $i++) {
        $tms = get_the_terms($res[$i]->quizId, 'language');
        if($tms[0]->name == $language ) {
            $level = get_the_terms($res[$i]->quizId, 'level')[0]->name;
            if($level == "Beginner"){
                if($b_s < $res[$i]->score) {
                    $b_s = $res[$i]->score;
                }
            }
            else if($level == "Intermediate") {
                if($i_s < $res[$i]->score) {
                    $i_s = $res[$i]->score;
                }
            }
            else
            {
                if($a_s < $res[$i]->score) {
                    $a_s = $res[$i]->score;
                }
            }
            $t_s = $b_s + $i_s + $a_s;
            array_push($insights, array(
                    'date' => $res[$i]->date,
                    'level' => $level,
                    'score' => $res[$i]->score,
                    'total_score' => $t_s
                    )
            );
        }
    }
    
    return json_encode($insights);
}

function checkout_process_payment($userId, $memberplan, $option, $txnId, $amount, $status) {
    global $wpdb;
    global $memberplanTable;
    global $ordersTable;
    
    if($status == 1) {
        $wpdb->insert(
                $memberplanTable,
                array(
                    'userId' => $userId,
                    'memberplan' => $memberplan,
                    'option' => $option,
                    'startDate' => date("Y-m-d")
                )
            );
    }
    $wpdb->insert(
                $ordersTable,
                array(
                    'userId' => $userId,
                    'memberplan' => $memberplan,
                    'option' => $option,
                    'date' => date("Y-m-d"),
                    'txnId' => $txnId,
                    'amount' => $amount,
                    'status' => $status
                )
            );
    
    sendPaymentMail($userId, $memberplan, $option, $txnId, $amount, $status);
    
    return;
}

function sendPaymentMail($userId, $memberplan, $option, $txnId, $amount, $status){
    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    $user = get_user_by( 'id', $userId );
    $name = $user->first_name . ' ' . $user->last_name;
    $email = $user->user_email;
    
    $message = getMailHeader();
    $message .= getMainSenderName($name);
    
    $info = ($memberplan == 1) ? 'Intermediate Plan' : 'Advanced Plan';
    if($option == 1) {
        $info .= ', 1 month duration ';
    }
    else if($option == 2) {
        $info .= ', 6 months duration ';
    }
    else if($option == 3) {
        $info .= ', 12 months duration ';
    }
    
    $message .= '<p>Your transaction of Rs. ' . $amount . ' for ' . $info . 'with transation id ' . $txnId . ' is ' . (($status == 1) ? 'success' : 'failed.') . '</p>';
    
    $message .= getMailFooter();
    
    wp_mail($email, 'SmartGnan - Crack It, membership plan transaction status', $message);
    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

}

function dashboard_account_details($userId) {
    global $wpdb;
    global $memberplanTable;
    global $ordersTable;
    
    $sql1 = "SELECT * FROM $memberplanTable WHERE userId = $userId";
    $res1 = $wpdb->get_results($sql1);
    
    $membership = array();
    
    if(count($res1) == 0) {
        array_push($membership, array(
                    'level' => 'Beginner Plan',
                    'startdate' => '-',
                    'enddate' => 'Lifetime Access',
                    'status' => 'Active'
                    )
            );
    }
    else {
        $level = ($res1[0]->memberplan == 1) ? "Intermediate Plan" : "Advanced Plan";
        $startDate = strtotime($res1[0]->startDate);
        $str = '';
        
        if($res1[0]->option == 1) {
            $level .= ' For 1 Month';    
            $str = "+1 month";
        }
        else if($res1[0]->option == 2) {
            $level .= ' For 6 Months';    
            $str = "+6 month";
        }
        else if($res1[0]->option == 3) {
            $level .= ' For 12 Months';    
            $str = "+12 month";
        }
        $expiryDate = date( "Y-m-d",strtotime($str, $startDate));
        $status = 'Active';
        if($expiryDate < date("Y-m-d")) {
           $status = 'Expired'; 
        }
        array_push($membership, array(
                    'level' => $level,
                    'startdate' => $res1[0]->startDate,
                    'enddate' => $expiryDate,
                    'status' => $status
                    )
            );
    }
    
    $sql2 = "SELECT * FROM $ordersTable WHERE userId = $userId";
    $res2 = $wpdb->get_results($sql2);
    
    $orders = array();
    
    foreach($res2 as $o){
        $level = ($o->memberplan == 1) ? "Intermediate Plan" : "Advanced Plan";
        
        if($o->option == 1) {
            $level .= ' For 1 Month';    
        }
        else if($o->option == 2) {
            $level .= ' For 6 Months';    
        }
        else if($o->option == 3) {
            $level .= ' For 12 Months';    
        }
        array_push($orders, array(
            'info' => $level,
            'txnid' => $o->txnId,
            'date' => $o->date,
            'amount' => $o->amount,
            'status' => ($o->status == 0) ? 'Failed' : 'Success'
        ));
    }
    
    return json_encode(array('membership' => $membership, 'orders' => $orders));
}
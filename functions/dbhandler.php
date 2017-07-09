<?php

$questionsTable = $wpdb->prefix . "sgct_questions";
$progressTable = $wpdb->prefix . "sgct_progress";
$scoreTable = $wpdb->prefix . "sgct_score";
$postsTable = $wpdb->prefix . "posts";

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
        date DATE NOT NULL,
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
    
    $message .= '<!doctype html>';
$message .= '<html>';
  $message .= '<head>';
    $message .= '<meta name="viewport" content="width=device-width" />';
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    $message .= '<title>Simple Transactional Email</title>';
    $message .= '<style>';
      $message .= '/* -------------------------------------';
          $message .= 'GLOBAL RESETS';
      $message .= '------------------------------------- */';
      $message .= 'img {';
        $message .= 'border: none;';
        $message .= '-ms-interpolation-mode: bicubic;';
        $message .= 'max-width: 100%; }';
      $message .= 'body {';
        $message .= 'background-color: #f6f6f6;';
        $message .= 'font-family: sans-serif;';
        $message .= '-webkit-font-smoothing: antialiased;';
        $message .= 'font-size: 14px;';
        $message .= 'line-height: 1.4;';
        $message .= 'margin: 0;';
        $message .= 'padding: 0; ';
        $message .= '-ms-text-size-adjust: 100%;';
        $message .= '-webkit-text-size-adjust: 100%; }';
      $message .= 'table {';
        $message .= 'border-collapse: separate;';
        $message .= 'mso-table-lspace: 0pt;';
        $message .= 'mso-table-rspace: 0pt;';
        $message .= 'width: 100%; }';
        $message .= 'table td {';
          $message .= 'font-family: sans-serif;';
          $message .= 'font-size: 14px;';
          $message .= 'vertical-align: top; }';
      $message .= '/* -------------------------------------';
          $message .= 'BODY & CONTAINER';
      $message .= '------------------------------------- */';
      $message .= '.body {';
        $message .= 'background-color: #f6f6f6;';
        $message .= 'width: 100%; }';
      $message .= '/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */';
      $message .= '.container {';
        $message .= 'display: block;';
        $message .= 'Margin: 0 auto !important;';
        $message .= '/* makes it centered */';
        $message .= 'max-width: 580px;';
        $message .= 'padding: 10px;';
        $message .= 'width: 580px; }';
      $message .= '/* This should also be a block element, so that it will fill 100% of the .container */';
      $message .= '.content {';
        $message .= 'box-sizing: border-box;';
        $message .= 'display: block;';
        $message .= 'Margin: 0 auto;';
        $message .= 'max-width: 580px;';
        $message .= 'padding: 10px; }';
      $message .= '/* -------------------------------------';
          $message .= 'HEADER, FOOTER, MAIN';
      $message .= '------------------------------------- */';
      $message .= '.main {';
        $message .= 'background: #fff;';
        $message .= 'border-radius: 3px;';
        $message .= 'width: 100%; }';
      $message .= '.wrapper {';
        $message .= 'box-sizing: border-box;';
        $message .= 'padding: 20px; }';
      $message .= '.footer {';
        $message .= 'clear: both;';
        $message .= 'padding-top: 10px;';
        $message .= 'text-align: center;';
        $message .= 'width: 100%; }';
        $message .= '.footer td,';
        $message .= '.footer p,';
        $message .= '.footer span,';
        $message .= '.footer a {';
          $message .= 'color: #999999;';
          $message .= 'font-size: 12px;';
          $message .= 'text-align: center; }';
      $message .= '/* -------------------------------------';
          $message .= 'TYPOGRAPHY';
      $message .= '------------------------------------- */';
      $message .= 'h1,';
      $message .= 'h2,';
      $message .= 'h3,';
      $message .= 'h4 {';
        $message .= 'color: #000000;';
        $message .= 'font-family: sans-serif;';
        $message .= 'font-weight: 400;';
        $message .= 'line-height: 1.4;';
        $message .= 'margin: 0;';
        $message .= 'Margin-bottom: 30px; }';
      $message .= 'h1 {';
        $message .= 'font-size: 35px;';
        $message .= 'font-weight: 300;';
        $message .= 'text-align: center;';
        $message .= 'text-transform: capitalize; }';
      $message .= 'p,';
      $message .= 'ul,';
      $message .= 'ol {';
        $message .= 'font-family: sans-serif;';
        $message .= 'font-size: 14px;';
        $message .= 'font-weight: normal;';
        $message .= 'margin: 0;';
        $message .= 'Margin-bottom: 15px; }';
        $message .= 'p li,';
        $message .= 'ul li,';
        $message .= 'ol li {';
          $message .= 'list-style-position: inside;';
          $message .= 'margin-left: 5px; }';
      $message .= 'a {';
        $message .= 'color: #3498db;';
        $message .= 'text-decoration: underline; }';
      $message .= '/* -------------------------------------';
          $message .= 'BUTTONS';
      $message .= '------------------------------------- */';
      $message .= '.btn {';
        $message .= 'box-sizing: border-box;';
        $message .= 'width: 100%; }';
        $message .= '.btn > tbody > tr > td {';
          $message .= 'padding-bottom: 15px; }';
        $message .= '.btn table {';
          $message .= 'width: auto; }';
        $message .= '.btn table td {';
          $message .= 'background-color: #ffffff;';
          $message .= 'border-radius: 5px;';
          $message .= 'text-align: center; }';
        $message .= '.btn a {';
          $message .= 'background-color: #ffffff;';
          $message .= 'border: solid 1px #3498db;';
          $message .= 'border-radius: 5px;';
          $message .= 'box-sizing: border-box;';
          $message .= 'color: #3498db;';
          $message .= 'cursor: pointer;';
          $message .= 'display: inline-block;';
          $message .= 'font-size: 14px;';
          $message .= 'font-weight: bold;';
          $message .= 'margin: 0;';
          $message .= 'padding: 12px 25px;';
          $message .= 'text-decoration: none;';
          $message .= 'text-transform: capitalize; }';
      $message .= '.btn-primary table td {';
        $message .= 'background-color: #3498db; }';
      $message .= '.btn-primary a {';
        $message .= 'background-color: #3498db;';
        $message .= 'border-color: #3498db;';
        $message .= 'color: #ffffff; }';
      $message .= '/* -------------------------------------';
          $message .= 'OTHER STYLES THAT MIGHT BE USEFUL';
      $message .= '------------------------------------- */';
      $message .= '.last {';
        $message .= 'margin-bottom: 0; }';
      $message .= '.first {';
        $message .= 'margin-top: 0; }';
      $message .= '.align-center {';
        $message .= 'text-align: center; }';
      $message .= '.align-right {';
        $message .= 'text-align: right; }';
      $message .= '.align-left {';
        $message .= 'text-align: left; }';
      $message .= '.clear {';
        $message .= 'clear: both; }';
      $message .= '.mt0 {';
        $message .= 'margin-top: 0; }';
      $message .= '.mb0 {';
        $message .= 'margin-bottom: 0; }';
      $message .= '.preheader {';
        $message .= 'color: transparent;';
        $message .= 'display: none;';
        $message .= 'height: 0;';
        $message .= 'max-height: 0;';
        $message .= 'max-width: 0;';
        $message .= 'opacity: 0;';
        $message .= 'overflow: hidden;';
        $message .= 'mso-hide: all;';
        $message .= 'visibility: hidden;';
        $message .= 'width: 0; }';
      $message .= '.powered-by a {';
        $message .= 'text-decoration: none; }';
      $message .= 'hr {';
        $message .= 'border: 0;';
        $message .= 'border-bottom: 1px solid #f6f6f6;';
        $message .= 'Margin: 20px 0; }';
      $message .= '/* -------------------------------------';
          $message .= 'RESPONSIVE AND MOBILE FRIENDLY STYLES';
      $message .= '------------------------------------- */';
      $message .= '@media only screen and (max-width: 620px) {';
        $message .= 'table[class=body] h1 {';
          $message .= 'font-size: 28px !important;';
          $message .= 'margin-bottom: 10px !important; }';
        $message .= 'table[class=body] p,';
        $message .= 'table[class=body] ul,';
        $message .= 'table[class=body] ol,';
        $message .= 'table[class=body] td,';
        $message .= 'table[class=body] span,';
        $message .= 'table[class=body] a {';
          $message .= 'font-size: 16px !important; }';
        $message .= 'table[class=body] .wrapper,';
        $message .= 'table[class=body] .article {';
          $message .= 'padding: 10px !important; }';
        $message .= 'table[class=body] .content {';
          $message .= 'padding: 0 !important; }';
        $message .= 'table[class=body] .container {';
          $message .= 'padding: 0 !important;';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .main {';
          $message .= 'border-left-width: 0 !important;';
          $message .= 'border-radius: 0 !important;';
          $message .= 'border-right-width: 0 !important; }';
        $message .= 'table[class=body] .btn table {';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .btn a {';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .img-responsive {';
          $message .= 'height: auto !important;';
          $message .= 'max-width: 100% !important;';
          $message .= 'width: auto !important; }}';
      $message .= '/* -------------------------------------';
          $message .= 'PRESERVE THESE STYLES IN THE HEAD';
      $message .= '------------------------------------- */';
      $message .= '@media all {';
        $message .= '.ExternalClass {';
          $message .= 'width: 100%; }';
        $message .= '.ExternalClass,';
        $message .= '.ExternalClass p,';
        $message .= '.ExternalClass span,';
        $message .= '.ExternalClass font,';
        $message .= '.ExternalClass td,';
        $message .= '.ExternalClass div {';
          $message .= 'line-height: 100%; }';
        $message .= '.apple-link a {';
          $message .= 'color: inherit !important;';
          $message .= 'font-family: inherit !important;';
          $message .= 'font-size: inherit !important;';
          $message .= 'font-weight: inherit !important;';
          $message .= 'line-height: inherit !important;';
          $message .= 'text-decoration: none !important; } ';
        $message .= '.btn-primary table td:hover {';
          $message .= 'background-color: #34495e !important; }';
        $message .= '.btn-primary a:hover {';
          $message .= 'background-color: #34495e !important;';
          $message .= 'border-color: #34495e !important; } }';
    $message .= '</style>';
  $message .= '</head>';
  $message .= '<body class="">';
    $message .= '<table border="0" cellpadding="0" cellspacing="0" class="body">';
      $message .= '<tr>';
        $message .= '<td>&nbsp;</td>';
        $message .= '<td class="container">';
          $message .= '<div class="content">';
$message .= '';
            $message .= '<!-- START CENTERED WHITE CONTAINER -->';
            $message .= '<span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>';
            $message .= '<table class="main">';
$message .= '';
              $message .= '<!-- START MAIN CONTENT AREA -->';
              $message .= '<tr>';
                $message .= '<td class="wrapper">';
                  $message .= '<table border="0" cellpadding="0" cellspacing="0">';
                    $message .= '<tr>';
                      $message .= '<td>';
                        $message .= '<p>Hi '.$name.',</p>';
                        $message .= '<p>Congratulations on completion of <strong>'.$lang.'</strong> for complexity level of <strong>'.$lvl.'</strong></p>';
    $message .= '<p>We will validate your quiz in 24 hours and send you the final score.</p>';
                        $message .= '<p>Good luck!</p>';
                        $message .= '<p>Regards,</p>';
    $message .= '<p>Smart Gnan Crack It team</p>';
                      $message .= '</td>';
                    $message .= '</tr>';
                  $message .= '</table>';
                $message .= '</td>';
              $message .= '</tr>';
$message .= '';
            $message .= '<!-- END MAIN CONTENT AREA -->';
            $message .= '</table>';
$message .= '';
            $message .= '<!-- START FOOTER -->';
            $message .= '<div class="footer">';
              $message .= '<table border="0" cellpadding="0" cellspacing="0">';
                $message .= '<tr>';
                  $message .= '<td class="content-block">';
                    $message .= '<span class="apple-link">Sweety Technologies LLP, Hyderabad</span>';
                    $message .= '</td>';
                $message .= '</tr>';
                $message .= '</table>';
            $message .= '</div>';
            $message .= '<!-- END FOOTER -->';
$message .= '            ';
          $message .= '<!-- END CENTERED WHITE CONTAINER -->';
          $message .= '</div>';
        $message .= '</td>';
        $message .= '<td>&nbsp;</td>';
      $message .= '</tr>';
    $message .= '</table>';
  $message .= '</body>';
$message .= '</html>';
    
    wp_mail($email, 'Successfully completion of '.$lang.'-'.$lvl.' quiz', $message);
    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

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
    
    $sqlSelect = "SELECT $progressTable.id as id, quizId, userId, post_title from $progressTable, $postsTable where isCompleted = 1 and $posts.ID = quizId and $postsTable.post_type = 'quiz'";
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
    
    $message = '';
    
    $message .= '<!doctype html>';
$message .= '<html>';
  $message .= '<head>';
    $message .= '<meta name="viewport" content="width=device-width" />';
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    $message .= '<title>Simple Transactional Email</title>';
    $message .= '<style>';
      $message .= '/* -------------------------------------';
          $message .= 'GLOBAL RESETS';
      $message .= '------------------------------------- */';
      $message .= 'img {';
        $message .= 'border: none;';
        $message .= '-ms-interpolation-mode: bicubic;';
        $message .= 'max-width: 100%; }';
      $message .= 'body {';
        $message .= 'background-color: #f6f6f6;';
        $message .= 'font-family: sans-serif;';
        $message .= '-webkit-font-smoothing: antialiased;';
        $message .= 'font-size: 14px;';
        $message .= 'line-height: 1.4;';
        $message .= 'margin: 0;';
        $message .= 'padding: 0; ';
        $message .= '-ms-text-size-adjust: 100%;';
        $message .= '-webkit-text-size-adjust: 100%; }';
      $message .= 'table {';
        $message .= 'border-collapse: separate;';
        $message .= 'mso-table-lspace: 0pt;';
        $message .= 'mso-table-rspace: 0pt;';
        $message .= 'width: 100%; }';
        $message .= 'table td {';
          $message .= 'font-family: sans-serif;';
          $message .= 'font-size: 14px;';
          $message .= 'vertical-align: top; }';
      $message .= '/* -------------------------------------';
          $message .= 'BODY & CONTAINER';
      $message .= '------------------------------------- */';
      $message .= '.body {';
        $message .= 'background-color: #f6f6f6;';
        $message .= 'width: 100%; }';
      $message .= '/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */';
      $message .= '.container {';
        $message .= 'display: block;';
        $message .= 'Margin: 0 auto !important;';
        $message .= '/* makes it centered */';
        $message .= 'max-width: 580px;';
        $message .= 'padding: 10px;';
        $message .= 'width: 580px; }';
      $message .= '/* This should also be a block element, so that it will fill 100% of the .container */';
      $message .= '.content {';
        $message .= 'box-sizing: border-box;';
        $message .= 'display: block;';
        $message .= 'Margin: 0 auto;';
        $message .= 'max-width: 580px;';
        $message .= 'padding: 10px; }';
      $message .= '/* -------------------------------------';
          $message .= 'HEADER, FOOTER, MAIN';
      $message .= '------------------------------------- */';
      $message .= '.main {';
        $message .= 'background: #fff;';
        $message .= 'border-radius: 3px;';
        $message .= 'width: 100%; }';
      $message .= '.wrapper {';
        $message .= 'box-sizing: border-box;';
        $message .= 'padding: 20px; }';
      $message .= '.footer {';
        $message .= 'clear: both;';
        $message .= 'padding-top: 10px;';
        $message .= 'text-align: center;';
        $message .= 'width: 100%; }';
        $message .= '.footer td,';
        $message .= '.footer p,';
        $message .= '.footer span,';
        $message .= '.footer a {';
          $message .= 'color: #999999;';
          $message .= 'font-size: 12px;';
          $message .= 'text-align: center; }';
      $message .= '/* -------------------------------------';
          $message .= 'TYPOGRAPHY';
      $message .= '------------------------------------- */';
      $message .= 'h1,';
      $message .= 'h2,';
      $message .= 'h3,';
      $message .= 'h4 {';
        $message .= 'color: #000000;';
        $message .= 'font-family: sans-serif;';
        $message .= 'font-weight: 400;';
        $message .= 'line-height: 1.4;';
        $message .= 'margin: 0;';
        $message .= 'Margin-bottom: 30px; }';
      $message .= 'h1 {';
        $message .= 'font-size: 35px;';
        $message .= 'font-weight: 300;';
        $message .= 'text-align: center;';
        $message .= 'text-transform: capitalize; }';
      $message .= 'p,';
      $message .= 'ul,';
      $message .= 'ol {';
        $message .= 'font-family: sans-serif;';
        $message .= 'font-size: 14px;';
        $message .= 'font-weight: normal;';
        $message .= 'margin: 0;';
        $message .= 'Margin-bottom: 15px; }';
        $message .= 'p li,';
        $message .= 'ul li,';
        $message .= 'ol li {';
          $message .= 'list-style-position: inside;';
          $message .= 'margin-left: 5px; }';
      $message .= 'a {';
        $message .= 'color: #3498db;';
        $message .= 'text-decoration: underline; }';
      $message .= '/* -------------------------------------';
          $message .= 'BUTTONS';
      $message .= '------------------------------------- */';
      $message .= '.btn {';
        $message .= 'box-sizing: border-box;';
        $message .= 'width: 100%; }';
        $message .= '.btn > tbody > tr > td {';
          $message .= 'padding-bottom: 15px; }';
        $message .= '.btn table {';
          $message .= 'width: auto; }';
        $message .= '.btn table td {';
          $message .= 'background-color: #ffffff;';
          $message .= 'border-radius: 5px;';
          $message .= 'text-align: center; }';
        $message .= '.btn a {';
          $message .= 'background-color: #ffffff;';
          $message .= 'border: solid 1px #3498db;';
          $message .= 'border-radius: 5px;';
          $message .= 'box-sizing: border-box;';
          $message .= 'color: #3498db;';
          $message .= 'cursor: pointer;';
          $message .= 'display: inline-block;';
          $message .= 'font-size: 14px;';
          $message .= 'font-weight: bold;';
          $message .= 'margin: 0;';
          $message .= 'padding: 12px 25px;';
          $message .= 'text-decoration: none;';
          $message .= 'text-transform: capitalize; }';
      $message .= '.btn-primary table td {';
        $message .= 'background-color: #3498db; }';
      $message .= '.btn-primary a {';
        $message .= 'background-color: #3498db;';
        $message .= 'border-color: #3498db;';
        $message .= 'color: #ffffff; }';
      $message .= '/* -------------------------------------';
          $message .= 'OTHER STYLES THAT MIGHT BE USEFUL';
      $message .= '------------------------------------- */';
      $message .= '.last {';
        $message .= 'margin-bottom: 0; }';
      $message .= '.first {';
        $message .= 'margin-top: 0; }';
      $message .= '.align-center {';
        $message .= 'text-align: center; }';
      $message .= '.align-right {';
        $message .= 'text-align: right; }';
      $message .= '.align-left {';
        $message .= 'text-align: left; }';
      $message .= '.clear {';
        $message .= 'clear: both; }';
      $message .= '.mt0 {';
        $message .= 'margin-top: 0; }';
      $message .= '.mb0 {';
        $message .= 'margin-bottom: 0; }';
      $message .= '.preheader {';
        $message .= 'color: transparent;';
        $message .= 'display: none;';
        $message .= 'height: 0;';
        $message .= 'max-height: 0;';
        $message .= 'max-width: 0;';
        $message .= 'opacity: 0;';
        $message .= 'overflow: hidden;';
        $message .= 'mso-hide: all;';
        $message .= 'visibility: hidden;';
        $message .= 'width: 0; }';
      $message .= '.powered-by a {';
        $message .= 'text-decoration: none; }';
      $message .= 'hr {';
        $message .= 'border: 0;';
        $message .= 'border-bottom: 1px solid #f6f6f6;';
        $message .= 'Margin: 20px 0; }';
      $message .= '/* -------------------------------------';
          $message .= 'RESPONSIVE AND MOBILE FRIENDLY STYLES';
      $message .= '------------------------------------- */';
      $message .= '@media only screen and (max-width: 620px) {';
        $message .= 'table[class=body] h1 {';
          $message .= 'font-size: 28px !important;';
          $message .= 'margin-bottom: 10px !important; }';
        $message .= 'table[class=body] p,';
        $message .= 'table[class=body] ul,';
        $message .= 'table[class=body] ol,';
        $message .= 'table[class=body] td,';
        $message .= 'table[class=body] span,';
        $message .= 'table[class=body] a {';
          $message .= 'font-size: 16px !important; }';
        $message .= 'table[class=body] .wrapper,';
        $message .= 'table[class=body] .article {';
          $message .= 'padding: 10px !important; }';
        $message .= 'table[class=body] .content {';
          $message .= 'padding: 0 !important; }';
        $message .= 'table[class=body] .container {';
          $message .= 'padding: 0 !important;';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .main {';
          $message .= 'border-left-width: 0 !important;';
          $message .= 'border-radius: 0 !important;';
          $message .= 'border-right-width: 0 !important; }';
        $message .= 'table[class=body] .btn table {';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .btn a {';
          $message .= 'width: 100% !important; }';
        $message .= 'table[class=body] .img-responsive {';
          $message .= 'height: auto !important;';
          $message .= 'max-width: 100% !important;';
          $message .= 'width: auto !important; }}';
      $message .= '/* -------------------------------------';
          $message .= 'PRESERVE THESE STYLES IN THE HEAD';
      $message .= '------------------------------------- */';
      $message .= '@media all {';
        $message .= '.ExternalClass {';
          $message .= 'width: 100%; }';
        $message .= '.ExternalClass,';
        $message .= '.ExternalClass p,';
        $message .= '.ExternalClass span,';
        $message .= '.ExternalClass font,';
        $message .= '.ExternalClass td,';
        $message .= '.ExternalClass div {';
          $message .= 'line-height: 100%; }';
        $message .= '.apple-link a {';
          $message .= 'color: inherit !important;';
          $message .= 'font-family: inherit !important;';
          $message .= 'font-size: inherit !important;';
          $message .= 'font-weight: inherit !important;';
          $message .= 'line-height: inherit !important;';
          $message .= 'text-decoration: none !important; } ';
        $message .= '.btn-primary table td:hover {';
          $message .= 'background-color: #34495e !important; }';
        $message .= '.btn-primary a:hover {';
          $message .= 'background-color: #34495e !important;';
          $message .= 'border-color: #34495e !important; } }';
    $message .= '</style>';
  $message .= '</head>';
  $message .= '<body class="">';
    $message .= '<table border="0" cellpadding="0" cellspacing="0" class="body">';
      $message .= '<tr>';
        $message .= '<td>&nbsp;</td>';
        $message .= '<td class="container">';
          $message .= '<div class="content">';
$message .= '';
            $message .= '<!-- START CENTERED WHITE CONTAINER -->';
            $message .= '<span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>';
            $message .= '<table class="main">';
$message .= '';
              $message .= '<!-- START MAIN CONTENT AREA -->';
              $message .= '<tr>';
                $message .= '<td class="wrapper">';
                  $message .= '<table border="0" cellpadding="0" cellspacing="0">';
                    $message .= '<tr>';
                      $message .= '<td>';
                        $message .= '<p>Hi '.$name.',</p>';
                        $message .= '<p>Congratulations, we have successfully validated <strong>'.$lang.'-'.$lvl.'</strong> quiz. </p>';
    $message .= '<h3>You Score: '.$score.'</h3>';
    $message .= '<p>Some inputs to you from our expers:</p>';
    $message .= json_decode(stripslashes_deep($report));
                        $message .= '<p>Good luck!</p>';
                        $message .= '<p>Regards,</p>';
    $message .= '<p>Smart Gnan Crack It team</p>';
                      $message .= '</td>';
                    $message .= '</tr>';
                  $message .= '</table>';
                $message .= '</td>';
              $message .= '</tr>';
$message .= '';
            $message .= '<!-- END MAIN CONTENT AREA -->';
            $message .= '</table>';
$message .= '';
            $message .= '<!-- START FOOTER -->';
            $message .= '<div class="footer">';
              $message .= '<table border="0" cellpadding="0" cellspacing="0">';
                $message .= '<tr>';
                  $message .= '<td class="content-block">';
                    $message .= '<span class="apple-link">Sweety Technologies LLP, Hyderabad</span>';
                    $message .= '</td>';
                $message .= '</tr>';
                $message .= '</table>';
            $message .= '</div>';
            $message .= '<!-- END FOOTER -->';
$message .= '            ';
          $message .= '<!-- END CENTERED WHITE CONTAINER -->';
          $message .= '</div>';
        $message .= '</td>';
        $message .= '<td>&nbsp;</td>';
      $message .= '</tr>';
    $message .= '</table>';
  $message .= '</body>';
$message .= '</html>';
    
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

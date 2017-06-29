var isQuizStarted = false;
var currentQuestion = 0;
var data = null;
var ans;


var sampleData = '{"Title": "Test Quiz 1","ID": "1","Level": "Beginner","Questions": [{"ID": "1","Type": "MC","Question": "This is single choice question","Options": ["option1","option2"],"TimeLimit": "30"},{"ID": "1","Type": "MA","Question": "This is multiple select question","Options": ["choice1","choice2","choice3","choice4"],"TimeLimit": "30"},{"ID": "1","Type": "SORT","Question": "This is sorting question","Options": ["item1","item2","item3","item4","item5","item6","item7","item8"],"TimeLimit": "60"},{"ID": "1","Type": "DESC","Question": "This is descriptive question","TimeLimit": "2"}]}';


function startQuiz() {
    //Get data from ajax
    
    data = jQuery.parseJSON(sampleData);
    
    jQuery(function($){
        $('#quiz-content').fadeOut(500, function(){ 
            jQuery(function($){
                isQuizStarted = true;
                currentQuestion = 1;
                prepareQuestion();
                $('#quiz-content').fadeIn(500);
            });
        });
    });
}

function prepareQuestion() {
    var content = '';
    content += '<form>';
    content += '<div class="container">';
    content += prepareProgressBar();
    content += '<br/>';
    content += '<div class="row">';
    content += prepareQuestionTitle();
    content += prepareTimer();
    content += '</div>';
    content += '<hr/>';
    if( data.Questions[currentQuestion - 1].Type == "SORT") {
        content += prepareSORTQuestion();
    }
    else if( data.Questions[currentQuestion - 1].Type == "MC") {
        content += prepareMCQuestion();
    }
    else if( data.Questions[currentQuestion - 1].Type == "MA") {
        content += prepareMAQuestion();
    }
    else if( data.Questions[currentQuestion - 1].Type == "DESC") {
        content += prepareDESCQuestion();
    }
    content += '<hr/>';
    content += prepareNextButton();
    content += '</div>';
    content += '</form>';
    jQuery(function($){
        $('#quiz-content').html(content);
    });
}

function prepareQuestionTitle() {
    var content = '';
    content += '<div class="col-sm-10">';
    content += '<span id="quiz-question">';
    content += data.Questions[currentQuestion-1].Question;
    content += '</span>';
    content += '</div>';
    return content;
}


function prepareTimer() {
    var content = '';
    content += '<div class="col-sm-2">';
    content += '<div class="countdown">';
    content += '<div class="countdown-number"></div>';
    content += '<svg>';
    content += '<circle r="18" cx="20" cy="20"></circle>';
    content += '</svg>';
    content += '</div>';
    content += '</div>';
    content += '<script>';
    content += 'var countdownNumberEl = document.getElementsByClassName(\'countdown-number\')[0];';
    content += 'var countdown = 30;';
    content += 'countdownNumberEl.textContent = countdown+\'s\';';
    content += 'setInterval(function() {';
    content += 'countdown = --countdown < 0 ? 30 : countdown;';
    content += 'countdownNumberEl.textContent = countdown+\'s\';';
    content += '}, 1000);';
    content += '</script>';
    return content;
}
function prepareProgressBar() {
    var content = '';
    var progress = (currentQuestion / data.Questions.length) * 100;
    
    content += '<div class="row">';
    content += '<div class="col-sm-12">';
    content += '<div class="progress">';
    content += '<div class="progress-bar bg-success" role="progressbar" style="width: '+ progress +'%; height: 3px;"></div>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function prepareNextButton() {
    var content = '';
    content += '<div class="row">';
    content += '<div class="col-sm-2 offset-sm-10">';
    content += '<div id="qtn-button">';
    content += '<button name="btn" type="button" class="btn btn-primary" onclick="processQuestion();">';
    content += (currentQuestion == data.Questions.length) ? 'Finish' : 'Next';
    content += '</button>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function prepareMAQuestion() {
    var content = '';
    content += '<div class="row">';
    content += '<div class="col-sm-8 offset-sm-2">';
    content += '<div id="qtn-options">';
    content += '<div class="custom-controls-stacked">';
    var arr = data.Questions[currentQuestion-1].Options;
    for(i = 0; 0 < arr.length; i++) {
        var ri = Math.floor(Math.random() * arr.length);
        var vl = arr.splice(ri, 1);
        content += '<label class="custom-control custom-checkbox">';
        content += '<input id="check'+i+'" name="check-'+i+'" type="checkbox" class="custom-control-input">';
        content += '<span class="custom-control-indicator"></span>';
        content += '<span class="custom-control-description">'+ vl +'</span>';
        content += '</label>';    
    }
    content += '</div>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function prepareMCQuestion() {
    var content = '';
    content += '<div class="row">';
    content += '<div class="col-sm-8 offset-sm-2">';
    content += '<div id="qtn-options">';
    content += '<div class="custom-controls-stacked">';
    var arr = data.Questions[currentQuestion-1].Options;
    for(i = 0; 0 < arr.length; i++) {
        var ri = Math.floor(Math.random() * arr.length);
        var vl = arr.splice(ri, 1);
        content += '<label class="custom-control custom-radio">';
        content += '<input id="radio'+i+'" name="radio-'+currentQuestion+'" type="radio" class="custom-control-input" value="'+ vl +'">';
        content += '<span class="custom-control-indicator"></span>';
        content += '<span class="custom-control-description">'+ vl +'</span>';
        content += '</label>';    
    }
    content += '</div>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function prepareSORTQuestion() {
    var content = '';
    content += '<div class="row">';
    content += '<div class="col-sm-8 offset-sm-2">';
    content += '<div id="qtn-options">';
    content += '<div class="container-fluid">';
    content += '<div class="row sorting">';
    var arr = data.Questions[currentQuestion-1].Options;
    for(i = 0; 0 < arr.length; i++) {
        var ri = Math.floor(Math.random() * arr.length);
        var vl = arr.splice(ri, 1);
        content += '<div class="col-md-12">';
        content += '<div class="card card-outline-primary forsorting">';
        content += '<div class="card-block ">';
        content += vl;
        content += '</div>';
        content += '</div>';
        content += '</div>';   
    }
    content += '</div>';
    content += '</div>';
    content += '<script>';
    content += 'jQuery(function($){';
    content += '$(\'.sorting\').sortable({';
    content += 'connectWith: ".card",';
    content += 'handle: ".card-block",';
    content += 'placeholder: "card-placeholder",';
    content += 'start: function(e, ui){';
    content += 'ui.placeholder.width(ui.item.find(\'.card\').width());';
    content += 'ui.placeholder.height(ui.item.find(\'.card\').height());';
    content += 'ui.placeholder.addClass(ui.item.attr("class"));';
    content += '}';
    content += '});';
    content += '$(\'.card\').on(\'mousedown\', function(){';
    content += '$(this).css( \'cursor\', \'move\' );';
    content += '}).on(\'mouseup\', function(){';
    content += '$(this).css( \'cursor\', \'auto\' );';
    content += '});;';
    content += '});';
    content += '</script>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function prepareDESCQuestion() {
    var content = '';
    content += '<div class="row">';
    content += '<div class="col-sm-8 offset-sm-2">';
    content += '<div id="qtn-options">';
    content += '<textarea class="form-control" id="DESCAnswer" name="desccode" rows="10"></textarea>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    return content;
}

function processAnswer() {
    if( data.Questions[currentQuestion - 1].Type == "SORT") {
        return processSORTAnswer();
    }
    else if( data.Questions[currentQuestion - 1].Type == "MC") {
        return processMCAnswer();
    }
    else if( data.Questions[currentQuestion - 1].Type == "MA") {
        return processMAAnswer();
    }
    else if( data.Questions[currentQuestion - 1].Type == "DESC") {
        return processDESCAnswer();
    }
}

function processSORTAnswer() {
    jQuery(function($){
        $(".sorting").find(".card-block").each(function() {
          console.log($( this ).text() );
        });
    });
    return false;
}

function processMCAnswer() {
    var isError = false;
    jQuery(function($){
        var MCAnswer = $(":radio").serializeArray();
        if(MCAnswer.length == 0) {
            isError = true;
            return;
        }
        isError = false;
        return;
    });
    return isError;
}

function processMAAnswer() {
    var isError = false;
    jQuery(function($){
        var MAAnswer = $(":checkbox").serializeArray();
        if(MAAnswer.length == 0) {
            isError = true;
            return;
        }
        isError = false;
        return;
    });
    return isError;
}

function processDESCAnswer() {
    var isError = false;
    jQuery(function($){
        var DESCAnswer = $("textarea").serializeArray();
        if(DESCAnswer.length == 0) {
            isError = true;
            return;
        }
        val = DESCAnswer[0].value.trim();
        if( val === "") {
            isError = true;
            return;
        }
        isError = false;
        return;
    });
    return isError;
}

function processQuestion() {
    //send response to ajax
    if(processAnswer()) {
        jQuery(function($){
           $("#no-answer").show();
       });
       return;
    }
    if(currentQuestion == data.Questions.length) {
        endingPage();
        return;
    }
    jQuery(function($){
        $("#no-answer").hide();
        $('#quiz-content').fadeOut(500, function(){ 
            jQuery(function($){
                currentQuestion++;
                prepareQuestion();
                $('#quiz-content').fadeIn(500);
            });
        });
    });
}

function endingPage(){
    jQuery(function($){
        $('#quiz-content').fadeOut(500, function(){ 
            $("#no-answer").hide();
            jQuery(function($){
                var content = '';
                content += '<div class="card card-inverse card-success mb-3 text-center">';
                content += '<div class="card-block">';
                content += '<blockquote class="card-blockquote">';
                content += '<h5>Congratulations on completing the quiz.</h5>';
                content += '<p>You will recieve result of the quiz to your mail in 4 hours.</p>';
                content += '</blockquote>';
                content += '</div>';
                content += '</div>';
                content += '<div class="text-center">';
                content += '<a href="#" class="btn btn-primary">Dashboard</a>';
                content += '</div>';
                $('#quiz-content').html(content);
                $('#quiz-content').fadeIn(500);
            });
        });
    });
}
    
function startingPage(){
    var content = '';
    content += '<div class="text-center">'
    content += '<ul style="list-style-type: none;">';
    content += '<li>Quiz contains total 30 questions.</li>';
    content += '<li>Each question carries its own timeout.</li>';
    content += '<li>You can not skip any question.</li>';
    content += '<li>You can not go back to previous question.</li>';
    content += '</ul>';
    content += '<button type="button" class="btn btn-primary" onclick="startQuiz();">Start</button>';
    content += '</div>';
    jQuery(function($){
        $("#no-answer").hide();
        $('#quiz-content').html(content);
    });
}
    
if (!isQuizStarted) {
    jQuery(function($){
        $('#quiz-title').html("Khan1");
    });
    startingPage();
}

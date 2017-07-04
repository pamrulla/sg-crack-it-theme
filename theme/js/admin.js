function sgcrackit_import_questions() {
    jQuery(function($){
        $("#loader").show();
        f = $("#fileImport")[0].files[0];
        fileReader = new FileReader();
        fileReader.onload = function(event) {
            
            d = {
                action: 'sgcrackit_ajax_admin_import_questions',
                data : event.target.result
            };

            jQuery.post(ajaxurl, d, function(resp){
                $("#loader").hide();
                if(resp.data == 1) {
                    var content = '<div class="alert alert-success" role="alert">Success</div>';
                    $('#import-status').html(content);
                }
                else {
                    var content = '<div class="alert alert-danger" role="alert">Failed</div>';
                    $('#import-status').html(content);
                }
                
            });
        };
        fileReader.readAsText(f);
    });
}

function fetchQuestionsList() {
    jQuery(function($){
    
        $("#loader1").show();
        var level = '';
        var language = '';
        
        var fields = $(":input").serializeArray();
        
        if(fields[0].name == 'select-level') {
            level = fields[0].value;
        }
        else if(fields[0].name == 'select-language') {
            language = fields[0].value;
        }
        
        if(fields[1].name == 'select-level') {
            level = fields[1].value;
        }
        else if(fields[1].name == 'select-language') {
            language = fields[1].value;
        }
        
        if( level == 'Select Level' || language == 'Select Language') {
            alert("Please select valid inputs");
            return;
        }
        
        d = {
            action: 'sgcrackit_ajax_admin_get_questions',
            level : level,
            language : language
        };
    
        jQuery.post(ajaxurl, d, function(resp){
            $("#loader1").hide();
            data = jQuery.parseJSON(resp.data);
            
            content = '';
            console.log(data);
            for(i=0; i<data.length; i++) {
                console.log(data[i]);
                content += '<tr>';
                content += '<th scope="row">'+data[i].id+'</th>';
                content += '<td>'+data[i].question+'</td>';
                content += '<td>'+data[i].type+'</td>';
                content += '<td>'+data[i].options+'</td>';
                content += '<td>'+data[i].answers+'</td>';
                //content += '<td>'+data[i].TimeLimit+'</td>';
                content += '</tr>';
            }
            $("#questions-list").html(content);
        });
    });
}

var pendingQuestions = 0;
var currentValidationQuestion = 0;
var manualWrongAnswers = 0;
var manualRightAnswers = 0;
var autoRightAnswers = 0;
var autoWrongAnswers = 0;
var userId = 0;

function getPendingFullQuiz(quizId, prgId) {
    jQuery(function($) {
        d = {
            action: 'sgcrackit_ajax_admin_get_quiz_for_validation',
            quizId: quizId,
            prgId: prgId
        }
       
        jQuery.post(ajaxurl, d, function(resp){
            res = jQuery.parseJSON(resp.data);
            pendingQuestions = res.manualQuestions.length;
            autoRightAnswers = res.numberOfAutoRightAnswers;
            autoWrongAnswers = res.numberOfAutoWrongAnswers;
            userId = res.userId;
            
            $("#auto-right-ans").html(autoRightAnswers);
            $("#auto-wrong-ans").html(autoWrongAnswers);
            $("#manual-right-ans").html(manualRightAnswers);
            $("#manual-wrong-ans").html(manualWrongAnswers);
            $("#pending-questions").html(pendingQuestions);
            $("#manual-question").html(res.manualQuestions[currentValidationQuestion].Question);
            $("#manual-answer").html(res.manualQuestions[currentValidationQuestion].Answer);
        });
        
    });
}

function updateValidation(isValid) {
    jQuery(function($) {
        if(isValid) {
            manualRightAnswers += 1;
            $("#manual-right-ans").html(manualRightAnswers);
        }
        else {
            manualWrongAnswers += 1;
            $("#manual-wrong-ans").html(manualWrongAnswers);
        }
        pendingQuestions -= 1;
        if(pendingQuestions != 0) {
            currentValidationQuestion += 1;
            $("#pending-questions").html(pendingQuestions);
            $("#manual-question").html(res.manualQuestions[currentValidationQuestion].Question);
            $("#manual-answer").html(res.manualQuestions[currentValidationQuestion].Answer);
        }
        else {
            $("#validation-of-desc").hide();
            $("#total-right").html(manualRightAnswers + autoRightAnswers);
            $("#total-wrong").html(manualWrongAnswers + autoWrongAnswers);
            $("#final-validation").show();
        }
    });
}

function onSubmittingQuizValidation() {
    jQuery(function($){
        console.log(tinyMCE.activeEditor.getContent());
        d = {
            action: 'sgcrackit_ajax_admin_submit_quiz_score',
            quizId: quizId,
            userId: userId,
            score: '1'
        }
        
        jQuery.post(ajaxurl, d, function(resp){
        
        });
    });
}

jQuery(function($) { 
    if($("#loader").length){$("#loader").hide();} 
    if($("#loader1").length){$("#loader1").hide();}
    if($("#quiz-list").length) {
        d = {
            action: 'sgcrackit_ajax_admin_get_pending_quiz'
        };
    
        jQuery.post(ajaxurl, d, function(resp){
            data = jQuery.parseJSON(resp.data);
            
            content = '';
            console.log(data);
            for(i=0; i<data.length; i++) {
                console.log(data[i]);
                content += '<tr>';
                content += '<th scope="row">'+data[i].id+'</th>';
                content += '<td>'+data[i].post_title+'</td>';
                content += '<td><a class="btn btn-danger" href="'+window.location+'&quizId='+data[i].quizId+'&prgId='+data[i].id+'">Validate</a></td>';
                content += '</tr>';
            }
            $("#quiz-list").html(content);
        });
    }
});
<script>
var quizapp = '<?php echo get_permalink(get_page_by_path('quiz-app')); ?>';
</script>
<br><br>
<h5>Welcome, <?php echo wp_get_current_user()->user_firstname; ?></h5>
<br><br>
<div class=" text-center" id="main-loader"><i class="fa fa-spinner fa-spin" style="font-size:64px;color:lightgreen"></i></div>
<div class="row" id="list-validated-title" style="display:none">
    <div class="col-sm-12">
        <div class="card card-inverse card-success text-center">
            <div class="card-block">
                <h5>Completed Quizes</h5>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row" id="list-validated" style="display:none"></div>
<br>
<div class="row">
    <div class="col-sm-6">
        <div class="row" id="list-inprogress-title" style="display:none">
            <div class="col-sm-12">
                <div class="card card-inverse card-info text-center">
                    <div class="card-block">
                        <h5>Inprogress Quizes</h5>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row" id="list-inprogress" style="display:none"></div>
    </div>
    <div class="col-sm-6">
        <div class="row" id="list-pending-title" style="display:none">
            <div class="col-sm-12">
                <div class="card card-inverse card-warning text-center">
                    <div class="card-block">
                        <h5>Pending for Validation Quizes</h5>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row" id="list-pending" style="display:none">
            
        </div>
    </div>
</div>
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var pending = null;
    var validated = null;
    var inprogress = null;
    
    var classesColor = ['primary', 'success', 'info', 'warning', 'danger'];

    
    jQuery(function($){
       d = {
           action: 'sgcrackit_ajax_dashboard_get_participated_quiz',
           userId: <?php echo get_current_user_id(); ?>
       };
        
       jQuery.post(ajaxurl, d, function(resp){
           dt = JSON.parse(resp.data);
           pending = dt.pending;
           validated = dt.validated;
           inprogress = dt.inprogress;
           
           renderValidated(validated);
           renderInprogress(inprogress);
           renderPending(pending);
           displayContents();
       });
    });
    
    function renderValidated(validated) {
        content = '';
        for(i=0; i < validated.length; i++) {
            content += '<div class="col-sm-4">';
            content += '<div class="card text-center card-outline-'+classesColor[i%classesColor.length]+'">';
            content += '<div class="card-header">'+ validated[i].language +'</div>';
            content += '<div class="card-block">';
            content += '<canvas id="'+validated[i].language+'" width="150" height="150"></canvas>';
content += '<script type="text/javascript">';
content += '(function() {';
content += 'var requestAnimationFrame'+validated[i].language+' = window.requestAnimationFrame || window.mozRequestAnimationFrame ||';
  content += 'window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;';
content += 'window.requestAnimationFrame = requestAnimationFrame'+validated[i].language+';';
content += '})();';
content += 'var canvas'+validated[i].language+' = document.getElementById("'+validated[i].language+'");';
content += 'var context'+validated[i].language+' = canvas'+validated[i].language+'.getContext("2d");';
content += 'var x'+validated[i].language+' = canvas'+validated[i].language+'.width / 2;';
content += 'var y'+validated[i].language+' = canvas'+validated[i].language+'.height / 2;';
content += 'var radius'+validated[i].language+' = 50;';
content += 'var endPercent'+validated[i].language+' = '+(validated[i].score / 3)+';';
content += 'var curPerc'+validated[i].language+' = 0;';
content += 'var counterClockwise'+validated[i].language+' = false;';
content += 'var circ'+validated[i].language+' = Math.PI * 2;';
content += 'var quart'+validated[i].language+' = Math.PI / 2;';
content += 'context'+validated[i].language+'.lineWidth = 10;';
content += 'context'+validated[i].language+'.strokeStyle = "red";';
content += 'clr'+validated[i].language+' = "red";';
content += 'if('+validated[i].score+' <= 100) {';
content += 'clr'+validated[i].language+' = "red";';
content += '}';
content += 'else if('+validated[i].score+' <= 200) {';
content += 'clr'+validated[i].language+' = "orange";';
content += '}';
content += 'else{';
content += 'clr'+validated[i].language+' = "green";';
content += '}';
content += 'function animate'+validated[i].language+'(current'+validated[i].language+') {';
content += 'context'+validated[i].language+'.clearRect(0, 0, canvas'+validated[i].language+'.width, canvas'+validated[i].language+'.height);';
content += 'context'+validated[i].language+'.beginPath();';
content += 'context'+validated[i].language+'.shadowOffsetX = 0;';
content += 'context'+validated[i].language+'.shadowOffsetY = 0;';
content += 'context'+validated[i].language+'.shadowBlur = 0;';
content += 'context'+validated[i].language+'.shadowColor = "rgba(101, 101, 101, 0)";';
content += 'context'+validated[i].language+'.strokeStyle = "rgba(152, 152, 152, 0.13)";';
content += 'context'+validated[i].language+'.arc(x'+validated[i].language+', y'+validated[i].language+', radius'+validated[i].language+', 0, 2*Math.PI, false);';
content += 'context'+validated[i].language+'.stroke();';
content += 'context'+validated[i].language+'.shadowOffsetX = 0;';
content += 'context'+validated[i].language+'.shadowOffsetY = 0;';
content += 'context'+validated[i].language+'.shadowBlur = 10;';
content += 'context'+validated[i].language+'.shadowColor = "#656565";';
content += 'context'+validated[i].language+'.beginPath();';
content += 'context'+validated[i].language+'.strokeStyle = clr'+validated[i].language+';';
content += 'context'+validated[i].language+'.arc(x'+validated[i].language+', y'+validated[i].language+', radius'+validated[i].language+', -(quart'+validated[i].language+'), ((circ'+validated[i].language+') * current'+validated[i].language+') - quart'+validated[i].language+', false);';
content += 'context'+validated[i].language+'.stroke();';
content += 'curPerc'+validated[i].language+'++;';
content += 'context'+validated[i].language+'.beginPath();';
content += 'context'+validated[i].language+'.font = "25px Georgia";';
content += 'context'+validated[i].language+'.fillStyle = clr'+validated[i].language+';';
content += 'context'+validated[i].language+'.fillText(curPerc'+validated[i].language+'*3, 50, 80);';
content += 'context'+validated[i].language+'.fill();';
content += 'if ((curPerc'+validated[i].language+'*3) <= '+validated[i].score+') {';
content += 'requestAnimationFrame(function () {';
content += 'animate'+validated[i].language+'(curPerc'+validated[i].language+' / 100)';
content += '});';
content += '}';
content += '}';
content += '';
content += 'animate'+validated[i].language+'();';
content += '<\/script>';
            content += '</div>';
            content += '<div class="card-footer"><a href="#" class="btn btn-'+classesColor[i%classesColor.length]+'">Insights</a></div>';
            content += '</div>';
            content += '</div>';
            if(i % 3 == 0){
                content += '<br>';
            } 
        }
        if(validated.length == 0) {
            content = '<div class="col-sm-12 text-center"><h6>No completed quizes</h6></div>';
        }
        jQuery(function($){
           $("#list-validated").html(content);
        });
    }
    
    function renderInprogress(validated) {
        content = '';
        for(i=0; i < validated.length; i++) {
            content += '<div class="col-sm-12">';
            content += '<div class="card card-outline-'+classesColor[i%classesColor.length]+' card-info text-center">';
            content += '<div class="card-block">';
            content += '<div class="row">';
            content += '<div class="col-sm-4">'+validated[i].language+'</div>';
            content += '<div class="col-sm-4">'+validated[i].level+'</div>';
            content += '<div class="col-sm-4"><a href="'+quizapp+'?id='+ validated[i].quizId +'&isResume=1" class="btn btn-'+classesColor[i%classesColor.length]+'">Resume</a></div>';
            content += '</div>';
            content += '</div>';
            content += '</div>';
            content += '</div>';
        }
        if(validated.length == 0) {
            content = '<div class="col-sm-12 text-center"><h6>No inprogress quizes</h6></div>';
        }
        jQuery(function($){
           $("#list-inprogress").html(content);
        });
    }
    
    function renderPending(validated) {
        content = '';
        for(i=0; i < validated.length; i++) {
            content += '<div class="col-sm-12">';
            content += '<div class="card card-outline-'+classesColor[i%classesColor.length]+' card-info text-center">';
            content += '<div class="card-block">';
            content += '<div class="row">';
            content += '<div class="col-sm-6">'+validated[i].language+'</div>';
            content += '<div class="col-sm-6">'+validated[i].level+'</div>';
            content += '</div>';
            content += '</div>';
            content += '</div>';
            content += '</div>';
        }
        if(validated.length == 0) {
            content = '<div class="col-sm-12 text-center"><h6>No pending quizes for validation</h6></div>';
        }
        jQuery(function($){
           $("#list-pending").html(content);
        });
    }
    
    function displayContents() {
        jQuery(function($){
            $("#list-validated-title").show();
            $("#list-validated").show();
            $("#list-inprogress-title").show();
            $("#list-inprogress").show();
            $("#list-pending-title").show();
            $("#list-pending").show();
            $("#main-loader").hide();
        });
    }
</script>
<script>
var quizapp = '<?php echo get_permalink(get_page_by_path('quiz-app')); ?>';
</script>
<h5>Welcome, <?php echo wp_get_current_user()->user_firstname; ?></h5>
<div class="row">
    <div class="col-sm-2">
        <a href="?dashboard" style="text-decoration: none">
        <div class="card card-inverse card-success mb-3 text-center">
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <span>Dashboard</span>
                </blockquote>
            </div>
        </div>
        </a>
    </div>
    <div class="col-sm-2">
        <a href="?account" style="text-decoration: none">
        <div class="card card-inverse card-warning mb-3 text-center">
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <span>Account</span>
                </blockquote>
            </div>
        </div>
        </a>
    </div>
</div>
<div class=" text-center" id="main-loader"><i class="fa fa-spinner fa-spin" style="font-size:64px;color:lightgreen"></i></div>
<?php if(!isset($_GET['account'])) { ?>
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
        <div class="row" id="list-pending" style="display:none;">
            
        </div>
    </div>
</div>
<button class="btn btn-info"  style="display:none" id="insights-btn" onclick="displayContents();">Goto Dashboard</button>
<hr/>
<div class="row" id="insights-title" style="display:none">
    <div class="col-sm-12">
        <div class="card card-inverse card-success text-center">
            <div class="card-block">
                <h5 id="insights-header"></h5>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row" id="insights" style="display:none">
    <div class="col-sm-6">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Level</th>
                    <th>Score</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody id="insights-content">
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        <canvas id="insights-graph" width="500px" height="280px"></canvas>
    </div>
</div>
<br>
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
            content += '<div class="card-footer"><button class="btn btn-'+classesColor[i%classesColor.length]+'"  onClick="showInsights(\''+validated[i].language+'\');">Insights</a></div>';
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
            content += '<div class="row" style="margin-top:-10px;">';
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
            content += '<div class="row" style="margin-top:-10px;">';
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
    
    var CanvasChart = function () {
        var ctx;
        var margin = { top: 10, left: 40, right: 0, bottom: 40 };
        var chartHeight, chartWidth, yMax, xMax, data;
        var maxYValue = 0;
        var ratio = 0;
        var renderType = { lines: 'lines', points: 'points' };

        var render = function(canvasId, dataObj) {
            data = dataObj;
            getMaxDataYValue();
            var canvas = document.getElementById(canvasId);
            chartHeight = parseInt(canvas.getAttribute('height'));
            chartWidth = parseInt(canvas.getAttribute('width'));
            xMax = chartWidth - (margin.left + margin.right);
            yMax = chartHeight - (margin.top + margin.bottom);
            ratio = yMax / maxYValue;
            ctx = canvas.getContext("2d");
            renderChart();
        };

        var renderChart = function () {
            renderBackground();
            renderText();
            renderLinesAndLabels();

            //render data based upon type of renderType(s) that client supplies
3           //if (data.renderTypes == undefined || data.renderTypes == null) data.renderTypes = [renderType.lines];
            for (var i = 0; i < data.renderTypes.length; i++) {
                renderData(data.renderTypes[i]);
            }
        };

        var getMaxDataYValue = function () {
            for (var i = 0; i < data.dataPoints.length; i++) {
                if (data.dataPoints[i].y > maxYValue) maxYValue = data.dataPoints[i].y;
            }
        };

        var renderBackground = function() {
            /*var lingrad = ctx.createLinearGradient(0, margin.top, 0, yMax);
            lingrad.addColorStop(0.1, 'rgba(173, 255, 173, 0.31)');
            lingrad.addColorStop(0.5, 'rgba(253, 182, 50, 0.29)');
            lingrad.addColorStop(0.6, 'rgba(253, 182, 50, 0.29)');
            lingrad.addColorStop(0.9, 'rgba(250, 61, 61, 0.31)');*/
            ctx.fillStyle = 'white';
            ctx.fillRect(margin.left, margin.top, xMax - margin.left, yMax - margin.top);
            ctx.fillStyle = 'black';
        };

        var renderText = function() {
            var labelFont = (data.labelFont != null) ? data.labelFont : '20pt Arial';
            ctx.font = labelFont;
            ctx.textAlign = "center";

            //Title
            /*var txtSize = ctx.measureText(data.title);
            ctx.fillText(data.title, (chartWidth / 2), (margin.top / 2));*/

            //X-axis text
            txtSize = ctx.measureText(data.xLabel);
            ctx.fillText(data.xLabel, margin.left + (xMax / 2) - (txtSize.width / 2), yMax + (margin.bottom / 1.2));

            //Y-axis text
            ctx.save();
            ctx.rotate(-Math.PI / 2);
            ctx.font = labelFont;
            ctx.fillText(data.yLabel, (yMax / 2) * -1, margin.left / 4);
            ctx.restore();
        };

        var renderLinesAndLabels = function () {
            //Vertical guide lines
            var yInc = yMax / data.dataPoints.length;
            var yPos = 0;
            var yLabelInc = (maxYValue * ratio) / data.dataPoints.length;
            var xInc = getXInc();
            var xPos = margin.left;
            for (var i = 0; i < data.dataPoints.length; i++) {
                yPos += (i == 0) ? margin.top : yInc;
                //Draw horizontal lines
                drawLine(margin.left, yPos, xMax, yPos, 'rgba(232, 232, 232, 0.31)');

                //y axis labels
                ctx.font = (data.dataPointFont != null) ? data.dataPointFont : '10pt Calibri';
                var txt = Math.round(maxYValue - ((i == 0) ? 0 : yPos / ratio));
                var txtSize = ctx.measureText(txt);
                ctx.fillText(txt, margin.left - ((txtSize.width >= 14) ? txtSize.width : 10) - 7, yPos + 4);

                //x axis labels
                txt = data.dataPoints[i].x;
                txtSize = ctx.measureText(txt);
                ctx.fillText(txt, xPos, yMax + (margin.bottom / 3));
                xPos += xInc;
            }

            //Vertical line
            drawLine(margin.left, margin.top, margin.left, yMax, 'black');

            //Horizontal Line
            drawLine(margin.left, yMax, xMax, yMax, 'black');
        };

        var renderData = function(type) {
            var xInc = getXInc();
            var prevX = 0, 
                prevY = 0;

            for (var i = 0; i < data.dataPoints.length; i++) {
                var pt = data.dataPoints[i];
                var ptY = (maxYValue - pt.y) * ratio;
                if (ptY < margin.top) ptY = margin.top;
                var ptX = (i * xInc) + margin.left;

                if (i > 0 && type == renderType.lines) {
                    //Draw connecting lines
                    if(pt.y<=100) {
                        drawLine(ptX, ptY, prevX, prevY, 'red', 1);
                    }
                    else if(pt.y<=200) {
                        drawLine(ptX, ptY, prevX, prevY, 'orange', 1);
                    }
                    else {
                        drawLine(ptX, ptY, prevX, prevY, 'green', 1);
                    }
                }

                if (type == renderType.points) {
                    var radgrad = ctx.createRadialGradient(ptX, ptY, 8, ptX - 5, ptY - 5, 0);
                    radgrad.addColorStop(0, 'Blue');
                    radgrad.addColorStop(0.9, 'White');
                    ctx.beginPath();
                    ctx.fillStyle = radgrad;
                    //Render circle
                    ctx.arc(ptX, ptY, 3, 0, 2 * Math.PI, false)
                    ctx.fill();
                    //ctx.lineWidth = 1;
                    //ctx.strokeStyle = '#000';
                    //ctx.stroke();
                    //ctx.closePath();
                }

                prevX = ptX;
                prevY = ptY;
            }
        };

        var getXInc = function() {
            return Math.round(xMax / data.dataPoints.length) - 1;
        };

        var drawLine = function(startX, startY, endX, endY, strokeStyle, lineWidth) {
            if (strokeStyle != null) ctx.strokeStyle = strokeStyle;
            if (lineWidth != null) ctx.lineWidth = lineWidth;
            ctx.beginPath();
            ctx.moveTo(startX, startY);
            ctx.lineTo(endX, endY);
            ctx.stroke();
            ctx.closePath();
        };

        return {
            renderType: renderType,
            render: render
        };
    } ();

    
    var dataDef = { title: "US Population Chart",
                            xLabel: 'Completion Date', 
                            yLabel: 'Total Score',
                            labelFont: '10pt Arial', 
                            dataPointFont: '7pt Arial',
                            renderTypes: [CanvasChart.renderType.lines, CanvasChart.renderType.points],
                            dataPoints: [{x:0, y:0}]
                           };
    
    
    function showInsights(language) {
        jQuery(function($){
            $("#list-validated-title").hide();
            $("#list-validated").hide();
            $("#list-inprogress-title").hide();
            $("#list-inprogress").hide();
            $("#list-pending-title").hide();
            $("#list-pending").hide();
            $("#main-loader").show();
            
            d = {
                action: 'sgcrackit_ajax_dashboard_get_quiz_insights',
                language: language,
                userId : <?php echo get_current_user_id(); ?>
            };
            
            jQuery.post(ajaxurl, d, function(resp){
                data = JSON.parse(resp.data);
                $("#insights-header").html(language);
                
                content = '';
                
                for(i=0; i<data.length; i++) {
                    content += '<tr>';
                    content += '<td>'+data[i].date+'</td>';
                    content += '<td>'+data[i].level+'</td>';
                    content += '<td>'+data[i].score+'</td>';
                    content += '<td>'+data[i].total_score+'</td>';
                    content += '<\tr>';
                    dataDef.dataPoints.push({ x: data[i].date, y: data[i].total_score })
                }
                
                $("#insights-content").html(content);
                
            CanvasChart.render('insights-graph', dataDef);
            
                $("#main-loader").hide();
                $("#insights").show();
                $("#insights-title").show();
                $("#insights-btn").show();
            });
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
            $("#insights").hide();
            $("#insights-title").hide();
            $("#insights-btn").hide();
        });
    }
    
</script>
<?php } ?>
<?php if(isset($_GET['account'])) { ?>
<div class="row" id="membership-title" style="display:none">
    <div class="col-sm-12">
        <div class="card card-inverse card-info text-center">
            <div class="card-block">
                <h5>Membership Plan</h5>
            </div>
        </div>
    </div>
</div>
<div class="row" id="membership-content" style="display:none">
    <div class="col-sm-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Membership Level</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="membership-level">
            </tbody>
        </table>
    </div>
</div>
<div class="row" id="orders-title" style="display:none">
    <div class="col-sm-12">
        <div class="card card-inverse card-warning text-center">
            <div class="card-block">
                <h5>Orders History</h5>
            </div>
        </div>
    </div>
</div>
<div class="row" id="orders-content" style="display:none">
    <div class="col-sm-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Info</th>
                    <th>Transaction Id</th>
                    <th>Date</th>
                    <th>Amoutn in Rupees</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="orders-details">
            </tbody>
        </table>
    </div>
</div>
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var userId = '<?php echo get_current_user_id(); ?>';
    
    jQuery(function($){
       d = {
           action: 'sgcrackit_ajax_dashboard_account_details',
           userId: userId
       };
        
        jQuery.post(ajaxurl, d, function(resp){
           data = JSON.parse(resp.data);
            content = '';
            content += '<tr>';
            content += '<td>'+data.membership[0].level+'</td>';
            content += '<td>'+data.membership[0].startdate+'</td>';
            content += '<td>'+data.membership[0].enddate+'</td>';
            content += '<td>'+data.membership[0].status+'</td>';
            content += '</tr>';
            $('#membership-level').html(content);
            
            content = '';
            for(i=0; i<data.orders.length; i++){
                content += '<tr>';
                content += '<td>'+(i+1)+'</td>';
                content += '<td>'+data.orders[i].info+'</td>';
                content += '<td>'+data.orders[i].txnid+'</td>';
                content += '<td>'+data.orders[i].date+'</td>';
                content += '<td>'+data.orders[i].amount+'</td>';
                content += '<td>'+data.orders[i].status+'</td>';
                content += '</tr>';
            }
            if(data.orders.length == 0) {
                content = '<tr><td class="text-center" colspan=6>No previous orders</td></tr>';
            }
            $('#orders-details').html(content);
            $('#main-loader').hide();
            $('#membership-title').show();
            $('#membership-content').show();
            
            $('#orders-title').show();
            $('#orders-content').show();
        });
        
    });
</script>
<?php } ?>
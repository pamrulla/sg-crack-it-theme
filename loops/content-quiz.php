<?php
/*
The Single Posts Loop
=====================
*/

global $memberplanTable;
global $scoreTable;    
global $wpdb;

$user = '';
$isLoggedIn = false;
if(is_user_logged_in()) {
    $user = wp_get_current_user();
    $isLoggedIn = true;
}

$userMemberShip = 0;
$isExpired = false;

if($isLoggedIn) {
    $sqlSelect = "SELECT * FROM $memberplanTable WHERE userId = $user->ID";
    $result = $wpdb->get_results($sqlSelect);

    if(count($result) == 0) {
        $userMemberShip = 0;
    }
    else {
        $userMemberShip = $result[0]->memberplan;
        $today = date("Y-m-d");
        $startDate = strtotime($result[0]->startDate);
        $str = '';
        if($result[0]->option == 1){
            $str = "+1 month";
        }
        else if($result[0]->option == 2){
            $str = "+6 month";
        }
        else if($result[0]->option == 3){
            $str = "+12 month";
        }
        $expiryDate = date( "Y-m-d",strtotime($str, $startDate));
        if($expiryDate < $today) {
           $isExpired = true; 
        }
    }
}

$isPassed = false;
$messageToStart = '';
$targetId = 0;

$t = get_the_terms(get_the_ID(), 'language')[0]->name;
$l = get_the_terms(get_the_ID(), 'level')[0]->name;

if($l == 'Beginner')
{
    $isPassed = true;
}
else
{
    $posts_array = get_posts(
        array(
            'posts_per_page' => -1,
            'post_type' => 'quiz',
            'tax_query' => array(
                array(
                    'taxonomy' => 'language',
                    'field' => 'name',
                    'terms' => $t,
                ),
                array(
                    'taxonomy' => 'level',
                    'field' => 'name',
                    'terms' => 'Beginner',
                )
            )
        )
    );
    $i1 = $posts_array[0]->ID;
    $sql = "SELECT MAX(score) as sc FROM $scoreTable WHERE userId = $user->ID and quizId = $i1 GROUP BY quizId";
    $res = $wpdb->get_results($sql);
    
    if(count($res) == 0) {
        $isPassed = false;
        $messageToStart = ' Pass Beginner Level';
        $targetId = $posts_array[0]->ID;
    }
    else if($res[0]->sc < 80){
        $isPassed = false;
        $messageToStart = ' Pass Beginner Level';
        $targetId = $posts_array[0]->ID;
    }
    else {
        $isPassed = true;
    }
    
    
    if($l == 'Advanced' && $isPassed) {
        $posts_array1 = get_posts(
            array(
                'posts_per_page' => -1,
                'post_type' => 'quiz',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'language',
                        'field' => 'name',
                        'terms' => $t,
                    ),
                    array(
                        'taxonomy' => 'level',
                        'field' => 'name',
                        'terms' => 'Intermediate',
                    )
                )
            )
        );
        
        $i2 = $posts_array1[0]->ID;
        $sql1 = "SELECT MAX(score) as sc FROM $scoreTable WHERE userId = $user->ID and quizId = $i2 GROUP BY quizId";
        $res1 = $wpdb->get_results($sql1);

        if(count($res1) == 0) {
            $isPassed = false;
            $messageToStart = ' Pass Intermediate Level';
            $targetId = $posts_array1[0]->ID;
        }
        else if($res1[0]->sc < 80){
            $isPassed = false;
            $messageToStart = ' Pass Intermediate Level';
            $targetId = $posts_array1[0]->ID;
        }
        else {
            $isPassed = true;
        }
    }
}
?> 

<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <?php $id = get_the_ID(); $title = get_the_title(); $isResume = false; ?>
    <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
        <section>
            <div class="card-group">
              <div class="card">
                <div class="card-block">
                  <h4 class="card-title"><?php the_title()?></h4>
                  <p class="card-text"><?php the_content()?></p>
                </div>
                <div class="card-footer text-right">
                    <?php 
                        if($isLoggedIn){ 
                            global $progressTable;
                            global $wpdb;
                            $sql = "SELECT COUNT(*) as c FROM $progressTable WHERE userId = ". get_current_user_id() ." AND quizId = $id and isCompleted = 0 ";
                            $result = $wpdb->get_results($sql);
                            $isResume = ($result[0]->c != 0);
                            
                            $terms = get_the_terms(get_the_ID(), 'level');
                            $finalValid = false;
                            if($terms[0]->name == "Beginner") {
                                $finalValid = true;
                            }
                            else if($terms[0]->name == "Intermediate") {
                                $finalValid = !$isExpired && (1 <= $userMemberShip);
                            }
                            else if($terms[0]->name == "Advanced") {
                                $finalValid = !$isExpired && (2 <= $userMemberShip);
                            }
                            if($isResume)
                            { ?>
                    <a href="<?php echo get_permalink(get_page_by_path('quiz-app')->ID) .'?id='.$id.'&isResume=1'; ?>" class="btn btn-primary">Resume</a>
                    <?php   } else {  ?>
                        <?php   if($finalValid) { ?>
                    <a href="<?php if($isPassed){echo get_permalink(get_page_by_path('quiz-app')->ID) .'?id='.$id.'&isResume=0';} else { echo get_permalink($targetId); } ?>" class="btn btn-primary"><?php if($isPassed){ echo 'Start'; }else{ echo '<i class="fa fa-lock fa-2x" aria-hidden="true"></i>'.$messageToStart; }?></a>
                        <?php } else { ?>
                            <a href="<?php echo get_permalink(get_page_by_path('membership-plans')->ID) ?>" class="btn btn-primary">Buy @ Rs. <?php echo ($terms[0]->name == "Intermediate") ? '300/month' : '500/month'; ?></a>
                        <?php } ?>
                    <?php } } else { ?>
                    <a href="#" class="btn btn-primary">Sign In/Sign Up</a>`
                    <?php } ?>
                </div>
              </div>
              <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-8">
                            <canvas id="score-draw" width="250" height="250"></canvas>
                        </div>
                        
                        <div class="col-sm-4">
                            <br>
                            <small style="color:red"><strong>Beginner 0-100</strong></small>
                            <br><br><br>
                            <small style="color:orange"><strong>Intermediate 101-200</strong></small>
                            <br><br><br>
                            <small style="color:green"><strong>Advanced 201-300</strong></small>
                        </div>
                    </div>
                </div>
              </div>
            <script type="text/javascript">
                  // requestAnimationFrame Shim
                (function() {
                    var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
                                  window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
                    window.requestAnimationFrame = requestAnimationFrame;
                })();

                var canvas = document.getElementById('score-draw');
                var context = canvas.getContext('2d');
                var x = canvas.width / 2;
                var y = canvas.height / 2;
                var radius = 100;
                var endPercent = 60;
                var curPerc = 0;
                var counterClockwise = false;
                var circ = Math.PI * 2;
                var quart = Math.PI / 2;
                
                
                context.lineWidth = 30;
                context.strokeStyle = 'red';
                
                clr = "red";
                if(endPercent <= 33) {
                    clr = "red";
                }
                else if(endPercent <= 66) {
                    clr = "orange";
                }
                else{
                    clr = "green";
                }

                function animate(current) {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.beginPath();
                    context.shadowOffsetX = 0;
                    context.shadowOffsetY = 0;
                    context.shadowBlur = 0;
                    context.shadowColor = 'rgba(101, 101, 101, 0)';
                    context.strokeStyle = 'rgba(152, 152, 152, 0.13)';
                    context.arc(x, y, radius, 0, 2*Math.PI, false);
                    context.stroke();
                    context.shadowOffsetX = 0;
                    context.shadowOffsetY = 0;
                    context.shadowBlur = 10;
                    context.shadowColor = '#656565';
                    context.beginPath();
                    context.strokeStyle = clr;
                    context.arc(x, y, radius, -(quart), ((circ) * current) - quart, false);
                    context.stroke();
                    context.beginPath();
                    context.font = "30px Georgia";
                    context.fillStyle = clr;
                    context.fillText(curPerc*3, 100, 125);
                    context.fill();
                    curPerc++;
                    if (curPerc <= endPercent) {
                        requestAnimationFrame(function () {
                            animate(curPerc / 100)
                        });
                    }
                }

                animate();
            </script>
        </section>
    </article>
<?php endwhile; ?>
<?php else: ?>
<?php wp_redirect(get_bloginfo('url').'/404', 404); exit; ?>
<?php endif; ?>

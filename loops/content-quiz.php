<?php
/*
The Single Posts Loop
=====================
*/
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
                  <small class="text-muted">Number of students used it</small>
                    <?php 
                        if(is_user_logged_in()){ 
                            global $progressTable;
                            global $wpdb;
                            $sql = "SELECT COUNT(*) as c FROM $progressTable WHERE userId = ". get_current_user_id() ." AND quizId = $id and isCompleted = 0 ";
                            $result = $wpdb->get_results($sql);
                            $isResume = ($result[0]->c != 0);
                            
                            if($isResume)
                            { ?>
                    <a href="<?php echo get_permalink(get_page_by_path('quiz-app')->ID) .'?id='.$id.'&isResume=1'; ?>" class="btn btn-primary">Resume</a>
                    <?php   } else {  ?>
                    <a href="<?php echo get_permalink(get_page_by_path('quiz-app')->ID) .'?id='.$id.'&isResume=0'; ?>" class="btn btn-primary">Start</a>
                    <?php } } else { ?>
                    <a href="#" class="btn btn-primary">Sign In/Sign Up</a>
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

<?php
/*
The Single Posts Loop
=====================
*/
?> 

<?php if(have_posts()): while(have_posts()): the_post(); ?>
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
                    <a href="<?php echo get_permalink(get_page_by_path('quiz-app')->ID);/*.'?id='.the_ID().'&name='.the_title();*/ ?>" class="btn btn-primary">Start</a>
                </div>
              </div>
              <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-8 offset-sm-1">
                            <div id="chart_div" style="width: 400px; height: 400px;"></div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-sm-4"><small style="color:red"><strong>Beginner 0-100</strong></small></div>
                        <div class="col-sm-4"><small style="color:orange"><strong>Intermediate 101-200</strong></small></div>
                        <div class="col-sm-4"><small style="color:green"><strong>Advanced 201-300</strong></small></div>
                    </div>
                </div>
              </div>
            <script type="text/javascript">
                  function LoadGoogle()
                    {
                        if(typeof google != 'undefined')
                        {
                              google.charts.load('current', {'packages':['gauge']});
                              google.charts.setOnLoadCallback(drawChart);

                              function drawChart() {

                                var data = google.visualization.arrayToDataTable([
                                  ['Label', 'Value'],
                                  ['Score', 0],
                                  
                                ]);

                                var options = {
                                  width: 400, height: 400,
                                  animation:{
                                    duration: 800,
                                    easing: 'inAndOut'
                                  },
                                  redFrom: 0, redTo: 100,
                                  yellowFrom:101, yellowTo: 200,
                                  greenFrom: 201, greenTo: 300,
                                  max: 300,
                                  minorTicks: 0
                                };

                                var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                                chart.draw(data,  options);

                                setInterval(function() {
                                  var data = google.visualization.arrayToDataTable([
                                    ['Label', 'Value'],
                                    ['Score', 200],
                                  ]);
                                    chart.draw(data, options);
                                }, 300);
                                
                              }
                        }
                        else
                        {
                            setTimeout(LoadGoogle, 30);
                        }
                    }

                    LoadGoogle();
                
            </script>
        </section>
    </article>
<?php endwhile; ?>
<?php else: ?>
<?php wp_redirect(get_bloginfo('url').'/404', 404); exit; ?>
<?php endif; ?>

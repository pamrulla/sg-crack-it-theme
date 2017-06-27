<?php
/*
The Single Posts Loop
=====================
*/
?> 

<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
        <header>
            <h2><?php the_title()?></h2>
        </header>
        <section>
            <!-- <?php the_post_thumbnail(); ?>
            <?php the_content()?>
            <?php wp_link_pages(); ?> -->
            <div id="chart_div" style="width: 400px; height: 400px;"></div>
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

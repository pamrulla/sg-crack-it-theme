<?php
/*
The Single Posts Loop
=====================
*/

$sgQuizes = array();
?> 

<?php if(have_posts()): while(have_posts()): the_post(); ?>
        <?php 
            $sgterm = get_the_terms(get_the_ID(), 'level'); 
            if($sgterm <> null && !is_wp_error($sgterm))
            {
                if($sgterm[0]->name == "Beginner"){
                    $sgQuizes[0] = $post;
                }
                else if($sgterm[0]->name == "Intermediate"){
                    $sgQuizes[1] = $post;
                }
                else if($sgterm[0]->name == "Advanced"){
                    $sgQuizes[2] = $post;
                }
            }
        ?>
<?php endwhile; ?>
<div class="card-deck">
    <?php for($i = 0; $i < count($sgQuizes); $i++)
        { 
    ?>
            <div class="card <?php if($i == 0) { echo 'card-outline-danger'; } else if($i == 1) { echo 'card-outline-warning'; } else if($i == 2) { echo 'card-outline-success'; } ?> ">
                <div class="card-block">
                    <h4 class="card-title <?php if($i == 0) { echo 'text-danger'; } else if($i == 1) { echo 'text-warning'; } else if($i == 2) { echo 'text-success'; } ?>"><?php echo $sgQuizes[$i]->post_title; ?></h4>
                    <p class="card-text"><?php echo $sgQuizes[$i]->post_content; ?></p>
                </div>
                <div class="card-footer text-right">
                    <a href="<?php echo get_permalink($sgQuizes[$i]->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Start</a>
                </div>
            </div>
    <?php
        }
    ?>
</div>
<?php else: ?>
<?php wp_redirect(get_bloginfo('url').'/404', 404); exit; ?>
<?php endif; ?>

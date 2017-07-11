<?php
/*
The Single Posts Loop
=====================
*/

$sgQuizes = array();

global $memberplanTable;
    
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
                    <?php if($isLoggedIn) { 
                        if($i == 0) { ?>
                    <a href="<?php echo get_permalink($sgQuizes[$i]->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Start</a>
                    <?php } else { ?>
                        <?php if($userMemberShip == 0) { ?>
                        <a href="<?php echo get_permalink(get_page_by_path('membership-plans')->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Buy @ Rs. <?php echo ($i == 1) ? '300/month' : '500/month'; ?></a>
                        <?php } else { ?>
                            <?php if($isExpired) { ?>
                                <a href="<?php echo get_permalink(get_page_by_path('membership-plans')->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Buy @ Rs. <?php echo ($i == 1) ? '300/month' : '500/month'; ?></a>
                            <?php } else { ?>
                                <?php if($i <= $userMemberShip) { ?>
                                    <a href="<?php echo get_permalink($sgQuizes[$i]->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Start</a>
                                <?php } else { ?>
                                    <a href="<?php echo get_permalink(get_page_by_path('membership-plans')->ID) ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">Buy @ Rs. <?php echo ($i == 1) ? '300/month' : '500/month'; ?></a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <?php } else { ?>
                    <a href="<?php echo get_permalink(get_page_by_path('login')->ID); ?>" class="btn <?php if($i == 0) { echo 'btn-danger'; } else if($i == 1) { echo 'btn-warning'; } else if($i == 2) { echo 'btn-success'; } ?>">SignIn/SignUp</a>
                    <?php } ?>
                </div>
            </div>
    <?php
        }
    ?>
</div>
<?php else: ?>
<?php wp_redirect(get_bloginfo('url').'/404', 404); exit; ?>
<?php endif; ?>

<?php
global $wpdb, $user_ID;
$errors = array();
$success = false;
{
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

        if(!isset($_REQUEST['firstname'])) {
            $errors['firstname'] = "First name should not be emtpy";
        }
        
        if(!isset($_REQUEST['lastname'])) {
            $errors['firstname'] = "Last name should not be emtpy";
        }
        
		// Check email address is present and valid
        if(isset($_REQUEST['email'])) {
            $email = esc_sql($_REQUEST['email']);
            if( !is_email( $email ) ) { 
                $errors['email'] = "Please enter a valid email";
            }
        }
        else {
            $errors['email'] = "Email can not be empty";
        }
        
        if(isset($_REQUEST['message'])) {
            $msg = esc_sql($_REQUEST['message']);
        }
        else {
            $errors['message'] = "Message can not be empty";
        }
        
		if(isset($_REQUEST['g-recaptcha-response']) && $_REQUEST['g-recaptcha-response'] <> '') {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = ['secret'   => '6Le_dBsTAAAAAGilvajDTCQGwYE4fPG3UmZpxF8t',
                     'response' => $_REQUEST['g-recaptcha-response'],
                     'remoteip' => $_SERVER['REMOTE_ADDR']];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data) 
                ]
            ];

            //$ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result)->success;
            //echo curl_exec($ch);
            //echo 'Curl error: ' . curl_error($ch);
            //print_r(curl_getinfo($ch));
            //$result = json_decode(curl_exec($ch))->success;
            if($result == false){
                $errors['g-recaptcha-response'] = "Captcha verification is failed.";
            }
            //curl_close($ch);
        }
        else{
            $errors['g-recaptcha-response'] = "You must verify using captcha";
        }
            
        if(0 === count($errors)) {
            add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    
			$message = getMailHeader();
            $message .= getMainSenderName($_REQUEST['firstname'] . ' ' . $_REQUEST['lastname']);

            $message .= '<p>'. $_REQUEST['message'] .'</p>';
            $message .= '<p>Email:'. $email . '</p>';
            $message .= getMailFooter();

            wp_mail('support@smartgnan.com', 'SmartGnan CrackIt - Contact Us - Message', $message);

	        remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
            
            $success = true;
		}

	}
} ?>
<?php get_header(); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
.back-svg {
    width: 100%;
    height: 100%;
    z-index: 1;
    position: relative;
    background-color: white;
    overflow: hidden;
    top: 50px;
    opacity: 0.1;
    pointer-events: none;
}
    
#human1 {
    width: 200px;
    height: 310px;
    position: absolute;
    right: 33.5%;
    bottom: 40%;
    z-index: 5;
    pointer-events: none;
}

#human {
    width: 160px;
    height: 210px;
    position: absolute;
    right: 20%;
    bottom: 33%;
    z-index: 5;
    pointer-events: none;
}
    
#chel1 {
    width: 160px;
    height: 210px;
    position: absolute;
    right: 50%;
    bottom: 30%;
    z-index: 5;
    pointer-events: none;
}
    
    
#chel2 {
    width: 160px;
    height: 210px;
    position: absolute;
    right: 21%;
    bottom: 33%;
    z-index: 5;
    pointer-events: none;
}
    
#chel3 {
    width: 220px;
    height: 360px;
    position: absolute;
    right: 18%;
    bottom: 35%;
    z-index: 5;
    pointer-events: none;
}

.contact-us{
    width: 50%;
    height: 50%;
    position: absolute;
    z-index: 100;
}
    
.item-data {
    width: 20%;
    height: 20%;
    position: absolute;
    z-index: 100;
    pointer-events: none;
}

.floating {  
    animation-name: floating;
    animation-duration: 3s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
    margin-left: 30px;
    margin-top: 5px;
}

@keyframes floating {
    from { transform: translate(0,  0px); }
    65%  { transform: translate(0, 15px); }
    to   { transform: translate(0, -0px); }    
}

 .mission {
    width: 40%;
    height: 40%;
    position: absolute;
    z-index: 100;
    pointer-events: none;
}
    
 .mission-data {
    width: 50%;
    height: 50%;
    position: absolute;
    z-index: 100;
    pointer-events: none;
}

.team-images {
    width: 128px;
    height: 128px;
    z-index: 100;
    position: absolute;
}

 .team-images-text {
    z-index: 100;
    position: absolute;
}
    
.scroll-downs {
  position: absolute;
  bottom: 10%;
  left: 50%;
  z-index: 1000;
  width :34px;
  height: 55px;
}
.mousey {
  width: 3px;
  padding: 6px 15px;
  height: 50px;
  border: 2px solid #000000;
  border-radius: 20px;
  opacity: 1;
}
.scroller {
  width: 3px;
  height: 10px;
  border-radius: 25%;
  background-color: #000000;
  animation-name: scroll;
  animation-duration: 2.2s;
  animation-timing-function: cubic-bezier(.15,.41,.69,.94);
  animation-iteration-count: infinite;
}
@keyframes scroll {
  0% { opacity: 0; }
  10% { transform: translateY(0); opacity: 1; }
  100% { transform: translateY(15px); opacity: 0;}
}
    
</style>
    <div id="pagepiling"  style="width: 100%; min-height:100%">
        <div class="section" id="what-you-want">
            <div class="back-svg">
                <object id="svg1" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/back-svg1.svg" type="image/svg+xml" class="block-svg"></object>
            </div>
            <object id="chel1" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/chel1.svg" type="image/svg+xml" class="block-svg"></object>
            <object id="page1-1" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page11.svg" type="image/svg+xml" class="item-data floating" style="right: 70%; bottom: 30%;"></object>
            <object id="page1-2" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page12.svg" type="image/svg+xml" class="item-data floating" style="right: 65%; bottom: 50%;"></object>
            <object id="page1-3" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page13.svg" type="image/svg+xml" class="item-data floating" style="right: 56%; bottom: 70%;"></object>
            <object id="page1-4" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page14.svg" type="image/svg+xml" class="item-data floating" style="right: 34%; bottom: 70%;"></object>
            <object id="page1-5" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page15.svg" type="image/svg+xml" class="item-data floating" style="right: 25%; bottom: 50%;"></object>
            <object id="page1-6" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page16.svg" type="image/svg+xml" class="item-data floating" style="right: 20%; bottom: 30%;"></object>
            <?php if(!is_user_logged_in()) { ?>
            <a href="<?php echo get_permalink(get_page_by_path('register')->ID); ?>" class="btn btn-primary" style="position: absolute; left:45%; bottom: 20%;">Join Us for Free</a>
            <?php } ?>
            
            <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>
        </div>
        <div class="section" id="what-you-get">
            <div class="back-svg">
                <object id="svg2" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/back-svg2.svg" type="image/svg+xml" class="block-svg"></object>
            </div>
            <object id="chel2" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/chel2.svg" type="image/svg+xml" class="block-svg"></object>
            <object id="page2-1" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page21.svg" type="image/svg+xml" class="item-data floating" style="right: 65%; bottom: 30%;"></object>
            <object id="page2-2" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page22.svg" type="image/svg+xml" class="item-data floating" style="right: 60%; bottom: 50%;"></object>
            <object id="page2-3" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page23.svg" type="image/svg+xml" class="item-data floating" style="right: 55%; bottom: 70%;"></object>
            <object id="page2-4" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page24.svg" type="image/svg+xml" class="item-data floating" style="right: 30%; bottom: 70%;"></object>
            <object id="page2-5" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page25.svg" type="image/svg+xml" class="item-data floating" style="right: 35%; bottom: 50%;"></object>
            <object id="page2-6" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/page26.svg" type="image/svg+xml" class="item-data floating" style="right: 40%; bottom: 30%;"></object>
            <?php if(!is_user_logged_in()) { ?>
            <a href="<?php echo get_permalink(get_page_by_path('register')->ID); ?>" class="btn btn-info" style="position: absolute; left:45%; bottom: 20%;">Join Us for Free</a>
            <?php } ?>
            
            <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>
        </div>
        <div class="section" id="our-mission">
            <div class="back-svg">
                <object id="svg3" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/back-svg11.svg" type="image/svg+xml" class="block-svg"></object>
            </div>
            
            <object id="human1" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/human1.svg" type="image/svg+xml" class="block-svg"></object>
            <object id="chel3" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/chel3.svg" type="image/svg+xml" class="block-svg"></object>
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/mission.gif" id="mission" class="mission" style="left: 5%; top: 10%;"/>
            <!--<object id="mission" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/mission.svg" type="image/svg+xml" class="mission" style="left: 5%; top: 1%;"></object>-->
            <object id="mission-data" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/missiondata.svg" type="image/svg+xml" class="mission-data" style="left: 1%; top: 20%;"></object>
            <?php if(!is_user_logged_in()) { ?>
            <a href="<?php echo get_permalink(get_page_by_path('register')->ID); ?>" class="btn btn-success" style="position: absolute; left:45%; bottom: 20%;">Join Us for Free</a>
            <?php } ?>
            
            <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>
        </div>
        <div class="section" id="our-team">
            <div class="back-svg">
                <object id="svg4" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/back-svg3.svg" type="image/svg+xml" class="block-svg"></object>
            </div>
            <h1 style="position:absolute; z-index: 100; top: 0%; right:50%;">Our Team</h1>
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/nikku.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 10%; right:80%;"/>
            <span id="team1-text" class="team-images-text floating text-center" style="top: 30%; right:80%;"><strong>Pathan Nihal Khan</strong> <br/> Insipiration</span>
            
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/sona.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 10%; right:50%;"/>
            <span id="team1-text" class="team-images-text floating text-center" style="top: 30%; right:50%;"><strong>Sravani Kumari</strong> <br/> Web Technologies</span>
            
            
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/anil.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 10%; right:20%;"/>
            <span id="team1-text" class="team-images-text floating text-center" style="top: 30%; right:20%;"><strong>Ch Anil Kumar</strong> <br/> JAVA and Webservices</span>
            
            
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/reddy.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 45%; right:80%;"/>
            <span id="team1-text" class="team-images-text floating text-center" style="top: 65%; right:78%;"><strong>K Ganghadhar Reddy</strong> <br/> Python and its Frameworks</span>
            
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/nagendra.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 45%; right:50%;"/>
            <span id="team1-text" class="team-images-text floating text-center" style="top: 65%; right:46%;"><strong>P Nagendra Prasad</strong> <br/> Bigdata and Mobile Technologies</span>
            
            
            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/ammu.jpg" id="team1" class="team-images floating rounded-circle img-thumbnail" style="top: 45%; right:20%;"/>
            <span id="team1-text" class="team-images-text floating  text-center" style="top: 65%; right:19%;"><strong>Patan Amrulla Khan</strong> <br/> C, C++ and C#</span>
            <?php if(!is_user_logged_in()) { ?>
            <a href="<?php echo get_permalink(get_page_by_path('register')->ID); ?>" class="btn btn-warning" style="position: absolute; left:45%; bottom: 20%;">Join Us for Free</a>
            <?php } ?>  
            
            <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>          
        </div>
        <div class="section" id="contact-us">
            <div class="back-svg">
                <object id="svg5" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/back-svg2.svg" type="image/svg+xml" class="block-svg"></object>
            </div>
            <object id="human" data="<?php echo get_bloginfo('stylesheet_directory'); ?>/theme/images/human.svg" type="image/svg+xml" class="block-svg" style="width: 260px; height:320px"></object>
 <div class="contact-us" id="contact-us" style="left: 20%; top: 10%">
    <div class="row1">
        <div class="form-signin card card-outline-success" style="background-color:white; min-width: 70%">
            <div class="card-header text-center">Contact Us</div>
            <div class="card-block">
                <?php if( 0 <> count($errors) ) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach( $errors as $err ) { ?>
                    <p></p><strong><?php echo $err; ?></strong></p>
                    <?php } ?>
                </div>
                <?php } else if($success == true){ ?>
                <div class="alert alert-success" role="alert">
                    <strong>Thank you for contacting us.</strong>
                </div>
                <?php } ?>
                <form id="login" name="form" action="" method="post">
                    <div class="form-group">
                        <input class="form-control" id="firstname" type="text" placeholder="First Name" name="firstname">
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="lastname" type="text" placeholder="Last Name" name="lastname">
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="username" type="text" placeholder="Email" name="email">
                    </div>
                    <div class="form-group">
                        <textarea  class="form-control" id="meesage" placeholder="Enter Your Message" name="message"></textarea>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6Le_dBsTAAAAAPETzwqtS6UAgb1P9iQqk3IX4Dp5"></div>
                    <div class="form-group">
                        <input class="btn btn-primary btn-lg btn-block" id="submit" type="submit" name="submit" value="Send">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            
            <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>
           
        </div>
    </div>
<script>
    var myVivus = new Array(5);
    jQuery(function($){
        $(document).ready(function() {
           $("#chel1").hide();
            $("#chel2").hide();
            $("#human1").hide();
            $("#human").hide();
            $("#chel3").hide();
            $("#page1-1").hide();
            $("#page1-1").css("animation-delay", Math.random()+"s");
            $("#page1-2").hide();
            $("#page1-2").css("animation-delay", Math.random()+"s");
            $("#page1-3").hide();
            $("#page1-3").css("animation-delay", Math.random()+"s");
            $("#page1-4").hide();
            $("#page1-4").css("animation-delay", Math.random()+"s");
            $("#page1-5").hide();
            $("#page1-5").css("animation-delay", Math.random()+"s");
            $("#page1-6").hide();
            $("#page1-6").css("animation-delay", Math.random()+"s");
            $("#page2-1").hide();
            $("#page2-1").css("animation-delay", Math.random()+"s");
            $("#page2-2").hide();
            $("#page2-2").css("animation-delay", Math.random()+"s");
            $("#page2-3").hide();
            $("#page2-3").css("animation-delay", Math.random()+"s");
            $("#page2-4").hide();
            $("#page2-4").css("animation-delay", Math.random()+"s");
            $("#page2-5").hide();
            $("#page2-5").css("animation-delay", Math.random()+"s");
            $("#page2-6").hide();
            $("#page2-6").css("animation-delay", Math.random()+"s");
            $("#mission").hide();
            $("#mission-data").hide();
            $("#contact-us").hide();
	       $('#pagepiling').pagepiling({
               direction: 'horizontal',
               sectionsColor: ['white', 'white', 'white', 'white', 'white'],
               afterRender: function(){
                   myVivus[0] = new Vivus('svg1', {duration: 100}, function(){
                       startPage(1, -1);
                   });
                   myVivus[1] = new Vivus('svg2', {duration: 100, start: 'manual'});
                   myVivus[2] = new Vivus('svg3', {duration: 100, start: 'manual'});
                   myVivus[3] = new Vivus('svg4', {duration: 100, start: 'manual'});
                   myVivus[4] = new Vivus('svg5', {duration: 10, start: 'manual'});
               },
               onLeave: function(index, nextIndex, direction){
                   myVivus[index-1].reset();
                   myVivus[nextIndex-1].reset();
                   myVivus[nextIndex-1].play(function(){
                       startPage(nextIndex, index);
                   });
               }
           });
           $.fn.pagepiling.setMouseWheelScrolling(true);
           //addTouchHandler();
            
        });
    });
    
    function startPage(index, prev) {
        jQuery(function($){
            index = index - 1;
            prevPage(prev);
            if(index == 0) {
                $("#chel1").show();
                new Vivus('page1-1', {duration: 100, selfDestroy: true});
                new Vivus('page1-2', {duration: 100, selfDestroy: true});
                new Vivus('page1-3', {duration: 100, selfDestroy: true});
                new Vivus('page1-4', {duration: 100, selfDestroy: true});
                new Vivus('page1-5', {duration: 100, selfDestroy: true});
                new Vivus('page1-6', {duration: 100, selfDestroy: true});
                $("#page1-1").show();
                $("#page1-2").show();
                $("#page1-3").show();
                $("#page1-4").show();
                $("#page1-5").show();
                $("#page1-6").show();
            }
            else if(index == 1) {
                $("#chel2").show();
                new Vivus('page2-1', {duration: 100, selfDestroy: true});
                new Vivus('page2-2', {duration: 100, selfDestroy: true});
                new Vivus('page2-3', {duration: 100, selfDestroy: true});
                new Vivus('page2-4', {duration: 100, selfDestroy: true});
                new Vivus('page2-5', {duration: 100, selfDestroy: true});
                new Vivus('page2-6', {duration: 100, selfDestroy: true});
                $("#page2-1").show();
                $("#page2-2").show();
                $("#page2-3").show();
                $("#page2-4").show();
                $("#page2-5").show();
                $("#page2-6").show();
            }
            else if(index == 2) {
                $("#human1").show();
                $("#chel3").show();
                //new Vivus('mission', {duration: 100, selfDestroy: true});
                //new Vivus('mission-data', {duration: 100, selfDestroy: true});
                $("#mission").show();
                $("#mission-data").show();
            }
            else if(index == 3) {
                
            }
            else if(index == 4) {
                $("#human").show();
                $("#contact-us").show();
            }
        });
    }
    
    function prevPage(index) {
        jQuery(function($){
            if(index > 0) {
                index = index - 1;

                if(index == 0) {
                    $("#chel1").hide();
                    $("#page1-1").hide();
                    $("#page1-2").hide();
                    $("#page1-3").hide();
                    $("#page1-4").hide();
                    $("#page1-5").hide();
                    $("#page1-6").hide();
                }
                else if(index == 1) {
                    $("#chel2").hide();
                    $("#page2-1").hide();
                    $("#page2-2").hide();
                    $("#page2-3").hide();
                    $("#page2-4").hide();
                    $("#page2-5").hide();
                    $("#page2-6").hide();
                }
                else if(index == 2) {
                    $("#human1").hide();
                    $("#chel3").hide();
                    $("#mission").hide();
                    $("#mission-data").hide();
                }
                else if(index == 3) {

                }
                else if(index == 4) {
                    $("#human").hide();
                    $("#contact-us").hide();
                }
            }
        });
    }
</script>

<?php get_footer(); ?>

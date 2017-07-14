<?php
/*
    Template Name: Register
*/
?>

<?php
global $wpdb, $user_ID;
$errors = array();
//Check whether the user is already logged in
if ($user_ID) {

	// They're already logged in, so we bounce them back to the homepage.

	header( 'Location:' . home_url() );

} else {

	

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
            } elseif( email_exists( $email ) ) {
                $errors['email'] = "This email address is already in use";
            }
        }
        else {
            $errors['email'] = "Email can not be empty";
        }

		if(isset($_REQUEST['password']) && isset($_REQUEST['password_confirmation'])) {
            // Check password is valid
            if(0 === preg_match("/.{6,}/", $_POST['password'])){
              $errors['password'] = "Password must be at least six characters";
            }

            // Check password confirmation_matches
            if(0 !== strcmp($_POST['password'], $_POST['password_confirmation'])){
              $errors['password_confirmation'] = "Passwords do not match";
            }
        }
        else {
            $errors['password'] = "Password can not be empty";
        }
        
		if(isset($_REQUEST['terms'])) {
           // Check terms of service is agreed to
            if($_POST['terms'] != "Yes"){
                $errors['terms'] = "You must agree to Terms of Service";
            }
        }
        else {
            $errors['terms'] = "You must agree to Terms of Service";
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
            curl_close($ch);
        }
        else{
            $errors['g-recaptcha-response'] = "You must verify using captcha";
        }
            
        if(0 === count($errors)) {

			$password = $_POST['password'];

			$new_user_id = wp_create_user( $email, $password, $email );

			// You could do all manner of other things here like send an email to the user, etc. I leave that to you.
            wp_update_user(array( 'ID' => $new_user_id, 'first_name' => $_REQUEST['firstname'], 'last_name' => $_REQUEST['lastname']));

            add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    
            $message = getMailHeader();
            $message .= getMainSenderName($_REQUEST['firstname'] . ' ' . $_REQUEST['lastname']);

            $message .= '<p>Thank you for joining with us. We carefully crafted questions to effectively validate your skills.</p>';

            $message .= '<p>We personally thank you for helping us reaching our mission - <strong><i>Build a platform which validates individual skills as per industry standards and makes our score as a baseline for the industry.</i></strong></p>';

            $message .= 'We hope our platform helps you enhance your skills and reach your goal. You are always welcome to provide your feedback.';
            
            $message .= getMailFooter();

            wp_mail($email, 'SmartGnan CrackIt - Thank you for joining', $message);

	        remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
            
            $success = 1;
            
            $login_data = array();
            $login_data['user_login'] = $email;
            $login_data['user_password'] = $password;
            $login_data['remember'] = false;
 
            $user_verify = wp_signon( $login_data, false ); 

			header( 'Location:' . get_bloginfo('url') . '/dashboard' );

		}

	}
}

?>
<?php get_header(); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container">
    <div class="row">
        <div class="form-signin card card-outline-success">
            <div class="card-header text-center">Register</div>
            <div class="card-block">
                <?php if( 0 <> count($errors) ) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach( $errors as $err ) { ?>
                    <p></p><strong><?php echo $err; ?></strong></p>
                    <?php } ?>
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
                        <input  class="form-control" id="password" type="password" placeholder="Password" name="password">
                    </div>
                    <div class="form-group">
                        <input  class="form-control" id="password_confirmation" type="password" placeholder="Password Confirmation" name="password_confirmation">
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input name="terms" type="checkbox" class="custom-control-input" value="Yes">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">I agree to the Terms of Service</span>
                        </label>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6Le_dBsTAAAAAPETzwqtS6UAgb1P9iQqk3IX4Dp5"></div>
                    <div class="form-group">
                        <input class="btn btn-primary btn-lg btn-block" id="submit" type="submit" name="submit" value="Register">
                    </div>
                    <div class="form-group">
                        <a class="btn btn-outline-info" href="<?php echo get_the_permalink(get_page_by_path('login')->ID); ?>">Log In</a>
                        <!--<a class="btn btn-outline-warning" href="<?php echo get_permalink(get_page_by_path('lost-password')->ID); ?>">Forgot Password</a>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>

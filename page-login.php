<?php 
/*
Template Name: Login
*/
$user_verify = '';
if ($user_ID) {

	// They're already logged in, so we bounce them back to the homepage.

	header( 'Location:' . home_url() . '/dashboard' );

}
if($_POST) {
 
	global $wpdb;
    
    //We shall SQL escape all inputs
	$username = esc_sql($_REQUEST['username']);
	$password = esc_sql($_REQUEST['password']);
    $remember = false;
    if(isset($_REQUEST['rememberme'])) {
	   $remember = esc_sql($_REQUEST['rememberme']);
    }
 
	if($remember) $remember = "true";
	else $remember = "false";
 
	$login_data = array();
	$login_data['user_login'] = $username;
	$login_data['user_password'] = $password;
	$login_data['remember'] = $remember;
 
	$user_verify = wp_signon( $login_data, false ); 
    if ( !is_wp_error($user_verify) ) {
	   echo "<script type='text/javascript'>window.location='". home_url() ."'</script>";
	   exit();
	 }
 
}

?>
<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="form-signin card card-outline-success">
            <div class="card-header text-center">Sign In</div>
            <div class="card-block">
                <?php if( is_wp_error($user_verify) ) { ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Invalid email or password</strong>
                </div>
                <?php } ?>
                <form id="login" name="form" action="" method="post">
                    <div class="form-group">
                        <input class="form-control" id="username" type="text" placeholder="Email" name="username">
                    </div>
                    <div class="form-group">
                        <input  class="form-control" id="password" type="password" placeholder="Password" name="password">
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input name="rememberme" type="checkbox" class="custom-control-input" value="true">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Remember Me</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary btn-lg btn-block" id="submit" type="submit" name="submit" value="Login">
                    </div>
                    <div class="form-group">
                        <a class="btn btn-outline-info" href="<?php echo get_the_permalink(get_page_by_path('register')->ID); ?>">SignUp</a>
                        <!--<a class="btn btn-outline-warning" href="<?php echo get_permalink(get_page_by_path('lost-password')->ID); ?>">Forgot Password</a>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>

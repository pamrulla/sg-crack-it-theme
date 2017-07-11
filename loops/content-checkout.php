<?php
$isSubmit = false;
$isConfirm = false;
$isSuccess = false;
$isFailure = false;
$isCancel = false;

$MERCHANT_KEY = "JIDpuZ";//"D77ZO7";
$SALT = "fHgfBVAf";//"KezrgVWb";
// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://test.payu.in";

$action = '';

$posted = array();

$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
$hash = '';
if(!empty($_POST) && isset($_POST['service_provider'])) {
    foreach($_POST as $key => $value) {    
        $posted[$key] = $value; 	
    }
    $isConfirm = true;
    $txnid = $posted['txnid'];
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
    if(empty($posted['hash']) && sizeof($posted) > 0) {
        if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
        ) {
            $isSubmit = true;
        } else {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';	
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;
            $hash = strtolower(hash('sha512', $hash_string));
            $action = $PAYU_BASE_URL . '/_payment';
        }
    }
}


if(isset($_GET['plan'])){
    if($isConfirm == false) {
        $isSubmit = true;
    }
    $plan = $_GET['plan'];
}

if(isset($_GET['success'])){
    $isSuccess = true;
}

if(isset($_GET['fail'])){
    $isFailure = true;
}

if(isset($_GET['cancel'])){
    $isCancel = true;
}

if($isSubmit == false && $isConfirm == false && $isSuccess == false && $isFailure == false && $isCancel == false) {
    echo "<script>location.href='".get_home_url()."';</script>";
}

if(!is_user_logged_in()) {
    echo "<script>location.href='".get_home_url()."';</script>";
}

$user = wp_get_current_user();

?>

<h3><?php echo the_title(); ?></h3>
<?php if($isSubmit) { ?>
<div class="row" id="checkout-form">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <form name="paymentSubmitForm" method="post">
                    <h6>Personal Details:</h6>
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label">First Name*</label>
                        <div class="col-sm-4">
                            <input name="firstname" type="text" value="<?php echo $user->user_firstname; ?>" required class="form-control" id="firstname" placeholder="First Name">
                        </div>
                        <label for="lastname" class="col-sm-2 col-form-label">Last Name*</label>
                        <div class="col-sm-4">
                            <input name="lastname" type="text" value="<?php echo $user->user_lastname; ?>" class="form-control" id="lastname" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-4">
                            <input name="email" type="text" value="<?php echo $user->user_email; ?>" class="form-control" readonly id="email" placeholder="Email">
                        </div>
                        <label for="phone" class="col-sm-2 col-form-label">Mobile*</label>
                        <div class="col-sm-4">
                            <input name="phone" required type="number" class="form-control" id="phone" placeholder="Mobile">
                        </div>
                    </div>
                    <hr/>
                    <h6>Choose Period:</h6>
                    <div class="form-group row">
                        <label class="custom-control custom-radio col-sm-3 offset-sm-1">
                            <input id="option1" name="option" type="radio" class="custom-control-input" value="1">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Rs. <?php if($plan == 1) {echo '300'; } else {echo '500'; } ?>/month</span>
                        </label>
                        <label class="custom-control custom-radio col-sm-4">
                            <input id="option2" name="option" type="radio" class="custom-control-input" value="2">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Rs. <?php if($plan == 1) {echo '1500'; } else {echo '2500'; } ?>/6 months <small class="text-success">(save 1 month)</small></span>
                        </label>
                        <label class="custom-control custom-radio col-sm-3">
                            <input id="option3" checked name="option" type="radio" class="custom-control-input" value="3">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Rs. <?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?>/year <small class="text-success">(save 2 months)</small></span>
                        </label>
                    </div>
                    <hr/>
                    <h6>Plan Details:</h6>
                    <div class="text-center"><strong><?php if($plan == 1) {echo 'Intermediate Plan'; } else {echo 'Advance Plan'; } ?> for <span id="plan-period">12 months</span>.</strong></div>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-8"><h3>Total Amount: Rs. <span id="toatl-amount"><?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?></span> </h3></div>
                        <input hidden id="amount" name="amount" value="<?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?>"  />
                        <input hidden id="productinfo" name="productinfo" value="Smartgnan Crack It <?php if($plan == 1) { echo 'Intermediate Plan for 12 months.';} else {echo 'Advanced Plan for 12 months.';} ?>"  />
                        <input hidden name="surl" value="<?php echo the_permalink(); ?>?success=1" />
                        <input hidden name="furl" value="<?php echo the_permalink(); ?>?fail=1" />
                        <input hidden name="curl" value="<?php echo the_permalink(); ?>?cancel=1" />
                        <input hidden name="service_provider" value="payu_paisa"/>
                        <input hidden id="udf1" name="udf1" value="<?php echo $plan; ?>"  />
                        <input hidden id="udf2" name="udf2" value="3"  />
                        <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                        <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                        <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                        <div class="col-sm-2"><button class="btn btn-success" type="submit">Proceed</button></div>
                        <div class="col-sm-2"><button class="btn btn-danger">Cancel</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($isConfirm) { ?>
<div class="row" id="checkout-form">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <form name="paymentConfirmForm" method="post" action="<?php echo $action; ?>">
                    <h6>Personal Details:</h6>
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label">First Name*</label>
                        <div class="col-sm-4">
                            <input readonly name="firstname" type="text" value="<?php echo $posted['firstname']; ?>" required class="form-control" id="firstname" placeholder="First Name">
                        </div>
                        <label for="lastname" class="col-sm-2 col-form-label">Last Name*</label>
                        <div class="col-sm-4">
                            <input readonly name="lastname" type="text" value="<?php echo $posted['lastname']; ?>" class="form-control" id="lastname" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-4">
                            <input name="email" type="text" value="<?php echo $posted['email']; ?>" class="form-control" readonly id="email" placeholder="Email">
                        </div>
                        <label for="phone" class="col-sm-2 col-form-label">Mobile*</label>
                        <div class="col-sm-4">
                            <input readonly name="phone" required type="number" value="<?php echo $posted['phone']; ?>" class="form-control" id="phone" placeholder="Mobile">
                        </div>
                    </div>
                    <hr/>
                    <h6>Plan Details:</h6>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <input name="productinfo" type="text" value="<?php echo $posted['productinfo']; ?>" class="form-control" readonly id="productinfo" placeholder="productinfo">
                        </div>
                        <div class="col-sm-2">
                            <span>Total Amount Rs. </span>
                        </div>
                        <div class="col-sm-4">
                            <input name="amount" type="text" value="<?php echo $posted['amount']; ?>" class="col-sm-4 form-control" readonly id="amount" placeholder="amount">
                        </div>
                    </div>
                    <div class="row">
                        <input hidden name="surl" value="<?php echo $posted['surl']; ?>?success=1" />
                        <input hidden name="furl" value="<?php echo $posted['furl']; ?>?fail=1" />
                        <input hidden name="curl" value="<?php echo $posted['curl']; ?>?cancel=1" />
                        <input hidden name="service_provider" value="<?php echo $posted['service_provider']; ?>"/>
                        <input hidden id="udf1" name="udf1" value="<?php echo $posted['udf1']; ?>"  />
                        <input hidden id="udf2" name="udf2" value="<?php echo $posted['udf2']; ?>"  />
                        <input type="hidden" name="key" value="<?php echo $posted['key']; ?>" />
                        <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                        <input type="hidden" name="txnid" value="<?php echo $posted['txnid']; ?>" />
                        <div class="col-sm-2 offset-sm-8"><button class="btn btn-success" type="submit">Confirm</button></div>
                        <div class="col-sm-2"><button class="btn btn-danger">Cancel</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if($isSuccess || $isFailure || $isCancel) { ?>
    <script type="text/javascript">
        function UpdateTransaction(pl, op, ti, amt, status){
            var userId = <?php echo $user->ID; ?>;
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            d = {
                action: 'sgcrackit_ajax_checkout_process_payment',
                userId: userId,
                memberplan: pl,
                option: op,
                txnId: ti,
                amount: amt,
                status: status
            }
            jQuery.post(ajaxurl, d, function(resp){});
        }
    </script>
<?php } ?>
<?php if($isSuccess) { ?>
    <?php
        $status=$_POST["status"];
        $firstname=$_POST["firstname"];
        $amount=$_POST["amount"];
        $txnid=$_POST["txnid"];
        $pl = $_POST["udf1"];
        $op = $_POST["udf2"];
        $posted_hash=$_POST["hash"];
        $key=$_POST["key"];
        $productinfo=$_POST["productinfo"];
        $email=$_POST["email"];
        $salt=$SALT;
        if (isset($_POST["additionalCharges"])) {
            $additionalCharges=$_POST["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||'.$op.'|'.$pl.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;            }
        else {	  
            $retHashSeq = $salt.'|'.$status.'|||||||||'.$op.'|'.$pl.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) { ?>
        <div class="card card-inverse card-danger text-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p>Invalid Transaction. Please try again.</p>
              <footer><a href="<?php echo get_permalink().'?plan='.$pl; ?>" class="btn btn-primary">Try Again</a></footer>
            </blockquote>
          </div>
        </div>
        <script> UpdateTransaction(<?php echo $pl; ?>, <?php echo $op; ?>, '<?php echo $txnid; ?>', <?php echo $amount; ?>, 0); </script>
        <?php } 
        else { ?>
        <div class="card card-inverse card-success mb-3 text-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <h3>Your order status is <?php echo $status; ?>.</h3>
              <h4>Your Transaction ID for this transaction is <?php echo $txnid; ?>.</h4>
              <h4>Please try again.</h4>
              <footer><a href="<?php echo get_permalink(get_page_by_path('dashboard')->ID); ?>" class="btn btn-primary">Dashboard</a></footer>
            </blockquote>
          </div>
        </div>
        <script> UpdateTransaction(<?php echo $pl; ?>, <?php echo $op; ?>, '<?php echo $txnid; ?>', <?php echo $amount; ?>, 1); </script>
    <?php } ?>
<?php } ?>

<?php if($isFailure || $isCancel) { ?>
    <?php
        $status=$_POST["status"];
        $firstname=$_POST["firstname"];
        $amount=$_POST["amount"];
        $txnid=$_POST["txnid"];
        $pl = $_POST["udf1"];
        $op = $_POST["udf2"];
        $posted_hash=$_POST["hash"];
        $key=$_POST["key"];
        $productinfo=$_POST["productinfo"];
        $email=$_POST["email"];
        $salt=$SALT;
        if (isset($_POST["additionalCharges"])) {
            $additionalCharges=$_POST["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||'.$op.'|'.$pl.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
                          }
        else {	  
            $retHashSeq = $salt.'|'.$status.'|||||||||'.$op.'|'.$pl.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) { ?>
        <div class="card card-inverse card-danger text-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p>Invalid Transaction. Please try again.</p>
              <footer><a href="<?php echo get_permalink().'?plan='.$pl; ?>" class="btn btn-primary">Try Again</a></footer>
            </blockquote>
          </div>
        </div>
        <script> UpdateTransaction(<?php echo $pl; ?>, <?php echo $op; ?>, '<?php echo $txnid; ?>', <?php echo $amount; ?>, 0); </script>
        <?php } 
        else { ?>
        <div class="card card-inverse card-danger mb-3 text-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <h3>Thank You. Your order status is <?php echo $status; ?>.</h3>
              <h4>Your Transaction ID for this transaction is <?php echo $txnid; ?>.</h4>
              <h4>We have received a payment of Rs. <?php echo $amount; ?>.</h4>
              <footer><a href="<?php echo get_permalink().'?plan='.$pl; ?>" class="btn btn-primary">Try Again</a></footer></footer>
            </blockquote>
          </div>
        </div>
        <script> UpdateTransaction(<?php echo $pl; ?>, <?php echo $op; ?>, '<?php echo $txnid; ?>', <?php echo $amount; ?>, 0); </script>
    <?php } ?>
<?php } ?>
<?php if($isSubmit) { ?>
<script type="text/javascript">
    var plan = <?php echo $plan; ?>;
    var userId = <?php echo $user->ID; ?>;
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(function($){
       $('input[type=radio][name=option]').change(function() {
            if (this.value == '1') {
                $("#plan-period").html('1 month');
                $("#toatl-amount").html('<?php if($plan == 1) {echo '300'; } else {echo '500'; } ?>');
                $("#amount").val('<?php if($plan == 1) {echo '300'; } else {echo '500'; } ?>');
                var t = 'Smartgnan Crack It ';
                if(plan == 1) {
                    t += 'Intermediate Plan for 1 month.';
                }
                else{
                    t += 'Advanced Plan for 1 month.';
                }
                $("#productinfo").val(t);
            }
            else if (this.value == '2') {
                $("#plan-period").html('6 months');
                $("#toatl-amount").html('<?php if($plan == 1) {echo '1500'; } else {echo '2500'; } ?>');
                $("#amount").val('<?php if($plan == 1) {echo '1500'; } else {echo '2500'; } ?>');
                var t = 'Smartgnan Crack It ';
                if(plan == 1) {
                    t += 'Intermediate Plan for 6 months.';
                }
                else{
                    t += 'Advanced Plan for 6 months.';
                }
                $("#productinfo").val(t);
            }
            else if (this.value == '3') {
                $("#plan-period").html('12 months');
                $("#toatl-amount").html('<?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?>');
                $("#amount").val('<?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?>');
                var t = 'Smartgnan Crack It ';
                if(plan == 1) {
                    t += 'Intermediate Plan for 12 months.';
                }
                else{
                    t += 'Advanced Plan for 12 months.';
                }
                $("#productinfo").val(t);
            }
            $("#udf2").val(this.value);
        }); 
    });
    
</script>
<?php } ?>

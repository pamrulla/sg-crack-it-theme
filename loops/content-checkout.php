<?php
$plan = $_GET['plan'];
$user = wp_get_current_user();
?>

<h3><?php echo the_title(); ?></h3>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <form>
                    <h6>Personal Details:</h6>
                    <div class="form-group row">
                        <label for="first-name" class="col-sm-2 col-form-label">First Name*</label>
                        <div class="col-sm-4">
                            <input type="text" value="<?php echo $user->user_firstname; ?>" required class="form-control" id="first-name" placeholder="First Name">
                        </div>
                        <label for="last-name" class="col-sm-2 col-form-label">Last Name*</label>
                        <div class="col-sm-4">
                            <input type="text" value="<?php echo $user->user_lastname; ?>" required class="form-control" id="last-name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-4">
                            <input type="text" value="<?php echo $user->user_email; ?>" class="form-control" readonly id="email" placeholder="Email">
                        </div>
                        <label for="mobile" class="col-sm-2 col-form-label">Mobile*</label>
                        <div class="col-sm-4">
                            <input required type="text" class="form-control" id="mobile" placeholder="Mobile">
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
                        <div class="col-sm-8"><h3>Total Amount: Rs. <span id="toatl-amount">3000</span> </h3></div>
                        <div class="col-sm-2"><button class="btn btn-success">Proceed</button></div>
                        <div class="col-sm-2"><button class="btn btn-danger">Cancel</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($){
       $('input[type=radio][name=option]').change(function() {
        if (this.value == '1') {
            $("#plan-period").html('1 month');
            $("#toatl-amount").html('<?php if($plan == 1) {echo '300'; } else {echo '500'; } ?>');
        }
        else if (this.value == '2') {
            $("#plan-period").html('6 months');
            $("#toatl-amount").html('<?php if($plan == 1) {echo '1500'; } else {echo '2500'; } ?>');
        }
        else if (this.value == '3') {
            $("#plan-period").html('12 months');
            $("#toatl-amount").html('<?php if($plan == 1) {echo '3000'; } else {echo '5000'; } ?>');
        }
    }); 
    });
</script>
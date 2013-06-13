<div class="span9">


    <?php
    $attributes = array('class' => 'form-horizontal');
    echo form_open('advertiser/settings/password', $attributes); ?>

    <legend>Change Password</legend>
    <?php if(isset($notification)): ?>
    <div class="alert <?php echo $alertType;?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $notification?>
    </div>
    <?php endif;?>


    <fieldset>
        <div class="well">

            <div class="control-group">
                <label class="control-label" for="inputCurrentPassword">Current Password</label>
                <div class="controls">
                    <input class="input-xlarge" name="inputCurrentPassword" type="password" id="inputCurrentPassword" placeholder="Current Password">
                    <!--<p><a href="#">Forgot Your Password ?</a> </p> -->
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputNewPassword">New Password</label>
                <div class="controls">
                    <input value=""  name="inputNewPassword" class="input-xlarge" type="password" id="inputNewPassword" placeholder="New Password">
                    <a href="#" id="passwordttp" data-placement="right" rel="tooltip" title="Password must be atleast 8 characters.</br>Use alphanumeric characters.</br>Password is case sensitive." data-html="true"><i class="icon-question-sign"></i> </a>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputReEnterPassword">Re-Enter Password</label>
                <div class="controls">
                    <input class="input-xlarge" name="inputReEnterPassword" type="password" id="inputReEnterPassword" placeholder="Re-Enter Password (Required)">
                    <p class="text-error" id="inputReEnterPasswordMessage"></p>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
        </div>
    </fieldset>

    </form>



</div>
</div>
</div>
<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>

<script type="text/javascript">
    $('#passwordttp').tooltip();
</script>
<div class="container">
    <div class="span8 offset2 well">
        <?php if(isset($successMessage)):?>
        <p class="lead text-success" align="center">
            <?php echo $successMessage; ?></p>
        </br></br></br></br>
        <p align="center">
            <a class="btn btn-primary" href="<?php site_url('publisher/billing/index')?>"> &nbsp;&nbsp;&nbsp;&nbsp;Go Back &nbsp;&nbsp;&nbsp;&nbsp;</a>
        </p>
        <?php endif; ?>

        <?php if(!isset($successMessage)):?>
        <p class="lead text-info">Debit Card / Net Banking / Credit Card Payment</p>

        <?php if(isset($error)):?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

        <?php
        $ci=& get_instance();
        $ci->load->helper('ccavenuelibrary');
        $ci->config->load('ccavenue');
        $merchantId=$ci->config->item('merchantId');
        $workingKey=$ci->config->item('workingKey');
        $redirectUrl=$ci->config->item('redirectUrl');
        $amount=$amount;
        $orderId=$userInfo['advKeyInfo'].'break'.timestampToday();

        $Checksum = getCheckSum($merchantId,$amount,$orderId ,$redirectUrl,$workingKey);

        $address=explode('$~$',$userInfo['address']);

        $billing_cust_name=$userInfo['firstname'].' '.$userInfo['lastname'];
        $billing_cust_address=$address[3];
        $billing_cust_state="";
        $billing_cust_country=$address[1];
        $billing_cust_tel=$userInfo['phone'];
        $billing_cust_email=$userInfo['email'];
        $delivery_cust_name=$userInfo['firstname'].' '.$userInfo['lastname'];
        $delivery_cust_address=$address[3];
        $delivery_cust_state = "";
        $delivery_cust_country = $address[1];
        $delivery_cust_tel=$userInfo['phone'];
        $delivery_cust_notes="";
        $Merchant_Param=$userInfo['advKeyInfo'];
        $billing_city = $address[2];
        $billing_zip = $address[0];
        $delivery_city = $address[2];
        $delivery_zip = $address[0];

        ?>

        <form method="post" action="https://www.ccavenue.com/shopzone/cc_details.jsp" class="form-horizontal"  style="margin: 0px; padding: 0px;">
            <input type="hidden" name="Merchant_Id" value="<?php echo $merchantId; ?>">
            <input type="hidden" name="Order_Id" value="<?php echo $orderId; ?>">
            <input type="hidden" name="Redirect_Url" value="<?php echo $redirectUrl; ?>">
            <input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>">
            <input type="hidden" name="Amount" value="<?php echo $amount; ?>">

            <div class="control-group" >
                <label class="control-label" for="Amount">Amount</label>
                <div class="controls">
                    <input class="input-xlarge" name="Amount-shown" type="text" id="Amount" value="<?php  if($currency=='USD'){ echo '$ ';}elseif($currency=='INR'){ echo '&#8377; ';}?><?php echo $amount; ?>" disabled>
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_name">Name</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_name" type="text" id="billing_cust_name" value="<?php echo $billing_cust_name; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_tel">Phone</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_tel" type="text" id="billing_cust_tel" value="<?php echo $billing_cust_tel; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_email">E-Mail</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_email" type="text" id="billing_cust_email" value="<?php echo $billing_cust_email; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_address">Address</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_address" type="text" id="billing_cust_address" value="<?php echo $billing_cust_address; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_city">City</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_city" type="text" id="billing_cust_city" value="<?php echo $billing_city; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_state">State</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_state" type="text" id="billing_cust_state" value="<?php echo $billing_cust_state; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_zip">ZIP/Postal</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_zip" type="text" id="billing_zip" value="<?php echo $billing_zip; ?>">
                </div>
            </div>
            <div class="control-group" >
                <label class="control-label" for="billing_cust_country">Country</label>
                <div class="controls">
                    <input class="input-xlarge" name="billing_cust_country" type="text" id="billing_cust_country" value="<?php echo $delivery_cust_country; ?>">
                </div>
            </div>

            <input type="hidden" name="delivery_cust_name" value="<?php echo $delivery_cust_name; ?>">
            <input type="hidden" name="delivery_cust_address" value="<?php echo $delivery_cust_address; ?>">
            <input type="hidden" name="delivery_cust_country" value="<?php echo $delivery_cust_country; ?>">
            <input type="hidden" name="delivery_cust_state" value="<?php echo $delivery_cust_state; ?>">
            <input type="hidden" name="delivery_cust_tel" value="<?php echo $delivery_cust_tel; ?>">
            <input type="hidden" name="delivery_cust_notes" value="<?php echo $delivery_cust_notes; ?>">
            <input type="hidden" name="Merchant_Param" value="<?php echo $Merchant_Param; ?>">
            <input type="hidden" name="billing_cust_city" value="<?php echo $billing_city; ?>">
            <input type="hidden" name="billing_zip_code" value="<?php echo $billing_zip; ?>">
            <input type="hidden" name="delivery_cust_city" value="<?php echo $delivery_city; ?>">
            <input type="hidden" name="delivery_zip_code" value="<?php echo $delivery_zip; ?>">

            <div class="offset2">
                <button type="submit" class="btn btn-primary">    &nbsp;&nbsp;&nbsp;&nbsp; Pay Now &nbsp;&nbsp;&nbsp;&nbsp; </button>
            </div>
        </form>
        <?php endif; ?>

    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
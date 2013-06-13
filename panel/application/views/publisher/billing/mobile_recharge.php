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
        <p class="lead text-info" align="center">Enter mobile number and amount below</p>

        <?php if(isset($error)):?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>





        <?php $attributes = array('class' => 'form-horizontal','id'=>'','style' =>'margin: 0px; padding: 0px;');
        echo form_open('publisher/billing/mobileRecharge', $attributes); ?>

        <div class="control-group">
            <label class="control-label" for="inputMobile">Mobile Number</label>
            <div class="controls">
                <input class="input-xlarge" name="inputMobile" type="text" id="inputMobile" placeholder="Mobile Number">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputAmount">Amount</label>
            <div class="controls">
                <input class="input-medium" name="inputAmount" type="text" id="inputAmount" placeholder="Amount (USD)">
            </div>
        </div>

        <div class="offset2">
            <button type="submit" class="btn btn-primary">    &nbsp;&nbsp;&nbsp;&nbsp; Withdraw Now &nbsp;&nbsp;&nbsp;&nbsp; </button>
        </div>
        </form>
        <?php endif; ?>

    </div>
</div>
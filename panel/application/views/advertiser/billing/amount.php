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

        <form method="post" action="#" class="form-horizontal"  style="margin: 0px; padding: 0px;">
            <div class="control-group" >
                <label class="control-label" for="inputAmount">Amount</label>
                <div class="controls">
                    <input class="input-large" name="amount" type="text" id="inputAmount" placeholder="Amount (<?php echo $currency ;?>)"> <?php  if($currency=='USD'){ echo '$';}elseif($currency=='INR'){ echo '&#8377; ';}?>
                </div>
            </div>

            <div class="offset2">
                <button type="submit" class="btn btn-primary">    &nbsp;&nbsp;&nbsp;&nbsp; Pay Now &nbsp;&nbsp;&nbsp;&nbsp; </button>
            </div>
        </form>
        <?php endif; ?>

    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
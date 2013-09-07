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
        <p class="lead text-info" align="center">Enter Fund Details Below</p>

        <?php if(isset($error)):?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>





        <?php $attributes = array('class' => 'form-horizontal','id'=>'','style' =>'margin: 0px; padding: 0px;');
        echo form_open('admin/advertisers/addfund', $attributes); ?>

        <input type="hidden" value="<?php echo $advKey; ?>" name="advKey">
        <input type="hidden" value="<?php echo $currency; ?>" name="currency">

        <div class="control-group">
            <label class="control-label" for="inputAmount">Amount</label>
            <div class="controls">
                <input class="input-medium" name="inputAmount" type="text" id="inputAmount" placeholder="Amount (<?php echo $currency; ?>)">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputPayDate">Payment Date</label>
            <div class="controls">
                <input class="input-large" name="inputPayDate" type="text" id="inputPayDate" placeholder="Payment Date ">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputPayMethod">Payment Method</label>
            <div class="controls">
                <input class="input-large" name="inputPayMethod" type="text" id="inputPayMethod" placeholder="Payment Method ">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputDescription">Description</label>
            <div class="controls">
                <input class="input-xxlarge" name="inputDescription" type="text" id="inputDescription" placeholder="Add Description ">
            </div>
        </div>

        <div class="offset2">
            <button type="submit" class="btn btn-primary">    &nbsp;&nbsp;&nbsp;&nbsp; Add Fund &nbsp;&nbsp;&nbsp;&nbsp; </button>
        </div>
        </form>
        <?php endif; ?>

    </div>
</div>



<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','jquery-ui.css'=>'style','jquery-ui.js'=>'script'));
loadBootstrap('script.min') ;
?>


<script type="text/javascript">

    $('#inputPayDate').attr('readonly', true);


    $( "#inputPayDate" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1
    });

</script>
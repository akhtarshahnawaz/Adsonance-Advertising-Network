<div class="container">
    <div class="row">
        <div class="offset2 span8">
            <div class="well">
                <p class="lead text-info" align="center">Select a Payment Method</p>
                <?php $attributes = array('class' => '','id'=>'','style' =>'margin: 0px; padding: 0px;');
                echo form_open('advertiser/billing/addfund', $attributes); ?>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <?php if($currency=='USD'):?>
                    <tr>
                        <td><input name="pay_method" checked="" type="radio" value="paypal"></td>
                        <td>Paypal / Credit Card</td>
                    </tr>
                    <?php elseif($currency=='INR'): ?>
                    <tr>
                        <td><input name="pay_method" checked="" type="radio" value="ccavenue"></td>
                        <td>Debit Card / Net Banking / Credit Card</td>
                    </tr>
                    <?php endif; ?>

                    <?php /*<tr>
                        <td><input name="pay_method" type="radio" value="wireTransfer"></td>
                        <td>Wire Transfer (Transfer to Bank Account)</td>
                    </tr>*/
    ?>
                    </tbody>
                </table>

                <div class="offset1">
                    <button type="submit" class="btn btn-primary">Go</button>
                    <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
                </div>
                </form>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br>

        </div>
    </div>
</div>
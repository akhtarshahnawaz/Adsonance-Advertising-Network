<div class="container">
    <div class="row">
        <div class="offset2 span8">
            <div class="well">
                <p class="lead text-info" align="center">Select a Method to Withdraw</p>

                <?php $attributes = array('class' => '','id'=>'','style' =>'margin: 0px; padding: 0px;');
                echo form_open('publisher/billing/withdraw', $attributes); ?>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td><input name="withdraw_method" checked="" type="radio" value="mobile_recharge"></td>
                        <td>Mobile Recharge</td>
                    </tr>
                    </tbody>
                </table>

                <div class="offset1">
                    <button type="submit" class="btn btn-primary"> &nbsp;&nbsp;&nbsp;&nbsp;Go&nbsp;&nbsp;&nbsp;&nbsp;</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
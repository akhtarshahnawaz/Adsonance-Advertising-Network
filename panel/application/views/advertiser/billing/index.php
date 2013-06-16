<div class="container">

    <div class="row">
        <div class="span3 well">

            <table class="table table-bordered table-striped table-hover">
                <tr class="<?php if($balances['remainingBalance']<0){echo 'error';}elseif($balances['remainingBalance']==0){echo 'warning';}else{echo "success";}?>">
                    <th><h5 class="<?php if($balances['remainingBalance']<0){echo 'text-error';}elseif($balances['remainingBalance']==0){echo 'text-warning';}else{echo "text-success";}?>" style="margin: 0px; padding: 0px;">Account Balance</h5></th>
                    <td><p class="<?php if($balances['remainingBalance']<0){echo 'text-error';}elseif($balances['remainingBalance']==0){echo 'text-warning';}else{echo "text-success";}?>" style="margin: 0px; padding: 0px;"><?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';} echo $balances['remainingBalance'].' '.$this->session->userdata('currency');?></p></td>
                </tr>
            </table>

            <p align="center"><a class="btn btn-success" href="<?php echo site_url('advertiser/billing/addfund')?>">Add Fund</a>
            </p>
        </div>
        <div class="span8 pull-right">
            <div class="well">

                <div id="flotdailyspend" style="width: 730px;height:250px; text-align: center; margin:0 auto;">
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="span12">

            <div class="well">
                <?php $date=explode(' - ',$range)?>

                <?php
                $attributes = array('class' => 'form-inline pull-left','id'=>'dateRangeForm','style' =>'margin: 0px; padding: 0px;');
                echo form_open('advertiser/billing/index', $attributes); ?>
                <input type="text" style="background: #fff; cursor: default;" name="daterange" id="daterange" value="<?php if(isset($range)){echo $range;}?>" placeholder="<?php if(isset($placeholder)){echo $placeholder;}else{echo 'Select Date Range';}?>"/>
                <button type="submit" class="btn btn-small btn-inverse">Get Billing Summary</button>
                </form>
                <p class="lead text-info pull-right" align="center"><?php echo dateToString($date[0]);?> <b class="text-warning">to</b> <?php echo dateToString($date[1]);?></p>

            </div>

            <?php if($billingSummary!=null):?>

            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Transaction Date</th>
                    <th>Transaction Type</th>
                    <th>Description</th>
                    <th>Payment Method</th>
                    <th>Transaction Amount</th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach($billingSummary as $bill): ?>
                <tr class="<?php if($bill['transType']=='deposit'){ echo 'success';}elseif($bill['transType']=='spend'){ echo 'warning';} ?>">
                    <td><?php echo $bill['date']; ?></td>
                    <td><?php echo $bill['transType']; ?></td>
                    <td><?php echo $bill['description']; ?></td>
                    <td><?php echo $bill['paymentMethod']; ?></td>
                    <td><?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';}?> <?php echo $bill['amount']; ?></td>
                </tr>
                    <?php endforeach;?>
                </tbody>

            </table>
            <?php else: ?>
            <div class="well"><p class="lead text-error" align="center">No Transactions Found for the Period <b class="text-info"><?php echo dateToString($date[0]);?></b> to  <b class="text-info"><?php echo dateToString($date[1]);?></b> </p> </div>
            <?php endif;?>
        </div>
    </div>

</div>

<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','flot/excanvas.min.js'=>'script','flot/jquery.flot.min.js'=>'script','flot/jquery.flot.time.js'=>'script','flot/jquery.flot.axislabels.js'=>'script','flot/jquery.flot.tooltip.min.js'=>'script','datepicker/moment.js'=>'script','datepicker/daterangepicker.js'=>'script','datepicker/daterangepicker.css'=>'style'));
loadBootstrap('script.min') ;
?>


<script type="text/javascript">
    $(function () {

        $('#daterange').attr('readonly', true);
        $('#daterange').daterangepicker();
        plotSpendGraph();
    });

</script>

<script type="text/javascript">
    function plotSpendGraph(){

        var data = <?php echo $spendGraphData;?>;
        var plotarea = $("#flotdailyspend");

        var options = {
            legend: {
                show: true,
                margin: 0,
                backgroundOpacity: 0.5
            },
            points: {
                show: true,
                radius: 2
            },
            lines: {
                show: true,
                lineWidth: 1
            },
            xaxis: {
                mode: "time",
                timeformat: "%d %b "
            },
            grid: {
                hoverable: true,
                backgroundColor: "#fff"
            },
            tooltip: true,
            tooltipOpts: {
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>Spend: <?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';}?> </b>%y</p>",
                shifts: {
                    x: -60,
                    y: 25
                }
            }

        };
        $.plot( plotarea , data, options );

    }


    function gd(month, day, year) {
        return new Date(year, month - 1, day).getTime();
    }

</script>
<div class="container container-fluid" xmlns="http://www.w3.org/1999/html">


    <div class="row">
        <div class="span3 pull-left">
            <table class="table table-bordered table-striped table-hover">
                <tr class="success">
                    <th><h5 class="text-success" style="margin: 0px; padding: 0px;">Your Earning</h5></th>
                    <td><p class="text-success" style="margin: 0px; padding: 0px;"><?php echo '$ '.$totalEarning;?></p></td>
                </tr>
            </table>
        </div>
        <div class="span5 pull-right">
            <a class="btn btn-success  pull-right" href="<?php echo site_url('publisher/billing/withdraw');?>">Withdraw Earnings</a>
        </div>

    </div>

    <div class="row">
        <div class="span12">
            <div class="well">
                <p class="lead" align="center">Daily Earning Graph</p>
                <div id="flotdailyearning" style="width: 800px;height:280px; text-align: center; margin:0 auto;">
                </div>
            </div>
        </div>
    </div>

    <?php if($pendingPayments):?>
    <div class="row">
        <div class="span12">
            <p class="lead text-info" align="center">Pending Withdrawls</p>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <tr>
                    <th>Amount</th>
                    <th>Withdrawal method</th>
                    <th>Time</th>
                </tr>
                <?php foreach($pendingPayments as $payments):?>
                <tr class="warning">
                    <td><?php echo '$ '.$payments['amount'];?></td>
                    <td><?php echo $payments['method'];?></td>
                    <td><?php echo dateToString(timestampToDate($payments['time']));?></td>
                </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
    <?php endif;?>


    <div class="row">
        <div class="span12">

            <?php if($transactions):?>
            <p class="lead text-info" align="center">Monthly Transactions</p>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Description</th>
                </tr>
                <?php foreach($transactions as $transaction):?>
                <tr class="<?php if($transaction['transType']=='earned'){ echo 'success';}elseif($transaction['transType']=='withdrawal'){echo 'info';}?>">
                    <td><?php echo dateToString($transaction['date'])?></td>
                    <td><?php echo $transaction['transType'];?></td>
                    <td><?php echo '$ '.$transaction['amount'];?></td>
                    <td><?php echo $transaction['transMethod'];?></td>
                    <td><?php echo $transaction['description'];?></td>

                </tr>

                <?php endforeach;?>
            </table>
            <?php else:?>
            <div class="well">
                <p class="lead text-warning" align="center">
                    You Don't Have any Earning or Withdrawal in last 30 Days
                </p>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>

<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','flot/excanvas.min.js'=>'script','flot/jquery.flot.min.js'=>'script','flot/jquery.flot.time.js'=>'script','flot/jquery.flot.axislabels.js'=>'script','flot/jquery.flot.tooltip.min.js'=>'script'));
loadBootstrap('script.min') ;
?>


<script type="text/javascript">
    $(function () {
        plotearningGraph();
    });

</script>

<script type="text/javascript">
    function plotearningGraph(){

        var data = <?php echo $earningGraphData;?>;
        var plotarea = $("#flotdailyearning");

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
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>Earning: $ </b>%y</p>",
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

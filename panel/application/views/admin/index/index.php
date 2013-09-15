<div class="container" xmlns="http://www.w3.org/1999/html">

    <div class="span6">

        <p class="lead">Withdrawal Requests</p>
        <table class="table table-bordered table-condensed table-stripped">
            <thead>
            <td>Amount</td>
            <td>Method</td>
            <td></td>
            </thead>
            <tbody>
            <tr>
                <?php if(!empty($withdrawalRequests)):?>
                <?php foreach($withdrawalRequests as $row):?>
                <td><?php $row['amount'].' '.$row['currency']; ?></td>
                <td><?php $row['method']; ?></td>
                <td> <a class="btn btn-small btn-primary" href="<?php echo site_url('admin/publishers/withdrawalRequest').'/'.$row['userId'];?>">View Details</a></td>
                <?php endforeach; else:?>
                <tr>No Pending Withrawal Requests</tr>
    <?php endif; ?>
            </tbody>
            </tr>
        </table>
    </div>
    <div class="span5">

    </div>

</div>
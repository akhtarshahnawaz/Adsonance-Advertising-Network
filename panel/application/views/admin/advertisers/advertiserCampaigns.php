<div class="container">

    <div class="row">
        <div class="span12">

            <h2 align="center">Advertiser Campaigns</h2>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Campaign <a href="#" id="campaignttp" rel="tooltip" title="A Campaign is a group of ads that share similar interest">?</a></th>
                    <th>Status <a href="#" id="statusttp" rel="tooltip" title="Status can be Running, Paused, Stopped or Deleted">?</a> </th>
                    <th>Start Date <a href="#" id="startdatettp" rel="tooltip" title="Date from which campaign will start to run.">?</a></th>
                    <th>End Date <a href="#" id="enddatettp" rel="tooltip" title="Date on which campaign is sheduled to stop.It will be N/A if campaign is to run continuously">?</a></th>
                    <th>Budget <a href="#" id="budgetttp" rel="tooltip" title="Maximum amount you are willing to spend on the campaign.It can be per Day or Lifetime">?</a></th>
                    <th>Remaining <a href="#" id="remainingttp" rel="tooltip" title="Amount Remaining in your account ">?</a></th>
                    <th>Total Spend <a href="#" id="totalspendttp" rel="tooltip" title="Total amount spend on the Campaign">?</a></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($campaigns as $row): ?>
                <tr>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php if($row['status'] == 'pending'):?>
                        <span class="label label-warning">Pending Approval</span>
                        <?php elseif($row['status'] == 'completed'):?>
                        <span class="label label-success">Completed</span>
                        <?php else:?>
                        <?php if($row['status']== 'active'):?>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-mini btn-success" data-toggle="dropdown" href="#"><i class="icon-play"></i> Running <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/campaign/status').'/'.$row['pkey'].'/paused'; ?>"><i class="icon-pause"></i> Pause</a></li>
                                </ul>
                            </div>
                            <?php elseif($row['status']=='paused'):?>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-mini btn-info" data-toggle="dropdown" href="#"><i class="icon-pause"></i> Paused <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/campaign/status').'/'.$row['pkey'].'/active'; ?>"><i class="icon-play"></i> Start</a></li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </td>
                    <td><?php echo $row['startDate'] ?></td>
                    <td><?php echo $row['endDate'] ?></td>
                    <td><?php echo $row['budget'].'.00'; ?><p style="margin: 0px; padding: 0px;"><small><?php echo $row['budgetPeriod'] ?></small></p></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-mini btn-primary" href="<?php echo site_url('admin/advertisers/ads').'/'.$row['pkey']; ?>">View Ads</a>
                            <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- dropdown menu links -->
                                <li><a href="<?php echo site_url('admin/advertisers/edit').'/'.$row['pkey']; ?>"><i class="icon-edit"></i> Edit</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>


<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>


<script type="text/javascript">
    $('#campaignttp').tooltip();
    $('#statusttp').tooltip();
    $('#startdatettp').tooltip();
    $('#enddatettp').tooltip();
    $('#budgetttp').tooltip();
    $('#remainingttp').tooltip();
    $('#totalspendttp').tooltip();

</script>


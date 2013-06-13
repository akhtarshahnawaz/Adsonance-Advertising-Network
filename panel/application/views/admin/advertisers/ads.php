<div class="container">

    <div class="row">
        <div class="span12">
            <br/><br/><br/><br/>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Ad Name <a href="#" id="adnamettp" rel="tooltip" title="Ad Name is a name you use to reference a particular advertisement">?</a></th>
                    <th>Status <a href="#" id="statusttp" rel="tooltip" title="Status can be Running, Paused, Stopped or Deleted">?</a> </th>
                    <th>CPM <a href="#" id="cpmttp" rel="tooltip" title="CPM is total number of impressions an advertisement get.">?</a></th>
                    <th>CPC <a href="#" id="cpcttp" rel="tooltip" title="CPC is total number of clicks an advertisement get.">?</a></th>
                    <th>CTR <a href="#" id="ctrttp" rel="tooltip" title="CTR is rate of conversion of impressions into clicks on a particular advertisement.">?</a></th>
                    <th>Total Spend <a href="#" id="totalspendttp" rel="tooltip" title="Total amount spend on this advertisement.">?</a></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($ads as $row): ?>
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
                                    <li><a href="<?php echo site_url('advertiser/ad/status').'/'.$keyCamp.'/'.$row['pkey'].'/paused'; ?>"><i class="icon-pause"></i> Pause</a></li>
                                </ul>
                            </div>
                            <?php elseif($row['status']=='paused'):?>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-mini btn-info" data-toggle="dropdown" href="#"><i class="icon-pause"></i> Paused <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/ad/status').'/'.$keyCamp.'/'.$row['pkey'].'/active'; ?>"><i class="icon-play"></i> Start</a></li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['cpm'] ?></td>
                    <td><?php echo $row['clicks'] ?></td>
                    <td><?php echo $row['clicks']*100/$row['cpm'].' %'; ?></td>
                    <td><?php //echo $row['name'] ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?php echo site_url('advertiser/ad/single').'/'.$keyCamp.'/'.$row['pkey']; ?>" class="btn btn-mini btn-primary">View</a>
                            <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- dropdown menu links -->
                                <li><a href="<?php echo site_url('advertiser/ad/edit').'/'.$keyCamp.'/'.$row['pkey']; ?>"><i class="icon-edit"></i> Edit</a></li>
                                <li><a href="<?php echo site_url('advertiser/ad/remove').'/'.$keyCamp.'/'.$row['pkey']; ?>"><i class="icon-remove"></i> Delete</a></li>
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
loadAsset(array('jquery-1.7.1.min.js'=>'script','flot/excanvas.min.js'=>'script','flot/jquery.flot.min.js'=>'script','flot/jquery.flot.time.js'=>'script','flot/jquery.flot.axislabels.js'=>'script','flot/jquery.flot.tooltip.min.js'=>'script'));
loadBootstrap('script.min') ;
?>

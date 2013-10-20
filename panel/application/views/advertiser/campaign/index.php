<div class="container" xmlns="http://www.w3.org/1999/html">


    <div class="row">
        <div class="span12">
            <a href="#selectCampaign"  data-toggle="modal"  class="btn btn-small pull-right btn-success" >Create an Ad</a>
            <br/><br/>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <div class="well">
                <ul class="nav nav-tabs"  id="myTab">
                    <li class="active"><a data-toggle="tab" href="#impressions">Impressions</a></li>
                    <li><a  href="#clicks">Clicks</a></li>
                    <li><a href="#ctr">CTR</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="impressions">
                        <div>
                            <div id="flotimpressions" style="width: 1100px;height:350px; text-align: center; margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="clicks">
                        <div>
                            <div id="flotclicks" style="width: 1100px;height:350px; text-align: center; margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="ctr">
                        <div>
                            <div id="flotctr" style="width: 1100px;height:350px; text-align: center; margin:0 auto;">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <?php if(isset($notification)): ?>
            <div class="alert <?php echo $alertType;?>">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $notification?>
            </div>
            <?php endif;?>


            <?php if(isset($campaigns) && $campaigns != null):?>

            <h2 class="text-info" align="center"><strong>Campaigns</strong>  </h2>
            <a href="<?php echo site_url('advertiser/campaign/create')?>"  class="btn btn-small btn-primary" >Create a Campaign</a></br></br>


            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Campaign Name <a href="#" id="campaignttp" rel="tooltip" title="A Campaign is a group of ads that share similar interest">?</a></th>
                    <th>Status <a href="#" id="statusttp" rel="tooltip" title="Status can be Running, Paused, Stopped or Deleted">?</a> </th>
                    <th>Start Date <a href="#" id="startdatettp" rel="tooltip" title="Date from which campaign will start to run.">?</a></th>
                    <th>End Date <a href="#" id="enddatettp" rel="tooltip" title="Date on which campaign is sheduled to stop.It will be N/A if campaign is to run continuously">?</a></th>
                    <th>Budget <a href="#" id="budgetttp" rel="tooltip" title="Maximum amount you are willing to spend on the campaign.It can be per Day or Lifetime">?</a></th>
                    <th>Clicks <a href="#" id="clicksttp" rel="tooltip" title="Total clicks you obtained on advertisements under this campaign">?</a></th>
                    <th>Impressions <a href="#" id="impressionsttp" rel="tooltip" title="Total number of people who saw advertisements under this campaign">?</a></th>
                    <th>CTR <a href="#" id="ctrttp" rel="tooltip" title="Rate of Conversion of impressions into clicks">?</a></th>
                    <th>Total Spend <a href="#" id="totalspendttp" rel="tooltip" title="Total amount spend on the Campaign">?</a></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach($campaigns as $row): ?>
                <tr>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php if($row['status'] == '4' || $row['status'] == 4):?>
                        <span class="label label-success">Completed</span>
                        <?php elseif($row['status']=='0'):?>
                        <span class="label label-warning">Pending Approval</span>
                        <?php else:?>
                        <?php if($row['status'] == '2' || $row['status'] =='1'):?>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-mini btn-success" data-toggle="dropdown" href="#"><i class="icon-play"></i> Running <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/campaign/status').'/'.$row['pkey'].'/3'; ?>"><i class="icon-pause"></i> Pause</a></li>
                                </ul>
                            </div>
                            <?php elseif($row['status'] == '3' || $row['status'] == 3):?>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-mini btn-info" data-toggle="dropdown" href="#"><i class="icon-pause"></i> Paused <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/campaign/status').'/'.$row['pkey'].'/2'; ?>"><i class="icon-play"></i> Start</a></li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </td>
                    <td><?php echo dateToString($row['startDate']); ?></td>
                    <td><?php if($row['endDate']!='N/A'){ echo dateToString($row['endDate']);}else{ echo $row['endDate']; } ?></td>
                    <td><?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';} echo $row['budget']; ?><p style="margin: 0px; padding: 0px;"><small><?php echo $row['budgetPeriod'] ?></small></p></td>
                    <td><?php if(isset($row['clicks'])){echo $row['clicks'];}else{echo '-';} ?></td>
                    <td><?php if(isset($row['cpm'])){echo $row['cpm'];}else{echo '-';} ?></td>
                    <td><?php if(isset($row['cpm']) && isset($row['clicks']) && $row['cpm']!=0){echo $row['clicks']*100/$row['cpm'].' %'; } else { echo '-';}?></td>
                    <td><?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';} echo ($row['clicks']*$spendConfig['pointPerClick']+$row['cpm']*$spendConfig['pointPerImpression'])*$spendConfig[$this->session->userdata('currency')]; ?></td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-mini btn-primary" href="<?php echo site_url('advertiser/campaign/single').'/'.$row['pkey']; ?>">View</a>
                            <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <!-- dropdown menu links -->
                                <li><a href="<?php echo site_url('advertiser/campaign/edit').'/'.$row['pkey']; ?>"><i class="icon-edit"></i> Edit</a></li>
                                <li><a href="<?php echo site_url('advertiser/campaign/remove').'/'.$row['pkey']; ?>"><i class="icon-remove"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="well">
                <p class="lead text-warning" align="center">You haven't created any Campaign till Now.</br>
                    <a class="btn btn-success" href="<?php echo site_url('advertiser/campaign/create');?>">Create a Campaign Now</a>
                </p>
            </div>
            <?php endif;?>
        </div>
    </div>

</div>



<!--Start of Select Campaign Modal-->
<div id="selectCampaign" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Choose a Campaign</h3>

    </div>
    <div class="modal-body">
        <?php  if($campaigns): ?>
        <a href="<?php echo site_url('advertiser/campaign/create') ?>" class="btn btn-primary btn-small btn-success pull-right">Create a New Campaign</a><br/><br/>
        <?php
        $attributes = array('class' => 'form-horizontal');
        echo form_open('advertiser/ad/create', $attributes); ?>

        <table class="table table-bordered table-condensed table-hover">
            <tbody>

                <?php foreach($campaigns as $row): ?>
            <tr>
                <td><input name="campaign_key" checked="" type="radio" value="<?php echo $row['pkey'];?>"><input type="hidden" name="campaign_choose"/> </td>
                <td><?php echo $row['name'] ?></td>
            </tr>

                <?php endforeach;?>


            </tbody>
        </table>
        <?php else: ?>
        <p class="text-error">Sorry! You didn't have created any campaign till now.Please create a campaign now.</p>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <?php if($campaigns):?>
        <button class="btn btn-primary btn-small">Create  Ad</button>
        <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php else: ?>
        <a href="<?php echo site_url('advertiser/campaign/create') ?>" class="btn btn-primary btn-small btn-success">Create a Campaign</a>
        <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Close</button>

        <?php endif; ?>

    </div>
    <?php if($campaigns):?>
    </form>
    <?php endif; ?>
</div>
<!--End of Modal-->


<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','flot/excanvas.min.js'=>'script','flot/jquery.flot.min.js'=>'script','flot/jquery.flot.time.js'=>'script','flot/jquery.flot.axislabels.js'=>'script','flot/jquery.flot.tooltip.min.js'=>'script'));
loadBootstrap('script.min') ;
?>


<script type="text/javascript">
    $('#campaignttp').tooltip();
    $('#statusttp').tooltip();
    $('#startdatettp').tooltip();
    $('#enddatettp').tooltip();
    $('#budgetttp').tooltip();
    $('#clicksttp').tooltip();
    $('#impressionsttp').tooltip();
    $('#ctrttp').tooltip();
    $('#totalspendttp').tooltip();

</script>
<script language="javascript" type="text/javascript">
    $('#myTab a[href="#clicks"]').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        plotClickGraph();
    });

    $('#myTab a[href="#ctr"]').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        plotCtrGraph();
    });

</script>


<script language="javascript" type="text/javascript">

    $(function () {
        plotImpressionsGraph();
    });

    //Graph Of CPM DATA

    function plotImpressionsGraph(){
        var data = <?php echo $cpmGraphData;?>;
        var plotarea = $("#flotimpressions");

        var options = {
            legend: {
                show: true,
                margin: 10,
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
                timeformat: "%d %b"
            },
            grid: {
                hoverable: true,
                backgroundColor: "#fff"
            },
            tooltip: true,
            tooltipOpts: {
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>Impressions: </b>%y</p>",
                shifts: {
                    x: -60,
                    y: 25
                }
            }

        };
        $.plot( plotarea , data, options );
    }

    //Graph of Clicks Data
    function plotClickGraph(){

        var data = <?php echo $clicksGraphData;?>;
        var plotarea = $("#flotclicks");

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
                timeformat: "%d %b"
            },
            grid: {
                hoverable: true,
                backgroundColor: "#fff"
            },
            tooltip: true,
            tooltipOpts: {
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>Clicks: </b>%y</p>",
                shifts: {
                    x: -60,
                    y: 25
                }
            }

        };
        $.plot( plotarea , data, options );

    }

    //Graph of Clicks Data
    function plotCtrGraph(){

        var data = <?php echo $ctrGraphData;?>;
        var plotarea = $("#flotctr");

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
                timeformat: "%d %b"
            },
            grid: {
                hoverable: true,
                backgroundColor: "#fff"
            },
            tooltip: true,
            tooltipOpts: {
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>CTR: </b>%y %</p>",
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


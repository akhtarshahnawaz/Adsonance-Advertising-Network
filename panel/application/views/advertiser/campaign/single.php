<div class="container">
    <div class="row">
        <div class="span12">
            <a href="<?php echo site_url('advertiser/ad/create').'/'.$keyCamp; ?>"  class="btn btn-small pull-right btn-success" >Create an Ad</a>
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


            <?php if(isset($ads) && $ads!=null):?>
            <h2 class="text-info" align="center"><strong>Advertisements</strong>  </h2>

            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Ad Name <a href="#" id="adnamettp" rel="tooltip" title="Ad Name is a name you use to reference a particular advertisement">?</a></th>
                    <th>Status <a href="#" id="statusttp" rel="tooltip" title="Status can be Running, Paused, Stopped or Deleted">?</a> </th>
                    <th>Impressions <a href="#" id="impressionsttp" rel="tooltip" title="Impressions is total number of people who saw your advertisement.">?</a></th>
                    <th>Clicks <a href="#" id="clicksttp" rel="tooltip" title="Clicks is total number of clicks an advertisement get.">?</a></th>
                    <th>CTR <a href="#" id="ctrttp" rel="tooltip" title="CTR or Click Through Rate is rate of conversion of impressions into clicks on a particular advertisement.">?</a></th>
                    <th>Total Spend <a href="#" id="totalspendttp" rel="tooltip" title="Total amount spend on this advertisement.">?</a></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                    <?php if($ads): ?>
                    <?php foreach($ads as $row): ?>
                    <tr>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php if($row['status'] == '0'):?>
                            <span class="label label-warning">Pending Approval</span>
                            <?php elseif($row['status'] == '4'):?>
                            <span class="label label-success">Completed</span>
                            <?php elseif($row['status'] == '1' || $row['status'] == '2' || $row['status'] == '3'):?>
                            <?php if($row['status']== '2' || $row['status']== '1'):?>
                                <div class="btn-group">
                                    <a class="btn dropdown-toggle btn-mini btn-success" data-toggle="dropdown" href="#"><i class="icon-play"></i> Running <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <!-- dropdown menu links -->
                                        <li><a href="<?php echo site_url('advertiser/ad/status').'/'.$keyCamp.'/'.$row['pkey'].'/3'; ?>"><i class="icon-pause"></i> Pause</a></li>
                                    </ul>
                                </div>
                                <?php elseif($row['status']=='3'):?>
                                <div class="btn-group">
                                    <a class="btn dropdown-toggle btn-mini btn-info" data-toggle="dropdown" href="#"><i class="icon-pause"></i> Paused <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <!-- dropdown menu links -->
                                        <li><a href="<?php echo site_url('advertiser/ad/status').'/'.$keyCamp.'/'.$row['pkey'].'/2'; ?>"><i class="icon-play"></i> Start</a></li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            <?php else:?>
                                <p class="text-error"> <?php echo $row['status']; ?></p>
                            <?php endif; ?>
                        </td>
                        <td><?php if(isset($row['cpm'])){echo $row['cpm'];}else{echo '-';} ?></td>
                        <td><?php if(isset($row['clicks'])){echo $row['clicks'];}else{echo '-';} ?></td>
                        <td><?php if(isset($row['cpm']) && isset($row['clicks']) && $row['cpm']!=0){echo $row['clicks']*100/$row['cpm'].' %'; } else { echo '-';}?></td>
                        <td><?php if($this->session->userdata('currency')=='INR'){ echo '&#8377; ';}elseif($this->session->userdata('currency')=='USD'){ echo '$ ';} echo ($row['clicks']*$spendConfig['pointPerClick']+$row['cpm']*$spendConfig['pointPerImpression'])*$spendConfig[$this->session->userdata('currency')]; ?></td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-mini btn-primary" href="<?php echo site_url('advertiser/ad/edit').'/'.$keyCamp.'/'.$row['pkey']; ?>"><i class="icon-edit"></i> Edit</a>
                                <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <!-- dropdown menu links -->
                                    <li><a href="<?php echo site_url('advertiser/ad/remove').'/'.$keyCamp.'/'.$row['pkey']; ?>"><i class="icon-remove"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="well">
                <p class="lead text-warning" align="center">You haven't created any Advertisement in this Campaign.</br>
                    <a class="btn btn-success" href="<?php echo site_url('advertiser/ad/create').'/'.$keyCamp; ?>">Create an Advertisement Now</a>
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
    $('#adnamettp').tooltip();
    $('#statusttp').tooltip();
    $('#impressionsttp').tooltip();
    $('#clicksttp').tooltip();
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
                content: "<p style='margin: 0px'><b>%s</b></p> <p style='margin: 0px'><b>Date: </b>%x</p><p style='margin: 0px'><b>CTR: </b>%y</p>",
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

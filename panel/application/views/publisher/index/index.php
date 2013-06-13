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

    </br>
    <div class="row">
        <div class="span12">
            <?php if($adsList):?>
            <?php  $publisherEarning=$this->session->userdata('totalFriends')*$this->config->item('pointPerImpression')*$this->config->item('usdMultiplier')*$this->config->item('publisherPercentage');
            ?>
            <p class="text-info lead" align="center">Stories to Share</p>
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                <th>Story Title</th>
                <th>Your Earning</th>
                <th></th>
                </thead>
                <tbody>

                    <?php foreach($adsList as $ad):?>
                <tr>
                    <td><?php echo $ad['title'];?></td>
                    <td><?php echo '$ '.$publisherEarning; ?></td>
                    <td><a href="<?php echo site_url('publisher/post/postad')."/".$ad['pkey']; ?>" class="btn btn-success btn-mini">Share Story</a> </td>
                </tr>
                    <?php endforeach; ?>



                </tbody>
            </table>
            <?php else:?>
            <div class="well">
                <p align="center" class="lead text-info">
                    Sorry! Currently no story matches with your profile to Share.Visit later to post Stories.
                </p>
            </div>
            <?php endif;?>
        </div>
    </div>

</div>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a style="padding: 0px;" class="brand" href=http://apps.facebook.com/adsonance><img src="<?php assetLink(array('adsonance-logo.png'=>'image')); ?>"/>   &nbsp;&nbsp;&nbsp;&nbsp;</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="<?php echo site_url('publisher/index/index')?>">Home</a></li>
                    <li class="active"><a href="<?php echo site_url('publisher/billing/index')?>">Earning Details</a></li>
                </ul>

                <a href="<?php echo site_url('publisher/index/sendrequest'); ?>" class="pull-right btn btn-small btn-warning">Invite Friends</a>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

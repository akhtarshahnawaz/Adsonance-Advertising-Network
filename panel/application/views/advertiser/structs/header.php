<?php
$CI =& get_instance();
$page=$CI->uri->segment(2);
?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a style="padding: 0px;" class="brand" href=http://www.adsonance.com><img src="<?php assetLink(array('adsonance-logo.png'=>'image')); ?>"/>   &nbsp;&nbsp;&nbsp;&nbsp;</a>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li <?php if($page == 'index' || $page==''){ echo 'class="active"';} ?>><a href="<?php echo site_url("advertiser") ?>">Home</a></li>
                    <li <?php if($page == 'campaign'){ echo 'class="active"';} ?>><a href="<?php echo site_url("advertiser/campaign") ?>">Campaigns & Ads</a></li>
                    <li <?php if($page == 'billing'){ echo 'class="active"';} ?>><a href="<?php echo site_url("advertiser/billing") ?>">Billing</a></li>
                    <li <?php if($page == 'settings'){ echo 'class="active"';} ?>><a href="<?php echo site_url("advertiser/settings") ?>">Settings</a></li>
                </ul>
                <a class="btn btn-mini btn-warning pull-right" href="<?php echo site_url('advertiser/index/logout')?>"><i class="icon-off"></i> Log Out</a>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

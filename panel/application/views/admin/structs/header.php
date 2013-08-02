<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Adsonance</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="<?php echo site_url("admin/index/index") ?>">Home</a></li>
                    <li class="active"><a href="<?php echo site_url("admin/advertisements/index") ?>">Advertisements</a></li>
                    <li class="active"><a href="<?php echo site_url("admin/advertisers/index") ?>">Advertisers</a></li>
                    <li class="active"><a href="<?php echo site_url("admin/publishers/index") ?>">Publishers</a></li>
                    <li class="active"><a href="<?php echo site_url("admin/publish/index") ?>">Publish Ads</a></li>
                </ul>

                <a href="<?php echo site_url('admin/index/logout');?>" class="btn btn-warning btn-mini pull-right"><i class="icon-off"></i> Logout</a>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

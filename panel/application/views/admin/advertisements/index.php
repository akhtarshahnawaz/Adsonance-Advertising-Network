<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="span12">
        <h2 align="center"> Advertisements Verification </h2>
        <?php if(isset($notification)): ?>
        <div class="alert <?php echo $alertType;?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $notification?>
        </div>
        <?php endif;?>
        <table class="table table-bordered table-condensed table-striped table-hover">
            <thead>
            <th>Image</th>
            <th>Ad Name</th>
            <th>Ad Title</th>
            <th>Ad Link</th>
            <th>Ad Description</th>
            <th></th>
            </thead>
            <tbody>
            <?php if($unverified_Ads):foreach($unverified_Ads as $row): ?>
            <tr>
                <td><img src="<?php echo base_url('').$this->config->item('ImageUploadPath').$row['image']; ?>" width="200px" height="250px" /> </td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['link'];?></td>
                <td><?php echo $row['description'];?></td>
                <td>
                    <a class="btn btn-mini btn-success" href="<?php echo site_url('admin/advertisements/verifyAd').'/'.$row['pkey']; ?>"><i class="icon-ok"></i> Verify</a></br></br>
                    <a class="btn btn-mini btn-warning" href="<?php echo site_url('admin/advertisements/unverifyAd').'/'.$row['pkey']; ?>"><i class="icon-ban-circle"></i> Un-Verify</a>
                </td>
            </tr>
                <?php
            endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>

</div>
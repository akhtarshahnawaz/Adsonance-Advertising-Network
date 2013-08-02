<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="span12">
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
            <th>Credits</th>
            <th></th>
            </thead>
            <tbody>
            <?php if($adstopublish):foreach($adstopublish as $row): ?>
            <tr>
                <td><img src="<?php echo base_url('').$this->config->item('ImageUploadPath').$row['image']; ?>" width="200px" height="250px" /> </td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['link'];?></td>
                <td><?php echo $row['description'];?></td>
                <td><?php echo $row['points'].' points </br></br> '.$row['remainingBalance'].' USD';?></td>

                <td>
                    <a class="btn btn-mini btn-success" href="<?php echo site_url('admin/publish/publishad').'/'.$row['pkey'].'/'.$row['points']; ?>"><i class="icon-ok"></i> Publish Ad</a></br></br>
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
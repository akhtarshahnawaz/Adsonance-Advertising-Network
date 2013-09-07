<div class="container">

    <div class="row">
        <div class="span12">
            <h2 class="" align="center">List of Advertisers</h2>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Advertiser Name</th>
                    <th>Designation</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Website</th>
                    <th>Address</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($advertisers as $row): ?>
                <tr>
                    <?php $address=explode('$~$',$row['address'])?>

                    <td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                    <td><?php echo $row['designation'] ?></td>
                    <td><?php echo $row['company'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['phone'] ?></td>
                    <td><?php echo $row['website'] ?></td>
                    <td><?php echo $address[3].', '.$address[2].', '.$address[1].' - '.$address[0]; ?></td>

                    <td>
                        <div class="btn-group">
                        <a class="btn btn-mini btn-primary" target="_blank" href="<?php echo site_url('admin/advertisers/loginas').'/'.$row['advKeyInfo']; ?>">Login</a>
                            <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>                            <ul class="dropdown-menu">
                                <!-- dropdown menu links-->
                                <li><a href="<?php echo site_url('admin/advertisers/addfund').'/'.$row['advKeyInfo'].'/'.$row['currency']; ?>"><i class="icon-arrow-down"></i> Add Fund</a></li>
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


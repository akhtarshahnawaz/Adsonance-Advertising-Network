<div class="container">

    <div class="row">
        <div class="span12">
            <h2 class="" align="center">List of Publishers</h2>
            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>Publisher Name</th>
                    <th>No.of Friends</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Address</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($publishers as $row): ?>
                <tr>
                    <td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['website'] ?></td>
                    <td><?php echo $row['address'] ?></td>

                    <td>
                        <div class="btn-group">
                            <a class="btn btn-mini btn-primary" href="">View</a>
                            <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- dropdown menu links -->
                                <li><a href=""><i class="icon-remove"></i> View Ads</a></li>
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


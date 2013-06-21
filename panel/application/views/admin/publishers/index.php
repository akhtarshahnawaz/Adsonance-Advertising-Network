<div class="container">
<div class="row">
    <a class="btn btn-success btn-mini pull-right" href="#selectPublishers"  data-toggle="modal"  >Send Notification</a>
</div>
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

<!--Start of Select Campaign Modal-->
<div id="selectPublishers" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Choose Publishers</h3>

    </div>
    <div class="modal-body">
        <?php  if($publishers): ?>
        <?php
        $attributes = array('class' => 'form-horizontal');
        echo form_open('advertiser/ad/create', $attributes); ?>

        <table class="table table-bordered table-condensed table-hover">
            <tbody>
            <a class="btn btn-mini" id="selectAll" href="#">Select All</a>
            <a class="btn btn-mini" id="deselectAll" href="#">De-Select All</a>
<br><br>
                <?php foreach($publishers as $row): ?>
            <tr>
                <td><input name="publisherKey[]" class="publisherKeys" type="checkbox" value="<?php echo $row['pkey'];?>"></td>
                <td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
            </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <?php else: ?>
        <p class="text-error">Sorry! No publishers found!.</p>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <?php if($publishers):?>
        <div class="control-group">
            <div class="controls">
                <input class="input-block-level" name="inputNotification" type="text" id="inputNotification" placeholder="Enter Notification Message">
            </div>
        </div>
        <button class="btn btn-primary btn-small">Send Notification</button>
        <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php else: ?>
        <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php endif; ?>

    </div>
    <?php if($publishers):?>
    </form>
    <?php endif; ?>
</div>
<!--End of Modal-->



<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#deselectAll').hide();
    });
    $('#selectAll').bind('click',checkall);

    $('#deselectAll').bind('click',uncheckall);

    function checkall(evnt){
        var checkboxes=$('.publisherKeys');
        for(var i=0; i<=checkboxes.length;i++){
            checkboxes[i].checked=true;
        }
        $('#selectAll').hide();
        $('#deselectAll').show();
        $('#deselectAll').bind('click',uncheckall);
    }

    function uncheckall(evnt){
        var checkboxes=$('.publisherKeys');
        for(var i=0; i<=checkboxes.length;i++){
            checkboxes[i].checked=false;
        }
        $('#selectAll').show();
        $('#deselectAll').hide();
        $('#selectAll').bind('click',checkall);
    }

</script>


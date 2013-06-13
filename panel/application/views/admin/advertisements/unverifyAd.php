<div class="offset3 span5">


    <?php
    $attributes = array('class' => 'form-horizontal');
    echo form_open('admin/advertisements/unverifyAd', $attributes); ?>

    <?php if(isset($notification)): ?>
    <div class="alert <?php echo $alertType;?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $notification?>
    </div>
    <?php endif;?>


    <fieldset>
        <div class="well">
            <p class="lead text-info">Send message with unverified Advertisement</p>
<input type="hidden" name="adId" value="<?php echo $adId; ?>">
            <div class="control-group">
                <label class="control-label" for="inputMessage">Message</label>
                <div class="controls">
                    <input value=""  name="inputMessage" class="input-xlarge" type="text" id="inputMessage" placeholder="Message">
                </div>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-warning"><i class="icon-ban-circle"></i> Unverify Ad</button>
            <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
        </div>
    </fieldset>

    </form>



</div>
</div>
</div>

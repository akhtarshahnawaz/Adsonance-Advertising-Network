<div class="container">
    <div class="offset2 span8">
        <?php
        $attributes = array('class' => 'form-horizontal');
        echo form_open('advertiser/campaign/create', $attributes); ?>
        <fieldset>
            <legend>Send Notification</legend>
            <div class="control-group">
                <label class="control-label" for="inputTitle">Title</label>
                <div class="controls">
                    <input class="input-xlarge" name="title" type="text" id="inputTitle" placeholder="Title">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputType">Type</label>
                <div class="controls">
                    <select name="type" class="input-medium" id="inputType">
                        <option value="information">Information</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>

                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputMessage">Message</label>
                <div class="controls">
                    <textarea class="input-xlarge" name="message"  type="text" id="inputMessage" placeholder="Message"></textarea>
                </div>
            </div>


            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send</button>
                <button type="button" class="btn">Cancel</button>
            </div>
        </fieldset>
        </form>

    </div>
</div>
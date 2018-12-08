<div class="container">

    <div class="row">
        <div class="span8 offset2">
            <?php
            $attributes = array('class' => 'form-horizontal');
            echo form_open('admin/index/settings', $attributes); ?>
            <fieldset>
                <legend>Admin Settings</legend>

                <div class="control-group">
                    <label class="control-label" for="inputPPI">Point per Impression</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputPPI'];?>" name="inputPPI" type="text" id="inputPPI" placeholder="Point Per Impression">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPPC">Point per Click</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputPPC'];?>"  name="inputPPC" type="text" id="inputPPC" placeholder="Point Per Click">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputDPP">Dollar/Point</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputDPP'];?>"  name="inputDPP" type="text" id="inputDPP" placeholder="Dollar Per Point">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputRPP">Rupees/Point</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputRPP'];?>"  name="inputRPP" type="text" id="inputRPP" placeholder="Rupees Per Point">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPSP">Publishers Share %</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputPSP'];?>"  name="inputPSP" type="text" id="inputPSP" placeholder="Publishers Share Percent">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPASP">Publishers Auto Share %</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputPASP'];?>"  name="inputPASP" type="text" id="inputPASP" placeholder="Publishers Auto Share Percent">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputTBA">Time Between Ads</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $data['inputTBA'];?>"  name="inputTBA" type="text" id="inputTBA" placeholder="Time Between Ads">
                    </div>
                </div>





                <div class="form-actions">
                    <button type="submit" class="btn btn-primary ">Save changes</button>
                </div>
            </fieldset>
            </form>


        </div>
    </div>

</div>

<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','jquery-ui.css'=>'style','jquery-ui.js'=>'script'));
loadBootstrap('script.min') ;
?>

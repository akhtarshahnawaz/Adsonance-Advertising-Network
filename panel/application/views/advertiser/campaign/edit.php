<div class="container">

    <div class="row">
        <div class="span8 offset2">
            <?php
            $attributes = array('class' => 'form-horizontal');
            echo form_open('advertiser/campaign/edit', $attributes); ?>
            <fieldset>
                <legend>Create New Campaign</legend>
                <input type="hidden" name="pkey" value="<?php echo $single['pkey']; ?>">
                <div class="control-group">
                    <label class="control-label" for="inputCampaignName">Campaign Name</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php echo $single['name'];?>" name="inputCampaignName" type="text" id="inputCampaignName" placeholder="Campaign Name">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputBudget">Campaign Budget</label>
                    <div class="controls">
                        <input name="inputBudget" value="<?php echo $single['budget'];?>"   class="input-medium" type="text" id="inputBudget" placeholder="Campaign Budget <?php if($this->session->userdata('currency')=='INR'){ echo '&#8377;';}elseif($this->session->userdata('currency')=='USD'){ echo '$';}?> ">
                        <?php echo $this->session->userdata('currency'); ?>
                        <select name="budgetPeriod" class="input-small">
                            <option <?php if($single['budgetPeriod']=='daily'){echo 'checked';}?> value="daily">Per day</option>
                            <option <?php if($single['budgetPeriod']=='lifetime'){echo 'checked';}?> value="lifetime">Lifetime Budget</option>
                        </select>

                    </div>
                </div>


                <div class="control-group">
                    <label class="control-label" for="inputSchedule">Campaign Schedule</label>
                    <div class="controls">
                        <label class="checkbox">
                            <input name="scheduleType" type="checkbox" id="inputSchedule" <?php if($single['endDate']=='N/A'){echo 'checked="checked"';}?>>
                            Run my campaign continuously starting today
                        </label>
                    </div>
                </div>

                <div id="campaignPeriod" <?php if($single['endDate']=='N/A'){echo 'style="display: none;"';}?>>
                    <div class="control-group">
                        <label class="control-label" for="inputStartDate">Start Date</label>
                        <div class="controls">
                            <input style="cursor:default;  background-color: #fff;"   name="inputStartDate"  value="<?php echo $single['startDate'];?>"  class="input-xlarge" type="text" id="inputStartDate" placeholder="Start Date">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputEndDate">End Date</label>
                        <div class="controls">
                            <input style="cursor:default;  background-color: #fff;"   name="inputEndDate"  value="<?php echo $single['endDate'];?>"  class="input-xlarge" type="text" id="inputEndDate" placeholder="End Date">
                        </div>
                    </div>

                </div>


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
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
<script type="text/javascript">
    $('#inputStartDate').attr('readonly', true);
    $('#inputEndDate').attr('readonly', true);


    $( "#inputStartDate" ).datepicker({
        changeMonth: true,
        numberOfMonths: 2,
        onClose: function( selectedDate ) {
            $( "#inputEndDate" ).datepicker( "option", "minDate", selectedDate );
        }

    });
    $( "#inputEndDate" ).datepicker({
        changeMonth: true,
        numberOfMonths: 2,
        onClose: function( selectedDate ) {
            $( "#inputStartDate" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    $(function(){
        var checked= $('#inputSchedule').is(":checked");
        if(checked){
            $('#campaignPeriod').hide();
        }
        $('#inputSchedule').change(function(){
            var checked= $('#inputSchedule').is(":checked");
            if(checked){
                $('#campaignPeriod').hide();
            }else{
                $('#campaignPeriod').show();

            }
        });

        $('#inputBudget').focusin(function(){
            $('#inputBudget').attr("value","");
        });
    });
</script>

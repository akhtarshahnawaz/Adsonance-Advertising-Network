<div class="container" xmlns="http://www.w3.org/1999/html">

    <div class="row">
        <div class="span8 offset2">

            <?php
            $attributes = array('class' => 'form-horizontal');
            echo form_open_multipart('advertiser/ad/create', $attributes); ?>
            <fieldset>
                <legend>Create New advertisement</legend>
                <div class="well">
                    <div class="span2">
                        <img src="<?php assetLink(array('screenshot.png'=>'image')); ?>" id="adImage" width="200px" height="250px" />
                    </div>
                    <div class="span5">
                        <p class="lead text-info" id="adTitle" style="margin: 1px; padding: 1px;">Your Title Goes Here</p>
                        <p id="adDescription">Your Description Goes here</p>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <input type="hidden" name="campaignKey" value="<?php echo $campaignKey?>"/>
                <input type="hidden" value="null" id="advImage" name="advImage"/>

                <div class="control-group">
                    <label class="control-label" for="inputAdvertisementName">Ad Name <a href="#" id="adnamettp" rel="tooltip" title="Name you to refer to this particular advertisement">[?]</a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="inputAdvertisementName" type="text" id="inputAdvertisementName" placeholder="Advertisement Name">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputTitle">Title <a href="#" id="titlettp" rel="tooltip" title="Heading that will appear in Advertisement">[?]</a></label>
                    <div class="controls">
                        <input name="inputTitle" class="input-xlarge" onkeyup="adTitle()" type="text" id="inputTitle" placeholder="Title of Advertisement">
                        &nbsp;&nbsp;Left:  <b id="titleCount">160</b>
                    </div>
                </div>


                <div class="control-group">
                    <label class="control-label" for="inputLink">Link <a href="#" id="linkttp" rel="tooltip" title="Website Address that will be attached to advertisement">[?]</a></label>
                    <div class="controls">
                        <input name="inputLink" class="input-xlarge" type="text" id="inputLink" placeholder="Link to be attached in Advertisement">

                    </div>
                </div>

                    <div class="control-group">
                        <label class="control-label" for="inputImage">Image <a href="#" id="imagettp" rel="tooltip" title="Image that will appear in Advertisement">[?]</a></label>
                        <div class="controls">
                            <input name="inputImage" class="input-xlarge" type="file" id="inputImage" placeholder="" action="<?php echo site_url('advertiser/ad/uploadImage');?>">
                            <br/><br/>
                        </div>
                    </div>


                <div class="control-group">
                        <label class="control-label" for="inputDescription">Description <a href="#" id="descriptionttp" rel="tooltip" title="Detailed Message that will appear in Advertisement">[?]</a></label>
                        <div class="controls">
                            <textarea name="inputDescription" onkeyup="adDescription()" class="input-xlarge" id="inputDescription"></textarea>
                            <p>Left: <b id="descriptionCount">600</b></p>
                        </div>
                    </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Advertisement</button>
                    <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
                </div>
            </fieldset>
            </form>


        </div>
    </div>

</div>

<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script','ajaxupload.js'=>'script'));
loadBootstrap('script.min') ;
?>

<script type="text/javascript">
    $('#adnamettp').tooltip();
    $('#titlettp').tooltip();
    $('#linkttp').tooltip();
    $('#imagettp').tooltip();
    $('#descriptionttp').tooltip();

</script>
<script type="text/javascript">
    //Updating Ad Title OnKeyUp
    function adTitle(e){
        var msgg=$('#inputTitle').val();
        $('#titleCount').text(160-msgg.length);

        if(msgg.length > 159){
            $('#titleCount').addClass('text-error');
            var msgg=msgg.substring(0,159);
            $('#inputTitle').attr('value',msgg);
        }else{
            $('#titleCount').removeClass('text-error');
            if(msgg!=''){
                $('#adTitle').text(msgg);
            }else{
                $('#adTitle').text('Your Title Goes Here');
            }
        }
    }

    //Updating Ad Description OnKeyUp
    function adDescription(e){
        var msgg=$('#inputDescription').val();
        $('#descriptionCount').text(600-msgg.length);

        if(msgg.length > 599){
            $('#descriptionCount').addClass('text-error');
            var msgg=msgg.substring(0,599);
            $('#inputDescription').attr('value',msgg);
        }else{
            $('#descriptionCount').removeClass('text-error');
            if(msgg!=''){
                $('#adDescription').text(msgg);
            }else{
                $('#adDescription').text('Your Description Goes here');
            }
        }
    }


    //Ajax Image Upload
    $(document).ready(function(){

        var thumb = $('#message');

        new AjaxUpload('inputImage', {
            action: $('#inputImage').attr('action'),
            name: 'inputImage',
            onSubmit: function(file, extension) {
                $('#adImage').attr('src','<?php assetLink(array('loading-screenshot.gif'=>'image'));  ?>');

            },
            onComplete: function(file, response) {
                $('#adImage').attr('src',response);
                var image=response.split('/');
                image=image[image.length-1];
                $('#advImage').attr('value',image);
            }
        });
    });

</script>

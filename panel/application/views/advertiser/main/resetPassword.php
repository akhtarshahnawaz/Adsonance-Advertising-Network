
<style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

    .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }

</style>


<div class="container">
    <?php
    $this->load->helper('form');
    $attributes = array('class' => 'form-signin');
    echo form_open('advertiser/index/resetPassword',$attributes);?>


    <p align="center" style="margin: 10px; padding: 10px;"><a href="http://www.adsonance.com"><img src="<?php assetLink(array('logo-adsonance-black.png'=>'image')); ?>"/></a></p>

    <hr/>


    <?php if(isset($notification)): ?>
    <div class="alert <?php echo $alertType;?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $notification?>
    </div>
    <?php endif;?>

    <div class="control-group">
        <div class="controls">
            <input value=""  name="inputEmail" class="input-block-level" type="email" id="inputEmail" placeholder="Enter Your Email">
        </div>
    </div>

    <button class="btn btn-large btn-inverse btn-block" type="submit">Reset Password</button>
    </form>


</div>
</br></br></br></br></br></br></br></br></br></br>
<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>
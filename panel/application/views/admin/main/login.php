
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
    <?php if(isset($notification)): ?>
    <div class="alert <?php echo $alertType;?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $notification?>
    </div>
    <?php endif;?>
    <?php
    $this->load->helper('form');
    $attributes = array('class' => 'form-signin');
    echo form_open('admin/index/login',$attributes);?>

   


    <h2 class="form-signin-heading" align="center">Adsonance</h2>
        <input name="username"  type="text" class="input-block-level" placeholder="Username">
        <input name="password"  type="password" class="input-block-level" placeholder="Password">
        <button class="btn btn-large btn-block btn-info" type="submit"><i class="icon-lock"></i> Login</button>
                <p class="pull-right"><a href="#" class="text-warning">Forgot Your Password ?</a></p>

    </form>

</div>
<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>
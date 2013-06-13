<?php
if(!empty($_POST)){
    if(!empty($_POST['inputName']) && !empty($_POST['inputEmail']) && !empty($_POST['inputPhone']) && !empty($_POST['inputMessage'])){
        $sento="sakhtar0092@gmail.com";
        $subject="Contact from Adsonance Contact Form";

        $message="Name: ".$_POST['inputName']."\r\n";
        $message.="Email: ".$_POST['inputEmail']."\r\n";
        $message.="Phone: ".$_POST['inputPhone']."\r\n \r\n";
        $message.="Message: ".$_POST['inputMessage'];
        if(mail($sento,$subject,$message)){
            $status="Message Succesfully Send.";
            $statusType=true;
        }else{
            $status="Problem While sending message";
            $statusType=false;
        }
    }else{
        $status="Please fill all the fields";
        $statusType=false;

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Us Adsonance.com Internet Advertising Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="assets/ico/favicon.png">
</head>

<body>

<?php include "include/header.php"; ?>


<h2 align="center" class="text-info">Contact Us</h2>

<div class="well content-holder">

    <div class="row">
        <div class="span8">

            <?php
            if(isset($status) && $statusType==false):?>
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $status; ?>
                </div>
                <?php elseif(isset($status) && $statusType==true): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $status; ?>
                </div>
                <?php endif; ?>
            <form class="well form-horizontal" method="post" action="#" >
                <p class="lead" align="center">Contact Form</p>
                <div class="control-group">
                    <label class="control-label" for="inputName">Name</label>
                    <div class="controls">
                        <input type="text" value="<?php if(!empty($_POST['inputName']) && $statusType!=true){ echo $_POST['inputName'];}?>" name="inputName" class="input-xlarge" id="inputName" placeholder="Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Email</label>
                    <div class="controls">
                        <input type="email" value="<?php if(!empty($_POST['inputEmail']) && $statusType!=true){ echo $_POST['inputEmail'];}?>"  name="inputEmail" class="input-xlarge"  id="inputEmail" placeholder="Email">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPhone">Phone</label>
                    <div class="controls">
                        <input type="text" value="<?php if(!empty($_POST['inputPhone']) && $statusType!=true){ echo $_POST['inputPhone'];}?>"  name="inputPhone" class="input-xlarge"  id="inputPhone" placeholder="Phone">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputMessage">Message</label>
                    <div class="controls">
                        <textarea name="inputMessage"  class="input-xlarge"  id="inputMessage"><?php if(!empty($_POST['inputMessage']) && $statusType!=true){ echo $_POST['inputMessage'];}?> </textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn">Cancel</button>
                </div>
            </form>
        </div>
        <div class="span3 well">
            <address>
                <strong>Adsonance</strong><br>
                S3A/4,Joga Bai Ext.<br>
                OKHLA <br>
                New Delhi-110025<br>
                <abbr title="Phone">P:</abbr> (+91) 981-034-4604<br>
                <abbr title="Email">E:</abbr> support@adsonance.com
            </address>
        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>



</div>
</div>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

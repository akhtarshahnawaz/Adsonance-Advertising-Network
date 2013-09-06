<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Adminstrator Panel</title>
    <?php
    loadBootstrap('style.min');
    ?>
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
        .sidebar{
        }
    </style>
    <?php
    loadBootstrap('style.responsive.min');
    loadAsset(array('advertiser-admin.css'=>'style'));

    ?>
    <link rel="shortcut icon" href="<?php assetLink(array('favicon.png'=>'image'));?>">

</head>

<body>

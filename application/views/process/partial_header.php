<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <base href="<?php echo get_instance()->config->item('base_url'); ?>">
    <link rel="icon" href="./images/favicon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="CGT">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link rel="stylesheet" href="./css/custom.css" type="text/css"/>
<title><?php echo $this->config->item('sis_nombre'); ?></title>
        <base href= "<?php echo $this->config->item('base_url'); ?>">
        <meta name="author" content="José Adrian Ruiz Carmona">
        <link rel="shortcut icon" href="./images/favicon.ico">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/AdminLTE.min.css">
        <link rel="stylesheet" href="./css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="./css/estilo_gral.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="./js/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="./css/bootstrap/js/bootstrap.min.js"></script>
        <script src="./js/jquery.blockUI.js"></script>
        <script src="./js/app.min.js"></script>
        <script src="./js/utilerias.js"></script>
        <script src="./js/utils.js"></script>
</head>
<body>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <span class="navbar-brand"></span>
            <h4 class="navbar-text navbar-right"><?php echo get_instance()->config->item('sis_nombre') ?></h4>
        </div>
    </div>
</div>
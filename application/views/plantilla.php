<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <base href= "<?php echo $this->config->item('base_url'); ?>">
        <title> <?php
            if (isset($title) && $title != '') {
                echo $title;
            } else {
                echo $this->config->item('sis_nombre');
            }
            ?> </title>
        <meta name="author" content="CGT">
        <meta http-equiv="content-type" CONTENT="text/html; charset=utf-8">
        <meta name="author" content="José Adrian Ruiz Carmona">
        <link rel="shortcut icon" href="./images/favicon.ico">
        <!--link rel="shortcut icon" href="./images/favicon.ico">
        <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css" type="text/css"/>
        <link rel="stylesheet" href="./css/estilo_gral.css" type="text/css"/>

        <script type="text/javascript" language="javascript" src="./js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" language="javascript" src="./css/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery.blockUI.js"></script>
        <script src="./js/jquery-1.9.1.js"></script>
        
        <script type="text/javascript" language="javascript" src="./js/js_gral.js"></script-->
        <link href="./css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css" type="text/css"/>   
        <link rel="stylesheet" href="./css/jquery.dataTables.css">
        <link rel="stylesheet" href="./css/jquery.treeview.css" />
        <link rel="stylesheet" href="./css/estilo_gral.css" type="text/css"/>
        <!-- javascript-->
        <script type="text/javascript" language="javascript" src="./js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="./css/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="./js/jqvalidation/jquery.validate.min.js"></script>
        <script type="text/javascript" src="./js/jqvalidation/messages_es.js"></script>
        <script type="text/javascript" src="./js/jquery.blockUI.js"></script>
        <script type="text/javascript" language="javascript" src="./js/utilerias.js"></script>
        <?php
        ob_flush();
        flush();
        $logueado = false;
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        (!$user_id) ? $logueado = false : $logueado = true;
        ?>
        <script type="text/javascript">
            var base_url = '<?php echo $this->config->item('base_url'); ?>';
            var usuario = '<?php echo $user_id ?>';
            $(document).ready(function() {
                function callserver() {
                    var remoteURL = base_url + 'index.php/keepsession';
                    $.get(remoteURL);
                }
                setInterval(function() {
                    callserver();
                }, 180000);

                $("#btn_salir").click(function(e) {
                    e.preventDefault();
                    redirect_to('acceso/logout');
                });

                $('#btn_regresar').click(function(e) {
                    e.preventDefault();
                    redirect_to('inicio');
                });
            });
            
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-58332803-1', 'auto');
            ga('send', 'pageview');


        </script>
    </head>
    <body>
<?php
if ($logueado) {
    ?> 
            <nav id="nav_session" class="navbar navbar-inverse navbar-fixed-top">
                <div class="row">
                    <div class="col-md-5"><div class="lblnav"><i class="fa fa-th-list"></i> Adminre Web</div></div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control " value="<?php echo $this->session->userdata('nombre' . $clv_sess); ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-2"> <button id="btn_regresar" class="btn btn-primary"><i class="fa fa-mail-reply"></i> Regresar</button></div>
                    <div class="col-md-2"> <button id="btn_salir" class="btn btn-primary"><i class="fa fa-sign-out"></i> Salir</button></div>
                </div>
            </nav>
    <?php
}
?> 
        <div id="body_admre" class="container">
            <?php
            if (isset($banner_lg) && $banner_lg) {
                ?> 
                <div id="header" class="row">
                    <div id="banner" class="col-md-12"></div>
                </div>
                        <?php
                    }
                    ?>
            <div id="content" class="row">
                <div id="contenidho" class="col-md-12">
<?php
if (isset($contenido) && ($contenido != '')) {
    echo $contenido . PHP_EOL;
}
?>  
                </div>
            </div>
            <div id="footer" class="row">
                <div class="col-md-12">
<?php
setlocale(LC_TIME, 'Spanish');
echo ' &copy; ' . date("F") . " " . date("Y");
?> 
                    Universidad Veracruzana. Todos los derechos reservados<br>
<?php echo $this->config->item('sis_nombre'); ?> / 
                    Instituto de Investigaciones en Educación (IIE)
                </div>
            </div>
        </div>
    </body>
</html>

<style>
    .menu_item .fa { font-size: 50px; color: #1F648C;}
    .lbl_menu {color: #1F648C; font-weight: bold; margin-top: 5px; line-height: 15px;}
    .content_menu_item {
        border: 3px dotted #1F648C;
        text-align: center;
        padding: 12px;
        background-color: white;
    }
    .menu_item_fa_hover{
        color: #16435E !important;
    }
    .lbl_menu_hover {
        color: #16435E;
        font-weight: bold;
        margin-top: 5px;
    }
    .content_menu_item_hover {
        border: 3px dotted #16435E;
        text-align: center;
        padding: 12px;
        background-color: rgb(237, 237, 237);
    }
    #id_menu{ min-height: 400px;}
    .menu_item {
        margin-bottom: 15px;
    }
    #btn_regresar{display: none;}
</style>
<?php
if (!isset($rol)) {
    $rol = 'ADM';
}
?>
<div id="id_menu" class="row">
    <div id="menu_modules" class="col-md-10 col-md-offset-1">
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('misreactivos');"><div class="content_menu_item"><i class="fa fa-star"></i> <div class="lbl_menu">Mis Reactivos <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('reactivo');"><div class="content_menu_item"><i class="fa fa-list"></i> <div class="lbl_menu">Reactivos <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('plan');"><div class="content_menu_item"><i class="fa fa-file-text"></i> <div class="lbl_menu">Planes <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('referencias');"><div class="content_menu_item"><i class="fa fa-book"></i> <div class="lbl_menu">Referencias <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('caso');"><div class="content_menu_item"><i class="fa fa-outdent"></i> <div class="lbl_menu">Casos <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('reportes');"><div class="content_menu_item"><i class="fa fa-file"></i> <div class="lbl_menu">Reportes <br> &nbsp;</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('revisar_reactivo');"><div class="content_menu_item"><i class="fa fa-thumbs-up"></i> <div class="lbl_menu">Revisar <br> Reactivos</div></div></div><?php } ?>
        <?php if (in_array($rol, array('ADM', 'B'))) { ?><div class="col-md-2 menu_item" onclick="redirect_to('usuarios_sistema');"><div class="content_menu_item"><i class="fa fa-users"></i> <div class="lbl_menu">Usuarios de <br>sistema</div></div></div><?php } ?>
    </div>
</div> 
<script>
    $(document).ready(function() {
        $('.content_menu_item').hover(function() {
            $(this).addClass("content_menu_item_hover");
            $(this).find('.fa').addClass("menu_item_fa_hover");
            $(this).find('.lbl_menu').addClass("lbl_menu_hover");
        }, function() {
            $(this).removeClass("content_menu_item_hover");
            $(this).find('.fa').removeClass("menu_item_fa_hover");
            $(this).find('.lbl_menu').removeClass("lbl_menu_hover");
        });
    });
</script>
<?php
$selects_vals = $changerol = '';
$foto = $login = $nombre = $apaterno = $amaterno = $email = $telefono = $lada = $celular = $rol = $estado = $permisos = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica->id;
    $login = $datos_modifica->login;
    $foto = $datos_modifica->foto;
    $nombre = $datos_modifica->nombre;
    $apaterno = $datos_modifica->apaterno;
    $amaterno = $datos_modifica->amaterno;
    $email = $datos_modifica->email;
    $lada = $datos_modifica->lada;
    $telefono = $datos_modifica->telefono;
    $celular = $datos_modifica->celular;
    $rol = $datos_modifica->rol;
    $estado = $datos_modifica->estado;
}
if (file_exists('images/perfil_usuarios_sistema/' . $foto) && $foto != '') {
    $foto_url = './images/perfil_usuarios_sistema/' . $foto;
} else {
    $foto_url = './images/no_photo.jpg';
}
?>
<style>
    #div_1{ overflow: hidden;background-color: #fff; padding: 0px;}
    img.img-circle {width: 120px;height: 120px;border: 5px solid;border-color: rgba(255,255,255,0.2); margin-bottom: 10px;}
    .foto_perfil {background-color: #574F78; padding: 15px;text-align: center; color: #fff; font-weight: bolder; font-size: 16px;}
    .data_perfil{background-color: #fff; padding: 15px; font-size: 16px;}
</style>
<div class="row">
    <div id="div_1" class="col-md-10 col-md-offset-1">
        <div class="col-md-4 foto_perfil col-xs-12 col-sm-12">
            <img class="img-circle" src="<?php echo $foto_url; ?>" alt="Foto Usuario"/>
            <div><?php echo $login; ?></div>
            <div><?php echo $nombre.' '.$apaterno.' '.$amaterno; ?></div>
        </div>
        <div class="col-md-8 data_perfil col-xs-12 col-sm-12">
            <div><b>Correo: </b><?php echo $email; ?></div>
            <div><b>Teléfono: </b><?php echo '('.$lada.') '.$telefono; ?></div>
            <div><b>Celular: </b><?php echo $celular; ?></div>
            <div><b>Estado de cuenta: </b><?php if ($estado=='0') {
     echo 'Activa';
}else{
    echo 'Inactiva';
}; ?></div>
        </div>
    </div>
</div>



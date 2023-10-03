<?php
// configuración básica del sistema
$config['sis_nombre'] = 'Admin RE';
$config['sis_version'] = 'Beta';
$config['sis_admin_nombre'] = '';
$config['sis_admin_correo'] = '';
// configuración básica de la empresa
$config['emp_nombre'] = 'TECNM-ITSX';
$config['emp_url'] = 'https://www.itsx.edu.mx/v2/';
//
$config['clave_master'] = '29890505';
$config['clv_sess'] = "@InstiEdu890505"; //clave de session para diferenciar datos de otros sitios

//////// Plugins /////////
$config['plg_tooltipster'] =true;  //Plugin que permite visualizacion de tips al agregar el atributo title: ver http://iamceege.github.io/tooltipster/

/////// Roles /////////////
$config['roles'] = array(
    'SAD' => array('rol' => 'Super Administrador', 'permisos' => 'rea>t|pla>t|ref>t|cas>t|rep>t|usu>t'),
    'ADM' => array('rol' => 'Administrador', 'permisos' => 'rea>t|pla>t|ref>t|cas>t|rep>t'),
    'CAP' => array('rol' => 'Capturista', 'permisos' => 'rea>i|pla>t|ref>t|cas>t|rep>t')
);


/**
 * La cadena de permisos se forma por : nombre de modulo (con 3 caracteres) seguido del simbolo ">" y despues una cadena con permisos cada caracter representa un permiso para el modulo
 * Cada modulo puede tener el permiso 't'el cual no restringe acceso a nada Ejemplo: 'esc>t'  donde el modulo de escuelas tiene todos los permisos el permisos v=  solo lectura donde se tendrá acceso al modulo pero no a ciertas características de el
 *  ejemplo: sol>abc el modulo de solicitudes tiene los permisos a, b y c
 *  La separacion entre modulos se presenta mediante el caracter '|' 
 */
// permisos cap,admr,sec,sed,sue,ofe,car,coo,cnd,con,cop,csu,dir,ger,gaf,fot,fin,wsr,art,usu
$config['descripcion_permisos'] = array(
    'Reactivos' => array('clave' => 'rea', 'permisos' => array('i'=>'Cambiar Autor')),
    'Planes' => array('clave' => 'pla', 'permisos' => array()),
    'Referencias' => array('clave' => 'ref', 'permisos' => array()),
    'Casos' => array('clave' => 'cas', 'permisos' => array()),
    'Reportes' => array('clave' => 'rep', 'permisos' => array()),
    'Usuarios del sistema' => array('clave' => 'usu', 'permisos' => array('a' => 'Agregar usuarios', 'b' => 'Eliminar usuarios', 'm' => 'Modificar usuarios'))
);

?>

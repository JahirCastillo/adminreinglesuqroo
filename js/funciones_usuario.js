$(document).ready(function(){

	//--datatable Usuario-----
	$('#datosUsuario').dataTable( {
		"sDom": "<'row'<'span1'><'span5'l><'span6'f>r>t<'row'<'span1'><'span5'i><'span7'p>>",
		"oLanguage": {
			 "sInfo": " _START_ a _END_ de _TOTAL_ registros",
			  "sLengthMenu": 'Mostrar <select>'+
			   '<option value="5">5</option>'+
			   '<option value="10">10</option>'+
			   '<option value="15">15</option>'+
			   '</select> por página',
		},
		"aoColumns": [
        	{ "sWidth": "20%" },
        	{ "sWidth": "40%" },
			{ "sWidth": "10%", "sClass": "center" },
			{ "sWidth": "10%", "sClass": "center" },
			{ "sWidth": "10%", "sClass": "center" },
			{ "sWidth": "5%", "sClass": "center" },
			{ "sWidth": "5%", "sClass": "center" },
    	],
	} );
	
	//--------boton de usuario activo
	$("#usu_activo").click(function(){
		$("#usu_activo").addClass('active btn-success');
		$("#usu_inactivo").removeClass('active btn-danger');
	});
	
	//------boton de usuario inactivo
	$("#usu_inactivo").click(function(){
		$("#usu_inactivo").addClass('active btn-danger');
		$("#usu_activo").removeClass('active btn-success');
	});
});

//-----------dialogo de eliminacion de usuario------------------
/**
* questiona si desea eliminar el usuario.
*
*@param int clave, identificador del usuario
*@param varchar nombre, nombre del usuario.
*/
function borrarUsuario(clave, nombre){
	//console.log(clave+' '+nombre);
	$("#clave").html(clave);
	$("#nombre").html('<h4>'+nombre+'</h4>');
	$.blockUI({ message: $('#eliminar'), 
				css: { 
					width: '350px', 
					right: '10px', 
					border: 'none', 
					padding: '5px', 
					backgroundColor: '#000', 
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .6, 
					color: '#fff'  
				}
	});
}

//----------elimina usuario------------------
/**
* si el cliente asegura la eliminacion del usuario ejecuta la siguiente funcion.
*
*@param int clave, identificador del usuario.
*@return mensaje de exito o error.
*/
function yes(){
	clave=$("#clave").html();
	$.blockUI({ message: "<h4>Remote call in progress...</h4>" }); 
    $.post('index.php/usuario/borrarUsuario',{clave:clave}, 
	function(dato) { 
    	$.unblockUI(); 
		nuevoUsuario();
		mensaje(dato);
    }); 
} 
 
function no() {
	$.unblockUI(); 
	return false; 
}
 


//-----mensajes de block ui----
function mensaje(msg){
  $.blockUI({ 
	message: msg, 
	fadeIn: 700, 
	fadeOut: 700, 
	timeout: 2000, 
	showOverlay: false, 
	centerY: false, 
	css: { 
		width: '350px', 
		top: '10px', 
		left: '', 
		right: '10px', 
		border: 'none', 
		padding: '5px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		opacity: .6, 
		color: '#fff' 
    } 
  });  
}

//-----------limpia validacion------------------
/**
* oculta todos los mensajes que validacion muestra.
*/
function limpiar_validar(){
	$("#usu_login").removeClass('error');
	$("#msg_usulogin").empty();
	$("#usu_password").removeClass('error');
	$("#msg_usupassword").empty();
	$("#usu_tipo").removeClass('error');
	$("#msg_usutipo").empty();
	$("#usu_nombre").removeClass('error');
	$("#msg_usunombre").empty();
	$("#usu_email").removeClass('error');
	$("#msg_usuemail").empty();
	$("#usu_telefono").removeClass('error');
	$("#msg_usutelefono").empty();
	$("#datosObligatorios").hide();
}

//-----------guardar usuario------------------
/**
* ingresa o actualiza los datos del formulario de usuario.
*
*@return mensaje de exito o error.
*/
function guardarUsuario(){
	cla=$("#usu_clave").val();
	login=$("#usu_login").val();
	password=$("#usu_password").val();
	tipo=$("#usu_tipo").val();
	if($("#usu_activo").hasClass('active')) estado=1;
	else estado=0;
	nombre=$("#usu_nombre").val();
	direccion=$("#usu_direccion").val();
	telefono=$("#usu_telefono").val();
	email=$("#usu_email").val();
	puesto=$("#usu_puesto").val();
	//console.log(cla+' '+login+' '+password+' '+nombre+' '+direccion+' '+telefono+' '+email+' '+puesto+' '+estado+' '+tipo);
	$.post('index.php/usuario/guardarUsuario',{cla:cla, login:login, password:password, nombre:nombre, direccion:direccion, telefono:telefono, email:email, puesto:puesto, estado:estado, tipo:tipo},
	function(data){
		mensaje(data);
		$("#btn_nuevoUsuario").show();
	});
}

//-----------validadar usuario------------------
/**
* valida los datos del formulario para ingresar un usuario.
*/
function validarUsuario(){
	pase=1;
	limpiar_validar();
	login=$("#usu_login").val();
	if(login.length < 4){
		pase=0;
		$("#usu_login").addClass('error');
		$("#msg_usulogin").append(' Se requiere 4 o más caracteres.');
	}
	//console.log('login:'+login);
	$.post('index.php/usuario/loginUsuario',{login:login},
	function(data){
		//console.log($("#titulo").html());
		if(data==1 && $("#titulo").html()=='<h3>Nuevo Usuario</h3>'){
			pase=0;
			$("#usu_login").addClass('error');
			$("#msg_usulogin").append(' El Usuario ya existe, favor de cambiarlo.');	
		}
	});
	password=$("#usu_password").val();
	if(password.length < 4){
		pase=0;
		$("#usu_password").addClass('error');
		$("#msg_usupassword").append('Se requiere 4 o más caracteres.');
	}
	tipo=$("#usu_tipo").val();
	if(tipo==0){
		pase=0;
		$("#usu_tipo").addClass('error');
		$("#msg_usutipo").append('Se requiere el tipo de usuario.');
	}
	nombre=$("#usu_nombre").val();
	if(nombre == ''){
		pase=0;
		$("#usu_nombre").addClass('error');
		$("#msg_usunombre").append('Se requiere el nombre del usuario.');
	}
	email=$("#usu_email").val();
	if(email!=''){
		if (email.match('^[A-z0-9._%-]+@[A-z0-9.-]+\.[A-z]{2,4}$') != email) {
			pase=0;
			$("#usu_email").addClass('error');
			$("#msg_usuemail").append('El e-mail es inválido.');
		}
	}
	telefono=$("#usu_telefono").val();
	if(telefono!=''){
		if(isNaN(telefono)) {  
		    pase=0;
        	$("#usu_telefono").addClass('error');
			$("#msg_usutelefono").append('Solo debe contener números.');
    	}  
	}
	if(pase==1){
		guardarUsuario(); //guarda datos del usuario.
	}
	else{
		$("#datosObligatorios").show();
	}
}

//---------datos usuario-------
/**
* visualiza los datos de un usuario especifico
*
*@param int clave, identificador del usuario.
*/
function datosUsuario(clave){
	limpiar_validar();
	$.post('index.php/usuario/datosUsuario',{clave:clave},
	function(datos){
		$("#titulo").html('<h3>Editar Usuario</h3>');
		$("#usu_clave").val(datos.cla);
		$("#usu_login").val(datos.usu);
		$("#usu_password").val(datos.pas);
		$("#usu_nombre").val(datos.nom);
		$("#usu_direccion").val(datos.dir);
		$("#usu_telefono").val(datos.tel);
		$("#usu_email").val(datos.ema);
		$("#usu_puesto").val(datos.pue);
		$("#usu_tipo").val(datos.tip);
		if(datos.est==1){ 
			$("#usuario_activo").addClass('active btn-success');
			$("#usuario_inactivo").removeClass('active btn-danger');
		}
		else{ 
			$("#usuario_activo").removeClass('active btn-success');
			$("#usuario_inactivo").addClass('active btn-danger');
		}
		$("#btn_nuevoUsuario").show();
		$("#btn_guardarUsuario").val('Guardar Nuevo');
	},'json');
}


//-----------nuevo usuario-----
/**
* limpia formulario para la captura de un usuario nuevo.
*/
function nuevoUsuario(){
	$("#titulo").html('<h3>Nuevo Usuario</h3>');
	$("#btn_nuevoUsuario").hide();
	$("#usu_clave").val('');
	$("#usu_login").val('');
	$("#usu_password").val('');
	$("#usu_nombre").val('');
	$("#usu_direccion").val('');
	$("#usu_telefono").val('');
	$("#usu_email").val('');
	$("#usu_puesto").val('');
	$("#usu_tipo").val('');
	$("#usuario_activo").removeClass('active btn-success');
	$("#usuario_inactivo").addClass('active btn-danger');
	$.post('index.php/usuario/claveUsuario',
	function(clave){
		$("#usu_clave").val(clave);	
	});
}


//-----------buscar registros de usuario------------------
/**
* busca concidencia de registros en usuarios.
*
*@param varchar cadena, cadena a buscar en reactivos.
*@return array, registros que contiene la cadena parametro palabra.
*/
function buscarUsuario(cadena){
	$("#mostrarUsuario").show();
	$.post("index.php/usuario/buscarUsuario",{cadena:cadena},
	function(data) {
		$('#datosUsuario').dataTable().fnClearTable();
		$.each(data, function(i,aDatos){
			nombre="'"+aDatos.nom+"'";
			//console.log(i+' '+aDatos.cla);
			if(aDatos.est == 1) estado = 'Activo';
			else estado = 'Inactivo';
        	$('#datosUsuario').dataTable().fnAddData([
			 aDatos.usu,
			 aDatos.nom,
			 aDatos.tip,
			 estado,
			 aDatos.fec,
			  '<button class="btn btn-success" onClick="datosUsuario('+aDatos.cla+')">Ver</button>',
			 '<button class="btn btn-danger" onClick="borrarUsuario('+aDatos.cla+','+nombre+')"><i class="icon-remove"></i></button>',
        	]);
    	});
	},'json');
}



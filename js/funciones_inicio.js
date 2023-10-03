$(document).ready(function(){
	/**
	* muestra nombre de usuario si la validacion del usuario es correcta.
	* 
	* @param varchar login
	* @return nombre de usuario.
	*/
	$("#usu_login").change(function(){
		login=$("#usu_login").val();
		$.post("index.php/inicio/validarUsuario", {login:login}, 
		function(data) {
			if(data != ''){
				console.log(data);
				$("#nombreUsuario").html('<label class="label label-info"><h5>'+data+'</h5></label>');
				$("#usu_password").removeAttr('disabled','disabled');
				$("#usu_password").focus();
			}
			else{
				$("#nombreUsuario").html('<label class="label label-important"><h5> Usuario Incorrecto </h5></label>');
				$("#usu_password").val('');
				$("#usu_password").attr('disabled','disabled');
			}
		});
	});
	
	$("#btn_clear_login").click(function(){
		$("input").empty();	
	});
});





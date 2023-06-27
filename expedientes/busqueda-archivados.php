<?php  
include('../estructura/navegacion.php');
?>

<body>
  <div class="container-fluid">
  	<hr>
    <h1 class="titulo">BÚSQUEDA DE EXPEDIENTES ARCHIVADOS</h1><hr><br>
     <input type="text" name="busqueda" id="busqueda" placeholder=" Ingresar ID o tipo o tramite o documento o cuit o código" maxlength="15" autocomplete="off"><br><br>
	
	<section id="tabla_resultado"><!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA --></section>

  </div>
</body>

<script type="text/javascript">

$(obtener_registros());
function obtener_registros(usuario){
	$.ajax({
		url : 'buscar-archivados.php',
		type : 'POST',
		dataType : 'html',
		data : {usuario: usuario},
		})
	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
	})
}

$(document).on('keyup', '#busqueda', function(){
	var valorBusqueda=$(this).val();
	if (valorBusqueda!=""){
		obtener_registros(valorBusqueda);
	}
	else{
		obtener_registros();
	}
});

</script>


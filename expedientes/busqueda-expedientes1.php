<?php  
include('../estructura/navegacion.php');
?>

<body>
  <div class="container-fluid">
    <span class="subtituloMenu">BÚSQUEDA DE EXPEDIENTES</span><br>
    <span style="color: red; font-size: 15px;">No archivados y no ocultos</span><br><br>
     <input type="text" name="busqueda" id="busqueda" placeholder=" Ingresar ID o tipo o tramite o documento o cuit o código" maxlength="15" autocomplete="off"><br><br>
	
	<section id="tabla_resultado"><!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA --></section>

  </div>
</body>


<script type="text/javascript">

$(obtener_registros());
function obtener_registros(usuario){
	$.ajax({
		url : 'buscar-expedientes1.php',
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


<style type="text/css">
#busqueda{
  text-transform:uppercase; 
  width:530px;
  height:40px; 
  border-radius: 10px; 
  border-color: black;
  font-size: 14px;
}
</style>



   
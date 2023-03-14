<?php 
include('../estructura/navegacion.php');
?>

<!DOCTYPE html>
<html lang="es">
<body>

  <div class="container" id="mycontainer">
    <form id="formularioBusqueda">
      <span class="subtituloMenu">BÚSQUEDA DE EXPEDIENTE</span><br>
      <input type="text" name="busqueda" id="busqueda" placeholder=" Ingrese id del expediente o numero de documento o cuit o tipo o trámite" maxlength="15" autocomplete="off">
      <input type="submit" id="botonBuscar" value="Buscar">
      <input type="submit" id="botonLimpiar" value="Limpiar">
  </form><br>
  <div id="loader" style="display:none"></div>

	<section id="tabla_resultado"><!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA --></section>
  
</div>
</body>
</html>


<script type="text/javascript">
$('#botonLimpiar').click(function(evento){
    evento.preventDefault();
    $("#formularioBusqueda")[0].reset();
    $("#tabla_resultado").html(""); 
  });

$('#botonBuscar').click(function(evento){
  evento.preventDefault();
  var valorBusqueda = $("#busqueda").val();
  if(validarFormulario()){
    $.ajax({
      url : 'buscar-expedientes2.php',
      type : 'POST',
      dataType : 'html',
      data : {valorBusqueda: valorBusqueda},
        beforeSend: function() {
          $("#loader").css('display','block');
        },
      })
    .done(function(resultado){
      $("#loader").css('display','none');
      $("#tabla_resultado").html(resultado);
    });
  }
});

function validarFormulario(){
  if($("#busqueda").val() == ""){
    return mensajeError("Debe ingresar datos para la búsqueda");
    $("#busqueda").focus();
    return false;
  }
  return true;
}

function mensajeError($mensaje){
  swal.fire({
    title: "ERROR",
    text: $mensaje,
    icon: 'error',
    width:'550px',
    allowOutsideClick: false,
    confirmButtonColor: '#007bff',
  });
}
</script>


<style type="text/css">
  #mycontainer { max-width: 1300px !important; }
  #busqueda{
    text-transform:uppercase; 
    width:500px;
    height:40px; 
    border-radius: 10px; 
    border-color: black; 
    font-size: 11px;
  }
  #botonBuscar{
    text-transform:uppercase; 
    height:40px; 
    border-radius: 10px; 
    font-size: 11px;
    background-color: #3181C3;
    border-color: black; 
    color:white;
  }
  #botonBuscar:hover{
    background-color: white;
    border-color: #3181C3;
    color:#3181C3 !important;
  }

  #botonLimpiar{
    text-transform:uppercase; 
    height:40px; 
    border-radius: 10px; 
    font-size: 11px;
    background-color: red;
    border-color: black; 
    color:white;
  }
  #botonLimpiar:hover {
    background-color: white;
    border-color: red;
    color:red !important;
  }

  #loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../util/imagenes/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
</style>



   
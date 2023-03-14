<?php  
session_start();
if (!isset($_SESSION['documento'])){
  header('Location: ../index.php');
}
else{
  include("../admin/conexion.php");
  include('../util/funcionesexp.php');
  $documento = $_SESSION['documento'];
  $usuario = $_SESSION['usuario'];
  $idUsuario = $_SESSION['id_usuario'];
  $tipoUsuario = $_SESSION['tipo_usuario'];
  $idAreaUsuario = idAreaUsuario($documento);

  $usuario1 = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento'");
  $usuario1 = mysqli_fetch_assoc($usuario1);
  $buscarExpedientes = $usuario1['buscar_expedientes'];
  $generarExpediente = $usuario1['generar_expediente'];
  $trabajarAnuales = $usuario1['trabajar_anuales'];
  $buscarAnuales = $usuario1['buscar_anuales'];
  $listadosExpedientes = $usuario1['listados_expedientes'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <title>Sistema Mutual Web</title>
  <link rel="icon" type="image/png" href="../util/imagenes/logo-mppn.png"/>
  <link rel="stylesheet" type="text/css" href="../util/css/bootstrap.min.css">
    <script type="text/javascript" src="../util/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="../util/js/popper.min.js"></script>
    <script type="text/javascript" src="../util/js/bootstrap.min.js"></script>
    <script src="../util/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../util/css/sweetalert2.min.css">
    <script src="../util/js/sweetalert2@10.js"></script>
    <link rel="stylesheet" href="../util/css/estilo.css?v50" type="text/css">
    <link rel="stylesheet" type="text/css" href="../util/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" src="../util/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../util/js/dataTables.bootstrap5.min.js"></script> 
</head>


<div class="container" align="center">
  <img src="../util/imagenes/cabecera1.png" height="100px" style="margin-top: 15px">
</div><br>

<header>
  <!--nav class="navbar navbar-expand-lg navbar-dark bg-dark"-->
  <nav class="navbar navbar-expand-lg" id="barraNavegacion">
  <div class="container-fluid">

    <ul class="navbar-nav">
       
      <li class="nav-item" style="margin-right: 20px;">
        <a class="nav-link" href="../principal/menu-principal.php">Inicio</a>
      </li>

      <li class="nav-item" style="margin-right: 20px;">
        <a class="nav-link" href="../expedientes/index.php" role="button">Expedientes</a>
      </li>

      <li class="nav-item dropdown" style="margin-right: 20px;">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Afiliados</a>
        <ul class="dropdown-menu" style="margin-top: 8px;">
          <li><a class="dropdown-item" href="">Búsqueda</a></li>
          <li><a class="dropdown-item" href="">Registrados</a></li>
        </ul>
      </li>

      <li class="nav-item" style="margin-right: 20px;">
        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">Listados</a>
      </li>

      <li class="nav-item" style="margin-right: 20px;">
        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">Turismo</a>
      </li>

      <li class="nav-item" style="margin-right: 20px;">
        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">Becas</a>
      </li>

      <li class="nav-item dropdown" style="margin-right: 20px;">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mi perfil</a>
        <ul class="dropdown-menu" style="margin-top: 8px;">
          <li><a class="dropdown-item" href="">Mis datos</a></li>
          <li><a class="dropdown-item" href="">Cambiar clave</a></li>
        </ul>
      </li>

    </ul>

    <ul class="nav navbar-nav navbar-right">
      <span id="nombreUsuario" style="margin-top: 9px; margin-right: 10px; color: white"><?php echo "Usuario: ".$usuario?></span>
      <a href="" class="btn" id="salir" title="Salir">Salir</a>
    </ul>

  </div>
</nav><br>
</header>
</html>


<script type="text/javascript">

$('#salir').click(function(evento){
  evento.preventDefault();
  Swal.fire({
    title: '¿Desea cerrar su sesión?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#148F77',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
   }).then((result) => {  
    if (result.isConfirmed) {
      $.ajax({
        url: '../estructura/cerrar-sesion.php',
        type:'POST',
        processData: false,
        contentType: false,
        beforeSend: function() {
          //$("#loader").css('display','block');
        },
        success: function(data){
        console.log(data);
        //$("#loader").css('display','none');
          var jsonData = JSON.parse(data);
          if(jsonData.salida == 0){
            //localStorage.removeItem("sesion");
            //sessionStorage.setItem("sesion", "1");
            //console.log("salida: "+localStorage.getItem("sesion"));
            return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
          }
          else{
            return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
          }
        }
       });
      return false;
    }
});
});

function mensajeError($mensaje1, $mensaje2){
  swal.fire({
    icon: 'error',
    title: $mensaje1,
    text: $mensaje2,
    icon: 'error',
    width:'550px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExito($mensaje1, $mensaje2){
  Swal.fire({
    icon: 'success',
    title: $mensaje1, 
    text: $mensaje2,
    width:'550px',
    allowOutsideClick: false,
    }).then(function(){
      //window.open('../index.php');
      window.location.replace("../index.php");
  });
}
</script>

<style type="text/css">
.nav-link{
  color: white;
}

.dropdown-item:hover{
  background-color: #0072bc !important;
  color: white !important;
  cursor: pointer;
  border-radius: 5px;
}
.dropdown-toggle:hover, .nav-link:hover{
  color: #A4A2A2 !important;
  cursor: pointer;
  border-radius: 5px;
}

#salir{
  text-decoration:none;
  color: white;
  background-color: red;
  font-size: 18px;
  font-family: Georgia;
  margin-right: 1px; 
  float:right;
}

#salir:hover{
   color: red;
   background-color:white;
}
</style>
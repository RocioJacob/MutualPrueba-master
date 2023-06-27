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


  $usuario1 = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento' and habilitado='0'");
  $usuario1 = mysqli_fetch_assoc($usuario1);
  $buscarExpedientes = $usuario1['permiso_buscar_expedientes']; //Si puede buscar expedientes
  $generarExpediente = $usuario1['permiso_generar_expediente']; //Si puede generar expediente

  $autorizarExpediente = $usuario1['permiso_autorizar_expediente'];
  $marcarAnual = $usuario1['permiso_marcar_anual'];
  $desmarcarAnual = $usuario1['permiso_desmarcar_anual'];
  $trabajarConArchivados = $usuario1['permiso_trabajar_archivados'];
  $trabajarConArchivadosOcultos = $usuario1['permiso_trabajar_archivados_ocultos'];
  $trabajarConExpedientes2020 = $usuario1['permiso_trabajar_expedientes_2020'];
  $trabajarConExpedientes2021 = $usuario1['permiso_trabajar_expedientes_2021'];
  
  
  //Archivos
  $eliminarArchivo = $usuario1['permiso_eliminar_archivo'];
  $agregarArchivo = $usuario1['permiso_agregar_archivo'];
  $verArchivo = $usuario1['permiso_ver_archivo'];


  //Expedientes anuales
  $trabajarAnuales = $usuario1['permiso_trabajar_anuales']; //permite marcar y desmarcar como anual


  //Expedientes archivados
  $trabajarConArchivados = $usuario1['permiso_trabajar_archivados'];
  $archivarExpediente = $usuario1['permiso_archivar_expediente'];
  $eliminarArchivoArchivado = $usuario1['permiso_eliminar_archivo_archivado'];
  $desarchivarExpediente = $usuario1['permiso_desarchivar_expediente'];
  $agregarArchivoArchivado = $usuario1['permiso_agregar_archivo_archivado'];
  $verArchivoArchivado = $usuario1['permiso_ver_archivo_archivado'];

  //Expedientes ocultos
  $ocultarExpediente = $usuario1['permiso_ocultar_expediente'];
  $desocultarExpediente = $usuario1['permiso_desocultar_expediente'];
  $archivarOculto = $usuario1['permiso_archivar_oculto'];
  $desarchivarOculto = $usuario1['permiso_desarchivar_oculto'];
  $trabajarOcultos = $usuario1['permiso_trabajar_ocultos'];
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
    <link rel="stylesheet" href="../util/css/estilo.css?v7" type="text/css">
    <link rel="stylesheet" type="text/css" href="../util/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" src="../util/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../util/js/dataTables.bootstrap5.min.js"></script> 
</head>


<header>
  <!--nav class="navbar navbar-expand-lg navbar-dark bg-dark"-->
  <nav class="navbar navbar-expand-lg" id="barraNavegacion">
  <div class="cajaDeLogo"><img class="imagenLogoBarra" src="../util/imagenes/logo-mppn.png"></div>
  <div class="container-fluid">

    <ul class="navbar-nav">
      
      <li class="nav-item" >
        <div class="cajaNavegador"><a class="nav-link" href="../principal/menu-principal.php"><img src="../util/imagenes/iconos/homeBsolido.png" class="iconoNavegador"><br>Inicio</a></div>
      </li>

       <li class="nav-item" >
        <div class="cajaNavegador"><a class="nav-link" href="../expedientes/index.php" role="button"><img src="../util/imagenes/iconos/expedienteBsolido.png" class="iconoNavegador"><br>Expedientes</a></div>
      </li>

      <li class="nav-item dropdown" >
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><div class="cajaNavegador"><img src="../util/imagenes/iconos/afiliadosB.png" class="iconoNavegador"></div>Afiliados</a>
        <ul class="dropdown-menu">
        <?php
        if(estaconectadoAfiliacion()){
        ?>
          <!--li><a class="dropdown-item" href="http://10.8.1.1" target="_blank">Afiliaciones</a></li-->
          <li><a class="dropdown-item" href="http://192.168.0.6" target="_blank">Afiliados</a></li>
        <?php
        }
        else{
        ?>
          <li><a class="dropdown-item" href="../principal/menu-principal.php">Afiliados</a></li>
        <?php
        }
        ?>        
          <li><a class="dropdown-item" href="http://192.168.0.6/afiliacion">Búsqueda</a></li>
          <li><a class="dropdown-item" href="http://192.168.0.6/afiliacion">Registrados</a></li>
        </ul>
      </li>
     
      <li class="nav-item" >
        <div class="cajaNavegador"><a class="nav-link" href="../listados/listados.php"><img src="../util/imagenes/iconos/listaBsolido.png" class="iconoNavegador"><br>Listados</a></div>
      </li>

      <li class="nav-item" >
        <div class="cajaNavegador"><a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"><img src="../util/imagenes/iconos/TurismoViajeroBsolido.png" class="iconoNavegador"><br>Turismo</a></div>
      </li>

      <li class="nav-item" >
        <div class="cajaNavegador"><a class="nav-link" href="../becas/index.php" role="button"><img src="../util/imagenes/iconos/BecasGorroBlanco.png" class="iconoNavegador"><br>Becas</a></div>
      </li>
 <li class="nav-item dropdown" >
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><div class="cajaNavegador"><img src="../util/imagenes/iconos/cuentaB.png" class="iconoNavegador"></div>Mi perfil</a>
        <ul class="dropdown-menu">        
          <li><a class="dropdown-item" href="../usuarios/usuarioempleado.php">Mis datos</a></li>
          <!--li><a class="dropdown-item" href="">Cambiar clave</a></li-->
        </ul>
      </li>

    </ul>
    <br>
    <ul id="usuarioySalir" class="nav navbar-nav navbar-right">
      <div><span id="nombreUsuario" class="nombreUsuario"><?php echo "Usuario: ".$usuario?></span>
      <a href="" class="btn" id="salir" title="Salir"><img src="../util/imagenes/iconos/salirB.png" class="iconoSalir">Salir</a></div>
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
    confirmButtonColor: '#0F4C75',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: '#1B262C',
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
        //console.log(data);
        //$("#loader").css('display','none');
          var jsonData = JSON.parse(data);
          if(jsonData.salida == 0){
            //localStorage.removeItem("sesion");
            //sessionStorage.setItem("sesion", "1");
            //console.log("salida: "+localStorage.getItem("sesion"));
            return mensajeExitoSesion(jsonData.mensaje);
          }
          else{
            return mensajeErrorSesion(jsonData.mensaje);
          }
        }
       });
      return false;
    }
});
});

function mensajeErrorSesion($mensaje){
  swal.fire({
    icon: 'error',
    title: "Error",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#0F4C75',
  });
}

function mensajeExitoSesion($mensaje){
  Swal.fire({
    icon: 'success',
    title: "Exito", 
    text: $mensaje,
    width:'500px',
    allowOutsideClick: false,
    }).then(function(){
      //window.open('../index.php');
      window.location.replace("../index.php");
  });
}
</script>

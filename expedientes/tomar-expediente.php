<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{//Es un usuario empleado
    include('../admin/conexion.php');
    include('../util/funcionesexp.php');
    $documento = $_SESSION['documento'];
    $usuario = $_SESSION['usuario'];

  //TOMO EL ID DEL EXPEDIENTE, ID DEL AREA DE INICIO E ID DEL AREA DE FIN
  $idExpediente = $_POST["idExpediente"];
  $anio = $_POST["anio"];
  $areaInicio = $_POST["areaInicio"];
  $areaFin = $_POST["areaFin"];
  $identificador = $idExpediente."-".$anio;

//Actualizo el estado del expediente
  $sql ="UPDATE bandejas SET estado='TOMADO' WHERE id_expediente='$idExpediente' AND anio='$anio'";
  $actualizar = mysqli_query($conexion, $sql);

  if($actualizar){
    $insert = mysqli_query($conexion,"INSERT INTO log_expedientes(id_expediente, id_inicio, id_fin, estado, usuario, anio, identificador) VALUES('$idExpediente', '$areaInicio', '$areaFin', 'TOMADO', '$usuario', '$anio', '$identificador')");

    if($insert){
      echo json_encode(array('mensaje' => '<p><span style="color:#0072BC; font-size: 28px; font-family: Georgia, cursive;">EXPEDIENTE TOMADO</span></p>', 'salida' => '0', 'salida2' =>$idExpediente));
    }
    else{
      echo json_encode(array('mensaje' => "ERROR al cargar en log".mysqli_error($conexion), 'salida' => '1'));
    }
  }
  else{ 
    echo json_encode(array('mensaje' => "ERROR al cargar en bandejas".mysqli_error($conexion), 'salida' => '1'));
  }
}

?>
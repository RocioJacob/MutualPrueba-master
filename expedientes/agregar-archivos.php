<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{//Es un usuario empleado
  include("../admin/conexion.php");
  include('../util/funcionesexp.php');
  $documento = $_SESSION['documento'];
  $usuario = $_SESSION['usuario'];
  $idExpediente = $_POST['id_expediente'];
  $anio = $_SESSION['anio'];
  $areaUsuario = idAreaUsuario($documento);
  echo "HOLAA ".$idExpediente;
}

$cantidad = count(array_filter($_FILES['archivo']['name']));
if($cantidad>0){
    /*$fsize = 0; 
      for ($i = 0; $i <= $cantidad - 1; $i++) { 
        $guardado = $_FILES['archivo']['tmp_name'][$i];
        $nombre = $_FILES['archivo']['name'][$i];
        $salida1 = $i+1;
        if(move_uploaded_file($guardado, '../archivos/'.$anio.'/'.$idExpediente.'/'.$nombre)){
          $salida2 = true;
        }
        else{
          $salida2 = false;
        }
      }
    if(!$salida2){*/
      echo json_encode(array('mensaje' => "Error al agregar archivos ".$idExpediente, 'salida' => '1'));
   /* }
    else{
      echo json_encode(array('mensaje' => "Archivos agregados con exito", 'salida' => '0', 'salida2' => $idExpediente));
    }*/
}

?>
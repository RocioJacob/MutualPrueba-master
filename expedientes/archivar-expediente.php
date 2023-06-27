<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{
  include('../admin/conexion.php');
  include('../util/funcionesexp.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idExpediente'];
    $idExpediente = idExpedienteGeneral($id);
    $anioExpediente = anioExpedienteGeneral($id);
    $idAreaInicio = idAreaInicio($idExpediente);
    $idAreaFin = idAreaFin($idExpediente);
    $usuario = $_SESSION['usuario'];

    $comentario = isset($_POST['comentario']) ? mb_strtoupper($_POST['comentario']) : '';

    // Inicia la transacción
    mysqli_begin_transaction($conexion);

    // Actualizo el estado del expediente
    $actualizado1 = actualizarEstadoBandeja($conexion, $idExpediente, $anioExpediente);
    $actualizado2 = actualizarEstadoExpediente($conexion, $idExpediente, $anioExpediente);
    $insertado = insertarLogExpediente($conexion, $idExpediente, $anioExpediente, $idAreaInicio, $idAreaFin, 'ARCHIVADO', $comentario, $usuario);

    if($actualizado1 && $actualizado2 && $insertado){
      // Se confirman los cambios realizados en la transacción
      mysqli_commit($conexion);
      echo json_encode(array('mensaje' => "Expediente N°".$idExpediente." archivado", 'salida' => '0'));
    }
    else{
      // Se deshacen los cambios realizados en la transacción
      mysqli_rollback($conexion);
      echo json_encode(array('mensaje' => "ERROR al realizar la acción. Se ha revertido.", 'salida' => '1'));
    }
  }
}



// Función para actualizar el estado de la bandeja, pasa a estado "ARCHIVADO"
function actualizarEstadoBandeja($conexion, $idExpediente, $anioExpediente) {
  $sql = "UPDATE bandejas SET estado='ARCHIVADO' WHERE id_expediente=? and anio=?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $idExpediente, $anioExpediente);
  return mysqli_stmt_execute($stmt);
}

// Función para actualizar el estado del expediente, pasa a archivado = 0
function actualizarEstadoExpediente($conexion, $idExpediente, $anioExpediente) {
  $sql = "UPDATE expedientes SET archivado='0' WHERE id_expediente=? and anio=?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $idExpediente, $anioExpediente);
  return mysqli_stmt_execute($stmt);
}

// Función para insertar un registro en el log de expedientes
function insertarLogExpediente($conexion, $idExpediente, $anioExpediente, $idAreaInicio, $idAreaFin, $estado, $comentario, $usuario) {
  $sql = "INSERT INTO log_expedientes(id_expediente, id_inicio, id_fin, estado, comentario, usuario, anio, identificador) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $identificador = $idExpediente."-".$anioExpediente;
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "iiisssis", $idExpediente, $idAreaInicio, $idAreaFin, $estado, $comentario, $usuario, $anioExpediente, $identificador);
  return mysqli_stmt_execute($stmt);
}

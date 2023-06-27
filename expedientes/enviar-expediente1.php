<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
} else {
  include('../admin/conexion.php');
  include('../util/funcionesexp.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idExpedienteEnviado1'];
    $idExpediente = idExpedienteGeneral($id);
    $anioExpediente = anioExpedienteGeneral($id);
    $idAreaUsuario = idAreaUsuario($_SESSION['documento']);
    $usuario = $_SESSION['usuario'];

    $idArea = isset($_POST['area']) ? mb_strtoupper($_POST['area']) : '';
    $comentario = isset($_POST['comentario']) ? mb_strtoupper($_POST['comentario']) : '';

    
    // Verificar que el campo "area" esté completo y no vacío
    if (empty($idArea)) {
      echo json_encode(array('mensaje' => "El campo 'Area' es requerido", 'salida' => '1'));
      return;
    }


    // Verificar que se haya seleccionado una opción en el radio button
    if (!isset($_POST['autorizar'])) {
      echo json_encode(array('mensaje' => "Debe seleccionar una opción", 'salida' => '1'));
      return;
    }

    if($_POST['autorizar'] == "SI"){
      $autorizado = '0';
    }
    if($_POST['autorizar'] == "NO"){
      $autorizado = '1';
    }
    if($_POST['autorizar'] == "PENDIENTE"){
      $autorizado = '2';
    }


    // Inicia la transacción
    mysqli_begin_transaction($conexion);

    // Actualizo el estado del expediente
    $actualizado = actualizarAutorizadoExpediente($conexion, $idExpediente, $anioExpediente, $autorizado, $usuario);
    $actualizado1 = actualizarEstadoBandeja($conexion, $idAreaUsuario, $idArea, $idExpediente, $anioExpediente);
    $insertado = insertarLogExpediente($conexion, $idExpediente, $anioExpediente, $idAreaUsuario, $idArea, 'ENVIADO', $comentario, $usuario);


    if($actualizado && $actualizado1 && $insertado){
      // Se confirman los cambios realizados en la transacción
      mysqli_commit($conexion);
      echo json_encode(array('mensaje' => "Expediente N°".$idExpediente." enviado", 'salida' => '0'));
    } else {
      // Se deshacen los cambios realizados en la transacción
      mysqli_rollback($conexion);
      echo json_encode(array('mensaje' => "ERROR al realizar la acción. Se ha revertido.", 'salida' => '1'));
    }
  }
}



// Función para actualizar el estado del expediente
function actualizarAutorizadoExpediente($conexion, $idExpediente, $anioExpediente, $autorizado, $usuario) {
  $sql = "UPDATE expedientes SET autorizado='$autorizado', usuario_autorizado='$usuario' WHERE id_expediente=? and anio=?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $idExpediente, $anioExpediente);
  return mysqli_stmt_execute($stmt);
}

// Función para actualizar el estado de la bandeja
function actualizarEstadoBandeja($conexion, $idAreaUsuario, $idArea, $idExpediente, $anioExpediente) {
  $sql = "UPDATE bandejas SET id_inicio='$idAreaUsuario', id_fin='$idArea', estado='ENVIADO' WHERE id_expediente=? and anio=?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $idExpediente, $anioExpediente);
  return mysqli_stmt_execute($stmt);
}

// Función para insertar un registro en el log de expedientes
function insertarLogExpediente($conexion, $idExpediente, $anioExpediente, $idAreaInicio, $idAreaFin, $estado, $comentario, $usuario) {
  $sql = "INSERT INTO log_expedientes (id_expediente, id_inicio, id_fin, estado, comentario, usuario, anio, identificador) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $identificador = $idExpediente."-".$anioExpediente;
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "iiisssis", $idExpediente, $idAreaInicio, $idAreaFin, $estado, $comentario, $usuario, $anioExpediente, $identificador);
  return mysqli_stmt_execute($stmt);
}

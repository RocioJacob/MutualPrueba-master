<?php  
include("../admin/conexion.php");
include('../util/funcionesexp.php');

$idExpediente = mysqli_real_escape_string($conexion, $_POST["idExpediente"]);
$anio = mysqli_real_escape_string($conexion, $_POST["anio"]);
$idUsuario = mysqli_real_escape_string($conexion, $_POST["idUsuario"]);
$idLink = mysqli_real_escape_string($conexion, $_POST['link']);
$idArchivo = mysqli_real_escape_string($conexion, $_POST['idArchivo']);

//Obtengo el id del expediente a traves del nro y anio del expediente
$expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
$expediente = mysqli_fetch_assoc($expediente);
$id = $expediente['id'];

//Se verifica la existencia del archivo
if (!file_exists($idLink)) {
    echo json_encode(array('mensaje' => "El archivo no existe", 'salida1' => '1'));
    exit;
}
try {
	//Se elimina el archivo
	if(!permisoEliminarArchivo($idUsuario)){
		echo json_encode(array('mensaje' => "Sin permiso de eliminar archivo", 'salida1' => '1'));
    	exit;
	}

	$salida = unlink($idLink);
	if($salida){
		$accion = "ELIMINACIÓN";

		// Insertar registro en archivos_log utilizando una consulta preparada
	    $insertArchivoLog = mysqli_prepare($conexion, "INSERT INTO archivos_log(id_archivo, id_usuario, accion) VALUES (?, ?, ?)");
	    mysqli_stmt_bind_param($insertArchivoLog, 'sss', $idArchivo, $idUsuario, $accion);
	    mysqli_stmt_execute($insertArchivoLog);

	    if (!$insertArchivoLog) {
	        echo json_encode(array('mensaje' => "Archivo eliminado sin Log", 'salida1' => '0', 'salida2' => $id));
	    }
	    else{
	    	//Se actualiza la tabla archivos utilizando una consulta preparada
	        $eliminarArchivo = mysqli_prepare($conexion, "UPDATE archivos SET eliminado='0' WHERE id = ?");
	        mysqli_stmt_bind_param($eliminarArchivo, 's', $idArchivo);
	        mysqli_stmt_execute($eliminarArchivo);

	        if ($eliminarArchivo) {
	            echo json_encode(array('mensaje' => "Archivo eliminado", 'salida1' => '0', 'salida2' => $id));
	        } else {
	            echo json_encode(array('mensaje' => "Error al actualizar la tabla archivos", 'salida1' => '1'));
	        }
	    }
	}
	else{
	    echo json_encode(array('mensaje' => "Error al eliminar el archivo", 'salida1' => '1'));
	    exit;
	}
}
catch (Exception $e) {
    echo json_encode(array('mensaje' => "Error: " . $e->getMessage(), 'salida1' => '1'));
}

mysqli_close($conexion);

?>
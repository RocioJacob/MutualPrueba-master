<?php  
include("../admin/conexion.php");
$idExpediente = $_POST["idExpediente"];
$idUsuario = $_POST["idUsuario"];
$idLink = $_POST['link'];
$idArchivo = $_POST['idArchivo'];
$salida = unlink($idArchivo); // función borra un archivo
//$salida = true;
if($salida){
	$accion = "ELIMINACIÓN";
	$insertArchivoLog = mysqli_query($conexion,"INSERT INTO archivos_log(id_archivo, id_usuario, accion) VALUES('$idArchivo' ,'$idUsuario', '$accion')");

	if(!$insertArchivoLog){
        echo json_encode(array('mensaje' => "Archivo eliminado sin Log ".mysqli_error($conexion), 'salida' => '0'));
    }
    else{
    	$eliminartArchivo = mysqli_query($conexion,"UPDATE archivos set eliminado='0' WHERE id='$idArchivo'");
    	if($eliminartArchivo){
			echo json_encode(array('mensaje' => "Archivo eliminado", 'salida' => '0'));
		}
		else{
			echo json_encode(array('mensaje' => "No se pudo actualizar la tabla archivos ".mysqli_error($conexion), 'salida' => '0'));
		}
	}
}
else{
	echo json_encode(array('mensaje' => "No se pudo eliminar el archivo ".mysqli_error($conexion), 'salida' => '1'));
}

?>
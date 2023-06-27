<?php
include("../admin/conexion.php");

if (isset($_POST["id"])) {
    $valor = $_POST["id"];

    //Se prepara la consulta utilizando sentencias preparadas para evitar inyección de SQL
    $sql = "UPDATE expedientes SET oculto = '1' WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $valor);
    mysqli_stmt_execute($stmt);

	//Se utilizó la función mysqli_stmt_affected_rows para verificar si se realizaron cambios en la base de datos. Esto permite determinar si el expediente fue encontrado y actualizado correctamente.

    if(mysqli_stmt_affected_rows($stmt) > 0){
        echo json_encode(array('mensaje' => "Expediente desocultado", 'salida' => '0'));
    } 
    else{
        echo json_encode(array('mensaje' => "Error al desocultar expediente ".mysqli_error($conexion), 'salida' => '1'));
    }

    mysqli_stmt_close($stmt);
}
else{
    echo json_encode(array('mensaje' => "Error al obtener el id del expediente", 'salida' => '1'));
}

mysqli_close($conexion);
?>

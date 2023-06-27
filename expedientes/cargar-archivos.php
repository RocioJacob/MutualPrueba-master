<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{
    include('../admin/conexion.php');
    include('../util/funcionesexp.php');
    $documento = $_SESSION['documento'];
    $idUsuario = idUsuario($documento);

//******************************************************************************************************
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$idExpediente = $_POST['idExpediente'];
    	$archivos = $_FILES['archivos']; // Obtener los archivos enviados

  		// Verificar si no se ha adjuntado ningún archivo
  		if (empty($archivos['name'][0])) {
    		echo json_encode(array('mensaje' => "Por favor, adjunta al menos un archivo.", 'salida' => '1'));
    		exit;
  		}

  		// Verificar la cantidad de archivos adjuntados
  		if (count($archivos['name']) > 5) {
  		   // Mostrar mensaje de error
  		   echo json_encode(array('mensaje' => "Por favor, adjunta un máximo de 5 archivos.", 'salida' => '1'));
  		   exit;
  		}

  		// Verificar el tamaño total de los archivos
  		$totalSize = array_sum($archivos['size']);
  		// Convertir el tamaño total a MB
  		$totalSizeMB = $totalSize / (1024 * 1024);
  		// Verificar el tamaño total de los archivos
  		if ($totalSizeMB > 10) {
  		   // Mostrar mensaje de error
  		   echo json_encode(array('mensaje' => "El tamaño total de los archivos no debe exceder los 10 MB.", 'salida' => '1'));
  		   exit;
  		}


		// Verificar cada archivo adjuntado
  		foreach ($archivos['name'] as $index => $nombreArchivo) {
    		$tipoArchivo = $archivos['type'][$index];

    		// Verificar si el tipo de archivo no es PDF ni JPG
    		if ($tipoArchivo !== 'application/pdf' && $tipoArchivo !== 'image/jpeg') {
      		// Mostrar mensaje de error
      			echo json_encode(array('mensaje' => "Por favor, selecciona solo archivos PDF o JPG.", 'salida' => '1'));
      			exit;
    		}
  		}
	  }//Fin = if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//**********************************************************************************************************

  	$expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
  	$expediente = mysqli_fetch_assoc($expediente);
  	$anio = $expediente['anio'];
  	$id_expediente = $expediente['id_expediente'];

	 //Se obtiene la carpeta destino
  	$carpetaDestino = '../archivos/'.$anio.'/'.$id_expediente.'/';

  	if (!is_dir($carpetaDestino)) {
    	//Se crea la carpeta si no existe
    	mkdir($carpetaDestino, 0777, true);
  	}


  // Inicio de la transacción
  mysqli_begin_transaction($conexion);
  try {

    	//Se insertan los archivos a la carpeta de destino
      $cantidad = count($archivos['name']);
      for ($i = 0; $i < $cantidad; $i++) {
        $guardado = $_FILES['archivos']['tmp_name'][$i];
        $nombreArchivo = $_FILES['archivos']['name'][$i];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreSinExtension = pathinfo($nombreArchivo, PATHINFO_FILENAME);

        $fechaHoraActual = date('Ymd_His'); // Obtiene la fecha y hora actual en el formato: AñoMesDia_HoraMinutosSegundos
        $nuevoNombreArchivo = $nombreSinExtension.'_'.$fechaHoraActual.'.'.$extension;
        $direccion = $carpetaDestino . $nuevoNombreArchivo;

          if(move_uploaded_file($guardado, $direccion)){
            //Se inserta en la tabla "archivos"
            $insertArchivo = mysqli_prepare($conexion, "INSERT INTO archivos(id_expediente, anio, nombre, direccion, id_usuario, extension) VALUES (?, ?, ?, ?, ?, ?)");
                  
              mysqli_stmt_bind_param($insertArchivo, "iissis", $id_expediente, $anio, $nuevoNombreArchivo, $direccion, $idUsuario, $extension);
                  $insertArchivoResult = mysqli_stmt_execute($insertArchivo);
                  
              if(!$insertArchivoResult){
                //echo json_encode(array('mensaje' => "Error al insertar en archivos " . mysqli_error($conexion), 'salida' => '1'));
                //exit;
                throw new Exception('Error al insertar en archivos');
              }
              mysqli_stmt_close($insertArchivo);

              //Se inserta en la tabla "archivos_log"
                $ultimo_id = mysqli_insert_id($conexion);
                $accion = "INSERCIÓN";
                $insertArchivoLog = mysqli_prepare($conexion, "INSERT INTO archivos_log(id_archivo, id_usuario, accion) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($insertArchivoLog, "iis", $ultimo_id, $idUsuario, $accion);
                $insertArchivoLogResult = mysqli_stmt_execute($insertArchivoLog);

                if (!$insertArchivoLogResult) {
                  //echo json_encode(array('mensaje' => "Error al insertar en archivosLog " . mysqli_error($conexion), 'salida' => '1'));
                  //exit;
                  throw new Exception('Error al insertar en archivosLog');
                }
                mysqli_stmt_close($insertArchivoLog);
          }
          else{
              //echo json_encode(array('mensaje' => "No se almacenaron los archivos", 'salida' => '1'));
              //exit;
            throw new Exception('No se almacenaron los archivos');
          }
    	}

      // Commit si todas las acciones se realizaron correctamente
      mysqli_commit($conexion);
    	// Mostrar mensaje de éxito
    	echo json_encode(array('mensaje' => "Archivos adjuntados correctamente.", 'salida' => '0'));
  }
  catch (Exception $e) {
    // Rollback en caso de error
    mysqli_rollback($conexion);
    
    // Mostrar mensaje de error
    echo json_encode(array('mensaje' => "Error al adjuntar los archivos: " . $e->getMessage(), 'salida' => '1'));
  }
}

?>
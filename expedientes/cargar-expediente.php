<?php
ini_set('max_execution_time', 60);

session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{//Es un usuario empleado
    include('../admin/conexion.php');
    include('../util/funcionesexp.php');
    $documento = $_SESSION['documento'];
    $usuario = $_SESSION['usuario'];
    $areaUsuario = idAreaUsuario($documento);
    $idUsuario = idUsuario($documento);

    // Verificar que todos los datos obligatorios lleguen
    if (empty($_POST["tipo"]) || empty($_POST["tramite"]) || empty($_POST["area"]) || empty($_POST["prioridad"])) {
        echo json_encode(array('mensaje' => "Faltan cargar datos", 'salida' => '1'));
    } 
    else {
        $tipo = $_POST["tipo"];
        $id_tramite = $_POST["tramite"];
        $id_area = $_POST["area"];
        $prioridad = $_POST["prioridad"];
        $nombreTramite = nombreTramite($id_tramite);

        $documentoAfiliado = isset($_POST["documento"]) ? $_POST["documento"] : "0";
        $apellidoNombre = "";
        if (!empty($documentoAfiliado)) {
          //Realizar validación y obtención de datos del afiliado
          //Se conecta a la VPN. devuelve vacio si no existe el afiliado
          if(estaConectado()){
            $apellidoNombre = apellidoNombreAfiliado($documentoAfiliado);
          }
          else{
            $apellidoNombre = "";
          }
        }

        $cuit = isset($_POST['cuit']) ? $_POST["cuit"] : "0";
        $nombreProveedor = "";
        if (!empty($cuit)) {
          //Realizar validación y obtención de datos del proveedor
          $nombreProveedor = nombreProveedor($cuit);
        }

        $autorizacion3 = "";
        $autorizado = '1';
        if (isset($_POST["autorizacion1"]) && !empty($_POST["autorizacion1"])) {
          $autorizacion1 = $_POST["autorizacion1"];
            if ($autorizacion1 == '0') {
              $autorizacion3 = "PENDIENTE";
              $autorizado = '2';
            }
            else {
              $autorizacion3 = apellidoNombreUsuariox($autorizacion1);
              $autorizado = '0';
            }
        } 
        else {
            $autorizacion3 = "NO REQUIERE";
        }

        $datos = isset($_POST["datos"]) ? mb_strtoupper($_POST["datos"]) : "";
        $extracto = isset($_POST["extracto"]) ? mb_strtoupper($_POST["extracto"]) : "";

        //Año actual
        $anioActual = date("Y");
        //Obtengo la cantidad de expedientes con año actual y le sumo 1 para obtener el id_expediente del nuevo expediente
        $totalExpedientes = mysqli_query($conexion, "SELECT COUNT(*) as totalExpedientes FROM expedientes WHERE anio = '$anioActual'");
        $totalExpedientes = mysqli_fetch_array($totalExpedientes);
        $totalExpedientes = $totalExpedientes['totalExpedientes'];
        $idExpediente = $totalExpedientes + 1;
        $identificador = $idExpediente."-".$anioActual;
        $nombreColumnaDocumento = $cuit == '0' ? 'afiliado' : 'proveedor';
        $valorColumnaDocumento = $cuit == '0' ? $apellidoNombre : $nombreProveedor;

        //echo $idExpediente." - ".$anioActual." - ".$tipo." - ".$id_tramite." - ".$datos." - ".$extracto." - ".$areaUsuario." - ".$documentoAfiliado." - ".$cuit." - ".$nombreTramite." - ".$idUsuario." - ".$prioridad." - ".$autorizado." - ".$autorizacion3." - ".$identificador." - ".$valorColumnaDocumento;

// Iniciar la transacción
mysqli_begin_transaction($conexion);
try {
        //Se inserta en la tabla "expedientes"
        $insert = mysqli_prepare($conexion, "INSERT INTO expedientes(id_expediente, anio, tipo, id_tramite, datos, extracto, id_area, documento, cuit, nombre_tramite, id_usuario, prioridad, autorizado, usuario_autorizado, identificador, $nombreColumnaDocumento) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if($insert){
          mysqli_stmt_bind_param($insert, "iisissiiisisisss", $idExpediente, $anioActual, $tipo, $id_tramite, $datos, $extracto, $areaUsuario, $documentoAfiliado, $cuit, $nombreTramite, $idUsuario, $prioridad, $autorizado, $autorizacion3, $identificador, $valorColumnaDocumento);

          $insertResult = mysqli_stmt_execute($insert);
          if (!$insertResult) {
            echo json_encode(array('mensaje' => "No se pudo cargar en expedientes " . mysqli_error($conexion), 'salida' => '1'));
            exit;
          }
          mysqli_stmt_close($insert);
        }
        else{
          echo json_encode(array('mensaje' => "No se pudo cargar en expedientes " . mysqli_error($conexion), 'salida' => '1'));
            exit;
        }
      
//************************************************************************************************
      // Crear la carpeta con los archivos adjuntos
        $rutaCarpeta = '../archivos/' . $anioActual . '/' . $idExpediente;
        
        //Si no existe la carpeta, la crea
        if (!is_dir($rutaCarpeta)) {
          mkdir($rutaCarpeta, 0777, true);
        }
        $archivos = array_filter($_FILES['archivo']['name']);
        $cantidad = count($archivos);

        if($cantidad>0){
          $fsize = 0; 

          for ($i = 0; $i < $cantidad; $i++) {
            $guardado = $_FILES['archivo']['tmp_name'][$i];
            $nombreDelArchivo = $_FILES['archivo']['name'][$i];
            $extension = pathinfo($nombreDelArchivo, PATHINFO_EXTENSION);
            $direccion = $rutaCarpeta . '/' . $nombreDelArchivo;

            if(move_uploaded_file($guardado, $direccion)){
              //Se inserta en la tabla "archivos"
              $insertArchivo = mysqli_prepare($conexion, "INSERT INTO archivos(id_expediente, anio, nombre, direccion, id_usuario, extension) VALUES (?, ?, ?, ?, ?, ?)");
                
                mysqli_stmt_bind_param($insertArchivo, "iissis", $idExpediente, $anioActual, $nombreDelArchivo, $direccion, $idUsuario, $extension);
                $insertArchivoResult = mysqli_stmt_execute($insertArchivo);
                
                if(!$insertArchivoResult){
                  echo json_encode(array('mensaje' => "Error al insertar en archivos " . mysqli_error($conexion), 'salida' => '1'));
                  exit;
                }
                mysqli_stmt_close($insertArchivo);

              //Se inserta en la tabla "archivos_log"
                $ultimo_id = mysqli_insert_id($conexion);
                $accion = "INSERCIÓN";
                $insertArchivoLog = mysqli_prepare($conexion, "INSERT INTO archivos_log(id_archivo, id_usuario, accion) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($insertArchivoLog, "iis", $ultimo_id, $idUsuario, $accion);
                $insertArchivoLogResult = mysqli_stmt_execute($insertArchivoLog);

                if (!$insertArchivoLogResult) {
                  echo json_encode(array('mensaje' => "Error al insertar en archivosLog " . mysqli_error($conexion), 'salida' => '1'));
                  exit;
                }
                mysqli_stmt_close($insertArchivoLog);

            }
            else{
                echo json_encode(array('mensaje' => "No se almacenaron los archivos", 'salida' => '1'));
                exit;
            }
          }
        }

//**************************************************************************************************

      //Se inserta en la tabla "bandejas"
      $insertBandejas = mysqli_prepare($conexion, "INSERT INTO bandejas(id_expediente, id_inicio, id_fin, estado, anio, identificador) VALUES (?, ?, ?, ?, ?, ?)");
      $estado = "ENVIADO";
      mysqli_stmt_bind_param($insertBandejas, "iiisis", $idExpediente, $areaUsuario, $id_area, $estado, $anioActual, $identificador);
      $insertBandejasResult = mysqli_stmt_execute($insertBandejas);
      
      if (!$insertBandejasResult) {
        echo json_encode(array('mensaje' => "Error al insertar en bandejas " . mysqli_error($conexion), 'salida' => '1'));
        exit;
      }
      mysqli_stmt_close($insertBandejas);

//***************************************************************************************************

      //Se inserta en la tabla "log_expedientes"
      $insertBandejasLog = mysqli_prepare($conexion, "INSERT INTO log_expedientes(id_expediente, id_inicio, id_fin, estado, usuario, anio, identificador) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $estado="ENVIADO";
      mysqli_stmt_bind_param($insertBandejasLog, "iiissis", $idExpediente, $areaUsuario, $id_area, $estado, $usuario, $anioActual, $identificador);
      $insertBandejasLogResult = mysqli_stmt_execute($insertBandejasLog);

      if (!$insertBandejasLogResult) {
        echo json_encode(array('mensaje' => "Error al insertar en log_expedientes " . mysqli_error($conexion), 'salida' => '1'));
      exit;
      }
      mysqli_stmt_close($insertBandejasLog);

//*****************************************************************************************************

    // Se confirma la transacción
    mysqli_commit($conexion);
    echo json_encode(array('mensaje' => "Expediente creado con éxito", 'salida' => '0'));
  } 
  catch (Exception $e) {
  // Se revierte la transacción en caso de error
    mysqli_rollback($conexion);
    rmdir($rutaCarpeta);//Se elimina la carpeta que se creo en caso de error
    echo json_encode(array('mensaje' => "Error en la inserción: " . $e->getMessage(), 'salida' => '1'));
    exit;
  }
}
}
?>
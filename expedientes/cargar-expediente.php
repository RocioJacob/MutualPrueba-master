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
    $areaUsuario = idAreaUsuario($documento);
    $idUsuario = idUsuario($documento);

    //Verifico que todos los datos obligatorios lleguen
    if (($_POST["tipo"]=="") or ($_POST["tramite"]=="") or ($_POST["area"]=="") or ($_POST["prioridad"]=="")){
      echo json_encode(array('mensaje' => "ERROR faltan cargar datos", 'salida' => '1'));
    }
    else{
      $tipo = $_POST["tipo"];
      $id_tramite = $_POST["tramite"];
      $id_area = $_POST["area"];
      $prioridad = $_POST["prioridad"];
       $nombreTramite = nombreTramite($id_tramite);

      //Si carga documento de afiliado, lo busca y obtiene los datos. Si no existe, se le asigna vacio
      //Si no carga documento se le asigna 0.
      if($_POST["documento"]!=""){
        $documentoAfiliado = $_POST["documento"];
        //Se conecta a la VPN
        //devuelve vacio si no existe el afiliado
        $apellidoNombre = apellidoNombreAfiliado($documentoAfiliado);
      }
      else{
        $documentoAfiliado = "0";
        $apellidoNombre = "";
      }

      //Si carga cuit del proveedor, lo busca y obtiene los datos. Si no existe, se le asigna vacio
      //Si no carga cuit se le asigna 0.
      if(isset($_POST['cuit'])){
        $cuit = $_POST["cuit"];
        //Devuelve vacio si no existe el proveedor, pero si se cargo el cuit en el formulario
        $nombreProveedor = nombreProveedor($cuit); 
      }
      else{
        $cuit = "0";
      }

      //Se eligio un tramite con autorizacion - devuelve el id del usuario- 0=pendiente
      if(isset($_POST["autorizacion1"])&&($_POST["autorizacion1"]!="")){ 

        if($_POST["autorizacion1"]=='0'){
          $autorizacion3 = "PENDIENTE";
          $autorizado = '2';
        }else{
          $autorizacion3 = apellidoNombreUsuariox($_POST["autorizacion1"]);
          $autorizado = '0';
        }
      }
      else{ //Se eligio un tramite sin autorizacion
          $autorizado = '1';
          $autorizacion3 = "NO REQUIERE";
      }

      if(isset($_POST["datos"])){
        $datos = mb_strtoupper($_POST["datos"]);
      }
      else{
        $datos = "";
      }

      //Ingresaria siempre aca, ya que siempre esta definido extracto. No importa si no lo completo el formulario. Lo pasoa a mayusculas.
      if(isset($_POST["extracto"])){ 
        $extracto = mb_strtoupper($_POST["extracto"]);
      }
      else{
        $extracto = "";
      }

      //Año actual
      $anioActual = date("Y");
      //Obtengo la cantidad de expedientes con año actual y le sumo 1 para obtener el id_expediente del nuevo expediente
      $totalExpedientes = mysqli_query($conexion, "SELECT COUNT(*) as totalExpedientes FROM expedientes WHERE anio = '$anioActual'");
      $totalExpedientes = mysqli_fetch_array($totalExpedientes);
      $totalExpedientes = $totalExpedientes['totalExpedientes'];
      $idExpediente = $totalExpedientes + 1;
      $identificador = $idExpediente."-".$anioActual;

      if ($cuit == '0'){ //No se cargo cuit en el formulario
        $insert = mysqli_query($conexion,"INSERT INTO expedientes(id_expediente, anio, tipo, id_tramite, datos, extracto, id_area, documento, cuit, nombre_tramite, id_usuario, prioridad, autorizado, usuario_autorizado, identificador, afiliado) VALUES('$idExpediente' ,'$anioActual' ,'$tipo', '$id_tramite', '$datos', '$extracto', '$areaUsuario', '$documentoAfiliado', '$cuit', '$nombreTramite', '$idUsuario', '$prioridad', '$autorizado', '$autorizacion3', '$identificador', '$apellidoNombre')");
      }
      else{ //No se cargo documento en el formulario
        $insert = mysqli_query($conexion,"INSERT INTO expedientes(id_expediente, anio, tipo, id_tramite, datos, extracto, id_area, documento, cuit, nombre_tramite, id_usuario, prioridad, autorizado, usuario_autorizado, identificador, proveedor) VALUES('$idExpediente' ,'$anioActual' ,'$tipo', '$id_tramite', '$datos', '$extracto', '$areaUsuario', '$documentoAfiliado', '$cuit', '$nombreTramite', '$idUsuario', '$prioridad', '$autorizado', '$autorizacion3', '$identificador', '$nombreProveedor')");
      }

      //Creo la carpeta con los archivos adjuntos
      mkdir('../archivos/'.$anioActual.'/'.$idExpediente, 0777, true);
      $cantidad = count(array_filter($_FILES['archivo']['name']));
      if($cantidad>0){
        $fsize = 0; 
        for ($i = 0; $i <= $cantidad - 1; $i++) { 
          $guardado = $_FILES['archivo']['tmp_name'][$i];
          $nombre = $_FILES['archivo']['name'][$i];
          $salida1 = $i+1;
          $direccion = '../archivos/'.$anioActual.'/'.$idExpediente.'/'.$nombre;
          if(move_uploaded_file($guardado, $direccion)){
            $salida2 = true;
          }
          else{
            $salida2 = false;
          }

          if(!$salida2){
            echo json_encode(array('mensaje' => "No se pudo guardar archivos", 'salida' => '1'));
          }
          else{
            $insertArchivo = mysqli_query($conexion,"INSERT INTO archivos(id_expediente, anio, nombre, direccion, id_usuario) VALUES('$idExpediente' ,'$anioActual' ,'$nombre', '$direccion', '$idUsuario')");

            if(!$insertArchivo){
              echo json_encode(array('mensaje' => "No se pudo insertar en la tabla archivos ".mysqli_error($conexion), 'salida' => '1'));
            }
            else{
              $ultimo_id = mysqli_insert_id($conexion);
              $accion = "INSERCIÓN";
              $insertArchivoLog = mysqli_query($conexion,"INSERT INTO archivos_log(id_archivo, id_usuario, accion) VALUES('$ultimo_id' ,'$idUsuario', '$accion')");
              if(!$insertArchivoLog){
                echo json_encode(array('mensaje' => "No se pudo insertar en la tabla archivosLog ".mysqli_error($conexion), 'salida' => '1'));
              }
            }
          }
        }
      }

      if($insert){
      //Si se cargo el expediente, cargo el mismo en la bandeja
        $_SESSION['expediente'] = $idExpediente;
          
        $insert1 = mysqli_query($conexion,"INSERT INTO bandejas(id_expediente, id_inicio, id_fin, estado, anio, identificador) VALUES('$idExpediente', '$areaUsuario', '$id_area', 'ENVIADO', '$anioActual', '$identificador')");

        if($insert1){//Cargo el log para mostrar rutas
          $insert2 = mysqli_query($conexion,"INSERT INTO log_expedientes(id_expediente, id_inicio, id_fin, estado, usuario, anio, identificador) VALUES('$idExpediente', '$areaUsuario', '$id_area', 'ENVIADO', '$usuario', '$anioActual', '$identificador')");
          
          if($insert2){
            echo json_encode(array('mensaje' => "EXPEDIENTE GENERADO N° ".$idExpediente, 'salida' => '0'));
          }
          else{
            echo json_encode(array('mensaje' => "No se pudo cargar el log ".mysqli_error($conexion), 'salida' => '1'));
          }
        }
        else{
            echo json_encode(array('mensaje' => "No se pudo cargar en bandejas ".mysqli_error($conexion), 'salida' => '1'));
        }
      }
      else{
        echo json_encode(array('mensaje' => "No se pudo cargar en expedientes ".mysqli_error($conexion), 'salida' => '1'));
      }
    }        
}
?>
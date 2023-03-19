<?php

function idAreaUsuario($documento){
  include('../admin/conexion.php');
  $areaUsuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento'");
  $areaUsuario = mysqli_fetch_assoc($areaUsuario);
  $areaUsuario = $areaUsuario['id_area'];
  return $areaUsuario;
}

function idUsuario($documento){
  include('../admin/conexion.php');
  $idUsuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento'");
  $idUsuario = mysqli_fetch_assoc($idUsuario);
  $idUsuario = $idUsuario['id'];
  return $idUsuario;
}

function puedeAutorizar($id){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$id'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado'];
  if($autorizado=='0'){
    return true;
  }
  else{
    return false;
  }
}

function nombreTramite($id_tramite){
  include('../admin/conexion.php');
  $query = mysqli_query($conexion, "SELECT nombre FROM tramites WHERE id='$id_tramite'");
  $valores = mysqli_fetch_array($query);
  $nombreTramite = $valores['nombre'];
  return $nombreTramite;
}

function apellidoNombreAfiliado($documento){
  $salida = "";
  if(existeAfiliadoTitular($documento)){
      $resultArray = titularCargas($documento);
      $resultArray = json_decode($resultArray, true);
      $arrayTitular = [];
      foreach($resultArray as $key=>$data){
        if(!is_array($data)){
          $arrayTitular[$key] = $data;
        }
      }
      $apellido = $arrayTitular['apellido'];
      $nombre = $arrayTitular['nombre'];
      $salida = $apellido." ".$nombre;
  }
  return $salida;
}

function nombreProveedor($cuit){
  include('../admin/conexion.php');
  $query = mysqli_query($conexion, "SELECT nombre FROM proveedores WHERE cuit='$cuit'");
  $valores = mysqli_fetch_array($query);
  if($valores!=null){
    $nombreProveedor = $valores['nombre'];
  }
  else{
    $nombreProveedor = "";
  }
  return $nombreProveedor;
}

function apellidoNombreUsuariox($id){
  include('../admin/conexion.php');
  $apellidoNombre = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$id'");
  $apellidoNombre = mysqli_fetch_assoc($apellidoNombre);
  $apellido = $apellidoNombre['apellido'];
  $nombre = $apellidoNombre['nombre'];
  return $apellido." ".$nombre;
}

//-------------------------------------------------------------------------------------

function datosTramite($id){
  include('../admin/conexion.php');
  $salida = "";
  $query = mysqli_query($conexion, "SELECT * FROM tramites WHERE id='$id'");
  $tramite = mysqli_fetch_assoc($query);
  $codigo = $tramite['codigo'];
  if($codigo!='0'){
    $salida = "Código de tramite: ".$codigo;
  }
  return $salida;
}

//-----------------------------------------------------------------
/** expedientes/detalles-expediente.php **/

//Busca la ruta del expediente revisando el log de expedientes
function mostrarRuta($idExpediente, $anio){
  include("../admin/conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
  if($result->num_rows>0){
  ?>
    <span style="font-size: 14px; color:#3A73A8;">RUTA DEL EXPEDIENTE</span><br>
  <?php  
    $fin2 = "";
    
    while($fila=$result->fetch_assoc()){
      
      if(($fila['estado']!="TOMADO")&&($fila['estado']!="ARCHIVADO")&&($fila['estado']!="ANULADO")&&($fila['estado']!="DESARCHIVADO-TOMADO")){
        if($fila['id_inicio']!=$fin2){
            $inicio = mostrarArea($fila['id_inicio']);
            $fin = mostrarArea($fila['id_fin']);
            $flecha = "<img title='GENERADO' src='../util/imagenes/flecha.jpg' width='25' height='25'>";
            echo $inicio." ".$flecha." ".$fin;
        }
        else{
          $fin = mostrarArea($fila['id_fin']);
          echo " ".$flecha." ".$fin;
        }
        $fin2 = $fila['id_fin'];
      }

    }
  }
  echo '<br>'; 
  echo '<br>';
}

function mostrarArea($id){
  include("../admin/conexion.php");
  $salida = "";
  $result = mysqli_query($conexion, "SELECT * FROM areas WHERE id='$id'");
  if($result->num_rows>0){
    while($fila=$result->fetch_assoc()){
        $salida = $fila['nombre'];
    }
  }
  return $salida;
}


//Verifica si un tramite necesita o no autorizacion
function tieneAutorizacion($nombreTramite){
  include('../admin/conexion.php');
  $tramite = mysqli_query($conexion, "SELECT * FROM tramites WHERE nombre='$nombreTramite'");
  $tramite = mysqli_fetch_assoc($tramite);
  $tramite = $tramite['autorizacion'];
  if($tramite == 'SI'){
    return true;
  }
  else{
    return false;
  }
}

function estadoActual($identificador){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM bandejas WHERE identificador='$identificador'");
  $expediente = mysqli_fetch_assoc($expediente);
  $estado = $expediente['estado'];
  return $estado;
}

function estaArchivado($idExpediente, $anio){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_expediente='$idExpediente' AND anio='$anio'");
  $expediente = mysqli_fetch_assoc($expediente);
  $estado = $expediente['estado'];
    if($estado=="ARCHIVADO"){
      return true;
    }
    else{
      return false;
    }
}

//Areas que pueden ver archivos de expedientes archivados
function esAreaArchivados($idAreaUsuario){
  if(($idAreaUsuario == '3') or ($idAreaUsuario == '9') or ($idAreaUsuario == '11')){
    return true;
  }
  else{
    return false;
  }
}

//Area que pueden anular expediente
function esAreaAnular($idAreaUsuario){
  if(($idAreaUsuario == '6') or ($idAreaUsuario == '9')){
    return true;
  }
  else{
    return false;
  }
}

//Areas que pueden desarchivar
function esAreaDesarchivar($idAreaUsuario){
  if(($idAreaUsuario == '3') or ($idAreaUsuario == '9') or ($idAreaUsuario == '11')){
    return true;
  }
  else{
    return false;
  }
}

//Areas que pueden agregar archivos de expedientes archivados
function esAreaAgregarArchivados($idAreaUsuario){
  if(($idAreaUsuario == '3') or ($idAreaUsuario == '9') or ($idAreaUsuario == '11')){
    return true;
  }
  else{
    return false;
  }
}

//Existe afiliado TITULAR
function existeAfiliadoTitular($documento){
  $resultArray = titularCargas($documento);
  $arrayafiliado = json_decode($resultArray, true);//Paso a json para ver si esta vacio
  if (array_key_exists('message', $arrayafiliado)){ //No existe afiliado
    return false;
  }
  else{
    return true;
  }
}

function titularCargas($documento){  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://10.8.0.1/mutpol/rest/titular_cargas',
    //CURLOPT_URL => 'http://192.168.0.5/mutpol/rest/titular_cargas',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array('dni:'.$documento),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}


//Verifica si un afiliado es o no carga--------------
function esCarga($documento){
  $resultArray = habilitado($documento);
  $arrayAfiliado = json_decode($resultArray, true);//Paso a json para ver si esta vacio
  if (array_key_exists('message', $arrayAfiliado)){ //No existe afiliado
    return false;
  }
  else{
        $arrayAfiliado = datosHabilitados($resultArray);
        if($arrayAfiliado['parentesco']!="TITULAR"){
          return true; //es carga
        }
        else{
          return false;
        }
  }
}

function datosHabilitados($response){
  $array = [];
  $resultArray = json_decode($response, true);
  
  foreach($resultArray as $key=>$data){
    if(!is_array($data)){
      $array[$key] = $data;
    }
  }
  return $array;
}

//Devuelve si esta o no habilitado titular o carga
function habilitado($documento){  
   $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => '10.8.0.1/mutpol/rest/habilitados',
    //CURLOPT_URL => '192.168.0.5/mutpol/rest/habilitados',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array('dni:'.$documento, 'Content-Type: application/json'),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

//Areas que pueden eliminar archivos de expedientes
function esAreaEliminar($idAreaUsuario){
  if( ($idAreaUsuario == '3') or ($idAreaUsuario == '9') or ($idAreaUsuario == '11') or ($idAreaUsuario == '6')){
    return true;
  }
  else{
    return false;
  }
}

function mostrarArchivos($id, $anio, $tipoUsuario, $idAreaUsuario, $idUsuario){
    $ruta1='../archivos/'.$anio.'/'.$id; //Facturas/2021/ABRIL/000001
    if (is_dir($ruta1)){ //Indica si el nombre de archivo es un directorio

        if ($dh = opendir($ruta1)){ //Abre un gestor de directorio
          ?>
          <!--div class="col"-->
          <?php
          while (($file = readdir($dh)) !== false){ //readdir — Lee una entrada desde un gestor de directorio
            $ext = pathinfo($file, PATHINFO_EXTENSION); //Obtengo la extension del archivo
            $nombre = pathinfo($file, PATHINFO_FILENAME); ////Obtengo el nombre del archivo
            
            //Si es un archivo PDF
            if (is_dir($ruta1) && $file != '.' && $file != '..' && ($ext =="pdf")or($ext =="PDF")){ 
              $archivo = $ruta1.'/'.$file;
          ?>
              <div class="col">
                <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none;">
                  <img src='../util/imagenes/pdf.png' height='50' width='50' align=center>
                  <button id="botonAcciones">Ver</button>
                </a>

              <?php 
                if(esAreaEliminar($idAreaUsuario)){ 
              ?>
                  <button onclick="eliminar('<?php echo $archivo ?>','<?php echo $id ?>', '<?php echo $idUsuario ?>')" id="botonAcciones">Eliminar</button>
              <?php 
                } 
              ?>

              <?php echo $nombre; ?>
              </div>
              <br>
<?php
            }

            //Si es un archivo JPG
            if (is_dir($ruta1) && $file != '.' && $file != '..' && (($ext =="jpg") or ($ext == "png") or ($ext == "jpeg") or ($ext =="JPG") or ($ext == "PNG") or ($ext == "JPEG"))){ 
              $archivo = $ruta1.'/'.$file;
?>
              <div class="col">
                <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none; color:#3A73A8;">
                  <img src='../imagenes/foto-jpg.png' height='50' width='50' align=center>
                  <button id="botonAcciones">Ver</button></a>
                
                <?php 
                if(($tipoUsuario=='1') or ($tipoUsuario=='4')){ 
                ?>
                  <button onclick="eliminar('<?php echo $archivo ?>','<?php echo $id ?>', '<?php echo $idUsuario ?>')" id="botonAcciones">Eliminar</button>
                <?php 
                } 
                ?>
                <?php echo $nombre; ?>

              </div>
              <br>
<?php
            }

          }//while
          ?>
        <!--/div-->
        <?php
          closedir($dh);
        }
    }
}


function listarArchivos($idExpediente, $idUsuario){
  include('../admin/conexion.php');
  $archivos = mysqli_query($conexion, "SELECT * FROM archivos WHERE id_expediente = '$idExpediente' AND eliminado = '1' ORDER BY fecha_subida ASC");
  ?>
  <span style="font-size: 14px; color:#3A73A8;">ARCHIVOS</span><br>

  <?php
  while($archivo=$archivos->fetch_assoc()){
    $link = $archivo['direccion'];
    $idArchivo = $archivo['id'];

    if(file_exists($link)){
    ?>
      <div class="col">
          <a href='<?php echo $link?>' target='_blank' style="text-decoration:none;">
            <img src='../util/imagenes/pdf.png' height='50' width='50' align=center><button id="botonAcciones">Ver</button>
          </a>
        <?php
        if(autorizadoEliminarArchivo($idUsuario)){ ?>
          <button onclick="eliminar('<?php echo $idExpediente ?>', '<?php echo $idUsuario ?>', '<?php echo $link ?>', '<?php echo $idArchivo ?>',)" id="botonAcciones">Eliminar</button>
        <?php
        }
        echo $archivo['nombre'];
      ?>  
      </div><br>
    <?php
    }
  }
}


function listarArchivosAnterior($idExpediente, $anio){
  $ruta1='../archivos/'.$anio.'/'.$idExpediente; 
  ?>
  <?php
  if (is_dir($ruta1)){ //Indica si el nombre de archivo es un directorio
    if ($dh = opendir($ruta1)){ //Abre un gestor de directorio   
  ?>
    <div class="col">
      <span style="font-size: 14px; color:#3A73A8;">ARCHIVOS</span><br>
      <?php 
      while (($file = readdir($dh)) !== false){ //readdir — Lee una entrada desde un gestor de directorio
        $ext = pathinfo($file, PATHINFO_EXTENSION); //Obtengo la extension del archivo
        $nombre = pathinfo($file, PATHINFO_FILENAME);
              
        if (is_dir($ruta1) && $file != '.' && $file != '..' && ($ext =="pdf" or $ext =="PDF")){ 
          $archivo = $ruta1.'/'.$file;
      ?>
          <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none; color:#3A73A8;">
            <img src='../util/imagenes/pdf.png' height='50' width='50' align=center target='_blank'>
          </a>
      <?php
          echo $nombre;
          echo"<br>";
        }
            
        if (is_dir($ruta1) && $file != '.' && $file != '..' && (($ext =="jpg") or ($ext == "png") or ($ext == "jpeg") or ($ext =="JPG") or ($ext == "PNG") or ($ext == "JPEG"))){ 
          $archivo = $ruta1.'/'.$file;
        ?>
            <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none; color:#3A73A8;"><img src='../imagenes/foto-jpg.png' height='50' width='50' align=center></a>
        <?php
          echo $nombre;
          echo"<br>";
        }
      }//while
      ?>
    <div><br>
    <?php 
    closedir($dh);
    }
  }
}

function obtenerfechaActual(){
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  $hoy=date("d-m-Y",time());
  return $hoy;
}

function nombreArea($id){
  include('../admin/conexion.php');
  $area = mysqli_query($conexion, "SELECT * FROM areas WHERE id='$id'");
  $area = mysqli_fetch_assoc($area);
  $area = $area['nombre'];
  return $area;
}


function nombreUsuario($idUsuario){
  include('../admin/conexion.php');
  $nombreUsuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $nombreUsuario = mysqli_fetch_assoc($nombreUsuario);
  $nombreUsuario = $nombreUsuario['usuario'];
  return $nombreUsuario;
}

//Verifica si un tramite necesita o no la respuesta de autorizacion(Si o no)
function tieneAutorizacion2($idTramite){
  include('../admin/conexion.php');
  $tramite = mysqli_query($conexion, "SELECT * FROM tramites WHERE id='$idTramite'");
  $tramite = mysqli_fetch_assoc($tramite);
  $tramite = $tramite['autorizacion'];
  if($tramite == 'SI'){
    return true;
  }
  else{
    return false;
  }
}

function estaAutorizado($idExpediente){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
  $expediente = mysqli_fetch_assoc($expediente);
  $autorizado = $expediente['autorizado'];
    if($autorizado=="0"){
      return true;
    }
    else{
      return false;
    }
}

function estaRechazado($idExpediente){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
  $expediente = mysqli_fetch_assoc($expediente);
  $autorizado = $expediente['autorizado'];
    if($autorizado=="1"){
      return true;
    }
    else{
      return false;
    }
}

function estaPendiente($idExpediente){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
  $expediente = mysqli_fetch_assoc($expediente);
  $autorizado = $expediente['autorizado'];
    if($autorizado=="2"){
      return true;
    }
    else{
      return false;
    }
}



function prioridadAlta($idExpediente){
  include('../admin/conexion.php');
  $expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
  $expediente = mysqli_fetch_assoc($expediente);
  $prioridad = $expediente['prioridad'];
    if($prioridad=="ALTA"){
      return true;
    }
    else{
      return false;
    }
}


//-----generar-expediente.php----------------------------------------------------------------
function datosAfiliadoExpediente($documento){
  $salida = "";
  if(existeAfiliadoTitular($documento)){ //Verifica si existe afiliado
    //if(existe($documento)){ //Verifica si existe y esta habilitado
      $resultArray = titularCargas($documento);
      $arrayTitular = datosTitular($resultArray);
      $apellido = $arrayTitular['apellido'];
      $nombre = $arrayTitular['nombre'];
      $codigo = $arrayTitular['codigo'];
      $documento = $arrayTitular['documento'];
      $cuil = $arrayTitular['cuil'];
      $cbu = $arrayTitular['cbu'];

      $salida = "Apellido y Nombre: ".$apellido." ".$nombre." - Documento: ".$documento." - Código de Tango: ".$codigo." - Cuil: ".$cuil." - CBU: ".$cbu;
    //}
  }
  return $salida;
}


function datosTitular($response){
  $array = [];
  $resultArray = json_decode($response, true);
  foreach($resultArray as $key=>$data){
    if(!is_array($data)){
      $array[$key] = $data;
    }
  }
  return $array;
}

function datosCargas($response){
  $array = [];
  $resultArray = json_decode($response, true);
  $i = 0;
  foreach($resultArray as $key=>$data){
    if(is_array($data)){
      foreach($data as $numero){
        foreach($numero as $clave=>$elemento){
          if(!is_array($elemento)){
            $array[$i][$clave] = $elemento;
          }
        }
        $i = $i+1;
      }
    }
  }
  return $array;;
}

function tienecargas($documento){
  $resultArray = titularCargas($documento);
  $arrayCarga = datosCargas($resultArray);
  $longitud = count($arrayCarga);
  if($longitud!=0){
    return true;
  }
  else{
    return false;
  }
}


function autorizadoEliminarArchivo($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado_eliminar_archivo'];
    if($autorizado==0){
      return true;
    }
    else{
      return false;
    }
}


function autorizadoHabilitarArchivo($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado_habilitar_archivo'];
    if($autorizado==0){
      return true;
    }
    else{
      return false;
    }
}

function autorizadoDeshabilitarArchivo($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado_deshabilitar_archivo'];
    if($autorizado==0){
      return true;
    }
    else{
      return false;
    }
}



//Busca la ruta del expediente revisando el log de expedientes
function mostrarDatosExpediente($idExpediente, $anio){
  include("../admin/conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
  $expediente = mysqli_fetch_assoc($result);
  
  if($result->num_rows>0){ ?>
    
    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">ID: </span><span class="subtituloDetalles2"><?php echo $expediente['identificador'].'<br>';?></span>
      </div>

      <div class="col">
        <span class="subtituloDetalles1">AÑO: </span><span class="subtituloDetalles2"><?php echo $expediente['anio'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">TIPO: </span><span class="subtituloDetalles2"><?php echo $expediente['tipo'].'<br>';?></span>
      </div>
              
      <div class="col">
        <span class="subtituloDetalles1">TRAMITE: </span><span class="subtituloDetalles2"><?php echo $expediente['nombre_tramite'].'<br>';?></span>
      </div>
    </div>


    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">DATOS: </span><span class="subtituloDetalles2"><?php echo $expediente['datos'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">EXTRACTO: </span><span class="subtituloDetalles2"><?php echo $expediente['extracto'].'<br>';?></span>
      </div>
    </div>

    <?php //Si existe documento cargado
    if($expediente['documento']!= 0) {
    ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">DOCUMENTO: </span><span class="subtituloDetalles2">
            <?php echo $expediente['documento'].'<br>';?></span>
        </div>
      </div>
    <?php 
    } 
    ?>

    <?php  //Si existe codigo cargado
    if($expediente['codigo']!= 0) {
    ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">CÓDIGO: </span><span class="subtituloDetalles2">
            <?php echo $expediente['codigo'].'<br>';?></span>
        </div>
      </div>
    <?php 
    }  
    
    $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); 
    ?>  
    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">GENERADO: </span><span class="subtituloDetalles2"><?php echo $fecha.'<br>';?></span>
      </div>
    </div>


    <?php //Verifica el activado del expediente
    if($expediente['activado']=='0'){?>  
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2">
            <?php echo "ACTIVADO".'<br>';?></span>
        </div>
      </div>
    <?php 
    } 
    if($expediente['activado']=='1'){ ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ANULADO".'<br>';?></a></span>
        </div>
      </div>
    <?php 
    } 
    if($expediente['activado']=='2'){ 
    ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ARCHIVADO".'<br>';?></a></span>
        </div>
      </div>
    <?php 
    }
    ?>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">PRIORIDAD: </span><span class="subtituloDetalles2">
          <?php echo $expediente['prioridad'].'<br>';?></span>
      </div>
    </div>


    <?php //Tiene cuit cargado
    if($expediente['cuit']!= 0){
    ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">CUIT: </span><span class="subtituloDetalles2">
            <?php echo $expediente['cuit'].'<br>';?></span>
        </div>
      </div>
    <?php 
    } 
    

    //Si el expediente necesita o no la autorizacion
    if(tieneAutorizacion($expediente['nombre_tramite'])) { 
      if($expediente['autorizado']=='0'){
        $autorizado = "SI";
        $responsable = $expediente['usuario_autorizado'];
    ?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "SI".'<br>'?></span>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles1">AUTORIZADO POR: </span><span class="subtituloDetalles2"><?php echo $responsable.'<br>'?></span>
          </div>
        </div>
      <?php
      }
      else{
        $autorizado = "PENDIENTE";
      ?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "SI".'<br>'?></span>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles1">AUTORIZADO POR: </span><span class="subtituloDetalles2"><?php echo "PENDIENTE".'<br>'?></span>
          </div>
        </div>
        <?php
      }
    } 
    else{
    ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "NO REQUIERE".'<br>';?></span>
        </div>
      </div>
    <?php 
    } 

    $estadoActual = estadoActual($expediente['identificador']);
    ?>
    <div class="row">
      <div class="col">
        <span class="subtituloDetalles1">ACTUALMENTE: </span><span class="subtituloDetalles2">
          <?php echo $estadoActual.'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col"><span class="subtituloDetalles1">ES ANUAL?</span><span class="subtituloDetalles2">
      <?php 
        if($expediente['es_anual']=='0'){ 
          echo "SI".'<br>';
        ?>
          </span></div>
          <?php 
        }else{ 
          echo "NO".'<br>';
        ?>
          </span></div>
        <?php 
        } 
        ?>
    </div>
    <br>
  <?php
  }
}


function verArchivoArchivado($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['ver_archivo_archivado'];
  if($autorizado==0){
    return true;
  }
  else{
    return false;
  }
}

function tieneComentarios($idExpediente, $anio){
  include("../admin/conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
  $i=0;
  $salida=false;
  while (($fila=$result->fetch_assoc()) && (!$salida)){
    if($fila['comentario']!=""){
      $salida = true;
    }
    $i=$i+1;
  }
  return $salida;
}

function devolverComentarios($idExpediente, $anio){
  include("../admin/conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
  if($result->num_rows>0){
    ?>
    <span style="font-size: 14px; color:#3A73A8;">COMENTARIOS</span><br>
      <table class="table table-bordered">
        <tr>
          <td id="filaC">N°</td>
          <td id="filaC">USUARIO</td>
          <td id="filaC">COMENTARIO</td>
          <?php 
            $i=0;
            $j=1;
            while ($fila=$result->fetch_assoc()) {
              if($fila['comentario']!=""){
          ?>
                <tr>
                  <td id="detalleC"> <?php echo $j; ?></td>
                  <td id="detalleC"> <?php echo $fila['usuario']; ?></td>
                  <td id="detalleC"> <?php echo $fila['comentario']; ?></td>
                </tr>
                <?php
                $j=$j+1;
              }
              $i=$i+1;
            }
            ?>
        </tr>
      </table>
      <?php
  }
}

function autorizadoAnularExpediente($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado_anular_expediente'];
  if($autorizado==0){
    return true;
  }
  else{
    return false;
  }
}

function autorizadoDesarchivarExpediente($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['autorizado_desarchivar'];
    if($autorizado==0){
      return true;
    }
    else{
      return false;
    }
}

function agregarArchivoArchivado($idUsuario){
  include('../admin/conexion.php');
  $usuario = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE id='$idUsuario'");
  $usuario = mysqli_fetch_assoc($usuario);
  $autorizado = $usuario['agregar_archivo_archivado'];
    if($autorizado==0){
      return true;
    }
    else{
      return false;
    }
}
?>
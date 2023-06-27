
<!-- Agregar modal -->
      <div class="modal fade" id="adjuntarModal" tabindex="-1" role="dialog" aria-labelledby="adjuntarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="adjuntarModalLabel">Adjuntar Archivo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="procesar-adjuntar.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="archivo">Seleccionar archivo:</label>
                <input type="file" class="form-control-file" id="archivo" name="archivo">
              </div>
              <input type="hidden" id="expedienteId" name="expedienteId" value="">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Adjuntar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>



    // Obtener el ID de expediente y mostrar el modal
    $(".agregarArchivo").click(function(e) {
      e.preventDefault();
      var expedienteId = $(this).attr('data');
      $('#expedienteId').val(expedienteId);
      $('#adjuntarModal').modal('show');
    });



     // Obtener el formulario y el campo de archivos por su ID
  //const form = document.querySelector('form');
 /* const archivosInput = document.getElementById('archivos');

  // Agregar evento de envío del formulario
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío del formulario

    const archivos = archivosInput.files; // Obtener los archivos seleccionados

    // Verificar si no se ha adjuntado ningún archivo
    if (archivos.length === 0) {
      // Mostrar mensaje de alerta utilizando SweetAlert
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, adjunta al menos un archivo.',
        confirmButtonColor: '#03989e'
      });
      return; // Detener la ejecución y no enviar el formulario
    } else if (archivos.length > 5) {
      // Mostrar mensaje de alerta utilizando SweetAlert
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, adjunta un máximo de 5 archivos.',
        confirmButtonColor: '#03989e'
      });
      return; // Detener la ejecución y no enviar el formulario
    }


    // Verificar el tamaño total de los archivos seleccionados
    let totalSize = 0;
    for (let i = 0; i < archivos.length; i++) {
      totalSize += archivos[i].size;
    }
    // Convertir el tamaño total a MB
    const totalSizeMB = totalSize / (1024 * 1024);

    // Verificar el tamaño total de los archivos
    if (totalSizeMB > 10) {
      // Mostrar mensaje de alerta utilizando SweetAlert
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'El tamaño total de los archivos no debe exceder los 10 MB.',
        confirmButtonColor: '#03989e'
      });
      return; // Detener la ejecución y no enviar el formulario
    }
    

    // Verificar cada archivo seleccionado
    for (let i = 0; i < archivos.length; i++) {
      const archivo = archivos[i];
      const tipo = archivo.type;

      // Verificar si el tipo de archivo no es PDF ni JPG
      if (tipo !== 'application/pdf' && tipo !== 'image/jpeg') {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Por favor, selecciona solo archivos PDF o JPG.',
          confirmButtonColor: '#03989e'
        });
        return; // Detener la ejecución y no enviar el formulario
      }
    }

    // Si todos los archivos son PDF o JPG, puedes enviar el formulario aquí
    form.submit();
  });*/









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
      if (isset($_POST["documento"])) {
      //if($_POST["documento"]!=""){
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





/*GENERAR EXPEDIENTE.PHP*/

<?php  
include('../estructura/navegacion.php');
?>

<body>
<div class="container" id="mycontainer">
  <span class="subtituloMenu">GENERAR EXPEDIENTE</span><br><br>

  <form method="POST" id="formulario" enctype="multipart/form-data">
    <div class="form-row">

      <div class="form-group col-md-4">
        <label id="tituloFormulario">* Tipo</label>
        <select id="tipo" name="tipo" class="form-control">
          <option value="">Seleccione un tipo</option>
          <option value="AFILIADO">AFILIADO</option>
          <option value="DELEGACION">DELEGACION</option>
          <option value="EMPLEADO">EMPLEADO</option>
          <option value="PROVEEDOR">PROVEEDOR</option>
          <option value="TURISMO">TURISMO</option>
          <option value="OTROS">OTROS</option>
        </select>
      </div>

      <!-- Aca iria Documento, Cuit o nada según lo que se elija en tipo-->
      <div class="form-group col-md-4" id="mensaje"></div>
      <div class="form-group col-md-4"></div>

      <div class="form-group col-md-4" style="display:none" id="delega1">
          <label id="tituloFormulario">* Delegacion</label>
          <select id="delegacion1" name="delegacion1" class="form-control">
            <option value="">Seleccione una delegación</option>
            <option value="ALUMINE">ALUMINE</option>
            <option value="CENTENARIO">CENTENARIO</option>
            <option value="CHOS MALAL">CHOS MALAL</option>
            <option value="CUTRAL CO">CUTRAL CO</option>
            <option value="EL CHOLAR">EL CHOLAR</option>
            <option value="JUNIN DE LOS ANDES">JUNIN DE LOS ANDES</option>
            <option value="LAS GRUTAS">LAS GRUTAS</option>
            <option value="LAS LAJAS">LAS LAJAS</option>
            <option value="LONCOPUE">LONCOPUE</option>
            <option value="PICUN LEUFU">PICUN LEUFU</option>
            <option value="PLOTTIER">PLOTTIER</option>
            <option value="SAN MARTIN DE LOS ANDES">SAN MARTIN DE LOS ANDES</option>
            <option value="SEDE CENTRAL">SEDE CENTRAL</option>
            <option value="VILLA LA ANGOSTURA">VILLA LA ANGOSTURA</option>
            <option value="ZAPALA">ZAPALA</option>
          </select>
        </div>

        <div class="form-group col-md-4">
          <label id="tituloFormulario">* Tramite</label>
          <select id="tramite" name="tramite" class="form-control">
            <option value="">Seleccione un tramite</option>
            <?php
              include('../admin/conexion.php');
              $result = mysqli_query($conexion, "SELECT id, nombre, activado, autorizacion FROM tramites WHERE activado = '0' ORDER BY nombre ASC");
              if($result->num_rows>0){
                while($fila=$result->fetch_assoc()){
                  echo '<option value="'.$fila['id'].'" data-autorizado="'.$fila['autorizacion'].'">'.$fila['nombre'].'</option>'; 
                }
              }
            ?>
          </select>
        </div>

        <?php echo $fila['autorizacion'];?>

        <div class="form-group col-md-4" id="autorizado1" style="display:none">
          <label id="tituloFormulario">* Autorizado por</label>
          <select id="autorizacion1" name="autorizacion1" class="form-control">
            <option value="">Seleccione una opción</option>
            <?php 
              include('../admin/conexion.php');
              $result = mysqli_query($conexion, "SELECT id, nombre, apellido, id_area FROM usuariosx ORDER BY apellido ASC");
              if($result->num_rows>0){
                echo '<option value="0">'.'PENDIENTE'.'</option>'; 
                while($fila=$result->fetch_assoc()){
                  if(puedeAutorizar($fila['id'])){
                    echo '<option value="'.$fila['id'].'">'.$fila['apellido'].' '.$fila['nombre'].'</option>'; 
                  } 
                }
              }
            ?>
          </select>
        </div>

        <div class="form-group col-md-4" id="autorizado2" style="display:none">
          <label id="tituloFormulario">* Autorizado por</label>
          <select id="autorizacion2" name="autorizacion2" class="form-control">
            <option value="NO">NO REQUIERE</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label id="tituloFormulario">Descripción</label><br>
          <textarea class="form-control" id="datos" name="datos" rows="4" autocomplete="off" maxlength="150" style="resize: none"></textarea>
        </div>

        <div class="form-group col-md-12">
          <label id="tituloFormulario">Extracto</label><br>
          <textarea class="form-control" id="extracto" name="extracto" rows="4" autocomplete="off" placeholder="Maximo 400 caracteres" maxlength="400" style="resize: none"></textarea>
        </div>

        <div class="form-group col-md-4">
          <label id="tituloFormulario">* Enviar a</label>
            <select id="area" name="area" class="form-control">
              <option value="">Seleccione un área</option>
            <?php
              $result = mysqli_query($conexion, "SELECT * FROM areas WHERE activado = '0' ORDER BY nombre ASC");
              if($result->num_rows>0){
                while($fila=$result->fetch_assoc()){
                  if($fila['id']!=$idAreaUsuario){
                    echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                  } 
                }
              }
            ?>
            </select>
        </div>
        
        <div class="form-group col-md-4">
          <label id="tituloFormulario">Prioridad</label>
          <select id="prioridad" name="prioridad" class="form-control">
            <option value="NORMAL">NORMAL</option>
            <option value="ALTA">ALTA</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label id="tituloFormulario">Adjuntar archivos</label><br>
            <input multiple="true" name="archivo[]" id="file" type="file"><br>
          <label>Formatos aceptados: PDF, PNG, JPG y JPEG.<br>Adjuntar seleccionando todos los archivos de una vez o presionando la tecla CTRL y seleccionando de a uno.</label><br>
        </div>

      </div><br>
      <button class="btn" type="submit" id="botonGenerar" title="Generar Expediente">Generar</button>
    </form>

      <p style="color:#003366">* Datos obligatorios</p><br/>
      <div id="loader" style="display:none"></div>
  </div>
</body>



<script type="text/javascript">
/*
$(document).on('change', '#tipo', function() {
  var $mensajeUno = $(
    '<div><label style="color:#0072BC"> Documento (sin puntos)</label><input type="text" id="documento" name="documento" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="8"></div>');
  var $mensajeDos = $(
    '<div><label style="color:#0072BC">* Cuit (sin puntos ni guiones)</label><input type="text" id="cuit" name="cuit" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="11"></div>');
*/

  /*var $mensajeTres = $(
    '<div><label style="color:#0072BC"> Código Tango (sin puntos)</label><input type="text" id="codigo" name="codigo" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="6"></div>');
  var $mensajeCuatro = $(
    '<div><label style="color:#0072BC">Monto (solo punto decimal)</label><input type="number" id="monto" name="monto" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="8"></div>');*/
  

  /*
  var seleccion = $(this).val();
  $('#mensaje').html('');
  switch (seleccion) {
    case 'AFILIADO':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'DELEGACION':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'EMPLEADO':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'PROVEEDOR':
      $('#datos').html('');
      $('#mensaje').append($mensajeDos);
      break;
    case 'OTROS':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
  }
});
*/


/*Al seleccionar el tipo de expediente, verifica que mostrar*/
/*Si se selecciona Afiliado, Delagación, Empleado, Otros o Turismo se nuestra Documento*/
/*Si selecciona Proveedor se muestra Proveedor*/

$(document).on('change', '#tipo', function() {
  var TIPO_AFILIADO = 'AFILIADO';
  var TIPO_DELEGACION = 'DELEGACION';
  var TIPO_EMPLEADO = 'EMPLEADO';
  var TIPO_PROVEEDOR = 'PROVEEDOR';
  var TIPO_OTROS = 'OTROS';

  var $mensajeUno = $('<div><label style="color:#0072BC"> Documento (sin puntos)</label><input type="text" id="documento" name="documento" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="8"></div>');
  var $mensajeDos = $('<div><label style="color:#0072BC">* Cuit (sin puntos ni guiones)</label><input type="text" id="cuit" name="cuit" class="form-control" style=" border-color: #0072BC; border-radius: 5px;" autocomplete="off" maxlength="11"></div>');
  var seleccion = $(this).val();

  function generarHTML(inputHTML) {
    $('#datos').html('');
    $('#mensaje').html(inputHTML);
  }

  switch (seleccion) {
    case TIPO_AFILIADO:
    case TIPO_DELEGACION:
    case TIPO_EMPLEADO:
    case TIPO_OTROS:
      generarHTML($mensajeUno);
      break;
    case TIPO_PROVEEDOR:
      generarHTML($mensajeDos);
      break;
  }
});

/* Al seleccionar expediente tipo DELEGACION debe mostrar select con todas las delegaciones */
$(document).on('change', '#tipo', function() {
  var seleccion = $(this).val();
  var a = document.getElementById('delega1');
  switch (seleccion) {
    case 'DELEGACION':
      if(a.style.display === 'none'){
        a.style.display = 'block';
      }
      break;
    default:
       if(a.style.display === 'block'){
        a.style.display = 'none';
      }
      break;
  }
});


/*Al seleccionar el tipo de TRAMITE **/
$(document).on('change', '#tramite', function() {

  var seleccion = $(this).val();
  if (seleccion!=""){
    obtener_datostramite(seleccion);
  }

  //Obtengo data-autorizado del formulario-Si necesita o no autorizacion el tramite seleccionado
  var autorizado = $(this).find(':selected').data('autorizado');
  switch (autorizado) {
    case 'SI':
      var a = document.getElementById('autorizado1');
      var b = document.getElementById('autorizado2'); //NO REQUIERE
      //if(b!=null){
        if(b.style.display === 'block'){
          b.style.display = 'none';
        }
     // }
     // if(a!=null){  
        if(a.style.display === 'none'){
          a.style.display = 'block';
        }
      //}
      break;
      
    case 'NO':
       var b = document.getElementById('autorizado1');
       //var a = document.getElementById('autorizado2'); /*NO REQUIERE*/

        //if(b!=null){
          if(b.style.display === 'block'){ /* si se ve*/
            b.style.display = 'none';
          }
        //}
          var a = document.getElementById('autorizado2'); /*NO REQUIERE*/
          if(a.style.display === 'none'){ /*no se ve*/
            a.style.display = 'block';
          }
      break;

    //Para eliminar el campo autorizado1, autorizado2 cuando no se elije nada
    default:
       var a = document.getElementById('autorizado2');
       var b = document.getElementById('autorizado1');
       if(a.style.display === 'block'){
         a.style.display = 'none';
       }
       if(b.style.display === 'block'){
         b.style.display = 'none';
       }
      break;
  }
});


//Obtengo el codigo del tramite
function obtener_datostramite(usuario){
  $.ajax({
    url : 'datos-tramite.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#extracto").html(resultado);
  });
}


//********************************************************
//Cuando se completa campo documento se ejecuta
$(document).on('change', '#documento', function() {
  var documento = $(this).val();
  if (documento!=""){
    obtener_datos(documento);
  }
});

function obtener_datos(usuario){
  $.ajax({
    url : 'datos-afiliado-expediente.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#datos").html(resultado);
  });
}
//*********************************************************

$('#botonGenerar').click(function(evento){
  evento.preventDefault();
    if(validarFormulario()){
      var datos = new FormData($('#formulario')[0]);
      Swal.fire({
          title: '<span style="font-size: 21px;">¿Desea generar el Expediente?</span>',
          width:'500px',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#0F4C75',
          confirmButtonText: 'Aceptar',
          cancelButtonColor: '#1B262C',
          allowOutsideClick: false,
      }).then((result) => {
        
        if (result.isConfirmed) {
          $.ajax({
            url: 'cargar-expediente.php',
            type:'POST',
            data: datos,
            processData: false,
            contentType: false,
            
            beforeSend: function() {
              $("#loader").css('display','block');
            },

            success: function(data){
              $("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExitoExpediente(jsonData.mensaje);
              }
              else{
                return mensajeErrorExpediente(jsonData.mensaje);
              }
            }

          });
            return false;
        }
      });
    }
});


function mensajeErrorExpediente($mensaje){
  swal.fire({
    title: "ERROR",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoExpediente($mensaje){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: $mensaje, 
    allowOutsideClick: false,
    }).then(function(){
      //window.open("../principal/menu-principal.php");
      window.location.replace("../principal/menu-principal.php");
  });
}

function validarFormulario(){
  
  if($("#tipo").val() == ""){
    return mensajeErrorExpediente("Debe seleccionar un TIPO");
    $("#tipo").focus();
    return false;
  }
  else{ //Se completo tipo
    if($("#tipo").val() == "AFILIADO"){
      if($("#documento").val() == ""){
        return mensajeErrorExpediente("Debe ingresar DOCUMENTO");
        $("#documento").focus();
        return false;
      }
    }

  //Verificar que el documento sea un numero
  /*if($("#documento").val() != ""){
    return mensajeErrorExpediente("Formato de documento erróneo");
    $("#documento").focus();
    return false;
  }*/

    if($("#tipo").val() == "PROVEEDOR"){
      if($("#cuit").val() == ""){
        return mensajeErrorExpediente("Debe ingresar CUIT");
        $("#cuit").focus();
        return false;
      }
    }

    if($("#tipo").val() == "DELEGACION"){
      if($("#delegacion1").val() == ""){
        return mensajeErrorExpediente("Debe seleccionar una DELEGACIÓN");
        $("#delegacion1").focus();
        return false;
      }
    }
  }

  if($("#tramite").val() == ""){
    return mensajeErrorExpediente("Debe seleccionar un TRÁMITE");
    $("#tramite").focus();
    return false;
  }
  else{
    if(($("#tramite").val()=="7") || ($("#tramite").val() == "8") || ($("#tramite").val() == "9") || 
      ($("#tramite").val() == "12")|| ($("#tramite").val() == "26") || ($("#tramite").val() == "27") || 
      ($("#tramite").val() == "28") || ($("#tramite").val() == "29") || ($("#tramite").val() == "30")|| 
      ($("#tramite").val() == "31") || ($("#tramite").val() == "38") || ($("#tramite").val() == "48") || 
      ($("#tramite").val() == "49")|| ($("#tramite").val() == "103")){

      if($("#autorizacion1").val() == ""){
        return mensajeErrorExpediente("Debe seleccionar AUTORIZACIÓN");
        $("#autorizacion1").focus();
        return false;
      }
    }
  }

  if($("#area").val() == ""){
    return mensajeErrorExpediente("Debe seleccionar un ÁREA");
    $("#area").focus();
    return false;
  }

  if($("#prioridad").val() == ""){
    return mensajeErrorExpediente("Debe seleccionar una PRIORIDAD");
    $("#prioridad").focus();
    return false;
  }

  if($('input[type="file"]').val()!=null){
    if($('input[type="file"]').val() != ''){ 
      var archivos = document.getElementById('file');
      var cantidad = archivos.files.length;

      if(cantidad > 10){
        return mensajeErrorExpediente("Se excedió en la cantidad máxima de 10 archivos");
        return false;
      }
      else{
        var fsize = 0;
        for (var i = 0; i <= cantidad - 1; i++) {
          fsize = fsize + archivos.files.item(i).size;   
        }
        if(fsize > 8000000){ //8MB de tamaño maximo - PHP permite hasta 8MB
          return mensajeErrorExpediente("Supero el tamaño máximo de 8Mb");
          return false;
        }
        else{
          var i=0;
          var bandera=true;
          var banderaNombre = true;
          while((i<cantidad) && (bandera) && (banderaNombre)){
            if((archivos.files.item(i).type!="application/pdf")&&(archivos.files.item(i).type!="image/jpeg")&&(archivos.files.item(i).type!="image/png")){
                bandera=false;
            }
            if(archivos.files.item(i).name.length > 35){
              banderaNombre=false;
            }
            i=i+1;
          }
          if(!bandera){
            return mensajeErrorExpediente("Solo se permiten adjuntar archivos en formato PDF, PNG, JPG Y JPEG");
            return false;
          }
          if(!banderaNombre){
            return mensajeErrorExpediente("Solo se permiten archivos con nombre no mayor a 35 caracteres");
            return false;
          }
        }
      }
    }
  }
  return true;
}

</script>




<style type="text/css">

#mycontainer { 
  max-width: 1300px !important; 
}

hr{
  background-color: #1C5D88;
}
  
#loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url('../util/imagenes/loader.gif') 50% 50% no-repeat rgb(249,249,249);
  opacity: .8;
}
</style>

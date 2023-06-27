<?php  
include('../estructura/navegacion.php');
?>

<body>
<div class="container-fluid">
  <span class="subtituloMenu">GENERAR EXPEDIENTE</span><br>
  <?php 
    if(estaConectado()){
  ?>
    <span style="color: red;">Con conexión a la VPN</span>
  <?php
    }else{
  ?>
    <span style="color: red;">Sin conexión a la VPN</span>
  <?php    
    }
  ?>

  <br><br>
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


/*Al seleccionar el tipo de TRAMITE*/
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

$('#botonGenerar').click(function(evento){
  evento.preventDefault();
    if(validarFormulario()){
      var datos = new FormData($('#formulario')[0]);
      Swal.fire({
          title: '<span style="font-size: 22px;">¿Desea generar el Expediente?</span>',
          width:'500px',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#148F77',
          confirmButtonText: 'Aceptar',
          cancelButtonColor: 'red',
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
            },
            
          });
            return false;
        }
      });
    }
});


function mensajeErrorExpediente($mensaje){
  swal.fire({
    icon: 'error',
    title: "Error",
    text: $mensaje,
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoExpediente($mensaje){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: "Éxito",
    text: $mensaje, 
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
      }else{
        if(!Number.isInteger($("#documento").val())){
          return mensajeErrorExpediente("DOCUMENTO debe ser númerico");
          $("#documento").focus();
          return false;
        }
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
      }else{
          if(!Number.isInteger($("#cuit").val())){
            return mensajeErrorExpediente("CUIT debe ser númerico sin guiones");
            $("#cuit").focus();
            return false;
          }
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

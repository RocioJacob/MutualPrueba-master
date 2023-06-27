<?php  
include('../estructura/navegacion.php');

if($trabajarConArchivados == "0"){ //Tengo permiso para trabajar con archivados
  include('modalDesarchivar.php');
  include('modalAgregar.php');
?>

  <body>
  <div class="container-fluid">
  <span class="subtituloMenu">DETALLES DE EXPEDIENTE ARCHIVADO</span><br><br>
    <?php
    $id = $_GET["id"];
    $expediente= mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$id'");
    $expediente = mysqli_fetch_assoc($expediente);
    $idExpediente = $expediente['id_expediente'];
    $anio = $expediente['anio'];
    $archivado = $expediente['archivado'];
    $_SESSION['identificador'] = $expediente['identificador'];
    $_SESSION['idexpediente'] = $id;

  if($archivado == "0"){ //Si esta archivado
    mostrarRuta($idExpediente, $anio);
    ?>
    <div class="caja">
      <?php 
      mostrarDatosExpediente($idExpediente, $anio);
      ?>
    </div><br>

    <?php
      //Verificar cuando se use la nueva version
        if((($idExpediente<915)&&($anio<=2023)) or ($anio<2023)){
          listarArchivosArchivadosViejo($idExpediente, $anio, $idUsuario);echo "<br>";
        }else{
          listarArchivosArchivadosNuevo($idExpediente, $anio, $idUsuario);echo "<br>";
        }
  ?>

<!---------------------------------------------------------------------------------------------------------->

      <div class="center">
        <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="botonVerExpediente" title="Ver expediente" target="_blank">Carátula</a>

        <a href="ver-historial-archivado.php">
          <button class="btn" id="botonVerHistorial" type="submit" title="Ver historial">Historial</button>
        </a>

<!---------------------------------------------------------------------------------------------------------->

        <?php //DESARCHIVAR EXPEDIENTE
        if($desarchivarExpediente == "0"){
        ?>
          <a href="#" class="desarchivarExpediente" data-toggle="modal" data-target="#myModalDesarchivar" data-idexpediente="<?php echo $id ?>" data-identificador="<?php echo $expediente['identificador'] ?>" style="text-decoration: none"><button class="btn" id="botonDesarchivar" type="button" title="Desarchivar Expediente">Desarchivar</button></a>
        <?php
        }
        else{
        ?>
          <a href="#" class="desarchivarExpediente" data-toggle="modal" data-target="#myModalDesarchivar" data-idexpediente="<?php echo $id ?>" data-identificador="<?php echo $expediente['identificador'] ?>" style="text-decoration: none"><button class="btn" id="botonDesarchivar" type="button" title="Desarchivar Expediente" disabled="">Desarchivar</button></a>
        <?php
        }
        ?>

<!---------------------------------------------------------------------------------------------------------->
        
        <?php //AGREGAR ARCHIVOS A EXPEDIENTE ARCHIVADO
        if($agregarArchivoArchivado == "0"){
        ?>
          <a href="#" class="agregarArchivo" data-toggle="modal" data-target="#myModalAgregar" data-idexpediente="<?php echo $id ?>" data-identificador="<?php echo $expediente['identificador'] ?>" style="text-decoration: none"><button class="btn" id="botonAgregar" type="button" title="Agregar archivos">Agregar</button></a>
        <?php
        }
        else{
        ?>
          <a href="#" class="agregarArchivo" data-toggle="modal" data-target="#myModalAgregar" data-idexpediente="<?php echo $id ?>" data-identificador="<?php echo $expediente['identificador'] ?>" style="text-decoration: none"><button class="btn" id="botonAgregar" type="button" title="Agregar archivos" disabled>Agregar</button></a>
        <?php
        }
        ?>

<!---------------------------------------------------------------------------------------------------------->

        <?php //MARCAR O DESMARCAR COMO ANUAL A EXPEDIENTE ARCHIVADO
        if($trabajarAnuales == "0"){   
          if($expediente['es_anual'] == "1"){ 
        ?>
          <button class="btn" type="submit" title="Marcar anual" id="botonAnual" onclick="marcarAnual('<?php echo $id; ?>', '<?php echo $expediente['identificador'] ?>')">Marcar Anual</button>
          <?php 
          }else{ 
          ?>
          <button class="btn" type="submit" title="Desmarcar anual" id="botonAnual" onclick="desmarcarAnual('<?php echo $id;?>', '<?php echo $expediente['identificador'] ?>')">Desmarcar</button>
          <?php 
          } 
        } 
        ?>
      </div>
      <br>
      <?php
  }
  else{ //Si intento acceder con un expdiente NO archivado
  ?>
    <div class="container-fluid">
      <span>El expediente NO se encuentra archivado</span>
    </div>
  <?php
  }
  ?>

</div>
</body><br>
<?php
}
else{
?>
  <div class="container-fluid">
    <span>No tiene permisos para trabajar con expedientes archivados</span>
  </div>
<?php
}
?>


<script type="text/javascript">

  $(".desarchivarExpediente").click(function(e) {
      e.preventDefault();
      var idExpediente = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpedienteDesarchivar").text("Desarchivar expediente: " + identificador);
      $('#idExpediente').val(idExpediente); // Establecer el valor del campo oculto
      $('#myModalDesarchivar').modal('show');
  });

  $(".agregarArchivo").click(function(e) {
      e.preventDefault();
      var idExpediente = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpediente").text("Agregar archivos al expediente: " + identificador);
      $('#idExpedienteInput').val(idExpediente); // Establecer el valor del campo oculto
      $('#myModalAgregar').modal('show');
  });



//***************************************************************************************************
function marcarAnual(valor, identificador){
  var datos = {"valor":valor, "identificador":identificador};
  Swal.fire({
    title: '<span style="font-size: 22px;">¿Desea marcar como anual?</span>',
    text: 'Expediente: '+identificador,
    width:'500px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#03989e',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: '#1B262C',
    allowOutsideClick: false,
  }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'marcar-expediente.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            //$("#loader").css('display','block');
          },
          success: function(data){
            //$("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida1 == 0){
                return mensajeExitoAnual(jsonData.mensaje, jsonData.salida2);
              }
              else{
                return mensajeErrorAnual(jsonData.mensaje);
              }
            }
          });
          return false;
        }
    });
}

function desmarcarAnual(valor, identificador){
  var datos = {"valor":valor, "identificador":identificador};
  Swal.fire({
    title: '<span style="font-size: 22px;">¿Desea desmarcar como anual?</span>',
    text: 'Expediente: '+identificador,
    width:'500px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#03989e',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: '#1B262C',
    allowOutsideClick: false,
  }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'desmarcar-expediente.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            //$("#loader").css('display','block');
          },
          success: function(data){
            //$("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida1 == 0){
                return mensajeExitoAnual(jsonData.mensaje, jsonData.salida2);
              }
              else{
                return mensajeErrorAnual(jsonData.mensaje);
              }
            }
          });
          return false;
        }
    });
}


function mensajeExitoAnual($mensaje, $expediente){
  Swal.fire({
    icon: 'success',
    title: 'Éxito',
    width:'500px',
    text: $mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    }).then(function(){
      //window.open("");
      window.location.replace("detalles-expediente-archivado.php?id="+$expediente);
  });
}

function mensajeErrorAnual($mensaje){
  swal.fire({
    title: "Error",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

//****************************************************************************************************

function eliminarArchivoArchivadoNuevo(idExpediente, anio, idUsuario, link, idArchivo){
    var datos = {"idExpediente":idExpediente, "anio":anio, "idUsuario":idUsuario, "link":link, "idArchivo": idArchivo};
    
    Swal.fire({
        title: '<span style="font-size: 22px;">¿Desea eliminar el archivo?</span>',
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
              url: 'eliminar-archivo-archivado-nuevo.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida1 == 0){
                  return mensajeExitoDetalles(jsonData.mensaje, jsonData.salida2);
                }
                else{
                  return mensajeErrorDetalles(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
  }


function mensajeErrorDetalles($mensaje){
  swal.fire({
    title: "Error",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoDetalles($mensaje, $expediente){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: 'Éxito',
    text: $mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    }).then(function(){
      //window.open("");
      window.location.replace("detalles-expediente-archivado.php?id="+$expediente);
  });
}


//******************************************************************************************************

function eliminarArchivoArchivadoViejo(archivo, nombreArchivo, idExpediente, anio, idUsuario){
    var datos = {"archivo":archivo, "nombreArchivo":nombreArchivo, "idExpediente":idExpediente, "anio":anio, "idUsuario":idUsuario};
    
    Swal.fire({
        title: '<span style="font-size: 22px;">¿Desea eliminar el archivo?</span>',
        text: "Expediente: "+idExpediente+"-"+anio,
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
              url: 'eliminar-archivo-archivado-viejo.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida1 == 0){
                  return mensajeExitoEliminarArchivadoViejo(jsonData.mensaje, jsonData.salida2);
                }
                else{
                  return mensajeErrorEliminarArchivadoViejo(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
}


function mensajeErrorEliminarArchivadoViejo(mensaje){
  swal.fire({
    title: "Error",
    text: mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoEliminarArchivadoViejo(mensaje, expediente){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: 'Éxito',
    text: mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    }).then(function(){
      //window.open("");
      window.location.replace("detalles-expediente-archivado.php?id="+expediente);
  });
}
//*********************************************************************************************************
</script>




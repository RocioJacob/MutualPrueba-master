<?php  
include('../estructura/navegacion.php');
$usuario1 = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento'");
$usuario1 = mysqli_fetch_assoc($usuario1);
$trabajarAnuales = $usuario1['trabajar_anuales'];
?>

<body>
<div class="container" id="mycontainer">
  <span class="subtituloMenu">DETALLES EXPEDIENTE</span><br><br>
  <?php
  $identificador = $_GET["id"];
  $expediente= mysqli_query($conexion, "SELECT * FROM expedientes WHERE identificador='$identificador'");
  $expediente = mysqli_fetch_assoc($expediente);
  $idExpediente = $expediente['id_expediente'];
  $anio = $expediente['anio'];
  $_SESSION['identificador'] = $identificador;
  mostrarRuta($idExpediente, $anio);
  ?>
    <div class="caja">
      <?php 
      mostrarDatosExpediente($idExpediente, $anio);
      ?>
    </div><br>

    <?php 
    //Si esta archivado, solo las areas permitidas pueden ver los archivos
    if(estaArchivado($idExpediente, $anio)){
      if(verArchivoArchivado($idUsuario)){
          if(($idExpediente<900)&&($anio<=2023)){
            listarArchivosAnterior($idExpediente, $anio);
          }
          else{
            listarArchivos($idExpediente, $idUsuario);
          }
      }
    }
    else{ 
        if(($idExpediente<900)&&($anio<=2023)){
          listarArchivosAnterior($idExpediente, $anio);
        }
        else{
          listarArchivos($idExpediente, $idUsuario);
        }
    } 
    ?>

      <div class="center">
        <a href="expedientePdf.php" target="_blank">
          <button class="btnDetalles" type="submit" title="Anular Expediente">Carátula</button>
        </a>
        <a href="ver-historial.php">
          <button class="btnDetalles" type="submit" title="Ver historial">Historial</button>
        </a>

        
        <?php 
        if(!estaArchivado($idExpediente, $anio)){ //Expedientes archivados no pueden ser ANULADOS
          if(autorizadoAnularExpediente($idUsuario)){ //Puede anular 
        ?>
            <button class="btnDetalles" type="submit" title="Anular Expediente" onclick="mostrarAnular();">Anular</button>
        <?php 
          }
        }
        else{ //Expediente esta archivado
          if(autorizadoDesarchivarExpediente($idUsuario)){ //Puede desarchivar 
        ?>
            <button class="btnDetalles" type="submit" title="Desarchivar Expediente" onclick="mostrarDesarchivar();">Desarchivar</button>
      <?php  
          }
        }
        //Los usuarios permitidos solo pueden agregar archivos a expediente archivado
        if(estaArchivado($idExpediente, $anio)){ 
          if(agregarArchivoArchivado($idUsuario)){ 
      ?>
            <button class="btnDetalles" type="submit" title="Agregar Archivos" onclick="mostrarAgregar();">Agregar</button>
      <?php 
          }
        }


      if($trabajarAnuales=="0"){   
        if($expediente['es_anual']=="1"){ 
      ?>
          <button class="btnDetalles" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>')">Marcar Anual</button>
      <?php 
        }else{ 
      ?>
          <button class="btnDetalles" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>')">Desmarcar Anual</button>
      <?php 
        } 
      } 
      ?>
      </div>
      <br>

      
      <form method="POST" id="formularioAnulacion" enctype="multipart/form-data" style="display: none;">
          <div class="form-group col-md-6"><br>
            <label style="color:#003366">Comentario</label>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
            <br>
            <button class="btnDetalles" type="submit" id="botonAceptarUno" title="Anular expediente">Aceptar</button>
          </div>
      </form>
      <br>


      <div class="center">
        <form method="POST" id="formularioDesarchivar" enctype="multipart/form-data" style="display:none;">
          <div class="form-group col-md-6"><br>
            <label style="color:#003366">Comentario</label>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
            <br>
            <button class="btnDetalles" type="submit" id="botonAceptarDos" title="Desarchivar expediente">Aceptar</button>
          </div>
        </form>
      </div>
      <br>


      <div class="center">
        <form method="POST" id="formularioAgregar" enctype="multipart/form-data" style="display:none;">
          <div class="form-group col-md-12">
            <label style="color:#003366">Agregar archivos</label><br>
              <input multiple="true" name="archivo[]" id="file" type="file"><br>
            <label style="color:#003366">Formatos aceptados: PDF, PNG, JPG y JPEG.
            <br>No agregar archivos con el mismo nombre</label><br>
            <button class="btnDetalles" type="submit" id="botonAceptarTres" title="Agregar archivos">Aceptar</button>
          </div>
        </form>
      </div>

    </div>
</div>
</body>




<style type="text/css">
.caja { 
  border-top: 1px solid #0072BC;
  border-right: 1px solid #0072BC;
  border-bottom: 1px solid #0072BC;
  border-left: 1px solid #0072BC;
  padding: 5px;
}

.subtituloArchivos{
  /*font-family: 'Georgia', cursive; */
  font-family: Arial;
  text-align: left;
  font-size: 20px;
}


.btnDetalles{
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
  font-size: 13px;
  width: 120px;
  height: 40px;
  margin-right: 10px;
}

.btnDetalles:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAcciones{
  background-color:#3A73A8;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  border-color:white;
  margin: 1px;
  font-family: 'Arial';
  font-size: 13px;
  height: 30px;
  width: 70px;
}

#botonAcciones:hover{
  color: #3A73A8;
  border-color: #3A73A8;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

.subtituloDetalles1{
  font-family: Arial;
  color: #3A73A8;
  font-weight: normal;
  font-size: 13px;
}

.subtituloDetalles2{
  font-family: Arial;
  color: black;
  font-weight: normal;
  font-size: 12px;
}
</style>



<script type="text/javascript">

  function mostrarAnular() {
    var x = document.getElementById('formularioAnulacion');
    if (x.style.display === 'none') { //Si no se ve, que se vea
        x.style.display = 'block';
    }
    else{
        x.style.display = 'none';
    }
  }

  function mostrarDesarchivar() {
    var x = document.getElementById('formularioDesarchivar');
    var y = document.getElementById('formularioAgregar');
    
    if (y.style.display === 'block') { //Si no se ve, que se vea
        y.style.display = 'none';
    }

    if (x.style.display === 'none') { //Si no se ve, que se vea
        x.style.display = 'block';
    }
    else{
        x.style.display = 'none';
    }
  }

  function mostrarAgregar() {
    var x = document.getElementById('formularioAgregar');
    var y = document.getElementById('formularioDesarchivar');
    
    if (y.style.display === 'block') { //Si no se ve, que se vea
        y.style.display = 'none';
    }

    if (x.style.display === 'none') { //Si no se ve, que se vea
        x.style.display = 'block';
    }
    else{
        x.style.display = 'none';
    }
  }

  function mostrarFormulario() {
    var x = document.getElementById('formularioextra');

    if (x.style.display === 'none') { //Si no se ve, que se vea
        x.style.display = 'block';
    }
    else{
        x.style.display = 'none';
    }
  }


  function marcar(valor){
  var datos = {"valor":valor};
  Swal.fire({
    title: '¿Desea marcar como Anual?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#03989e',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
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
              if(jsonData.salida == 0){
                return mensajeExito2(jsonData.mensaje);
              }
              else{
                return mensajeError(jsonData.mensaje);
              }
            }
          });
          return false;
        }
    });
}

function desmarcar(valor){
  var datos = {"valor":valor};
  Swal.fire({
    title: '¿Desea desmarcar como Anual?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#03989e',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
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
              if(jsonData.salida == 0){
                return mensajeExito2(jsonData.mensaje);
              }
              else{
                return mensajeError(jsonData.mensaje);
              }
            }
          });
          return false;
        }
    });
}


 function eliminar(idExpediente, idUsuario, link, idArchivo){
    var datos = {"idExpediente":idExpediente, "idUsuario":idUsuario, "link":link, "idArchivo": idArchivo};
    Swal.fire({
        title: '¿Desea eliminar el archivo?',
        width:'550px',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#148F77',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
    }).then((result) => {
          
          if (result.isConfirmed) {
            $.ajax({
              url: 'eliminar-expediente.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida == 0){
                  return mensajeExitoDetalles(jsonData.mensaje);
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
    title: "ERROR",
    text: $mensaje,
    icon: 'error',
    width:'550px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoDetalles($mensaje){
  Swal.fire({
    icon: 'success',
    width:'550px',
    title: 'ÉXITO',
    text: $mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    });
}
</script>
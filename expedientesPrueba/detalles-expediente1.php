<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: index.php');
}
else{
  include('../admin/conexion.php');
  include('../util/funcionesexp.php');
  $documento = $_SESSION['documento'];
  $usuario = $_SESSION['usuario'];
  $tipoUsuario = $_SESSION['tipo_usuario'];
  $idAreaUsuario = idAreaUsuario($documento);
  $tipoBuscador = $_SESSION['tipo_buscador'];

  $usuario1 = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE documento='$documento'");
  $usuario1 = mysqli_fetch_assoc($usuario1);
  $trabajarAnuales = $usuario1['trabajar_anuales'];
}
include('../estructura/navegacion.php');
?>

<body>
<div class="container" id="mycontainer">
  <span class="subtituloMenu">DETALLES EXPEDIENTE</span><br><br>

      <?php 
        $identificador = $_GET["identificador"];
        $expediente= mysqli_query($conexion, "SELECT * FROM expedientes WHERE identificador='$identificador'");
        $expediente = mysqli_fetch_assoc($expediente);
        //$_SESSION['expediente'] = $id;
        //$_SESSION['id_expediente'] = $id;
        //$tipoBuscador = $_SESSION['tipo_buscador'];
        //$documento = $usuario2['documento'];
        //$_SESSION['afiliado'] = $documento;
      ?>

      <?php mostrarRuta($identificador);echo '<br>'?>  
      <div class="caja">
        
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">ID: <a><?php echo $expediente['identificador'].'<br>';?></a></span>
          </div>

          <div class="col">
            <span class="subtituloDetalles">AÑO: <a><?php echo $expediente['anio'].'<br>';?></a></span>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">TIPO: <a><?php echo $expediente['tipo'].'<br>';?></a></span>
          </div>
          <div class="col">
            <span class="subtituloDetalles">TRAMITE: <a><?php echo $expediente['nombre_tramite'].'<br>';?></a></span>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">DATOS: <a><?php echo $expediente['datos'].'<br>';?></a></span>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">EXTRACTO: <a><?php echo $expediente['extracto'].'<br>';?></a></span>
          </div>
        </div>

    <?php if($expediente['documento']!= 0) {?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">DOCUMENTO: <a><?php echo $expediente['documento'].'<br>';?></a></span>
          </div>
        </div>
    <?php } ?>

    <?php if($expediente['cuit']!= 0) {?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">CUIT: <a><?php echo $expediente['cuit'].'<br>';?></a></span>
          </div>
        </div>
    <?php } ?>

    <?php if($expediente['codigo']!= 0) {?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">CÓDIGO: <a><?php echo $expediente['codigo'].'<br>';?></a></span>
          </div>
        </div>
    <?php } ?>

    <?php $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); ?>  
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">GENERADO: <a><?php echo $fecha.'<br>';?></a></span>
          </div>
        </div>

    <?php if($expediente['activado']=='0'){?>  
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">ESTADO: <a><?php echo "ACTIVADO".'<br>';?></a></span>
          </div>
        </div>
      <?php } if($expediente['activado']=='1'){ ?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">ESTADO: <a style="color: red;"><?php echo "ANULADO".'<br>';?></a></span>
          </div>
        </div>
      <?php } if($expediente['activado']=='2'){ ?>
        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">ESTADO: <a style="color: red;"><?php echo "ARCHIVADO".'<br>';?></a></span>
          </div>
        </div>
    <?php }?>

        <div class="row">
          <div class="col">
            <span class="subtituloDetalles">PRIORIDAD: <a><?php echo $expediente['prioridad'].'<br>';?></a></span></div>
        </div>

    <?php //Si el expediente necesita o no la autorizacion
    if(tieneAutorizacion($expediente['nombre_tramite'])) { 
        if($expediente['autorizado']=='0'){
          $autorizado = "SI";
          $responsable = $expediente['usuario_autorizado'];
          ?>
          <div class="row">
            <div class="col">
              <span class="subtituloDetalles">CON AUTORIZACIÓN: <a><?php echo "SI".'<br>'?></a></span>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <span>AUTORIZADO POR: <a><?php echo $responsable.'<br>'?></a></span>
            </div>
          </div>
          <?php
        }
        else{
          $autorizado = "PENDIENTE";
          ?>
          <div class="row">
            <div class="col">
              <span class="subtituloDetalles">CON AUTORIZACIÓN: <a><?php echo "SI".'<br>'?></a></span>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <span class="subtituloDetalles">AUTORIZADO POR: <a><?php echo "PENDIENTE".'<br>'?></a></span>
            </div>
          </div>
          <?php
        }
    } 
    else{
      ?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles">CON AUTORIZACIÓN: <a><?php echo "NO REQUIERE".'<br>';?></a></span>
        </div>
      </div>
    <?php } ?>

      <?php $estadoActual = estadoActual($id);?>
      <div class="row">
        <div class="col">
          <span class="subtituloDetalles">ACTUALMENTE: <a><?php echo $estadoActual.'<br>';?></a></span>
        </div>
      </div>

      <div class="row">
        <div class="col"><span class="subtituloDetalles">ES ANUAL?
        <?php if($expediente['es_anual']=='0'){ ?> 
          <a><?php echo "SI".'<br>';?></a></span>
        </div>
        <?php }else{ ?>
          <a><?php echo "NO".'<br>';?></a></span>
        </div>
        <?php } ?>
      </div>

    </div> <!-- FIN CAJA -->

      <br>
      <?php 
        if(estaArchivado($id)){ //Si esta archivado, solo las areas permitidas pueden ver los archivos
          if(esAreaArchivados($idAreaUsuario)){ ?>
            <h4 style = "font-family: 'Georgia', cursive; text-align: left;">Archivos</h4>
              <?php  mostrarArchivos($id, $expediente['anio'], $tipoUsuario, $idAreaUsuario); ?>
            <br>
      <?php 
          }
        }
        else{ ?>
            <h4 style = "font-family: 'Georgia', cursive; text-align: left;">Archivos</h4>
              <?php  mostrarArchivos($id, $expediente['anio'], $tipoUsuario, $idAreaUsuario); ?>
            <br>
      <?php  
        } 
      ?>
        <!--h4 style = "font-family: 'Georgia', cursive; text-align: left;">Historial</h4>
        <?php //mostrarHistorial($id); ?>
        <br-->

      <div class="center">
        <a href="expediente.php" class="btn" id="botonGeneral" title="Ver expediente" target="_blank">Ver expediente</a>
        <a href="ver-historial.php" class="btn" id="botonGeneral" title="Ver historial">Historial</a>

      <?php 
        if(!estaArchivado($id)){ //Expedientes archivados no pueden ser ANULADOS
          if(esAreaAnular($idAreaUsuario)){ //Area que puede anular ?>
          <!--button class="btn" type="submit" id="botonAnular" title="Anular Expediente">Anular</button-->
          <button class="btn" id="botonGeneral" type="submit" title="Anular Expediente" onclick="mostrarAnular();">Anular</button>
      <?php 
          }
        }
        else{ //Expediente esta archivado
          if(esAreaDesarchivar($idAreaUsuario)){ //Area que puede desarchivar ?>
          <button class="btn" id="botonGeneral" type="submit" title="Desarchivar Expediente" onclick="mostrarDesarchivar();">Desarchivar</button>
      <?php  
          }
        }
        //A los exp archivados solo se le pueden agregar archivos las areas permitidas
        if(estaArchivado($id)){ 
          if(esAreaAgregarArchivados($idAreaUsuario)){ ?>
            <button class="btn" id="botonGeneral" type="submit" title="Agregar Archivos" onclick="mostrarAgregar();">Agregar archivos</button>
      <?php 
          }
        }
      ?>

      <?php if(existeAfiliadoTitular($expediente['documento'])) { ?>
        <a href="../detalles-usuario.php?id=<?php echo $expediente['documento'];?>" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a>
      <?php } ?>

  <?php if($trabajarAnuales=="0"){ ?>  
      <?php if($expediente['es_anual']=="1"){ ?>
        <button class="btn" id="botonGeneral" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>')">Marcar Anual</button>
      <?php }else{ ?>
        <button class="btn" id="botonGeneral" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>')">Desmarcar Anual</button>
      <?php } ?>
  <?php } ?>

      </div>
      <br>

      <div class="center">
        <form method="POST" id="formularioAnulacion" enctype="multipart/form-data" style="display:none;">
          <div class="form-group col-md-4">
            <label style="color:#003366">Comentario</label><br>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
            <br>
            <button class="btn" type="submit" id="botonAceptar" title="Anular expediente">Aceptar</button>
          </div>
        </form>
      </div>
      <br>

      <div class="center">
        <form method="POST" id="formularioDesarchivar" enctype="multipart/form-data" style="display:none;">
          <div class="form-group col-md-4">
            <label style="color:#003366">Comentario</label><br>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
            <br>
            <button class="btn" type="submit" id="botonAceptar2" title="Desarchivar expediente">Aceptar</button>
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
            <button class="btn" type="submit" id="botonAceptar3" title="Agregar archivos">Aceptar</button>
          </div>
          
        </form>
      </div>

    </div> <!-- FIN DE <div class="container"> -->
    <br><br>
  </div><!-- <div class="container"> -->
</body>
</html>

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

  function eliminar(idArchivo, idExpediente){
    var datos = {"idArchivo":idArchivo, "idExpediente":idExpediente};
    Swal.fire({
        title: '¿Desea eliminar el archivo?',
        width:'600px',
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
                //console.log(data);
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida == 0){
                  return mensajeExito(jsonData.mensaje, jsonData.salida2);
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

  function mensajeError($mensaje){
    swal.fire({
      title: $mensaje, 
      icon: 'error',
      width:'600px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExito($mensaje, $idExpediente){
    Swal.fire({
      icon: 'success',
      width:'650px',
      title: $mensaje, 
      allowOutsideClick: false,
    }).then(function(){
        window.location.replace("detalles-expediente.php?id="+$idExpediente);
    });
  }

$('#botonAceptar').click(function(evento){
  evento.preventDefault();
      var datos = new FormData($('#formularioAnulacion')[0]);
      Swal.fire({
            title: '¿Desea anular el expediente?',
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#148F77',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
      }).then((result) => {
          
          if (result.isConfirmed) {
              $.ajax({
                  url: 'anular-expediente.php',
                  type:'POST',
                  data: datos,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                      //$("#loader").css('display','block');
                  },
                  success: function(data){
                    //console.log(data);
                    //$("#loader").css('display','none');
                    var jsonData = JSON.parse(data);
                    if(jsonData.salida == 0){
                      return mensajeExito(jsonData.mensaje, jsonData.salida2);
                    }
                    else{
                        return mensajeError(jsonData.mensaje);
                    }
                  }
              });
            return false;
          }
      });
});

$('#botonAceptar2').click(function(evento){
  evento.preventDefault();
    var datos = new FormData($('#formularioDesarchivar')[0]);
    Swal.fire({
        title: '¿Desea desarchivar el expediente?',
        width:'600px',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#148F77',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'desarchivar-expediente.php',
            type:'POST',
            data: datos,
            processData: false,
            contentType: false,
            beforeSend: function() {
              //$("#loader").css('display','block');
            },
            success: function(data){
              //console.log(data);
              //$("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje, jsonData.salida2);
              }
              else{
                  return mensajeError(jsonData.mensaje);
              }
            }
          });
         return false;
        }
    });
});

$('#botonAceptar3').click(function(evento){
  evento.preventDefault();
    var datos = new FormData($('#formularioAgregar')[0]);
    Swal.fire({
        title: '¿Desea agregar archivos?',
        width:'600px',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#148F77',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'agregar-archivo.php',
            type:'POST',
            data: datos,
            processData: false,
            contentType: false,
            beforeSend: function() {
              //$("#loader").css('display','block');
            },
            success: function(data){
              console.log(data);
              //$("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje, jsonData.salida2);
              }
              else{
                  return mensajeError(jsonData.mensaje);
              }
            }
          });
         return false;
        }
    });
});

/*
$('#botonAnular').click(function(evento){
  evento.preventDefault();
      //var datos = new FormData($('#formulario')[0]);
      Swal.fire({
            title: '¿Desea anular el expediente?',
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#148F77',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
      }).then((result) => {
          
          if (result.isConfirmed) {
              $.ajax({
                  url: 'anular-expediente.php',
                  type:'POST',
                  //data: datos,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                      //$("#loader").css('display','block');
                  },
                  success: function(data){
                    console.log(data);
                    //$("#loader").css('display','none');
                    var jsonData = JSON.parse(data);
                    if(jsonData.salida == 0){
                      return mensajeExito(jsonData.mensaje, jsonData.salida2);
                    }
                    else{
                        return mensajeError(jsonData.mensaje);
                    }
                  }
              });
            return false;
          }
      });
});
*/

function desarchivar(idExpediente){
    var datos = {"idExpediente":idExpediente};
    Swal.fire({
        title: '¿Desea desarchivar el expediente '+idExpediente+'?',
        width:'600px',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#148F77',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
    }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: 'desarchivar-expediente.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //console.log(data);
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida == 0){
                  return mensajeExito(jsonData.mensaje, jsonData.salida2);
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

function marcar(valor){
  var datos = {"valor":valor};
  Swal.fire({
    title: '¿Desea marcar como Anual?',
    width:'600px',
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
            //console.log(data);
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
    width:'600px',
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
            //console.log(data);
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

function mensajeExito2($mensaje){
    Swal.fire({
      icon: 'success',
      width:'650px',
      title: $mensaje, 
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    }).then(function(){
        //window.location.replace("enviados.php");
        window.location.reload();
    });
  }
</script>



<style type="text/css">

.contenedor{
  margin-right: 30px;
  margin-left: 50px;
}

a{
  color: black;
}

a:hover {
  color: black;
  cursor: pointer;
}

body{
    background-image: url("../imagenes/fondo5.jpg");
}

#datos{
  font-size: 14px;
}

#botonaccion{
  margin-right: 5px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  border-color:#3A73A8;
  margin: 5px;
  background-color:#3A73A8;
  font-family: 'Georgia';
}

#botonaccion:hover{
  color:#3A73A8;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonMenu{
  margin-right: 7px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: right;
  background-color: #148F77;
}

#botonMenu:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonGeneral{
  margin-right: 7px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
  margin-left: 10px;
}

#botonGeneral:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAceptar{
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
}

#botonAceptar:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAceptar2{
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
}

#botonAceptar2:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAceptar3{
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
}

#botonAceptar3:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

.subtituloDetalles{
  font-family: 'italic'; 
  color: #3A73A8;
  font-weight: normal;
}

#botonGeneral{
  margin-right: 5px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  border-color:#3A73A8;
  margin: 5px;
  background-color:#3A73A8;
  font-family: 'Georgia';
}

#botonGeneral:hover{
  color:#3A73A8;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAgregar{
  margin-right: 5px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  border-color:#3A73A8;
  margin: 5px;
  background-color:#3A73A8;
  font-family: 'Georgia';
}

#botonAgregar:hover{
  color:#3A73A8;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonComentario{
  margin-right: 5px;
  color: #3A73A8;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  border-color:#3A73A8;
  margin: 5px;
  background-color:white;
  font-family: 'Georgia';
}

#botonComentario:hover{
  color:white;
  background-color: #3A73A8;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAcciones{
  color: #3A73A8;
  border: 2px solid;
  border-radius: 10px;
  border-color:white;
  margin: 5px;
  background-color:white;
  font-family: 'Georgia';
}

#botonAcciones:hover{
  color:white;
  background-color: #3A73A8;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#detalle{
  font-family: 'Antonio Light';
  color:black;
  font-size: 13px;
}

#fila{
  background-color: #3A73A8; 
  color: white; 
  font-weight: 200;
  font-family: 'Antonio Light';
  font-size: 17px;
}

#botonVolver{
  float:right;
  margin-right: 8px;
  background-color: red;
  color: white;
  border: 2px solid;
  border-radius: 10px;
}

#botonVolver:hover{
   color: red;
   background-color:white;
  -webkit-transform:scale(1.1);transform:scale(1.1); /*Acercamiento*/
}

.caja { 
  border-top: 1px solid #0072BC;
  border-right: 1px solid #0072BC;
  border-bottom: 1px solid #0072BC;
  border-left: 1px solid #0072BC;
  padding: 5px;
  }
</style>
<?php 
include('../estructura/navegacion.php');

if(isset($_POST["id_expediente"])){
    $idExpediente = $_POST["idExpediente"];
    $_SESSION['expediente'] = $idExpediente;
    $expediente= mysqli_query($conexion, "SELECT * FROM expedientes WHERE id='$idExpediente'");
    $expediente = mysqli_fetch_assoc($expediente);
}
?>

<!DOCTYPE html>
<html lang="es">
<body>
<span class="subtituloMenu">DATOS EXPEDIENTES</span><br><br>

  <div class="contenedor1">
    <?php rutaExpediente($idExpediente); echo '<br>';echo '<br>'?>
      <!--div class="container" style="position: absolute;"-->
      
      <div class="row">
        <div class="col"><h6>Id: <a style="color: black;"><?php echo $expediente['id'].' - '.$expediente['anio'].'<br>';?></a></h6></div>
      </div>

      <div class="row">
        <div class="col"><h6>Tipo: <a style="color: black;"><?php echo $expediente['tipo'].'<br>';?></a></h6></div>
      </div>

      <div class="row">  
        <div class="col"><h6>Trámite: <a style="color: black;"><?php echo $expediente['nombre_tramite'].'<br>';?></a></h6></div>
      </div>

      

      <div class="row">
          <?php /*if($expediente['monto']!= 0) {?>
          <div class="col"><h6>Monto: <a style="color: black;"><?php echo $expediente['monto'].'<br>';?></a></h6></div>
          <?php } */?>

          <?php /*if($expediente['documento']!= 0) {?>
          <!--div class="col"><h6>DOCUMENTO: <span style="color: black;"><?php echo $expediente['documento'].'<br>';?></span></h6></div-->
          <div class="col"><h6>Documento: <a style="color: black;"><?php echo $expediente['documento'].'<br>';?></a></h6></div>
          <?php } ?>

          <?php if($expediente['cuit']!= 0) {?>
          <div class="col"><h6>Cuit: <a style="color: black;"><?php echo $expediente['cuit'].'<br>';?></a></h6></div>
          <?php } ?>

          <?php if($expediente['codigo']!= 0) {?>
          <div class="col"><h6>Código Tango: <a style="color: black;"><?php echo $expediente['codigo'].'<br>';?></a></h6></div>
          <?php } */?>
      </div>

      <?php $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); ?>  
      <div class="row">
        <div class="col"><h6>Generado: <a style="color: black;"><?php echo $fecha.'<br>';?></a></h6></div>
          <?php if($expediente['activado']=='0'){?>  
        <div class="col"><h6>Estado: <a style="color: black;"><?php echo "ACTIVADO".'<br>';?></a></h6></div>
          <?php } else{ ?>
        <div class="col"><h6>Estado: <a style="color: black;"><?php echo "ANULADO".'<br>';?></a></h6></div>
          <?php }?>
      </div>

        <!--div class="row">  
          <div class="col"><h6>Prioridad: <a style="color: black;"><?php echo $expediente['prioridad'].'<br>';?></a></h6></div>
        </div-->

      <div class="row"> 
        <?php 
        if(tieneAutorizacion($expediente['nombre_tramite'])){
          if($expediente['autorizado']=='0') {
            $responsable = $expediente['usuario_autorizado'];
                $autorizado = 'SI - '.$responsable;
          }
          elseif($expediente['autorizado']=='1'){
            $autorizado = "NO - ".$responsable;
          }
          else{
            $autorizado = "PENDIENTE";
          }
        ?>
          <div class="col"><h6>Autorizado: <a style="color: black;"><?php echo $autorizado.'<br>';?></a></h6></div>
        <?php }else{ ?>
          <div class="col"><h6>Autorización: <a style="color: black;"><?php echo "NO REQUIERE".'<br>';?></a></h6></div>
        <?php } ?>
       </div>

   <?php if($trabajarAnuales=="0"){ ?>       
        <div class="row">
          <div class="col"><h6>Es anual?
          <?php if($expediente['es_anual']=='0'){ ?> 
            <a style="color: black;"><?php echo "SI".'<br>';?></a></h6></div>
          <?php }else{ ?>
            <a style="color: black;"><?php echo "NO".'<br>';?></a></h6></div>
          <?php } ?>
        </div>
    <?php } ?>
        
        <div class="row">
          <div class="col"><?php verArchivos($idExpediente, $expediente['anio'], $tipoUsuario); echo '<br>' ?>
        </div>

        <div class="row">
          <a href="expediente.php" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Ver expediente</a>
            
          <?php if(estaTomado($idExpediente)){ ?>
          <button class="btn" id="verExpediente" type="submit" title="Agregar archivos" onclick="mostrarFormulario();">Agregar</button>
          <?php } ?>

          <?php //if(estaEnviado($idExpediente)){ 
          if(tieneComentarios($idExpediente)){ ?>
            <button class="btn" id="verExpediente" type="submit" title="Ver comentarios" onclick="mostrarComentarios();">Comentarios</button>
          <?php } ?>

          <?php 
          if(existeAfiliadoTitular($expediente['documento'])) { 
          ?>
            <a href="../detalles-usuario.php?id=<?php echo $expediente['documento'];?>" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a>
          <?php 
          } 
          ?>
            
          <?php $estadoActual = estadoActual($expediente['id']);
          if($estadoActual=="TOMADO"){ ?>
            <?php if($expediente['es_anual']=="1"){ ?>
              <button class="btn" id="botonGeneral" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>')">Marcar Anual</button>
              <?php }else{ ?>
              <button class="btn" id="botonGeneral" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>')">Desmarcar Anual</button>
            <?php } ?>
          <?php } ?>
        </div>

        <div class="row">
          <form method="POST" id="formularioextra" enctype="multipart/form-data" style="display:none;"><br>
            <div class="form-group col-md-12">
              <label style="color:#003366">Agregar archivos</label><br>
                <input multiple="true" name="archivo[]" id="file" type="file"><br>
              <label style="color:#003366">Formatos aceptados: PDF, PNG, JPG y JPEG.<br>No agregar archivos con el mismo nombre</label>
            </div>
            <button class="btn" type="submit" id="botonAgregar" title="Agregar archivos">Aceptar</button>
          </form>

          <div id="tablaComentarios" style="display:none"><br>
            <?php if(tieneComentarios($idExpediente)){
              devolverComentarios($idExpediente);
            }?>
          </div>
        </div>
  </div>
<?php
//Busca la ruta del expediente revisando el log de expedientes
function rutaExpediente($id){
  include("../conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE id_expediente='$id'");
  if($result->num_rows>0){
    $fin2 = "";
    while($fila=$result->fetch_assoc()){
      if(($fila['estado']!="TOMADO")&&($fila['estado']!="ARCHIVADO")&&($fila['estado']!="ANULADO")&&($fila['estado']!="DESARCHIVADO-TOMADO")){
        if($fila['id_inicio']!=$fin2){
          $inicio = getArea($fila['id_inicio']);
          $fin = getArea($fila['id_fin']);
          $flecha = "<img title='GENERADO' src='../imagenes/flecha.jpg' width='25' height='25'>";
          //echo $inicio." --> ".$fin;
          echo $inicio." ".$flecha." ".$fin;
        }
        else{
          $fin = getArea($fila['id_fin']);
          echo " ".$flecha." ".$fin;
        }
        $fin2 = $fila['id_fin'];
      }
    }
  }
}

function getArea($id){
  include("../conexion.php");
  $salida = "";
  $result = mysqli_query($conexion, "SELECT * FROM areas WHERE id='$id'");
  if($result->num_rows>0){
    while($fila=$result->fetch_assoc()){
        $salida = $fila['nombre'];
    }
  }
  return $salida;
}

function verArchivos($id, $anio, $tipoUsuario){
  $ruta1='../archivos/'.$anio.'/'.$id; 
  if (is_dir($ruta1)){ //Indica si el nombre de archivo es un directorio
    if ($dh = opendir($ruta1)){ //Abre un gestor de directorio   
  ?>
    <div class="col">
      <?php 
      while (($file = readdir($dh)) !== false){ //readdir — Lee una entrada desde un gestor de directorio
        $ext = pathinfo($file, PATHINFO_EXTENSION); //Obtengo la extension del archivo
        $nombre = pathinfo($file, PATHINFO_FILENAME);
              
        if (is_dir($ruta1) && $file != '.' && $file != '..' && ($ext =="pdf" or $ext =="PDF")){ 
          $archivo = $ruta1.'/'.$file;
      ?>
          <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none; color:#3A73A8;"><img src='../imagenes/pdf.png' height='50' width='50' align=center target='_blank'></a>  
      <?php
        }
            
        if (is_dir($ruta1) && $file != '.' && $file != '..' && (($ext =="jpg") or ($ext == "png") or ($ext == "jpeg") or ($ext =="JPG") or ($ext == "PNG") or ($ext == "JPEG"))){ 
          $archivo = $ruta1.'/'.$file;
        ?>
            <a href='<?php echo $archivo?>' target='_blank' style="text-decoration:none; color:#3A73A8;"><img src='../imagenes/foto-jpg.png' height='50' width='50' align=center></a>
        <?php
        }
      }//while
      ?>
    <div>
    <?php 
    closedir($dh);
    }
  }
}

function devolverComentarios($id){
  include("../conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE id_expediente='$id'");
  if($result->num_rows>0){
    ?>
      <table class="table table-bordered">
        <tr>
          <td id="filaComentario">N°</td>
          <td id="filaComentario">Usuario</td>
          <td id="filaComentario">Comentario</td>
          <?php 
            $i=0;
            $j=1;
            while ($fila=$result->fetch_assoc()) {
              if($fila['comentario']!=""){
          ?>
                <tr>
                  <td id="detalleComentario"> <?php echo $j; ?></td>
                  <td id="detalleComentario"> <?php echo $fila['usuario']; ?></td>
                  <td id="detalleComentario"> <?php echo $fila['comentario']; ?></td>
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

?>

<script type="text/javascript">
  function mostrarFormulario() {
    var x = document.getElementById('formularioextra');
     var y = document.getElementById('tablaComentarios');

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

  function mostrarComentarios() {
    var x = document.getElementById('tablaComentarios');
    var y = document.getElementById('formularioextra');

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

  function autorizar(idExpediente){
    var datos = {"idExpediente":idExpediente};
    Swal.fire({
        title: '¿Desea autorizar el expediente '+idExpediente+'?',
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
              url: 'autorizar-expediente.php',
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
        window.location.replace("bandejas-expediente.php");
    });
  }

  $('#botonAgregar').click(function(evento){
  evento.preventDefault();
  if(validarFormulario()){
      var datos = new FormData($('#formularioextra')[0]);
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
                    //console.log(data);
                    //$("#loader").css('display','none');
                    var jsonData = JSON.parse(data);
                    if(jsonData.salida == 0){
                      return mensajeExito1(jsonData.mensaje, jsonData.salida2);
                    }
                    else{
                        return mensajeError1(jsonData.mensaje);
                    }
                  }
              });
            return false;
          }
      });
    }
});

function mensajeError1($mensaje){
    swal.fire({
      title: $mensaje, 
      icon: 'error',
      width:'600px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExito1($mensaje, $idExpediente){
    Swal.fire({
      icon: 'success',
      width:'650px',
      title: $mensaje, 
      allowOutsideClick: false,
    }).then(function(){
        window.location.replace("tomados.php");
    });
  }

function validarFormulario(){

  if($('input[type="file"]').val()!=null){
    if($('input[type="file"]').val() != ''){ 
      var archivos = document.getElementById('file');
      var cantidad = archivos.files.length;

      if(cantidad > 8){
          return mensajeError("SUPERO LA CANTIDAD MAXIMA DE 8 ARCHIVOS");
          return false;
      }
      else{
          var fsize = 0;
          for (var i = 0; i <= cantidad - 1; i++) {
            fsize = fsize + archivos.files.item(i).size;   
          }
          if(fsize > 8000000){ //8MB de tamaño maximo - PHP permite hasta 8MB
            return mensajeError("SUPERO EL TAMAÑO MAXIMO DE 8MB");
            return false;
          }
          else{
            var i=0;
            var bandera=true;
            while((i<cantidad) && (bandera)){
              if((archivos.files.item(i).type!="application/pdf")&&(archivos.files.item(i).type!="image/jpeg")&&(archivos.files.item(i).type!="image/png")){
                  bandera=false;
              }
              i=i+1;
            }
            if(!bandera){
              return mensajeError("SOLO SE PERMITEN ARCHIVOS PDF, PNG, JPG Y JPEG");
              return false;
            }
          }
      }
    }
    else{
      return mensajeError("No agrego archivos");
      return false;
    }
  }
  else{
    return mensajeError("No agrego archivos");
    return false;
  }
return true;
};

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

.contenedor1{
border-top: 1px solid #0072BC;
border-right: 1px solid #0072BC;
border-bottom: 1px solid #0072BC;
border-left: 1px solid #0072BC;
padding: 10px;
margin-top: 8px;
}

a{
  font-size: 13px;
}

#botonEnviar{
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
}
#botonEnviar:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#verExpediente{
  margin-right: 3px;
  margin-left: 3px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 14px;
}
#verExpediente:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#verDetalles{
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
}
#verDetalles:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

h6{
  font-family: 'italic'; 
  color: #3A73A8;
  font-weight: normal;
}
span{
  font-size: 14px;
}

#botonAgregar{
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  margin-left: 5px;
}
#botonAgregar:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonGeneral{
  margin-right: 3px;
  margin-left: 3px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  background-color: #148F77;
  font-size: 13px;
}
#botonGeneral:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}
</style>


   
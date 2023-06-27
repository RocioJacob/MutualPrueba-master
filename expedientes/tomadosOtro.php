<?php 
include('../estructura/navegacion.php');
?>
<body>
  <script type="text/javascript" src="../util/js/recibidos.js"></script>
  <div class="container" id="mycontainer">
  <span class="subtituloMenu">BANDEJAS</span><br><br>

    <?php 
      $recibidos = mysqli_query($conexion, "SELECT count(*) as totalRecibidos FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'");
      $recibidos = mysqli_fetch_array($recibidos);
      $recibidos = $recibidos['totalRecibidos'];

      $tomados = mysqli_query($conexion, "SELECT count(*) as totalTomados FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='TOMADO' AND activado='0'");
      $tomados = mysqli_fetch_array($tomados);
      $tomados = $tomados['totalTomados'];

      $enviados = mysqli_query($conexion, "SELECT count(*) as totalEnviados FROM bandejas WHERE id_inicio='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'");
      $enviados = mysqli_fetch_array($enviados);
      $enviados = $enviados['totalEnviados'];

      $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='TOMADO' AND activado='0'order by id DESC");
    ?>

<!-- Para computadora -->
  <!--div class="d-none d-sm-none d-md-block"-->
    <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <a href="recibidos.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">RECIBIDOS 
        <span class="tituloBandeja"><?php echo "(".$recibidos.")";?></span></a>
      
      <a href="tomados.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">TOMADOS 
        <span class="tituloBandeja"><?php echo "(".$tomados.")";?></span></a>
      
      <a href="enviados.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">ENVIADOS 
        <span class="tituloBandeja"><?php echo "(".$enviados.")";?></span></a>
    </div><br><br>

    <div>
      <span id="subtituloBandejas">EXPEDIENTES TOMADOS </span>
      <img id="imagenRecargar" title="comentario" src="../util/imagenes/recargar.png" width="35" height="35" onclick="recargar();">
      
      <table class="table table-bordered">
        <tr>
        <td id="filaBandeja">N°</td>
        <td id="filaBandeja">Enviado por</td>
        <td id="filaBandeja">Fecha</td>
        <td id="filaBandeja">Afiliado/Proveedor</td>
        <td id="filaBandeja">Detalles</td>
        
        <?php 
        while($row = $sql->fetch_assoc()){
          $idExpediente = $row['id_expediente'];
          $anio = $row['anio'];
          $identificador = $row['identificador'];

          $query = mysqli_query($conexion, "SELECT * FROM expedientes WHERE id_expediente='$idExpediente' AND anio='$anio'");
          $expediente = mysqli_fetch_assoc($query);
          
          $afiliadoProveedor = "";
          if($expediente['afiliado']!=""){
            $afiliadoProveedor = $expediente['afiliado'];
          }
          if($expediente['proveedor']!=""){
            $afiliadoProveedor = $expediente['proveedor'];
          }
      ?>
          <tr>
          <?php 
          if(prioridadAlta($row['id_expediente'])){ 
          ?>
            <td id="detalleP"> <?php echo $row['id_expediente']; ?> </td>
          <?php 
          }else{ 
          ?>
            <td id="detalleBandeja"> <?php echo $row['id_expediente']; ?> </td>
          <?php 
          } ?>
            <td id="detalleBandeja"> <?php echo nombreArea($row['id_inicio']); ?> </td>
            <td id="detalleBandeja"> <?php echo date("d-m-Y", strtotime($row['fecha_actualizacion'])); ?></td>
            <td id="detalleBandeja"> <?php echo $afiliadoProveedor; ?></td>
            
            <td id="detalleBandeja" style="text-align: center">
              <a href="" class="verDetalles" data="<?php echo $row["id"] ?>" style="text-decoration: none">
                <!-- Muestro el signo "+" -->
                  <div id="mas<?php echo $row["id"];?>" style="display:block;">
                    <!--Signo "+" en html -->
                    <span style="font-size: 25px;" title="Ver detalle">&#10010;</span> 
                  </div>

                  <!-- Esta el signo "-" pero oculto -->
                  <div id="menos<?php echo $row["id"];?>" style="display:none;">
                    <!--Signo "-" en html -->
                    <span style="font-size: 40px;" title="Ocultar detalle">&#45;</span>
                  </div>
              </a>
            </td>
          </tr>
          

          <tr class="detalle<?php echo $row['id'];?>" style="display: none;">
            <td id="detalleBandeja2" colspan="6" style="border-color: #53BDEB;border-width: 3px;">
              <?php 
              mostrarRuta($idExpediente, $anio);
              mostrarDatosExpediente($idExpediente, $anio);
              if(($idExpediente<912)&&($anio<=2023)){
                listarArchivosAnterior($idExpediente, $anio);
                echo "<br>";
              }
              else{
                listarArchivos($idExpediente, $idUsuario);
                echo "<br>";
              }
              if(tieneComentarios($idExpediente, $anio)){ 
                devolverComentarios($idExpediente, $anio);
                echo "<br>";
              } 
              ?>

              <!--a href="expedientePdf.php" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a-->

              <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>

              <?php 
              if(existeAfiliadoTitular($expediente['documento'])) { 
              ?>
                <a href="detalles-afiliado.php?id=<?php echo $expediente['documento'];?>" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a>
              <?php 
              } 
              ?>

              <a href="" class="agregarArchivo" data="<?php echo $row["id"] ?>" style="text-decoration: none">
                <button class="btn botonArchivo" type="submit" title="Agregar archivos">Agregar</button>
              </a>

              <?php 
              if($expediente['es_anual']=="1"){ 
              ?>
                <button class="btn" id="botonGeneral" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>')">Marcar Anual</button>
              <?php 
              }else{ 
              ?>
                <button class="btn" id="botonGeneral" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>')">Desmarcar Anual</button>
              <?php 
              } 
              ?>

              <?php 
              if(existeAfiliadoTitular($expediente['documento'])) { 
              ?>
                <!--a href="" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a-->
              <?php 
              } 
              ?>

              <div id="formularioArchivo<?php echo $row['id'];?>" style="display:none; font-size: 15px">
                <form method="POST" class="formularioextra" enctype="multipart/form-data"><br>
                  <?php //echo $row['id']." - ".$row['id_expediente']." - ".$row['anio'];?>
                  <div class="form-group col-md-12">
                    <input type="hidden" name="idExpediente" id="idExpediente" value="<?php echo $row['id_expediente']?>">
                    <input type="hidden" name="anio" id="anio" value="<?php echo $row['anio'] ?>">
                    <label style="color:#003366">Agregar archivos a expediente <?php echo $idExpediente?></label><br>
                      <input multiple="true" name="archivo[]" id="file" type="file"><br>
                    <label style="color:#003366">Formatos aceptados: PDF, PNG, JPG y JPEG.<br>No agregar archivos con el mismo nombre</label>
                  </div>
                  <button type="submit" id="botonAgregar" title="Agregar archivos">Aceptar</button>
                </form>
              </div>

            </td>
          </tr>  

        <?php 
        } 
        ?>
      </tr>
      </table>
    </div>

  <!--/div--><!--Para computadora-->
</div>
</body>

<script type="text/javascript">

  $(".agregarArchivo").click(function(e){
    e.preventDefault();
    var z = document.getElementById('formularioArchivo'+$(this).attr('data'));
    if (z.style.display === 'none') { //Si no se ve, que se vea
      z.style.display = 'block';
    }
    else{
      z.style.display = 'none';
    }

  });

  //Se ejecuta si hago click en el signo "+" o "-"
  $(".verDetalles").click(function(e){
    e.preventDefault();

    $('.detalle'+$(this).attr('data')).toggle(); //La fila "detalle" alterna entre hide() y show()
    var w = document.getElementById('mas'+$(this).attr('data')); //boton +
    var y = document.getElementById('menos'+$(this).attr('data')); //boton -

    //Ingreso si la fila "detalle" es visible
    if($('.detalle'+$(this).attr('data')).is(':visible')){
      w.style.display = 'none'; //oculto boton +
      y.style.display = 'block'; //muestro boton -
    }
    else{//Ingreso si la fila "detalle" NO esta visible
      w.style.display = 'block'; //muestro boton +
      y.style.display = 'none'; //oculto boton -
    }
  }); 


function mensajeError($mensaje){
  swal.fire({
    title: $mensaje, 
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExito($mensaje, $idExpediente){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: $mensaje, 
    allowOutsideClick: false,
    }).then(function(){
        window.location.replace("tomados.php");
  });
}

function mensajeExito2($mensaje){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: $mensaje, 
    allowOutsideClick: false,
    }).then(function(){
        window.location.replace("tomados.php");
  });
}


function marcar(valor){
  var datos = {"valor":valor};
  Swal.fire({
    title: '<span style="font-size:23px;">¿Desea marcar como Anual?</span>',
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
    title: '<span style="font-size:23px;">¿Desea desmarcar como Anual?</span>',
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


/*$('.botonAgregar').click(function(evento){
  evento.preventDefault();
  //if(validarFormularioAgregar()){
      var datos = $('.formularioextra'+$(this).attr('data')).serialize();
      //var datos = new FormData($('#formularioextra')[0]);
      //var datos = new FormData($('.formularioextra'+$(this).attr('data'))[0]);
      //console.log(JSON.stringify(datos));
      Swal.fire({
        title: '<span style="font-size:23px;">¿Desea agregar archivos?</span>',
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
                  url: 'agregar-archivos.php',
                  type:'POST',
                  data: datos,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                      //$("#loader").css('display','block');
                  },
                  success: function(data){
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
    //}
});*/


function validarFormularioAgregar(){

  if($('input[type="file"]').val()!=null){
    if($('input[type="file"]').val() != ''){ 
      var archivos = document.getElementById('file');
      var cantidad = archivos.files.length;

      if(cantidad > 8){
          return mensajeError("Súpero la cantidad máxima de 8 archivos");
          return false;
      }
      else{
          var fsize = 0;
          for (var i = 0; i <= cantidad - 1; i++) {
            fsize = fsize + archivos.files.item(i).size;   
          }
          if(fsize > 8000000){ //8MB de tamaño maximo - PHP permite hasta 8MB
            return mensajeError("Súpero el tamaño máximo de 8MB");
            return false;
          }
          else{
            var i=0;
            var bandera=true;
            while((i<cantidad) && (bandera)){
              if((archivos.files.item(i).type!="application/pdf")&&(archivos.files.item(i).type!="image/jpeg")&&(archivos.files.item(i).type!="image/png")&&(archivos.files.item(i).type!="application/PDF")&&(archivos.files.item(i).type!="image/JPEG")&&(archivos.files.item(i).type!="image/PNG")){
                  bandera=false;
              }
              i=i+1;
            }
            if(!bandera){
              return mensajeError("Solo se permite archivos PDF, PNG, JPG y JPEG");
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
</script>


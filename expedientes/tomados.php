<?php 
include('../estructura/navegacion.php');
include('modalAgregar.php');
include('modalArchivar.php');
include('modalEnviar1.php');
include('modalEnviar2.php');
?>

<!DOCTYPE html>
<html lang="es">

<body>
  <div class="container-fluid">
  <span class="subtituloMenu">BANDEJAS</span>
    <?php
    include('bandejas.php');
    ?>
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
        $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='TOMADO' AND activado='0'order by fecha_actualizacion DESC");

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
              <td id="detalleP"><?php echo $row['id_expediente'];?></td>
            <?php 
            }
            else{ 
            ?>
              <td id="detalleBandeja"><?php echo $row['id_expediente'];?></td>
            <?php 
            } 
            ?>
              <td id="detalleBandeja"><?php echo nombreArea($row['id_inicio']);?></td>
              <td id="detalleBandeja"><?php echo date("d-m-Y", strtotime($row['fecha_actualizacion']));?></td>
              <td id="detalleBandeja"><?php echo $afiliadoProveedor;?></td>
              <td id="detalleBandeja">
                <a href="" class="verDetalles" data="<?php echo $row["id"]?>" style="text-decoration: none">
                  <div id="mas<?php echo $row["id"]?>" style="display:block;"><span style="font-size: 25px;" title="Ver detalle">&#10010;</span></div>
                  <div id="menos<?php echo $row["id"];?>" style="display:none;"><span style="font-size: 40px;" title="Ocultar detalle">&#45;</span></div>
                </a>
              </td>
          </tr>

          <tr class="detalle<?php echo $row['id'];?>" style="display: none;">
          <td id="detalleBandeja2" colspan="6" style="border-color: #53BDEB;border-width: 3px;">
              <?php 
                mostrarRuta($idExpediente, $anio); 
                mostrarDatosExpediente($idExpediente, $anio);

                //Verificar cuando se use la nueva version
                if((($idExpediente<915)&&($anio<=2023)) or ($anio<2023)){
                  listarArchivosViejo($idExpediente, $anio, $idUsuario);echo "<br>";
                }else{
                  //listarArchivosTomados($idExpediente, $idUsuario);echo "<br>";
                  listarArchivosNuevo($idExpediente, $anio, $idUsuario);echo "<br>";
                }
                
              ?>
              <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>

<!------------------------------------------------------------------------------------------------------->
              <?php //AGREGAR ARCHIVO
              //Tiene permiso de agregar archivo
              if($agregarArchivo=="0"){
              ?> 
                <a href="#" class="agregarArchivo" data-toggle="modal" data-target="#myModalAgregar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonAgregar" type="button" title="Agregar archivos">Agregar</button></a>
              <?php
              }else{
              ?>
                <a href="#" class="agregarArchivo" data-toggle="modal" data-target="#myModalAgregar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonAgregar" type="button" title="Agregar archivos" disabled>Agregar</button></a>
              <?php
              }
              ?>
<!------------------------------------------------------------------------------------------------------->

              <?php //ARCHIVAR EXPEDIENTE
              //Si expediente No esta oculto
              if(!estaOculto($row["id"])){
                if($archivarExpediente == "0"){ 
              ?>
                  <a href="#" class="archivarExpediente" data-toggle="modal" data-target="#myModalArchivar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonArchivar" type="button" title="Archivar">Archivar</button></a>
              <?php 
              }else{
              ?> 
                  <a href="#" class="archivarExpediente" data-toggle="modal" data-target="#myModalArchivar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonArchivar" type="button" title="Archivar" disabled>Archivar</button></a>
              <?php
                }
              }//Si expediente esta oculto
              else{
                //SI usuario puede archivar oculto
                if($archivarOculto == "0"){ 
                ?>
                    <a href="#" class="archivarExpediente" data-toggle="modal" data-target="#myModalArchivar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonArchivar" type="button" title="Archivar">Archivar Oculto</button></a>
                <?php 
                }//SI usuario NOpuede archivar oculto
                else{
                ?> 
                    <a href="#" class="archivarExpediente" data-toggle="modal" data-target="#myModalArchivar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonArchivar" type="button" title="Archivar" disabled>Archivar Oculto</button></a>
                <?php
                }
              }
              ?>
<!--------------------------------------------------------------------------------------------------->
            <?php //ENVIAR EXPEDIENTE
            //Si es un expediente que NO necesita autorizacion
            if(!expedienteConAutorizacion($idExpediente)){
            ?>
              <a href="#" class="enviarArchivo2" data-toggle="modal" data-target="#myModalEnviar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none">
                <button class="btn" id="botonEnviar" type="button" title="Enviar archivos">Enviar</button></a>
            <?php
            }
            //Si es un expediente que SI necesita autorizacion
            else{ 
                $estadoAutorizacion = estadoAutorizacion($idExpediente);

                //Esta autorizado el expediente o esta rechazado
                if(($estadoAutorizacion == "0")or($estadoAutorizacion == "1")){ 
            ?>
                  <a href="#" class="enviarArchivo2" data-toggle="modal" data-target="#myModalEnviar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonEnviar" type="button" title="Enviar archivos">Enviar</button></a>
                
                <?php
                }

                //Esta pendiente la autorizacion
                if($estadoAutorizacion == "2"){ 
                  //Usuario SI puede autorizar
                  if($autorizarExpediente == "0"){ 
                ?>
                    <a href="#" class="enviarArchivo1" data-toggle="modal" data-target="#myModalEnviar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none"><button class="btn" id="botonEnviar" type="button" title="Enviar archivos">Enviar</button></a>
                  <?php
                  }//Usuario NO puede autorizar
                  else{ 
                  ?>
                    <a href="#" class="enviarArchivo2" data-toggle="modal" data-target="#myModalEnviar" data-idexpediente="<?php echo $row["id"] ?>" data-identificador="<?php echo $row["identificador"] ?>" style="text-decoration: none">
                      <button class="btn" id="botonEnviar" type="button" title="Enviar archivos">Enviar</button></a>
                  <?php  
                  }
                }
            }
            ?>
<!------------------------------------------------------------------------------------------------------->
          
            <?php //MARCAR Y DESMARCAR COMO ANUAL
            if($expediente['es_anual'] == "1"){
              if($marcarAnual == "0"){
            ?>
                <button class="btn" id="botonGeneral" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')">Marcar Anual</button>
              <?php 
              }else{
              ?>
                <button class="btn" id="botonGeneral" type="submit" title="Marcar anual" onclick="marcar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')" disabled>Marcar Anual</button>
            <?php
              }
            }
            else{ 
              if($desmarcarAnual == "0"){
            ?>
                <button class="btn" id="botonGeneral" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')">Desmarcar Anual</button>
              <?php 
              }else{
              ?>
                <button class="btn" id="botonGeneral" type="submit" title="Desmarcar anual" onclick="desmarcar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')" disabled>Desmarcar Anual</button>
            <?php  
              }
            }
            ?>
<!------------------------------------------------------------------------------------------------------->
              <?php //OCULTAR Y DESOCULTAR EXPEDIENTE
              //Expediente NO esta oculto
              if($expediente['oculto'] == '1'){
                //Usuario puede ocultar expediente
                if($ocultarExpediente == '0'){ 
              ?>
                  <button class="btn" id="botonGeneral" type="submit" title="Ocultar" onclick="ocultar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')">Ocultar</button>
                <?php
                }
                else{
                ?>
                  <button class="btn" id="botonGeneral" type="submit" title="Ocultar" onclick="ocultar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')" disabled>Ocultar</button>
                <?php
                }
              }
              else{ //Expediente esta oculto
                  //Usuario puede desocultar expediente
                  if($desocultarExpediente == '0'){ 
              ?>
                    <button class="btn" id="botonGeneral" type="submit" title="Mostrar" onclick="desocultar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')">Desocultar</button>
                  <?php
                  }
                  else{//Usuario NO puede desocultar expediente
                  ?>
                    <button class="btn" id="botonGeneral" type="submit" title="Mostrar" onclick="desocultar('<?php echo $expediente['id']?>', '<?php echo $row['identificador'] ?>')" disabled>Desocultar</button>
                  <?php
                  }
              }
              ?>

<!---------------------------------------------------------------------------------------------------------->

    <?php
    if($expediente['documento']!="0"){
    ?>
      <a href="../afiliados/detalles-afiliado.php?id=<?php echo $expediente['documento'];?>" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a>

    <?php
    }
    ?>
<!---------------------------------------------------------------------------------------------------------->
          <?php
            echo '<br>'; echo '<br>'; echo '<br>';
              if(tieneComentarios($idExpediente, $anio)){ 
                devolverComentarios($idExpediente, $anio); 
              } 
            ?>
<!------------------------------------------------------------------------------------------------------->



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
</html>

<script type="text/javascript">

  $(document).ready(function() {
    $(".agregarArchivo").click(function(e) {
      e.preventDefault();
      var idExpediente = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpediente").text("Agregar archivos al expediente: " + identificador);
      $('#idExpedienteInput').val(idExpediente); // Establecer el valor del campo oculto
      $('#myModalAgregar').modal('show');
    });

    $(".enviarArchivo1").click(function(e) {
      e.preventDefault();
      var idExpedienteEnviado1 = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpedienteEnviar1").text("Enviar expediente: " + identificador);
      $('#idExpedienteEnviado1').val(idExpedienteEnviado1); // Establecer el valor del campo oculto
      $('#myModalEnviar1').modal('show');
    });

    $(".enviarArchivo2").click(function(e) {
      e.preventDefault();
      var idExpedienteEnviado2 = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpedienteEnviar2").text("Enviar expediente: " + identificador);
      $('#idExpedienteEnviado2').val(idExpedienteEnviado2); // Establecer el valor del campo oculto
      $('#myModalEnviar2').modal('show');
    });

    $(".archivarExpediente").click(function(e) {
      e.preventDefault();
      var idExpediente = $(this).data('idexpediente');
      var identificador = $(this).data('identificador');
      $("#mensajeExpedienteArchivar").text("Archivar expediente: " + identificador);
      $('#idExpediente').val(idExpediente); // Establecer el valor del campo oculto
      $('#myModalArchivar').modal('show');
    });
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


//*******************************************************************************************
  function marcar(valor, identificador){
    var datos = {"valor":valor, "identificador":identificador};
    Swal.fire({
      title: '<span style="font-size: 22px;">¿Desea marcar como Anual?</span>',
      text: 'Expediente: '+identificador,
      width:'500px',
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
              if(jsonData.salida1 == 0){
                return mensajeExitoAnual(jsonData.mensaje);
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

  function desmarcar(valor, identificador){
    var datos = {"valor":valor, "identificador":identificador};
    Swal.fire({
      title: '<span style="font-size: 22px;">¿Desea desmarcar como Anual?</span>',
      text: 'Expediente: '+identificador,
      width:'500px',
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
              if(jsonData.salida1 == 0){
                return mensajeExitoAnual(jsonData.mensaje);
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

  function mensajeExitoAnual($mensaje){
    swal.fire({
      title: "Exito",
      text: $mensaje,
      icon: 'success',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    }).then(function(){
          window.location.replace("tomados.php");
    });
  }

//*****************************************************************************************************

  function mensajeError($mensaje){
    swal.fire({
      title: $mensaje, 
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExito($mensaje){
    Swal.fire({
      icon: 'success',
      width:'500px',
      title: $mensaje, 
      allowOutsideClick: false,
      }).then(function(){
          window.location.replace("tomados.php");
    });
  }

//**********************************************************************************************
  function ocultar(id, identificador){
    var datos = {"id":id};
    Swal.fire({
        title:'<span style="font-size: 22px;">¿Desea ocultar el expediente?</span>',
        text: 'Expediente: '+identificador,
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
              url: 'ocultar-expediente.php',
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
                  return mensajeExitoOcultar(jsonData.mensaje);
                }
                else{
                  return mensajeErrorOcultar(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
  }

  function desocultar(id, identificador){
    var datos = {"id":id};
    Swal.fire({
        title:'<span style="font-size: 22px;">¿Desea desocultar el expediente?</span>',
        text: 'Expediente: '+identificador,
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
              url: 'desocultar-expediente.php',
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
                  return mensajeExitoOcultar(jsonData.mensaje);
                }
                else{
                  return mensajeErrorOcultar(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
  }

  function mensajeErrorOcultar($mensaje){
    swal.fire({
      title: "Error",
      text: $mensaje,
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExitoOcultar($mensaje){
    swal.fire({
      title: "Exito",
      text: $mensaje,
      icon: 'success',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    }).then(function(){
          window.location.replace("tomados.php");
    });
  }

//***********************************************************************************************
</script>

<script type="text/javascript">

  function eliminarArchivoViejo(archivo, nombreArchivo, idExpediente, anio, idUsuario){
    var datos = {"archivo":archivo, "nombreArchivo":nombreArchivo, "idExpediente":idExpediente, "anio":anio, "idUsuario":idUsuario};
    
    Swal.fire({
        title: '<span style="font-size: 22px;">¿Desea eliminar el archivo?</span>',
        text: "Expediente: "+idExpediente+"-"+anio,
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
              url: 'eliminar-archivo-viejo.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida1 == 0){
                  return mensajeExitoEliminarViejo(jsonData.mensaje);
                }
                else{
                  return mensajeErrorEliminarViejo(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
}

function mensajeExitoEliminarViejo($mensaje){
  swal.fire({
    title: "Error",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoEliminarViejo($mensaje){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: 'Exito',
    text: $mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    }).then(function(){
      //window.open("");
      window.location.replace("tomados.php");
  });
}

//****************************************************************************************

function eliminarArchivoNuevo(idExpediente, anio, idUsuario, link, idArchivo){
    var datos = {"idExpediente":idExpediente, "anio":anio, "idUsuario":idUsuario, "link":link, "idArchivo": idArchivo};
    
    Swal.fire({
        title: '<span style="font-size: 22px;">¿Desea eliminar el archivo?</span>',
        text: "Expediente: "+idExpediente+"-"+anio,
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
              url: 'eliminar-archivo-nuevo.php',
              type:'POST',
              data: datos,
              beforeSend: function() {
                  //$("#loader").css('display','block');
              },
              success: function(data){
                //$("#loader").css('display','none');
                var jsonData = JSON.parse(data);
                if(jsonData.salida == 0){
                  return mensajeExitoEliminarNuevo(jsonData.mensaje);
                }
                else{
                  return mensajeErrorEliminarNuevo(jsonData.mensaje);
                }
              }
            });
            return false;
          }
        });
}

function mensajeErrorEliminarNuevo($mensaje){
  swal.fire({
    title: "Error",
    text: $mensaje,
    icon: 'error',
    width:'500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExitoEliminarNuevo($mensaje){
  Swal.fire({
    icon: 'success',
    width:'500px',
    title: 'Exito',
    text: $mensaje, 
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
    }).then(function(){
      //window.open("");
      window.location.replace("tomados.php");
  });
}


//*******************************************************************************************

function mensajeErrorArchivo($mensaje){
    swal.fire({
      title: "Error",
      text: $mensaje,
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExitoArchivo($mensaje){
    swal.fire({
      title: "Exito",
      text: $mensaje,
      icon: 'success',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    }).then(function(){
          window.location.replace("tomados.php");
    });
  }
</script>


<style type="text/css">
#botonGeneral{
  margin-right: 3px;
  margin-left: 3px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 13px;
  width: 135px;
}

#botonGeneral:hover{
   color: #148F77;
   background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#verExpediente, #botonAgregar, #botonEnviar, #botonArchivar{
  margin-right: 3px;
  margin-left: 3px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 13px;
  width: 120px;
}
#verExpediente:hover, #botonAgregar:hover, #botonEnviar:hover, #botonArchivar:hover{
   color: #148F77;
   background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#filaC{
  border-color:white;
  background-color: #148F77;
  color: white;

}
#detalleC{
  border-color: #148F77;
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


#botonAccionesEliminar1{
  background-color:red;
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

#botonAccionesEliminar1:hover{
  color: red;
  border-color: red;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#botonAccionesEliminar2{
  background-color:red;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  border-color:white;
  margin: 1px;
  font-family: 'Arial';
  font-size: 13px;
  height: 30px;
  width: 70px;
  opacity: 0.5; /* Reduce la opacidad del botón */
  pointer-events: none; /* Evita que el botón sea interactivo */
  cursor: not-allowed; /* Cambia el cursor a "no permitido" */
}

</style>
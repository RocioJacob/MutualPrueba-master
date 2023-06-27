<?php 
include('../estructura/navegacion.php');
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
      <span id="subtituloBandejas">EXPEDIENTES RECIBIDOS </span>
      <img id="imagenRecargar" title="comentario" src="../util/imagenes/recargar.png" width="35" height="35" onclick="recargar();">
 

      <table class="table table-bordered">
      <tr>
        <td id="filaBandeja">N°</td>
        <td id="filaBandeja">Enviado por</td>
        <td id="filaBandeja">Fecha</td>
        <td id="filaBandeja">Afiliado/Proveedor</td>
        <td id="filaBandeja">Detalles</td>
        <!--td id="filaBandeja">Acciones</td-->
        
        <?php 
        $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'order by id DESC");

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
            <td id="detalleBandeja"><?php echo date("d-m-Y", strtotime($row['fecha_actualizacion'])); ?></td>
            <td id="detalleBandeja"><?php echo $afiliadoProveedor; ?></td>
            <td id="detalleBandeja" style="text-align: center">
              <a href="" class="verDetalles" data="<?php echo $row["id"]?>" style="text-decoration: none">

                  <div id="mas<?php echo $row["id"]?>" style="display:block;"><span style="font-size: 25px;" title="Ver detalle">&#10010;</span></div>

                  <div id="menos<?php echo $row["id"];?>" style="display:none;"><span style="font-size: 40px;" title="Ocultar detalle">&#45;</span></div>
              </a>
            </td>
            <!--td id="detalleBandeja">
              <button onclick="tomarExpediente('<?php //echo $row['id_expediente']?>', '<?php //echo $row['anio']?>', '<?php //echo $row['id_inicio'] ?>', '<?php //echo $row['id_fin'] ?>')" id="botonAccionR">Tomar</button>
            </td-->
          </tr>

           <tr class="detalle<?php echo $row['id'];?>" style="display: none;">
             <td id="detalleBandeja2" colspan="6" style="border-color: #53BDEB;border-width: 3px;">
              <?php 
                mostrarRuta($idExpediente, $anio); 
                mostrarDatosExpediente($idExpediente, $anio);
                
                //Ver el ultimo expeiente hecho hasta que se utilice la nueva versión
                if((($idExpediente<912)&&($anio<=2023)) or ($anio<2023)){
                  listarArchivosAnterior($idExpediente, $anio);echo "<br>";
                }else{
                  listarArchivosRecibidos($idExpediente, $idUsuario);echo "<br>";
                }
                
                if(tieneComentarios($idExpediente, $anio)){ 
                  devolverComentarios($idExpediente, $anio); 
                } 
              ?>
              <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>

              <button onclick="tomarExpediente('<?php echo $row['id_expediente']?>', '<?php echo $row['anio']?>', '<?php echo $row['id_inicio'] ?>', '<?php echo $row['id_fin'] ?>')" class="btn" id="botonTomar">Tomar</button>
             </td>
           </tr>  

        <?php 
        } 
        ?>
      </tr>
      </table>
    </div>
</body>
</html>

<script type="text/javascript">
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

  function tomarExpediente(idExpediente, anio, areaInicio, areaFin){
    var datos = {"idExpediente":idExpediente, "anio":anio, "areaInicio":areaInicio, "areaFin":areaFin};
    Swal.fire({
        title: '<span style="font-size:25px;">¿Desea tomar el expediente N°'+idExpediente+'?</span>',
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
            url: 'tomar-expediente.php',
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
    width:'550px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExito($mensaje, $idExpediente){
  Swal.fire({
    icon: 'success',
    width:'550px',
    title: $mensaje, 
    allowOutsideClick: false,
  }).then(function(){window.location.replace("tomados.php");});
}
</script>

<style type="text/css">
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

#verExpediente, #botonGeneral, #botonTomar{
  margin-right: 3px;
  margin-left: 3px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 14px;
  width: 115px;
}
#verExpediente:hover, #botonGeneral:hover, #botonTomar:hover{
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
</style>
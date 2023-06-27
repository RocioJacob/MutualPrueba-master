<?php 
include('../estructura/navegacion.php');
?>

<!DOCTYPE html>
<html lang="es">

<body>
  <div class="container-fluid">
    <?php
    include('bandejas.php');
    ?>
    <div>
      <h1 class="titulo">EXPEDIENTES ENVIADOS </h1>
      <img id="imagenRecargar" title="comentario" src="../util/imagenes/recargar.png" width="35" height="35" onclick="recargar();">
      
      <table class="table table-bordered">
      <tr>
        <td id="filaBandeja">N°</td>
        <td id="filaBandeja">Enviado por</td>
        <td id="filaBandeja">Fecha</td>
        <td id="filaBandeja">Afiliado/Proveedor</td>
        <td id="filaBandeja">Detalles</td>
        
        <?php
        $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_inicio='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'order by id DESC");

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
                if((($idExpediente<912)&&($anio<=2023)) or ($anio<2023)){
                  listarArchivosAnterior($idExpediente, $anio);echo "<br>";
                }else{
                  listarArchivosEnviados($idExpediente, $idUsuario);echo "<br>";
                }
                
              ?>
              <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>

<!------------------------------------------------------------------------------------------------------->

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
</script>


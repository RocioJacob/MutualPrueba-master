<?php 
include('../estructura/navegacion.php');
?>
<!DOCTYPE html>
<html lang="es">
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

      $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'order by id DESC");
    ?>

<!-- Para computadora -->
  <!--div class="d-none d-sm-none d-md-block"-->
    <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <a href="recibidos.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">RECIBIDOS <span class="tituloBandeja"><?php echo "(".$recibidos.")";?></span></a>
      
      <a href="tomados.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">TOMADOS <span class="tituloBandeja"><?php echo "(".$tomados.")";?></span></a>
      
      <a href="enviados.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">ENVIADOS <span class="tituloBandeja"><?php echo "(".$enviados.")";?></span></a>
    </div><br><br>

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
        <td id="filaBandeja">Acciones</td>
        
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
            <td id="detalleP"><?php echo $row['id_expediente'];?></td>
          <?php 
          }
          else{ 
          ?>
            <td id="detalleBandeja"><?php echo $row['id_expediente'];?></td>
          <?php 
          } ?>
            <td id="detalleBandeja"><?php echo nombreArea($row['id_inicio']);?></td>
            <td id="detalleBandeja"><?php echo date("d-m-Y", strtotime($row['fecha_actualizacion'])); ?></td>
            <td id="detalleBandeja"><?php echo $afiliadoProveedor; ?></td>
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
            <td id="detalleBandeja" style="text-align:center;">
              <button onclick="tomarExpediente('<?php echo $row['id_expediente']?>', '<?php echo $row['anio']?>', '<?php echo $row['id_inicio'] ?>', '<?php echo $row['id_fin'] ?>')" id="botonAccionR">Tomar</button>
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
              } 
              ?>

              <!--a href="expedientePdf.php" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a-->

              <a href="expedientePdf.php?idExpediente=<?php echo $idExpediente;?>&anio=<?php echo $anio;?>" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>

              <?php 
              if(existeAfiliadoTitular($expediente['documento'])) { 
              ?>
                <!--a href="" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cta cte</a-->
              <?php 
              } 
              ?>

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

#verExpediente, #botonGeneral{
  margin-right: 3px;
  margin-left: 3px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 14px;
  width: 115px;
}
#verExpediente:hover, #botonGeneral:hover{
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
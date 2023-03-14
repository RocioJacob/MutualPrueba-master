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

      $sql = mysqli_query($conexion, "SELECT * FROM bandejas WHERE id_fin='$idAreaUsuario' AND estado='ENVIADO' AND activado='0'order by id DESC");
    ?>

<!-- Para computadora -->
  <!--div class="d-none d-sm-none d-md-block"-->
    <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <a href="recibidos.php" class="botonBandeja" title="Ver historial" style="text-decoration: none;">RECIBIDOS 
        <span class="tituloBandeja"><?php echo "(".$recibidos.")";?></span></a>
      
      <a href="" class="botonBandeja" title="Ver historial" style="text-decoration: none;">TOMADOS 
        <span class="tituloBandeja"><?php echo "(".$tomados.")";?></span></a>
      
      <a href="" class="botonBandeja" title="Ver historial" style="text-decoration: none;">ENVIADOS 
        <span class="tituloBandeja"><?php echo "(".$enviados.")";?></span></a>
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
            <td id="detalleBandeja" style="text-align:center;">
              <button onclick="tomar('<?php echo $row['id_expediente']?>', '<?php echo $row['id_inicio'] ?>', '<?php echo $row['id_fin'] ?>')" id="botonAccionR">Tomar</button>
            </td>
          </tr>
          
          <tr class="detalle<?php echo $row['id'];?>" style="display: none;">
            <td id="detalleBandeja2" colspan="6">
              <?php 
              mostrarRuta($idExpediente, $anio);
              mostrarDatosExpediente($idExpediente, $anio);
              listarArchivos($idExpediente, $idUsuario);
              ?>
              
              <div class="row" style="margin-left: 10px;">
                <a href="expedientePdf.php" class="btn" id="verExpediente" title="Ver expediente" target="_blank">Carátula</a>
              </div>

              <?php
              if(tieneComentarios($idExpediente, $anio)){ ?>
                <button class="btn" id="verExpediente" type="submit" title="Ver comentarios" onclick="mostrarComentarios();">Comentarios</button>
              <?php 
              } 
              ?>

              <?php 
              //if(existeAfiliadoTitular($expediente['documento'])) { 
              ?>
                  <!--a href="" class="btn" id="botonGeneral" title="Cuenta corriente" target="_blank">Cuenta corriente</a-->
              <?php 
              //} 
              ?>

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
</style>
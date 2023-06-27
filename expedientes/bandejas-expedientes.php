<?php 
include('../estructura/navegacion.php');
?>

<!DOCTYPE html>
<html lang="es">
<body>
<div class="container" id="mycontainer">
  <hr>
  <h1 class="titulo">BANDEJAS</h1>
  <hr>

<!-- Para computadora -->
  <!--div class="d-none d-sm-none d-md-block"-->

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
    ?>
    
    <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <a href="recibidos.php" class="botonBandejas" title="Ver historial"><img src="../util/imagenes/iconos/bandejaRecibidosB.png" class="iconosBandeja">RECIBIDOS  
        <span class="tituloBandeja"><?php echo "(".$recibidos.")";?></span>
      </a>

      <a href="tomados.php" class="botonBandejas" title="Ver historial"><img src="../util/imagenes/iconos/tomarExpedienteB.png" class="iconosBandeja">TOMADOS
        <span class="tituloBandeja"><?php echo "(".$tomados.")";?></span>
      </a>

      <a href="enviados.php" class="botonBandejas" title="Ver historial"><img src="../util/imagenes/iconos/bandejaEnviadosB.png" class="iconosBandeja">ENVIADOS  
        <span class="tituloBandeja"><?php echo "(".$enviados.")";?></span>
      </a>
    </div><br/><br/>

  <!--/div--><!--Para computadora-->
</div>
</body>
</html>


<script type="text/javascript">
function actualizar(){
  location.reload(true);
}
//Función para actualizar cada 10 segundos(10000 milisegundos)
setInterval("actualizar()",10000);
</script>


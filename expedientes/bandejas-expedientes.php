<?php 
include('../estructura/navegacion.php');
?>

<!DOCTYPE html>
<html lang="es">
<body>
<div class="container" id="mycontainer">
  <span class="subtituloMenu">BANDEJAS</span><br><br>

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
      <a href="recibidos.php" class="btn boton" title="Ver historial">RECIBIDOS 
        <span class="tituloBandeja"><?php echo "(".$recibidos.")";?></span>
      </a>

      <a href="tomados.php" class="btn boton" title="Ver historial">TOMADOS 
        <span class="tituloBandeja"><?php echo "(".$tomados.")";?></span>
      </a>

      <a href="enviados.php" class="btn boton" title="Ver historial">ENVIADOS 
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


<style type="text/css">
  .boton{
  border: 2px solid;
  border-color: #0072BC;
  transition: all 1s ease;
  /*position: relative;*/
  position: relative;
  padding: 25px 1px; /*arriba-abajo, izq-der*/
  margin: 0px 20px 10px 0px;
  float: left;
  border-radius: 15px; /*redondeo de las esquinas*/
  /*font-family: 'Georgia', cursive;*/
  font-family: 'Arial';
  font-size: 15px; /*tamaño letra*/
  color: white; /*color letra*/
  /*text-decoration: none;  */
  width: 170px !important; /*tamaño botones*/
  height: 80px !important;
  text-align: center; /*alineacion de texto*/
  background: #0072BC;
}

.tituloBandeja{
  font-size: 18px;
  font-family: Arial;
}
</style>
<?php  
include('../estructura/navegacion.php');
$identificador = $_SESSION['identificador'];
?>

<body>
  <div class="container" id="mycontainer">
     <span class="subtituloMenu">HISTORIAL DE EXPEDIENTE N° <?php echo $identificador ?></span><br><br>
    <?php mostrarHistorial($identificador); ?>
  </div>
</body>


<?php
function mostrarHistorial($identificador){
  include("../admin/conexion.php");
  $result = mysqli_query($conexion, "SELECT * FROM log_expedientes WHERE identificador='$identificador'");
  if($result->num_rows>0){
    ?>
      <table class="table table-bordered">
        <tr>
          <td id="filaHistorial">Origen</td>
          <td id="filaHistorial">Destino</td>
          <td id="filaHistorial">Estado</td>
          <td id="filaHistorial">Usuario</td>
          <td id="filaHistorial">Fecha</td>
          <td id="filaHistorial">Comentario</td>
          <td id="filaHistorial">Acciones</td>
          <?php 
            $i=0;
            while ($fila=$result->fetch_assoc()) {
              $inicio = mostrarArea($fila['id_inicio']);
              $fin = mostrarArea($fila['id_fin']);
              $fecha = date("d-m-Y H:i:s", strtotime($fila['fecha']));
          ?>
              <tr>
                <td id="detalleHistorial"> <?php echo $inicio; ?> </td>
                <td id="detalleHistorial"> <?php echo $fin; ?> </td>
                <td id="detalleHistorial"> <?php echo $fila['estado']; ?> </td>
                <td id="detalleHistorial"> <?php echo $fila['usuario']; ?> </td>
                <td id="detalleHistorial"> <?php echo $fecha; ?> </td>
                <td id="detalleHistorial"> <?php if($fila['comentario']!='') { ?> 
                    <img title="comentario" src="../util/imagenes/si2.jpg" width="35" height="35" onclick="verComentarios('<?php echo $fila['comentario']?>')">
                    <?php }else{ ?>
                    <img title="Sin comentario" src="../util/imagenes/no1.jpg" width="25" height="25">
                      <?php } ?>
                </td>
                <td id="detalleHistorial">
                  <a href="detalles-expediente.php?id=<?php echo $identificador ?>" class="btn" id="botonVolver">Volver</a>
                </td>

              </tr>
              <?php
              $i=$i+1;
            }
            ?>
          </tr>
        </table>
      <?php
  }
}
?>



<style type="text/css">
#detalleHistorial{
  /*font-family: 'Verdana';*/
  /*font-family: 'Georgia', cursive;*/
  font-family: Arial;
  color:black;
  font-size: 13px;
}

#filaHistorial{
  background-color: #3A73A8; 
  color: white; 
  font-weight: 200;
  /*font-family: 'Georgia', cursive;*/
  font-family: Arial;
  font-size: 17px;
}

#botonVolver{
  float:center;
  margin-right: 5px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 14px;
}
#botonVolver:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}
</style>


<script type="text/javascript">
   function verComentarios(comentario){
    Swal.fire('Comentario',comentario);
  }
</script>
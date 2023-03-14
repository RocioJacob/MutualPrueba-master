<?php 
include_once('../estructura/navegacion.php');
$listaExpediente = mysqli_query($conexion, "SELECT * FROM expedientes");
$expedientes = $listaExpediente->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<body>
<div class="container">
  <table id="tablaExpedientes">
    <thead>
      <tr>
      <th id="columna">ID</th>
      <th id="columna">TIPO</th>
      <th id="columna">TRAMITE</th>
      <th id="columna">DOCUMENTO</th>
      <th id="columna">FECHA</th>
      </tr>
    </thead>
    
    <tbody>
      <?php 
      foreach($expedientes as $expediente){
      ?>   
        <tr>
          <td><?php echo $expediente['id'];?></td>
          <td><?php echo $expediente['tipo'];?></td>
          <td><?php echo $expediente['tipo'];?></td>
          <td><?php echo $expediente['tipo'];?></td>
          <td><?php echo $expediente['tipo'];?></td>
        </tr>
    <?php 
        } 
      ?>
    </tbody>
  </table>
</div>
</body>
</html>

<script type="text/javascript">
  $(document).ready(function () {
    $('#tablaExpedientes').DataTable({
      "language": {
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sSearch": "Buscar:",
          "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
          },
          "sProcessing":"Procesando...",
      },
      "columnDefs": [ { "orderable": false, "targets": 1 }], //ocultar para columna 0
    });
  });
</script>


   
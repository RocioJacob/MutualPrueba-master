<?php 
include_once('../estructura/navegacion.php');
$listaExpediente = mysqli_query($conexion, "SELECT * FROM expedientes ORDER BY id_expediente desc, anio desc");
$expedientes = $listaExpediente->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<body>
<div class="container" id="mycontainer"><br>
  <span class="subtituloMenu">BÚSQUEDA DE EXPEDIENTE</span><br><br>
  <table id="tablaExpedientes" class="table table-striped table-bordered" style="width:100%">
    <thead><tr>
      <th id="columna">Id</th>
      <th id="columna">Año</th>
      <th id="columna">Tipo</th>
      <th id="columna">Trámite</th>
      <th id="columna">Documento/Cuit</th>
      <th id="columna">Afiliado/Proveedor</th>
      <th id="columna">Fecha</th>
      </tr></thead>
  <?php
      foreach($expedientes as $expediente){
        $fechaCreacion = date("d-m-Y", strtotime($expediente['fecha_creacion']));
        $afiliadoProveedor = "";
        if($expediente['afiliado']!=""){
          $afiliadoProveedor = $expediente['afiliado'];
        }
        if($expediente['proveedor']!=""){
          $afiliadoProveedor = $expediente['proveedor'];
        }

        if($expediente['documento']!="0"){
        $documento = $expediente['documento'];
        }
        elseif($expediente['cuit']!="0"){
          $documento = $expediente['cuit'];
        }
        else{
          $documento = $expediente['codigo'];
        }
    ?>
      <tr>
        <td id="fila"> <?php echo $expediente['id_expediente'];?> </td>
        <td id="fila"> <?php echo $expediente['anio'];?> </td>
        <td id="fila"> <?php echo $expediente['tipo'];?> </td>
        <td id="fila"> <?php echo $expediente['tipo'];?> </td>
        <td id="fila"> <?php echo $documento;?> </td>
        <td id="fila"> <?php echo $afiliadoProveedor;?> </td>
        <td id="fila"> <?php echo $fechaCreacion;?> </td>
      </tr>
  <?php 
      } 
    ?>
  </table>
</div>
</body>
</html>


<style type="text/css">
#mycontainer { max-width: 1300px !important; }
#columna{
    background-color: #3498DB;
}
#fila{
  border-color: #3498DB;
  font-family: Verdana;
  font-size: 13px;
}
</style>

<script>
$(document).ready(function() {
$('#tablaExpedientes').DataTable({"order": [[ 1, 'desc']], "language":{"sSearch": "Buscar:", "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros", "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros", "infoFiltered": "(filtrado de un total de _MAX_ registros)", "lengthMenu": "Mostrar _MENU_ registros", "zeroRecords": "No se encontraron resultados", "oPaginate": {"sFirst": "Primero","sLast":"Último","sNext":"Siguiente","sPrevious": "Anterior"}, "sProcessing":"Procesando..."}})});
 </script>


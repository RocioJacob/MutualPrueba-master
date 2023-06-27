<?php 
session_start();
include("../admin/conexion.php");
$tabla = "";

if(isset($_POST["usuario"])){
   $entrada = mysqli_real_escape_string($conexion,(strip_tags($_POST["usuario"],ENT_QUOTES)));
   $query = "SELECT * FROM expedientes WHERE identificador LIKE '".$entrada."%' AND (oculto LIKE '1') AND (archivado LIKE '1') OR (tipo LIKE '%".$entrada."%' OR nombre_tramite LIKE '".$entrada."%' OR anio LIKE '%".$entrada."%' OR documento LIKE '%".$entrada."%' OR cuit LIKE '%".$entrada."%' OR codigo LIKE '%".$entrada."%' OR afiliado LIKE '".$entrada."%' OR proveedor LIKE '".$entrada."%') ORDER BY id DESC";

	//$conexion->query: Realiza una consulta a la base de datos
	$result=$conexion->query($query);
	//$result = mysqli_num_rows($query);

	if($result->num_rows > 0){ //si se obtuvieron resultados
		//$resultado = mysqli_fetch_array($sql);
		$totalExpedientes = $result->num_rows;
		echo '<br>';
		echo '<p style="color:#3A73A8">EXPEDIENTES: '.$totalExpedientes.'</p>';

		$tabla.= '<table class="table table-bordered table-striped">
				<thread>
				<tr class="p-3 mb-2 bg-secondary text-white">
				<td id="filaBusquedaTitulo">Id</td>
				<td id="filaBusquedaTitulo">Tipo</td>
				<td id="filaBusquedaTitulo">Tramite</td>
				<td id="filaBusquedaTitulo">Documento/Cuit</td>
				<td id="filaBusquedaTitulo">Afiliado/Proveedor</td>
				<td id="filaBusquedaTitulo">Fecha</td>
				<td id="filaBusquedaTitulo">Acciones</td>
				</tr>
				</thread>';

		while($row = $result->fetch_assoc()){
			$fechaCreacion = date("d-m-Y", strtotime($row['fecha_creacion']));
			if($row['documento']!="0"){
				$salida = $row['documento'];
			}
			elseif($row['cuit']!="0"){
				$salida = $row['cuit'];
			}
			else{
				$salida = $row['codigo'];
			}

			$salida2 = "";
			if($row['afiliado']!=""){
				$salida2 = $row['afiliado'];
			}
			if($row['proveedor']!=""){
				$salida2 = $row['proveedor'];
			}

			$id_expediente = $row['id_expediente'];
			$anio_expediente = $row['anio'];
			$identificador = $id_expediente."-".$anio_expediente;

			$tabla.='<tr>
					<td id="filaBusquedaResultado">'.$row['id_expediente']."-".$row['anio'].'</td>
					<td id="filaBusquedaResultado">'.$row['tipo'].'</td>
					<td id="filaBusquedaResultado">'.$row['nombre_tramite'].'</td>
					<td id="filaBusquedaResultado">'.$salida.'</td>
					<td id="filaBusquedaResultado">'.$salida2.'</td>
					<td id="filaBusquedaResultado">'.$fechaCreacion.'</td>
					<td id="filaBusquedaResultado">
						<a href=detalles-expediente.php?id='.$row['id'].' class="btn" id="botonAccionBusqueda">Detalles</a>
					</td>
					</tr>';	
		}
		$tabla.='</table>';
	}
	else{
		echo '<br>';
		$tabla.='<h3 style="color:#3A73A8">'."NO SE ENCONTRARON RESULTADOS".'<h3>';
	}
   echo $tabla;
   echo "<br>";
}
?>


<style type="text/css">

#filaBusquedaTitulo{
  /*background-color: #3A73A8;*/
  background-color: #5F5858;
  color: white; 
  font-weight: 200;
  font-family: 'italic';
  font-size: 18px;
  text-align: center;
}

#filaBusquedaResultado{ 
  font-family: 'Arial';
  font-size: 12px;
}

#botonAccionBusqueda{
  float:center;
  margin-right: 5px;
  background-color: #148F77;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  font-size: 14px;
}
#botonAccionBusqueda:hover{
   color: #148F77;
   background-color:white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}
</style>
    



   
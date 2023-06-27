<?php 
session_start();
include("../admin/conexion.php");
$tabla = "";

$query="SELECT * FROM expedientes WHERE archivado LIKE '0' AND (oculto LIKE '1') ORDER BY id DESC LIMIT 0, 50"; //Muestra por defecto los 50 expedientes mas recientes
$sql = mysqli_query($conexion, "SELECT COUNT(*) as totalExpedientes FROM expedientes WHERE archivado LIKE '0'");

    if(isset($_POST["usuario"])){
    	$entrada = mysqli_real_escape_string($conexion,(strip_tags($_POST["usuario"],ENT_QUOTES)));
    	$query = "SELECT * FROM expedientes WHERE archivado LIKE '0' AND (oculto LIKE '1') AND (identificador LIKE '".$entrada."%' OR (tipo LIKE '%".$entrada."%' OR nombre_tramite LIKE '%".$entrada."%' OR documento LIKE '%".$entrada."%' OR cuit LIKE '%".$entrada."%' OR codigo LIKE '%".$entrada."%')) ORDER BY id";

		$sql = mysqli_query($conexion, "SELECT COUNT(*) as totalExpedientes FROM expedientes WHERE archivado LIKE '0' AND (oculto LIKE '1') AND (identificador LIKE '".$entrada."%' OR (tipo LIKE '%".$entrada."%' OR nombre_tramite LIKE '%".$entrada."%' OR documento LIKE '%".$entrada."%' OR cuit LIKE '%".$entrada."%' OR codigo LIKE '%".$entrada."%')) ORDER BY id");
	}

	//$conexion->query: Realiza una consulta a la base de datos
	$result=$conexion->query($query);
	//$result = mysqli_num_rows($query);

	if($result->num_rows > 0){ //si se obtuvieron resultados
		$resultado = mysqli_fetch_array($sql);
		$totalExpedientes = $resultado['totalExpedientes'];

		echo '<br>';
		echo '<p style="color:#3A73A8">EXPEDIENTES: '.$totalExpedientes.'</p>';

		$tabla.= '<table class="table table-bordered table-striped">
					<thread>
					<tr class="p-3 mb-2 bg-secondary text-white">
					<td id="filaArchivado">Id</td>
					<td id="filaArchivado">Tipo</td>
					<td id="filaArchivado">Tramite</td>
					<td id="filaArchivado">Documento/Cuit/Código</td>
					<td id="filaArchivado">Fecha</td>
					<td id="filaArchivado">Acciones</td>
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

				$tabla.='<tr id="datosArchivado">
						<td>'.$row['id_expediente']."-".$row['anio'].'</td>
						<td>'.$row['tipo'].'</td>
						<td>'.$row['nombre_tramite'].'</td>
						<td>'.$salida.'</td>
						<td>'.$fechaCreacion.'</td>
						<td id="detalleArchivado"><a href=detalles-expediente-archivado.php?id='.$row['id'].' class="btn" id="botonAccionArchivado">Detalles</a></td>
						</tr>';	
			}
			$tabla.='</table>';
		}
		else
			{
			echo '<br>';
			$tabla.='<h3 class="text-primary">'."NO SE ENCONTRARON RESULTADOS".'<h3>';
		}
   echo $tabla;
   echo "<br>";
?>

<style type="text/css">


#datosArchivado{
  font-size: 13px;
}

#filaArchivado{
  /*background-color: #3A73A8;*/ 
  background-color: #5F5858;
  color: white; 
  font-weight: 200;
  font-family: 'italic';
  font-size: 18px;
  text-align: center;
}

#fila1{
  /*font-family: 'Georgia', cursive;*/
  font-family: 'Arial';
  font-size: 12px;
}

#detalleArchivado{
  text-align:center;
}

#detalles{
  font-size: 14px;
}

#botonAccionArchivado{
  float:center;
  margin-right: 5px;
  background-color: #0F4C75;
  color: white;
  border: 2px solid;
  border-radius: 10px;
}
#botonAccionArchivado:hover{
   color: #0F4C75;
   background-color:white;
  -webkit-transform:scale(1.1);transform:scale(1.1); /*Acercamiento*/
}
</style>
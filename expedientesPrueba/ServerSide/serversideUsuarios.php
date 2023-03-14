<?php
require 'serverside.php';
$anio = 'anio';
$fecha = 'fecha_creacion_dos';

$table_data->get('vista_expedientes','id',array('id',$anio, 'nombre_tramite', 'tipo', 'documentoCuit', 'afiliado', 'proveedor', $fecha,$anio));

?>
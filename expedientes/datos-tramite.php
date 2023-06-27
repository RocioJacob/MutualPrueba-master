<?php 
include('../util/funcionesexp.php');
include('../admin/conexion.php');

$entrada = $_POST["usuario"];
$salida = datosTramite($entrada);
echo $salida;
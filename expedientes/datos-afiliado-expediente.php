<?php 
session_start();

include('../util/funcionesexp.php');
include('../admin/conexion.php');

$entrada = $_POST["usuario"];
$salida = datosAfiliadoExpediente($entrada);
echo $salida;
<?php
$host = "localhost";
$user = "root";
$password = "MPPNsystemw2022*";
/*$password = "MPPNsystemw2022*";*/
$database = "mutualweb";

//La función devuelve una conexión almacenada en la variable $conexion, o FALSE en caso de error
$conexion = mysqli_connect($host, $user, $password, $database);
mysqli_set_charset($conexion, 'utf8');
return $conexion;


/*if(!$conexion){
	echo "NO CONECTADO A LA BASE DE DATOS ".$database;
}
else{
	echo "CONECTADO A LA BASE DE DATOS ".$database;
}*/

?>
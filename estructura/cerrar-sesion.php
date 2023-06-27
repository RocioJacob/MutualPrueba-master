<?php  
session_start(); 
if(session_destroy()){
	echo json_encode(array('mensaje' => 'Sesión finalizada', 'salida' => '0'));
}
else{
	echo json_encode(array('mensaje' => 'Error al cerrar sesión', 'salida' => '1'));
}
?>
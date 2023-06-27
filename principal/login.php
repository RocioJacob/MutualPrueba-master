<?php
session_start(); 
include("../admin/conexion.php");
//include('util/funcionesx.php');

if(isset($_POST["usuario"])){
	$usuarioIngresado = mb_strtoupper($_POST['usuario']);

	if(isset($_POST["clave"])){
		$passwordIngresado = $_POST['clave'];

		$query = mysqli_query($conexion, "SELECT * FROM usuariosx WHERE usuario='$usuarioIngresado'"); 
		$cantidad = mysqli_num_rows($query);

		if($cantidad>0){//Existe 
			$data = mysqli_fetch_array($query);
			$documento = $data['documento'];
			$clave = $data['clave'];
			$email = $data['email'];
			$usuario = $data['usuario'];
			$tipoUsuario = $data['id_tipo'];
			$idUsuario = $data['id'];

			if(password_verify($passwordIngresado, $clave)) {
				$_SESSION['active'] = true; //activo la sesion
				$_SESSION['documento'] = $documento; 
				$_SESSION['usuario'] = $usuario;
				$_SESSION['id_usuario'] = $idUsuario; 
				$_SESSION['afiliado'] = "";
				$_SESSION['tipo_usuario'] = $tipoUsuario;
				$_SESSION['id_expediente'] = ""; //Para manejar expedientes
				//iniciarSesion($_SESSION['documento']); //Agregado

				echo json_encode(array('mensaje' => '<p><img src="../util/imagenes/logoMutual.jpg"><br><span style="color:#0072BC; font-size: 35px; font-family: Georgia, cursive;">Bienvenido al<br>Sistema Mutual Policial</span><br><span style="color:black; font-size: 22px; font-family: Georgia, cursive;">Ya puede comenzar a utilizarlo</span></p>', 'salida' => '0'));
			}
			else{
				echo json_encode(array('mensaje' => '<p><span style="color:#0072BC; font-size: 32px; font-family: Georgia, cursive;">DATOS INCORRECTOS</span><br><span style="color:black; font-size: 22px; font-family: Georgia, cursive;">Verifique los datos ingresados</span></p>', 'salida' => '1'));
			}
		}
		else{
			echo json_encode(array('mensaje' => '<p><span style="color:#0072BC; font-size: 32px; font-family: Georgia, cursive;">DATOS INCORRECTOS</span><br><span style="color:black; font-size: 22px; font-family: Georgia, cursive;">Verifique los datos ingresados</span></p>', 'salida' => '1')); 
		}
	}
	else{
		echo json_encode(array('mensaje' => '<p><span style="color:#0072BC; font-size: 32px; font-family: Georgia, cursive;">DATOS INCOMPLETOS</span><br><span style="color:black; font-size: 22px; font-family: Georgia, cursive;">Falta ingresar clave</span></p>', 'salida' => '1'));
	}
}
else{
	echo json_encode(array('mensaje' => '<p><span style="color:#0072BC; font-size: 32px; font-family: Georgia, cursive;">DATOS INCOMPLETOS</span><br><span style="color:black; font-size: 22px; font-family: Georgia, cursive;">Falta ingresar usuario</span></p>', 'salida' => '1'));
}


?>

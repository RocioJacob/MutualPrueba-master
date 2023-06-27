
<?php  
include("../estructura/navegacion.php");

?>

<!DOCTYPE html>
<html lang="es">




<header><title>Detalles</title></header>
<body>
 <br>
<hr>
  <h3 class="titulo">Información del usuario</h3>  
<hr>
  <br>
<div class="cajaDeFoto"><img class="fotoPerfil" src="./fotos/$documento.png" alt="Foto de perfil del empleado" width="25%" height="25%"> <br></div>

<div class="cajaDeTextoDer">
<b> Nombre: <?php echo $usuario1['nombre'];/*$usuario1.nombre */?></b><br>  <!--preguntar como se llama desde la base -->
<b> Apellido: <?php echo $usuario1['apellido']; ?></b> <br> <!--usuariox apellido -->

<b> Sector: <?php switch ($idAreaUsuario) {
  case "1":
    echo "Afiliaciones";
    break;
  case "2":
    echo "Asistenciales";
    break;
  case "3":
     echo "Contable";
    break;
  case "4":
     echo "Facturacion";
    break;
  case "5":
     echo "Junta fiscalizadora";
    break;
  case "6":
     echo "Mesa de entrada";
    break;
  case "7":
     echo "Personal";
    break;
  case "8":
     echo "Presidencia";
    break;
  case "9":
     echo "Sistemas";
    break;
  case "10":
     echo "Supervision";
    break;
  case "11":
     echo "Tesoreria";
    break;
  case "12":
     echo "Tesorero";
    break;
  case "13":
     echo "Turismo";
    break;
  case "14":
     echo "Vicepresidencia";
    break;
  case "15":
     echo "Vicepresidencia";
    break;
  case "16":
     echo "Delegación Alumine";
    break;
  case "17":
     echo "Delegación Centenario";
    break;
  case "18":
     echo "Delegación Chos Malal";
    break;
  case "19":
     echo "Delegación Cutral Co";
    break;
  case "20":
     echo "Delegación El Cholar";
    break;
  case "21":
     echo "Delegación Junin De Los Andes";
    break;
  case "22":
     echo "Delegación Las Grutas";
    break;
  case "23":
     echo "Delegación Las Lajas";
    break;
  case "24":
     echo "Delegación Loncopue";
    break;
  case "25":
     echo "Delegación Picun Leufu";
    break;
  case "26":
     echo "Delegación Plottier";
    break;
  case "27":
     echo "Delegación San Martín de los Andes";
    break;
  case "28":
     echo "Delegación SUM Cutral Co";
    break;
  case "29":
     echo "Delegación Villa La Angostura";
    break;
  case "30":
     echo "Delegación Zapala";
    break;
  case "31":
     echo "Educación";
    break;
  case "32":
     echo "Directivos";
    break;
  default:
    echo "Desconocido";
    break;
}                  


?>
  

</b>   <br>       <!--usuariox id_area -->

<b> DNI: <?php echo $documento ?></b>   <br>        <!--usuariox documento -->
<b> e-mail: <?php echo $usuario1['email']; ?> </b>  <br>     <!--usuariox email -->
<b> Alta: <?php echo $usuario1['fecha_alta']; ?></b>  <br>     <!--usuariox fecha-alta-->

 <!--Condiciona que se muestre en pantalla solo si el usuario es de sistemas-->
<?php if($idAreaUsuario==="9"){ ?>  <br>   <!--arreglar condicional-->
   <b> Usuario: <?php echo $usuario; ?></b>   <br>    <!--usuariox usuario -->
   <b> Clave: <?php echo $usuario1['clave']; ?> </b>  <br>  <!--usuariox clave -->
<br>
<div id="cajaDeBotonesIzq"><a href="permisos-empleados.php" class="btn" id="botonVolver">Permisos</a></div>

</div>
</body>
  <?php } ?>
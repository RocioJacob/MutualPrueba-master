<?php 
include('../estructura/navegacion.php');
$documento = mysqli_real_escape_string($conexion,(strip_tags($_GET["id"],ENT_QUOTES)));
$salida = 1;

if(estaConectado()){
	if(existeAfiliadoTitular($documento)){
		$salida = 0;
	}
}
else{		
?>
  <div class="container-fluid">
  	<span style="color: red;">Sin conexión a la VPN</span>
  </div>
<?php    
}
?>

<!DOCTYPE html>
<html lang="es">

<body>
  <div class="container-fluid">
  	<hr>
  	<h1 class="titulo">DETALLES DEL AFILIADO</h1>
  	<hr>
  	<?php
  	if($salida == 0){
  		$resultArray = titularCargas($documento);
		$arrayTitular = datosTitular($resultArray);
    	$arrayCarga = datosCargas($resultArray);
    	$longitud = count($arrayCarga);
    ?>	

    	<h5 class="subtitulo">TITULAR</h5>
    	<div class="container">
	        
	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">CODIGO: </span>
	          	<span style="color: black;"><?php echo $arrayTitular['codigo'].'<br>';?></span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">APELLIDO: </span>
	          	<span style="color: black;"><?php echo $arrayTitular['apellido'].'<br>';?> </span>
	          </div>
	        </div>

	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">NOMBRE: </span>
	          	<span> <?php echo $arrayTitular['nombre'].'<br>';?> </span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">DOCUMENTO: </span>
	          	<span> <?php echo $arrayTitular['documento'].'<br>';?> </span>
	          </div>
	        </div>

	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">LEGAJO: </span>
	          	<span> <?php echo $arrayTitular['legajo'].'<br>';?> </span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">NACIMIENTO: </span>
	          	<span> <?php echo $arrayTitular['nacimiento'].'<br>';?> </span>
	          </div>
	        </div>

	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">CUIL: </span>
	          	<span> <?php echo $arrayTitular['cuil'].'<br>';?> </span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">DIRECCION: </span>
	          	<span> <?php echo $arrayTitular['direccion'].'<br>';?> </span>
	          </div>
	        </div>

	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">LOCALIDAD: </span>
	          	<span> <?php echo $arrayTitular['localidad'].'<br>';?> </span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">PROVINCIA: </span>
	          	<span> <?php echo $arrayTitular['provincia'].'<br>';?> </span>
	          </div>
	        </div>

        	<div class="row">
		        <div class="col">
		          	<span class="tituloDetalles">TELEFONO: </span>
		          	<span> <?php echo $arrayTitular['telefono'].'<br>';?> </span>
		         </div>
	          
	          	<div class="col">
	          		<span class="tituloDetalles">SSM: </span>
	          		<span>
          			<?php 
	          		if($arrayTitular['ssm']=='1'){
	                	echo "SI".'<br>';
	              	}
	              	else{
	                	echo "NO".'<br>';
	              	}
         			?>
          			</span>
          		</div>
        	</div>

        	<div class="row">
          		<div class="col">
          			<span class="tituloDetalles">FECHA DE ALTA: </span>
          			<span> <?php echo $arrayTitular['alta'].'<br>';?> </span>
          		</div>

          		<div class="col">
          			<span class="tituloDetalles">HABILITADO: </span>
          			<span>
          			<?php 
          			if($arrayTitular['habilitado']=='1'){
            			echo "SI".'<br>';
          			}
          			else{
            			echo "NO".'<br>';
          			}
          			?>
          			</span>
          		</div>
        	</div>

	        
	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">EMAIL REGISTRO: </span>
	          	<span> <?php echo $arrayTitular['email2'].'<br>';?> </span>
	          </div>

	          <div class="col">
	          	<span class="tituloDetalles">CELULAR REGISTRO: </span>
	          	<span><?php echo $arrayTitular['celular'].'<br>';?> </span>
	          </div>
	        </div>

	        <?php 
	        if($arrayTitular['baja']!=""){ 
	        ?>
		        <div class="row">
		          <div class="col">
		          	<span class="tituloDetalles">FECHA BAJA: </span>
		          	<span> <?php echo $arrayTitular['baja'].'<br>';?> </span>
		          </div>
		        </div>
	        <?php 
	    	} 
	    	?>

	        <div class="row">
	          <div class="col">
	          	<span class="tituloDetalles">CBU: </span>
	          	<span> <?php echo $arrayTitular['cbu'].'<br>';?> </span>
	          </div>
	        </div>

	        <div class="col"></div>
	        <div class="col"></div>
    	</div> <!-- FIN DE <div class="container"> -->
    	<br><br>
  	

  	<div class="center">
      <?php 
      $salida = tienecargas($arrayTitular['documento']);
      if($salida){ ?>
        <h5 class="subtitulo">CARGAS </h5>
        <?php 
        echo $arrayTitular['apellido']." ".$arrayTitular['nombre'];
        ?>
        <br>
        
        <div class="d-none d-sm-none d-md-block"> 
            <table class="table table-bordered">
            <tr>
              <td id="fila">Codigo</td>
              <td id="fila">Carga</td>
              <td id="fila">Parentesco</td>
              <td id="fila">Apellido</td>
              <td id="fila">Nombre</td>
              <td id="fila">Documento</td>
              <td id="fila">Nacimiento</td>
              <td id="fila">Fecha alta</td>
              <td id="fila">Fecha baja</td>
            <?php 
              $i=0;
              while ( $i < $longitud) {
            ?>
                <tr>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['codigo']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['carga']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['parentesco']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['apellido']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['nombre']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['documento']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['nacimiento']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['alta']; ?> </td>
                  <td id="detalle"> <?php echo $arrayCarga[$i]['baja']; ?> </td>
                </tr>
              <?php
                $i=$i+1;
             }
           ?>
            </tr>
          </table>
        </div><br>
      <?php
      }
      ?>

  </div>
    <?php
  	}
  	?>

</body>
</html>



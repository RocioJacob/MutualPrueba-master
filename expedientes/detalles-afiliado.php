<?php 
include('../estructura/navegacion.php');
?>
<body>
<div class="container" id="mycontainer">
  <span class="subtituloMenu">DETALLES AFILIADO</span><br><br>
  <?php 
  $id = mysqli_real_escape_string($conexion,(strip_tags($_GET["id"],ENT_QUOTES)));
  
  //Obtengo el documento de su titular
  if(esCarga($id)){ 
    $id = documentoTitular($id);
  }

  $_SESSION['afiliado'] = $id;
  $resultArray = titularCargas($id);
  if(empty($resultArray)){
    echo "PROBLEMAS DE CONEXIÓN CON EL SERVIDOR";
  }
  else{
    $arrayTitular = datosTitular($resultArray);
    $arrayCarga = datosCargas($resultArray);
    $longitud = count($arrayCarga);
  }
  ?>

  <span class="subtituloMenu">TITULAR</span>
  
  <div class="container">
    
    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">CODIGO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['codigo'].'<br>';?></span>
      </div>

      <div class="col">
        <span class="subtituloDetalle1">APELLIDO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['apellido'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">NOMBRE: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['nombre'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">DOCUMENTO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['documento'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">LEGAJO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['legajo'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">NACIMIENTO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['nacimiento'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">CUIL: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['cuil'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">DIRECCION: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['direccion'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">LOCALIDAD: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['localidad'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">PROVINCIA: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['provincia'].'<br>';?></span>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">TELEFONO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['telefono'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">SSM: </span>
        <span class="subtituloDetalle2">
          <?php if($arrayTitular['ssm']=='1'){
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
        <span class="subtituloDetalle1">FECHA DE ALTA: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['alta'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">HABILITADO: </span>
        <span class="subtituloDetalle2">
          <?php if($arrayTitular['habilitado']=='1'){
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
        <span class="subtituloDetalle1">EMAIL REGISTRO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['email2'].'<br>';?></span>
      </div>
      <div class="col">
        <span class="subtituloDetalle1">CELULAR REGISTRO: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['celular'].'<br>';?></span>
      </div>
    </div>

    <?php if($arrayTitular['baja']!=""){ ?>
    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">FECHA BAJA: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['baja'].'<br>';?></span>
      </div>
    </div>
    <?php } ?>

    <div class="row">
      <div class="col">
        <span class="subtituloDetalle1">CBU: </span>
        <span class="subtituloDetalle2"><?php echo $arrayTitular['cbu'].'<br>';?></span>
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
        <h4 style = "font-family: 'Arial';">CARGAS 
          <label style="color:#3A73A8; font-size:17px;"><?php echo $arrayTitular['apellido']." ".$arrayTitular['nombre']?></label>
        </h4>
        <div class="d-none d-sm-none d-md-block"> 
            <table class="table table-bordered">
            <tr>
              <td id="filaDetalle1">Codigo</td>
              <td id="filaDetalle1">Carga</td>
              <td id="filaDetalle1">Parentesco</td>
              <td id="filaDetalle1">Apellido</td>
              <td id="filaDetalle1">Nombre</td>
              <td id="filaDetalle1">Documento</td>
              <td id="filaDetalle1">Nacimiento</td>
              <td id="filaDetalle1">Fecha alta</td>
              <td id="filaDetalle1">Fecha baja</td>
            <?php 
              $i=0;
              while ( $i < $longitud) {
            ?>
                <tr>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['codigo']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['carga']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['parentesco']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['apellido']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['nombre']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['documento']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['nacimiento']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['alta']; ?> </td>
                  <td id="filaDetalle2"> <?php echo $arrayCarga[$i]['baja']; ?> </td>
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

      <a href="" class="btn" id="botonaccion" title="Cuenta corriente">Cuenta Corriente</a>
      <a href="" class="btn" id="botonaccion">Credencial</a>
      <a href="" class="btn" id="botonaccion">Facturas</a>
    </div>
  </div>
</body>
<br><br><br><br>



<style type="text/css">
#botonaccion{
  margin-right: 5px;
  color: white;
  border: 2px solid;
  border-radius: 10px;
  float: left;
  border-color:#3A73A8;
  margin: 5px;
  background-color:#3A73A8;
  font-family: 'Arial';
  width: 120px;
  font-size: 12px;
}

#botonaccion:hover{
  color:#3A73A8;
  background-color: white;
  -webkit-transform:scale(1);transform:scale(1); /*Acercamiento*/
}

#detalle{
  font-family: 'Verdana';
  color:black;
  font-size: 13px;
}

.subtituloDetalle1{
  font-size: 15px;
  color: #3A73A8;
}
.subtituloDetalle2{
  font-size: 12px;
}

#filaDetalle1{
  color: white;
  background-color: #3A73A8;
}

#filaDetalle2{
  font-size: 12px;
  font-family: Arial;
}
</style>



   
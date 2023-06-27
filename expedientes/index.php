<!DOCTYPE html>
<html lang="es">
<?php 
include('../estructura/navegacion.php');
?>
<body>
<div class="container" id="mycontainer"><br>
  <span class="subtituloMenu">EXPEDIENTES</span><br><br>

<!-- Para computadora -->
  <!--div class="d-none d-sm-none d-md-block"><br/-->

      <div class="container text-center h-100 d-flex justify-content-center align-items-center">
        
        <?php 
        if($generarExpediente == '0'){ ?>
          <button class="btn boton" id="boton1" type="submit">GENERAR EXPEDIENTE</button>
        <?php 
        } 
        ?>
        
        <button class="btn boton" id="boton3" type="submit">MIS BANDEJAS</button>

        <?php
        if($buscarExpedientes == "0"){
        ?>
          <button class="btn boton" id="boton5" type="submit">BUSQUEDA 1</button>
          <button class="btn boton" id="boton6" type="submit">BUSQUEDA 2</button>
        <?php
        }
        ?>
          
        <?php
        if($trabajarConArchivados == "0"){
        ?>
          <button class="btn boton" id="boton7" type="submit">ARCHIVADOS</button>
        <?php
        }
        ?>

      </div><br>

      <div class="container text-center h-100 d-flex justify-content-center align-items-center">
        <?php
        if($trabajarConExpedientes2020 == "0"){
        ?>
          <button class="btn boton" id="boton" type="submit">EXPEDIENTES<br>2020</button>
        <?php
        }
        ?>

        <?php
        if($trabajarConExpedientes2021 == "0"){
        ?>
          <button class="btn boton" id="boton" type="submit">EXPEDIENTES<br>2021</button>
        <?php
        }
        ?>

        <?php
        if($trabajarOcultos == "0"){
        ?>
          <button class="btn boton" id="boton" type="submit">OCULTOS</button>
        <?php
        }
        ?>

      </div>
</div>
</body>
</html>


<script type="text/javascript">
  $(document).ready(function() {
    $('#boton1').on('click', function() {
      window.location='generar-expediente.php';
      return false;
    });
    $('#boton2').on('click', function() {
      window.location='busqueda-expediente.php';
      return false;
    });
    $('#boton3').on('click', function() {
      window.location='bandejas-expedientes.php';
      return false;
    });
    $('#boton4').on('click', function() {
      window.location='busqueda-archivados.php';
      return false;
    });
    
    $('#boton5').on('click', function() {
      window.location='busqueda-expedientes1.php';
      return false;
    });

    $('#boton6').on('click', function() {
      window.location='busqueda-expedientes2.php';
      return false;
    });

    $('#boton7').on('click', function() {
      window.location='busqueda-archivados.php';
      return false;
    });

    /*$('#boton6').on('click', function() {
      window.location='mi-historial.php';
      return false;
    });*/ 

    /*$('#boton7').on('click', function() {
      window.location='listados.php';
      return false;
    });*/

    $('#boton8').on('click', function() {
      window.location='busqueda-archivados-dos.php';
      return false;
    });

    $('#boton9').on('click', function() {
      window.location='busqueda-mis-expedientes.php';
      return false;
    });

    
    /*$('#botonBucarExpediente3').on('click', function() {
      window.location='busqueda-expedientes5.php';
      return false;
    });*/

  });
</script>

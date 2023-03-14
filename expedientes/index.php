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

<?php 
    if(($usuario=='OALMENDRA')or($usuario=='JMONTESINO')){ ?>
      <div class="container text-center h-100 d-flex justify-content-center align-items-center">
        <?php 
        if($usuario=='OALMENDRA'){
        ?>
          <button class="btn boton" id="boton1" type="submit">GENERAR EXPEDIENTE</button>
        <?php 
        } 
        ?>
        <button class="btn boton" id="boton2" type="submit">BUSCAR<br> EXPEDIENTE</button>
        <button class="btn boton" id="boton3" type="submit">BANDEJAS</button>
        <button class="btn boton" id="boton6" type="submit">MI HISTORIAL</button>
      </div>
<?php 
    }else{ 
  ?>
      <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <?php 
        if($generarExpediente == '0'){ ?>
          <button class="btn boton" id="boton1" type="submit">GENERAR EXPEDIENTE</button>
        <?php 
        }
        ?>
            
        <?php 
        if($buscarExpedientes=="0"){
        ?>
            <button class="btn boton" id="boton5" type="submit">BUSCAR<br> EXPEDIENTE1</button>
            <button class="btn boton" id="botonBucarExpediente2" type="submit">BUSCAR<br> EXPEDIENTE2</button>
            <!--button class="btn boton" id="botonBucarExpediente3" type="submit">BUSCAR<br> EXPEDIENTE3</button-->
    <?php 
        } 
      ?>
        <button class="btn boton" id="boton3" type="submit">BANDEJAS</button>
        <?php 
        //if(($idAreaUsuario=='1')or($idAreaUsuario=='3')or($idAreaUsuario=='9')or($idAreaUsuario=='11')){ 
        ?>
            <!--button class="btn boton" id="boton4" type="submit">ARCHIVADOS</button-->
    <?php 
        //}
      ?>
      </div>


      <!--div class="container text-center h-100 d-flex justify-content-center align-items-center">
        <button class="btn boton" id="boton6" type="submit">MI HISTORIAL</button>
        <button class="btn boton" id="boton7" type="submit">LISTADOS</button>
        <?php 
          //if(($trabajarAnuales=="0")&&($buscarAnuales=="0")){
        ?> 
      <?php 
          } 
        ?>
        <button class="btn boton" id="boton8" type="submit">ARCHIVADOS2</button>
        <button class="btn boton" id="boton9" type="submit">MIS EXPEDIENTES2</button>
      </div-->
 <?php //} ?>
  <!--/div--><!--Para computadora-->

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
      window.location='busqueda-expedientes.php';
      return false;
    });
     $('#boton6').on('click', function() {
      window.location='mi-historial.php';
      return false;
    });

    $('#boton7').on('click', function() {
      window.location='listados.php';
      return false;
    });

    $('#boton8').on('click', function() {
      window.location='busqueda-archivados-dos.php';
      return false;
    });

    $('#boton9').on('click', function() {
      window.location='busqueda-mis-expedientes.php';
      return false;
    });

    $('#botonBucarExpediente2').on('click', function() {
      window.location='busqueda-expedientes2.php';
      return false;
    });
    
    /*$('#botonBucarExpediente3').on('click', function() {
      window.location='busqueda-expedientes5.php';
      return false;
    });*/

  });
</script>

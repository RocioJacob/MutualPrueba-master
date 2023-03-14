<!DOCTYPE html>
<html lang="es">
<?php 
include_once('../estructura/navegacion.php');
?>

<body>
  <div id="usuarioGeneral"><?php //echo $usuario?></div>
  
  <!--div class="container"-->
  <div class="container" id="mycontainer"><br>
    <!--h2 style = "font-family: 'Georgia', cursive;">MENÚ</h2-->
    <span class="subtituloMenu">ACCESOS DIRECTOS</span><br><br>

    <div class="container text-center h-100 d-flex justify-content-center align-items-center">
      <button class="btn boton" id="boton1" type="submit">GENERAR EXPEDIENTE</button>
      <button class="btn boton" id="boton2" type="submit">MI BANDEJA</button>
    </div>  

    <!-- Para computadora -->
      <!--div class="d-none d-sm-none d-md-block"--> 
  
        <?php 
          //if($tipoUsuario == '1'){
        ?>
            <!--div class="container text-center h-100 d-flex justify-content-center align-items-center">

              <a href="">
                <img id="img-menu" src="../util/imagenes/busqueda-afiliado3.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
              
              <a href="">
                <img id="img-menu" src="../util/imagenes/afiliados-registrados3.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="../expedientes">
                <img id="img-menu" src="../util/imagenes/expedientes1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="">
                <img id="img-menu" src="../util/imagenes/listados.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="">
                <img id="img-menu" src="../util/imagenes/tutoriales1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
            </div>


            <div class="container text-center h-100 d-flex justify-content-center align-items-center">
              
              <a href="">
                <img id="img-menu" src="../util/imagenes/administracion.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="" target="_blank">
                <img id="img-menu" src="../util/imagenes/busqueda-general.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="">
                <img id="img-menu" src="../util/imagenes/mi-perfil.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="" target="_blank">
                <img id="img-menu" src="../util/imagenes/emailInstitucional.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="">
                <img id="img-menu" src="../util/imagenes/proveedor1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
            </div>

            <div class="container text-center h-100 d-flex justify-content-center align-items-center">
              <a href="">
                <img id="img-menu" src="../util/imagenes/turismo1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
            </div> <br-->
        <?php
          //}
        ?>

          <?php 
          //if(($tipoUsuario == '4') or($tipoUsuario == '5') or($tipoUsuario == '3')){
        ?>
            <!--div class="container text-center h-100 d-flex justify-content-center align-items-center">
              <a href="">
                <img id="img-menu" src="../util/imagenes/busqueda-afiliado3.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
              
              <a href="">
                <img id="img-menu" src="../util/imagenes/afiliados-registrados3.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="../expedientes/">
                <img id="img-menu" src="../util/imagenes/expedientes1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="" target="_blank">
                <img id="img-menu" src="../util/imagenes/busqueda-general.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>
            </div>


            <div class="container text-center h-100 d-flex justify-content-center align-items-center">
              <a href="">
                <img id="img-menu" src="../util/imagenes/mi-perfil.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a>

              <a href="">
                <img id="img-menu" src="../util/imagenes/emailInstitucional.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a-->

            <?php //if($verProveedores=="0"){ ?>
              <!--a href="">
                <img id="img-menu" src="../util/imagenes/proveedor1.gif" width="180" height="180" HSPACE="10" VSPACE="10">
              </a-->
            <?php //} ?>

            <!--/div-->
        <?php
          //}
        ?>
      <!--/div--><!-- fin <div class="d-none d-sm-none d-md-block"> -->
  <!--/div>
</body>
</html-->


<script type="text/javascript">
  $(document).ready(function() {
    
    $('#botonEmail').click(function(evt){
      evt.preventDefault();
      window.open("https://mppneuquen.com.ar/roundcube/","_blank");
      return false;    
    });

    $('#boton1').on('click', function() {
      window.location='../expedientes/generar-expediente.php';
      return false;
    });

    $('#boton2').on('click', function() {
      window.location='../expedientes/bandejas-expedientes.php';
      return false;
    });

  });

</script>
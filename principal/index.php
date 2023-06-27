<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <title>Sistema Mutual Policial</title>
  <link rel="icon" type="image/png" href="../util/imagenes/logo-mppn.png"/>
  <link rel="stylesheet" type="text/css" href="../util/css/bootstrap.min.css">
    <script type="text/javascript" src="../util/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="../util/js/popper.min.js"></script>
    <script type="text/javascript" src="../util/js/bootstrap.min.js"></script>
    <script src="../util/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../util/css/sweetalert2.min.css">
    <script src="../util/js/sweetalert2@10.js"></script>
    <!--link rel="stylesheet" href="util/css/expedientes.css" type="text/css"-->
    <link rel="stylesheet" href="../util/css/estiloIS.css" type="text/css">
</head>
<body>

<div class="container">
  <div class="container" align="center"><br><br>
    <img id="imagen" src="../util/imagenes/logo-mppn.png">
  </div>

    <p class="titulo" id="tituloPrincipal">SISTEMA MUTUAL POLICIAL</p>
    <form method="POST" id="formulario">
      
      <div class="input-group mx-auto">
        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Nombre de usuario"  autocomplete="off" maxlength="20">
      </div>

      <div class="input-group mx-auto">
          <input id="clave" name="clave" class="form-control .text-primary" type="password" placeholder="Contraseña" autocomplete="off" maxlength="30">
      </div><br>

      <div class="text-center"> 
        <button type="submit" id="botonIngresar">INGRESAR</button>
      </div>

    </form>

</div>
</body>
</html>

<script type="text/javascript">

  function mensajeError($mensaje){
    swal.fire({
      title: $mensaje, 
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
    });
  }

  function mensajeExito($mensaje){
    Swal.fire({
      //icon: 'success',
      title: $mensaje, 
      width:'650px',
      allowOutsideClick: false,
    }).then(function(){
        window.location.replace("menu-principal.php");
    });
    
  }

  $('#botonIngresar').click(function(evento){
    evento.preventDefault();
    if(validarFormulario()){
      var datos=$('#formulario').serialize();
      $.ajax({
          url: 'login.php',
          type:'POST',
          data: datos,
          success: function(data){
            //console.log(data);
            var jsonData = JSON.parse(data);
            if(jsonData.salida == 1){ //Datos incorrectos
              return mensajeError(jsonData.mensaje);
            }
            else{
              //sessionStorage.setItem("sesion", "0");
              //var sesion = sessionStorage.getItem("sesion");
              //console.log("ingreso: "+sesion);
              return mensajeExito(jsonData.mensaje);
              //window.location.replace("menu.php");
            }
          }
      });
      return false;
    }
  });

  function validarFormulario(){
    if($("#documento").val() == ""){
      return mensajeError("Datos incompletos");
      $("#documento").focus();
      return false;
    }
    if($("#clave").val() == ""){
      return mensajeError("Datos incompletos");
      $("#clave").focus();
      return false;
    }
    return true;
  }
</script>



jQuery(document).ready(function() {

$('#botonGenerar').click(function(evento){
  evento.preventDefault();
  //var sesion = sessionStorage.getItem("sesion");
  //if (typeof valor !== 'undefined'){
  //console.log("expediente: "+sesion);

    if(validarFormulario()){
      var datos = new FormData($('#formulario')[0]);
      Swal.fire({
          title: '¿Desea generar el expediente?',
          width:'600px',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#148F77',
          confirmButtonText: 'Aceptar',
          cancelButtonColor: 'red',
          allowOutsideClick: false,
      }).then((result) => {
          
        if (result.isConfirmed) {
          $.ajax({
            url: '../expedientes/cargar-expediente.php',
            type:'POST',
            data: datos,
            processData: false,
            contentType: false,
            beforeSend: function() {
              //$("#loader").css('display','block');
            },
            success: function(data){
              //console.log(data);
              //$("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje);
              }
              else{
                return mensajeError(jsonData.mensaje);
              }
           }
          });
            return false;
        }
      });
    }
  //}
  //else{
   // window.location.replace("../index.php");
  //}
});
});


function validarFormulario(){
  
  if($("#tipo").val() == ""){
    //console.log("Ingreso aca tipo1");
    return mensajeError("Debe seleccionar un TIPO");
    $("#tipo").focus();
    return false;
  }
  else{ //Se completo tipo
    if($("#tipo").val() == "AFILIADO"){
      if($("#documento").val() == ""){
        return mensajeError("Debe ingresar DOCUMENTO");
        $("#documento").focus();
        return false;
      }
    }

    if($("#tipo").val() == "PROVEEDOR"){
      if($("#cuit").val() == ""){
        return mensajeError("Debe ingresar CUIT");
        $("#cuit").focus();
        return false;
      }
    }

    if($("#tipo").val() == "DELEGACION"){
      if($("#delegacion1").val() == ""){
        return mensajeError("Debe seleccionar una DELEGACION");
        $("#delegacion1").focus();
        return false;
      }
    }
  }

  if($("#tramite").val() == ""){
    return mensajeError("Debe seleccionar un TRAMITE");
    $("#tramite").focus();
    return false;
  }
  else{
    if(($("#tramite").val()=="7") || ($("#tramite").val() == "8") || ($("#tramite").val() == "9") || ($("#tramite").val() == "12")|| ($("#tramite").val() == "26") || ($("#tramite").val() == "27") || ($("#tramite").val() == "28") || ($("#tramite").val() == "29") || ($("#tramite").val() == "30")|| ($("#tramite").val() == "31") || ($("#tramite").val() == "38") || ($("#tramite").val() == "48") || ($("#tramite").val() == "49")|| ($("#tramite").val() == "103")){

        if($("#autorizacion1").val() == ""){
          return mensajeError("Debe seleccionar AUTORIZACION");
          $("#autorizacion1").focus();
          return false;
        }
    }
  }

  if($("#area").val() == ""){
    return mensajeError("Debe seleccionar un AREA");
    $("#area").focus();
    return false;
  }

  if($("#prioridad").val() == ""){
    return mensajeError("Debe seleccionar una PRIORIDAD");
    $("#prioridad").focus();
    return false;
  }

  if($('input[type="file"]').val()!=null){
    if($('input[type="file"]').val() != ''){ 
      var archivos = document.getElementById('file');
      var cantidad = archivos.files.length;

      if(cantidad > 10){
        return mensajeError("SUPERO LA CANTIDAD MAXIMA DE 10 ARCHIVOS");
        return false;
      }
      else{
        var fsize = 0;
        for (var i = 0; i <= cantidad - 1; i++) {
          fsize = fsize + archivos.files.item(i).size;   
        }
        if(fsize > 8000000){ //8MB de tamaño maximo - PHP permite hasta 8MB
          return mensajeError("SUPERO EL TAMAÑO MAXIMO DE 8MB");
          return false;
        }
        else{
          var i=0;
          var bandera=true;
          while((i<cantidad) && (bandera)){
            if((archivos.files.item(i).type!="application/pdf")&&(archivos.files.item(i).type!="image/jpeg")&&(archivos.files.item(i).type!="image/png")){
                bandera=false;
            }
            i=i+1;
          }
          if(!bandera){
            return mensajeError("SOLO SE PERMITEN ARCHIVOS PDF, PNG, JPG Y JPEG");
            return false;
          }
        }
      }
    }
  }
  return true;
}


$(document).on('change', '#tipo', function() {
  var $mensajeUno = $(
    '<div><label style="color:#003366"> Documento (sin puntos)</label><input type="text" id="documento" name="documento" class="form-control" style=" border-color: #2874A6; border-radius: 5px;" autocomplete="off" maxlength="8"></div>');
  var $mensajeDos = $(
    '<div><label style="color:#003366">* Cuit (sin puntos ni guiones)</label><input type="text" id="cuit" name="cuit" class="form-control" style=" border-color: #2874A6; border-radius: 5px;" autocomplete="off" maxlength="11"></div>');
  var $mensajeTres = $(
    '<div><label style="color:#003366"> Código Tango (sin puntos)</label><input type="text" id="codigo" name="codigo" class="form-control" style=" border-color: #2874A6; border-radius: 5px;" autocomplete="off" maxlength="6"></div>');
  var $mensajeCuatro = $(
    '<div><label style="color:#003366">Monto (solo punto decimal)</label><input type="number" id="monto" name="monto" class="form-control" style=" border-color: #2874A6; border-radius: 5px;" autocomplete="off" maxlength="8"></div>');
  

  var seleccion = $(this).val();
  $('#mensaje').html('');
  switch (seleccion) {
    case 'AFILIADO':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'DELEGACION':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'EMPLEADO':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
    case 'PROVEEDOR':
      $('#datos').html('');
      $('#mensaje').append($mensajeDos);
      break;
    case 'OTROS':
      $('#datos').html('');
      $('#mensaje').append($mensajeUno);
      break;
  }
});


$(document).on('change', '#tramite', function() {
  var seleccion = $(this).val();
  if (seleccion!=""){
    obtener_datostramite(seleccion);
  }

  //Obtengo data-autorizado del formulario-Si necesita o no autorizacion el tramite seleccionado
  var autorizado = $(this).find(':selected').data('autorizado');
  switch (autorizado) {
    case 'SI':
      var a = document.getElementById('autorizado1');
      var b = document.getElementById('autorizado2'); //NO REQUIERE
      
      if(b.style.display === 'block'){
        b.style.display = 'none';
      }
      if(a.style.display === 'none'){
        a.style.display = 'block';
      }
      break;

    case 'NO':
       var a = document.getElementById('autorizado2');
       var b = document.getElementById('autorizado1');

      if(b.style.display === 'block'){
        b.style.display = 'none';
      }
      if(a.style.display === 'none'){
          a.style.display = 'block';
      }
      break;

    //Para eliminar el campo autorizado1, autorizado2 cuando no se elije nada
    default:
       var a = document.getElementById('autorizado2');
       var b = document.getElementById('autorizado1');
       if(a.style.display === 'block'){
         a.style.display = 'none';
       }
       if(b.style.display === 'block'){
         b.style.display = 'none';
       }
      break;
  }
});

$(document).on('change', '#tipo', function() {
  var seleccion = $(this).val();
  var a = document.getElementById('delega1');
  switch (seleccion) {
    case 'DELEGACION':
      if(a.style.display === 'none'){
        a.style.display = 'block';
      }
      break;
    default:
       if(a.style.display === 'block'){
        a.style.display = 'none';
      }
      break;
  }
});

function mensajeError($mensaje){
  swal.fire({
    title: $mensaje, 
    icon: 'error',
    width:'600px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExito($mensaje){
  Swal.fire({
    icon: 'success',
    width:'600px',
    title: $mensaje, 
    allowOutsideClick: false,
    }).then(function(){
      $("#formulario")[0].reset();
      document.getElementById("datos").value = "";
      window.open('../expedientes/expediente.php');
      //window.location.replace("index.php");
  });
}

//Cuando completo campo documento se ejecuta
$(document).on('change', '#documento', function() {
  var documento = $(this).val();
  if (documento!=""){
    obtener_datos(documento);
  }
});

//Cuando completo campo cuit se ejecuta
$(document).on('change', '#cuit', function() {
  var cuit = $(this).val();
  if (cuit!=""){
    obtener_datosprov(cuit);
  }
});

function obtener_datos(usuario){
  $.ajax({
    url : '../expedientes/datos-usuarioexp.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#datos").html(resultado);
  });
}

function obtener_datosprov(usuario){
  $.ajax({
    url : '../expedientes/datos-proveedor.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#datos").html(resultado);
  });
}


//Obtengo el codigo del tramite
function obtener_datostramite(usuario){
  $.ajax({
    url : '../expedientes/datos-tramite.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#extracto").html(resultado);
  });
}
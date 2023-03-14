
/*Al seleccionar el tipo de expediente, verifica que mostrar**/
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



/* Al seleccionar expediente tipo DELEGACION debe mostrar select con todas las delegaciones */

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





/*Al seleccionar el tipo de TRAMITE **/
$(document).on('change', '#tramite', function() {

  var seleccion = $(this).val();
  //console.log(seleccion);
  if (seleccion!=""){
    obtener_datostramite(seleccion);
  }

  //Obtengo data-autorizado del formulario-Si necesita o no autorizacion el tramite seleccionado
  var autorizado = $(this).find(':selected').data('autorizado');
  console.log(autorizado);
  switch (autorizado) {
    case 'SI':
      var a = document.getElementById('autorizado1');
      var b = document.getElementById('autorizado2'); //NO REQUIERE
      //console.log(a);
      //console.log(b);

      //if(b!=null){
        if(b.style.display === 'block'){
          b.style.display = 'none';
        }
     // }

     // if(a!=null){  
        if(a.style.display === 'none'){
          a.style.display = 'block';
        }
      //}

      break;
      

    case 'NO':
       var b = document.getElementById('autorizado1');
       //var a = document.getElementById('autorizado2'); /*NO REQUIERE*/
       //console.log(b);

        //if(b!=null){
          if(b.style.display === 'block'){ /* si se ve*/
            b.style.display = 'none';
          }
        //}

          var a = document.getElementById('autorizado2'); /*NO REQUIERE*/
          //console.log(a);
          if(a.style.display === 'none'){ /*no se ve*/
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


//Obtengo el codigo del tramite
function obtener_datostramite(usuario){
  //console.log(usuario);
  $.ajax({
    url : '../../expedientes/datos-tramite.php',
    type : 'POST',
    dataType : 'html',
    data : {usuario: usuario},
    })
  .done(function(resultado){
    $("#extracto").html(resultado);
  });
}
  
function recargar(){
  window.location.reload();
}

function verRecibidos(idExpediente){
  $.ajax({
    url : '../expedientes/ver-expediente.php',
    type : 'POST',
    dataType : 'html',
    data : {idExpediente: idExpediente},
    }).done(function(resultado){
        $("#recibidos2").html(resultado);
  });

  var z = document.getElementById('recibidos2');
  if (z.style.display === 'none') { //Si no se ve, que se vea
      z.style.display = 'block';
  } 
}

function tomarExpediente(idExpediente, anio, areaInicio, areaFin){
    var datos = {"idExpediente":idExpediente, "anio":anio, "areaInicio":areaInicio, "areaFin":areaFin};
    Swal.fire({
        title: '<span style="color:red;">¿Desea tomar el expedientee N°</span>'+idExpediente+'?',
        width:'550px',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#148F77',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
    }).then((result) => {
          
        if (result.isConfirmed) {
          $.ajax({
            url: '../expedientes/tomar-expediente.php',
            type:'POST',
            data: datos,
            beforeSend: function() {
              //$("#loader").css('display','block');
            },
            success: function(data){
              //console.log(data);
              //$("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje, jsonData.salida2);
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

function mensajeError($mensaje){
  swal.fire({
    title: $mensaje, 
    icon: 'error',
    width:'600px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

function mensajeExito($mensaje, $idExpediente){
  Swal.fire({
    icon: 'success',
    width:'650px',
    title: $mensaje, 
    allowOutsideClick: false,
    }).then(function(){
        window.location.replace("tomados.php");
  });
}

function actualizar(){
  location.reload(true);
}
//setInterval("actualizar()",8000);

function actualizar(){
  //location.reload(true);
   (() => {
            if (window.localStorage) {
                if (!localStorage.getItem('reload')) {
                    localStorage['reload'] = true;
                    window.location.reload();
                } else {
                    localStorage.removeItem('reload');
                }
            }
  })();
}
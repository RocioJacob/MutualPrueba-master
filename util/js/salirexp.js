
jQuery(document).ready(function() {

$('#salir').click(function(evento){
  evento.preventDefault();
  //var valor = $('#session').val();
  //if (typeof valor !== 'undefined'){
    //console.log("ingreso 1 "+valor);

      Swal.fire({
          title: '¿Desea cerrar su sesión?',
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
            url: 'cerrar-sesion.php',
            type:'POST',
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
                //localStorage.removeItem("sesion");
                //sessionStorage.setItem("sesion", "1");
                //console.log("salida: "+localStorage.getItem("sesion"));
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
  //}
  //else{
   // window.location.replace("../index.php");
  //}
});
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
      //window.open('../index.php');
      window.location.replace("index.php");
  });
}

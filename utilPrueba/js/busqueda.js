
$(document).ready(function(){
$('#botonBuscar').click(function(evento){
  evento.preventDefault();
    var busqueda=$('#busqueda').val();
      $.ajax({
        url: '../expedientes/buscar-mis-expedientes.php',
        type:'POST',
        data: {'busqueda': busqueda},
        success: function(data){
        }
      })
      .done(function(resultado){
        $("#tabla_resultado").html(resultado);
      })
      return false;
});
});


$(document).ready(function(){
$('#botonLimpiar').click(function(evento){
    evento.preventDefault();
    $("#formularioBusqueda")[0].reset();
    $("#tabla_resultado").html(""); 
  });
});

function recargar(){
window.location.reload();
}
  
function verTomados(idExpediente){
    var z = document.getElementById('tomados2'); //detalles
    var y = document.getElementById('tomados3'); //Enviar
    var w = document.getElementById('tomados4'); //Archivar

    if (y.style.display === 'block') { //Si se ve que No se vea
        y.style.display = 'none';
    }
    if (w.style.display === 'block') { //Si se ve que No se vea
        w.style.display = 'none';
    }
    if (z.style.display === 'none') { //Si no se ve que se vea
        z.style.display = 'block';
    } 

    $.ajax({
      url : '../expedientes/ver-expediente.php',
      type : 'POST',
      dataType : 'html',
      data : {idExpediente: idExpediente},
    }).done(function(resultado){
        $("#tomados2").html(resultado);
    });
}


function enviar(idExpediente){
    var z = document.getElementById('tomados2'); //Detalles
    var y = document.getElementById('tomados3');
    var w = document.getElementById('tomados4'); //Archivar
    
    if (z.style.display === 'block') { //Si se ve que No se vea
        z.style.display = 'none';
    } 

    if (w.style.display === 'block') { //Si se ve que No se vea
        w.style.display = 'none';
    }

    if (y.style.display === 'none') {//Si no se ve que se vea
        y.style.display = 'block';
    }

    $.ajax({
      url : '../expedientes/enviar-expediente.php',
      type : 'POST',
      dataType : 'html',
      data : {idExpediente: idExpediente},
    }).done(function(resultado){
        $("#tomados5").html(resultado); //Muestra el select para enviar
    });
}

  
function archivar(idExpediente){
    var z = document.getElementById('tomados2');
    var y = document.getElementById('tomados3');
    var w = document.getElementById('tomados4');
    
    if (z.style.display === 'block') { //Si se ve que No se vea
        z.style.display = 'none';
    } 

    if (y.style.display === 'block') {//Si no se ve que se vea
        y.style.display = 'none';
    }

    if (w.style.display === 'none') {
        w.style.display = 'block';
    }

    $.ajax({
      url : '../expedientes/archivar-expediente.php',
      type : 'POST',
      dataType : 'html',
      data : {idExpediente: idExpediente},
    }).done(function(resultado){
        $("#tomados4").html(resultado); //Muestra el select para enviar
    });
}
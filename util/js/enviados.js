
  function recargar(){
    window.location.reload();
  }
  
  function verEnviados(idExpediente){
    $.ajax({
      url : '../expedientes/ver-expediente.php',
      type : 'POST',
      dataType : 'html',
      data : {idExpediente: idExpediente},
    }).done(function(resultado){
        $("#enviados2").html(resultado);
    });

    var y = document.getElementById('enviados2');
    if (y.style.display === 'none') { //Si no se ve, que se vea
        y.style.display = 'block';
    }
  }
  
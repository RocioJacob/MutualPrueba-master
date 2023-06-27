<!-- Modal -->
<div class="modal fade" id="myModalArchivar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="mensajeExpedienteArchivar" style="color:#0072BC;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButtonEnviarX3"><span aria-hidden="true">&times;</span></button>
      </div>
         
      <div class="modal-body">
        <form enctype="multipart/form-data" method="post" id="formularioArchivar">
          <input type="hidden" id="idExpediente" name="idExpediente" value="">
          
          <div class="form-group col-md-12">
            <label style="color:003366">Comentario</label><br>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="botonArchivarExpediente">Archivar</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  const closeModalButtonEnviar3 = document.getElementById('closeModalButtonEnviarX3');
  closeModalButtonEnviar3.addEventListener('click', function() {
    $('#myModalArchivar').modal('hide');
  });
</script>


<script type="text/javascript">
  $('#botonArchivarExpediente').click(function(evento){
    evento.preventDefault();
      var datos = new FormData($('#formularioArchivar')[0]);
      Swal.fire({
          title: '<span style="font-size: 22px;">¿Desea archivar el expediente?</span>',
          width:'500px',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#0F4C75',
          confirmButtonText: 'Aceptar',
          cancelButtonColor: '#1B262C',
          allowOutsideClick: false,
      }).then((result) => {
        
        if (result.isConfirmed) {
          $.ajax({
            url: 'archivar-expediente.php',
            type:'POST',
            data: datos,
            processData: false,
            contentType: false,
            
            beforeSend: function() {
              //$("#loader").css('display','block');
            },
            success: function(data){
              //$("#loader").css('display','none');
              var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExitoArchivar(jsonData.mensaje);
              }
              else{
                return mensajeErrorArchivar(jsonData.mensaje);
              }
            }

          });
          return false;
        }
      });
  });


  function mensajeErrorArchivar($mensaje){
    swal.fire({
      title: "Error",
      text: $mensaje,
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExitoArchivar($mensaje){
    swal.fire({
      title: "Éxito",
      text: $mensaje,
      icon: 'success',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    }).then(function(){
          window.location.replace("tomados.php");
    });
  }
</script>
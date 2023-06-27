<!-- Modal -->
<div class="modal fade" id="myModalDesarchivar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="mensajeExpedienteDesarchivar" style="color:#0072BC;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
         
      <div class="modal-body">
        <form enctype="multipart/form-data" method="post" id="formularioDesarchivar">
          <input type="hidden" id="idExpediente" name="idExpediente" value="">
          
          <div class="form-group col-md-12">
            <label style="color:003366">Comentario</label><br>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="botonDesarchivarExpediente">Desarchivar</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  // Obtener el botón de cierre del modal por su ID
  const closeModalButton = document.getElementById('closeModalButton');
  // Agregar evento de clic al botón de cierre
  closeModalButton.addEventListener('click', function() {
    // Ocultar el modal al hacer clic en el botón de cierre
    $('#myModalDesarchivar').modal('hide');
  });
</script>


<script type="text/javascript">
  $('#botonDesarchivarExpediente').click(function(evento){
    evento.preventDefault();
      var datos = new FormData($('#formularioDesarchivar')[0]);
      Swal.fire({
          title: '<span style="font-size: 22px;">¿Desea desarchivar el expediente?</span>',
          width:'500px',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#148F77',
          confirmButtonText: 'Aceptar',
          cancelButtonColor: 'red',
          allowOutsideClick: false,
      }).then((result) => {
        
        if (result.isConfirmed) {
          $.ajax({
            url: 'desarchivar-expediente.php',
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
                return mensajeExitoDesarchivar(jsonData.mensaje);
              }
              else{
                return mensajeErrorDesarchivar(jsonData.mensaje);
              }
            }

          });
          return false;
        }
      });
  });


  function mensajeErrorDesarchivar($mensaje){
    swal.fire({
      title: "Error",
      text: $mensaje,
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExitoDesarchivar($mensaje){
    swal.fire({
      title: "Exito",
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
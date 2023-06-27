<!-- Modal -->
<div class="modal fade" id="myModalEnviar2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="mensajeExpedienteEnviar2" style="color:#0072BC;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButtonEnviarX2"><span aria-hidden="true">&times;</span></button>
      </div>
         
      <div class="modal-body">
        <form enctype="multipart/form-data" method="post" id="formularioEnviar2">
          <input type="hidden" id="idExpedienteEnviado2" name="idExpedienteEnviado2" value="">
          
          <div class="form-group col-md-12">
            <select id="area2" name="area2" class="form-control" style=" border-color: #2874A6; border-radius: 5px;"><option value="">Seleccione un area</option>
                <?php
                    $result = mysqli_query($conexion, "SELECT * FROM areas WHERE activado = '0' ORDER BY nombre ASC");
                      if($result->num_rows>0){
                          while($fila=$result->fetch_assoc()){
                            if($fila['id']!=$idAreaUsuario){
                              echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>'; 
                            }
                          }
                      }
                      ?>
            </select>
          </div><br>

          <div class="form-group col-md-12">
            <label style="color:003366">Comentario</label><br>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" autocomplete="off" placeholder="Maximo 100 caracteres" style="text-transform:uppercase; border-color: #2874A6; border-radius: 5px; resize: none;" maxlength="100"></textarea>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="botonEnviarExpediente2">Enviar</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  const closeModalButtonEnviar2 = document.getElementById('closeModalButtonEnviarX2');
  closeModalButtonEnviar2.addEventListener('click', function() {
    $('#myModalEnviar2').modal('hide');
  });
</script>

<script type="text/javascript">
$('#botonEnviarExpediente2').click(function(evento){
    evento.preventDefault();
    if(validarFormularioEnviar2()){
      var datos = new FormData($('#formularioEnviar2')[0]);
      Swal.fire({
          title: '<span style="font-size: 25px;">¿Desea enviar el expediente?</span>',
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
            url: 'enviar-expediente2.php',
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
                return mensajeExitoEnviar(jsonData.mensaje);
              }
              else{
                return mensajeErrorEnviar(jsonData.mensaje);
              }
            }

          });
          return false;
        }
      });
    }
});

function validarFormularioEnviar2() {
  if ($("#area2").val() === "") {
    mensajeErrorEnviar("Debe seleccionar un AREA");
    return false;
  }
  return true;
}


function mensajeErrorEnviar(mensaje) {
  Swal.fire({
    title: "Error",
    text: mensaje,
    icon: 'error',
    width: '500px',
    allowOutsideClick: false,
    confirmButtonColor: '#03989e',
  });
}

  function mensajeExitoEnviar($mensaje){
    Swal.fire({
      icon: 'success',
      width:'500px',
      title: $mensaje, 
      allowOutsideClick: false,
      }).then(function(){
          window.location.replace("tomados.php");
    });
  }

  </script>
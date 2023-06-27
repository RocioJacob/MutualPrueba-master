<!-- Modal -->
<div class="modal fade" id="myModalAgregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="mensajeExpediente" style="color:#0072BC;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButtonAgregar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form enctype="multipart/form-data" method="post" id="formularioArchivos">
          <div class="form-group">
            <label for="archivos">Seleccionar archivos</label>
            <input type="hidden" id="idExpedienteInput" name="idExpediente" value="">
            <input type="file" class="form-control-file" id="archivos" name="archivos[]" multiple>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="botonAdjuntar">Adjuntar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Obtener el botón de cierre del modal por su ID
  const closeModalButtonAgregar = document.getElementById('closeModalButtonAgregar');
  // Agregar evento de clic al botón de cierre
  closeModalButtonAgregar.addEventListener('click', function() {
    // Ocultar el modal al hacer clic en el botón de cierre
    $('#myModalAgregar').modal('hide');
  });
</script>

<script type="text/javascript">
  $('#botonAdjuntar').click(function(evento){
    evento.preventDefault();
    if(validarFormularioArchivos()){
      var datos = new FormData($('#formularioArchivos')[0]);
      Swal.fire({
          title: '<span style="font-size: 25px;">¿Desea adjuntar los archivos?</span>',
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
            url: 'cargar-archivos.php',
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
                return mensajeExitoArchivo(jsonData.mensaje);
              }
              else{
                return mensajeErrorArchivo(jsonData.mensaje);
              }
            }

          });
          return false;
        }
      });
    }
  });

  function validarFormularioArchivos(){
    const archivosInput = document.getElementById('archivos');
    const archivos = archivosInput.files; // Obtener los archivos seleccionados

    // Verificar si no se ha adjuntado ningún archivo
    if (archivos.length === 0) {
      return mensajeErrorArchivo("Por favor, adjunta al menos un archivo.");
      return false;
    }
    if(archivos.length > 5) {
      return mensajeErrorArchivo("Por favor, adjunta un máximo de 5 archivos.");
      return false;
    }

    // Verificar el tamaño total de los archivos seleccionados
    let totalSize = 0;
    for (let i = 0; i < archivos.length; i++) {
      totalSize += archivos[i].size;
    }
    // Convertir el tamaño total a MB
    const totalSizeMB = totalSize / (1024 * 1024);

    // Verificar el tamaño total de los archivos
    if (totalSizeMB > 10) {
      return mensajeErrorArchivo("El tamaño total de los archivos no debe exceder los 10 MB.");
      return false;
    }
    
    // Verificar cada archivo seleccionado
    for (let i = 0; i < archivos.length; i++) {
      const archivo = archivos[i];
      const tipo = archivo.type;

      // Verificar si el tipo de archivo no es PDF ni JPG
      if (tipo !== 'application/pdf' && tipo !== 'image/jpeg') {
        return mensajeErrorArchivo("Por favor, selecciona solo archivos PDF o JPG.");
        return false;
      }
    }
    return true;
  }

  function mensajeErrorArchivo($mensaje){
    swal.fire({
      title: "Error",
      text: $mensaje,
      icon: 'error',
      width:'500px',
      allowOutsideClick: false,
      confirmButtonColor: '#03989e',
    });
  }

  function mensajeExitoArchivo($mensaje){
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

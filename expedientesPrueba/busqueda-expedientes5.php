<?php 
include('../estructura/navegacion.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<!--    Datatables  -->
    <!--link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/-->  
    <title></title>

  </head>
  <body>

    <div class="container" id="mycontainer">
      <span class="subtituloMenu">BÚSQUEDA DE EXPEDIENTE</span><br><br>
       <!--div class="row"-->
           <!--div class="col-lg-12"-->
            <table id="tablaUsuarios" class="table table-striped table-bordered table-condensed">
                <thead class="text-center">
                <tr>
                    <th>Id</th>
                    <th>Año</th>                          
                    <th>Tramite</th>
                    <th>Tipo</th> 
                    <th>Documento/Cuit</th>
                    <th>Afiliado</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table><br><br>  
           <!--/div-->
       <!--/div--> 
    </div>
   
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
      
      
<!--    Datatables-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>  
      
    <script>
        $(document).ready(function(){
           $("#tablaUsuarios").DataTable({
              "processing": true,
              "serverSide": true,
              "sAjaxSource": "ServerSide/serversideUsuarios.php",
              "columnDefs":[{"data":null}],
              "order": [[ 1, 'desc'], [ 0, 'desc']],
              "language":{ "sSearch": "Buscar:", "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros", "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros", "infoFiltered": "(filtrado de un total de _MAX_ registros)", "lengthMenu": "Mostrar _MENU_ registros", "zeroRecords": "No se encontraron resultados", "oPaginate": {"sFirst": "Primero","sLast":"Último","sNext":"Siguiente","sPrevious": "Anterior"},"sProcessing":"Procesando..."}
           }); 
        });
    </script>  
      
  </body>
</html>


<style type="text/css">
#mycontainer { max-width: 1300px !important; }
th{
    background-color: #3498DB;
    font-size: 14px !important;
}
td{
  font-family: Arial;
  font-size: 11px !important;
}

</style>
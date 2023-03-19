<?php  
session_start();
if (!isset($_SESSION['documento'])) {
  header('Location: ../index.php');
}
else{//Es un usuario empleado
      include('../admin/conexion.php');
      include('../util/funcionesexp.php');
      $documento = $_SESSION['documento'];
      $usuario = $_SESSION['usuario'];
      //$identificador = $_SESSION['identificador'];
      $idExpediente = $_GET['idExpediente'];
      $anio = $_GET['anio'];
      $identificador = $idExpediente."-".$anio;
      
    $expediente = mysqli_query($conexion, "SELECT * FROM expedientes WHERE identificador='$identificador'");
    $expediente = mysqli_fetch_assoc($expediente);
      
    $idExpediente = $expediente['id_expediente'];
    $anioExpediente = $expediente['anio'];
    $fechaCreacion = date("d-m-Y", strtotime($expediente['fecha_creacion']));
    $tipo = $expediente['tipo'];
    $nombre = $expediente['nombre'];
    $idTramite = $expediente['id_tramite'];
    $nombreTramite = nombreTramite($idTramite);
    $monto = $expediente['monto'];
    $datos = $expediente['datos'];
    $extracto = $expediente['extracto'];
    $documento = $expediente['documento'];
    $codigo = $expediente['codigo'];
    $cuit = $expediente['cuit'];
    $idArea = $expediente['id_area'];
    $nombreArea = nombreArea($idArea);
    $fechaActual = obtenerfechaActual();
    $idUsuario = $expediente['id_usuario'];
    $nombreUsuario = nombreUsuario($idUsuario);

    $tramita = $nombreArea." - ".$nombreUsuario;

    //Si es un tramite con autorizacion
    $usuarioAutorizado = "";
    if(tieneAutorizacion2($idTramite)){
      if(estaAutorizado($idExpediente)){
        $usuarioAutorizado = $expediente['usuario_autorizado'];
        //$usuarioAutorizado = apellidoNombre($usuarioAutorizado);
        $usuarioAutorizado = "Autorizado: SI - Autoriza: ".$usuarioAutorizado;
      }
      if(estaRechazado($idExpediente)){
        $usuarioAutorizado = "Autorizado: NO - Rechaza: ".$usuarioAutorizado;
      }
      if(estaPendiente($idExpediente)){
        $usuarioAutorizado = "Autorización: PENDIENTE";
      }
    }
    else{
      $usuarioAutorizado = "Autorización: NO REQUIERE";
    }
}

include ('plantilla.php');
//Instaciamos la clase para generar el documento pdf
$pdf=new PDF();
$pdf->AliasNbPages();
//Agregamos la primera pagina al documento pdf

#Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(7,15,10);
$pdf->AddPage();
$pdf->SetFillColor(255,255,255);

$pdf->SetTextColor(0,0,0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(175,10,utf8_decode('Número de expediente: '.$idExpediente." - ".$anioExpediente),0,0,'C',1);
$pdf->SetTextColor(0,0,0);
$pdf->ln(10);

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(195,10,utf8_decode('Fecha actual: '.$fechaActual),0,0,'R',1);
$pdf->ln(8);

$pdf->SetFont('Helvetica','',11);
$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->Cell(80,10,utf8_decode('Fecha creación: '.$fechaCreacion),0,0,'L',1);
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->Cell(80,10,utf8_decode('Tipo: '.$tipo),0,0,'L',1);
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->Cell(80,10,utf8_decode('Trámite: '.$nombreTramite),0,0,'L',1);
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->MultiCell(0,5,utf8_decode('Datos: '.$datos),0,'L');
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->MultiCell(0,5,utf8_decode('Extracto: '.$extracto),0,'L');
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->Cell(80,10,utf8_decode('Tramita: '.$tramita),0,0,'L',1);
$pdf->ln(12);

$pdf->Cell(0,10,"_________________________________________________________________________________________");
$pdf->ln(8);
$pdf->Cell(80,10,utf8_decode($usuarioAutorizado),0,0,'L',1);
$pdf->ln(12);

$pdf->Output('ExpedienteNro'.$identificador.'.pdf', 'I');

?>
<?php
require('../util/fpdf/fpdf.php');
	
class PDF extends FPDF{
		
	function Header(){
		$this->Image('../util/imagenes/logo-expedientes.png', 5, 5, 75 );
		$this->ln(20);
	}
		
	function Footer(){
		$this->SetY(-15);
		$this->SetFont('Arial','I', 8);
		$this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
	}		
}
?>
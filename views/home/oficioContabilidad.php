<?php
	require('/var/www/html/sistge/fpdf/fpdf.php');
    include('/var/www/html/sistge/config/dbfonretyf.php');
    
    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(2.5,2,2.5);
    $pdf->SetAutoPageBreak(true,2.5); 

	$pdf->AddPage();
    
	$identr = $_GET['identr'];
	$fecha = fechactual();
	
	$pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();
	
	$numEntrega=intval(substr($identr,4,2));
	$anioentrega=substr($identr,0,4);
			
	$statementCarpetas = $db->prepare("SELECT * FROM public.carpetas WHERE anioentrega=".$anioentrega." and numentrega=".$numEntrega);
    $statementCarpetas->execute();
    $resultsCarpetas = $statementCarpetas->fetchAll(PDO::FETCH_ASSOC);
			
	$meses=[1=>"ENERO", 2=>"FEBRERO", 3=>"MARZO", 4=>"ABRIL", 5=>"MAYO", 6=>"JUNIO", 7=>"JULIO", 8=>"AGOSTO", 9=>"SEPTIEMBRE", 10=>"OCTUBRE", 11=>"NOVIEMBRE", 12=>"DICIEMBRE"];
	
	$statementE = $db->prepare("SELECT numentrega,fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identr."'");
    $statementE->execute();
    $resultsE = $statementE->fetchAll(PDO::FETCH_ASSOC);
    $fechaEntrega = substr($resultsE[0]['fechentrega'],8,2) . " DE " . $meses[intval(substr($resultsE[0]['fechentrega'],5,2))] . " DE " . substr($resultsE[0]['fechentrega'],0,4);
            		
	$statementFolI = $db->prepare("SELECT folini FROM public.carpetas WHERE anioentrega=? AND numentrega=? AND numcarpeta=1");
    $statementFolI->bindValue(1,$anioentrega);
    $statementFolI->bindValue(2,$numEntrega);
    $statementFolI->execute();
    $resultFolI = $statementFolI->fetchAll();
			
	$statementMax = $db->prepare("SELECT max(numcarpeta) FROM public.carpetas WHERE anioentrega=? AND numentrega=?");
    $statementMax->bindValue(1,$anioentrega);
    $statementMax->bindValue(2,$numEntrega);
    $statementMax->execute();
    $resultMax = $statementMax->fetchAll();
	$resultFolF = $db->prepare("SELECT folfin FROM public.carpetas WHERE anioentrega=? AND numentrega=? AND numcarpeta=?");
    $resultFolF->bindValue(1,$anioentrega);
    $resultFolF->bindValue(2,$numEntrega);
	$resultFolF->bindValue(3,intval($resultMax[0]['max']));
    $resultFolF->execute();
    $resultFolF = $resultFolF->fetchAll();
			
	$pdf->Image('/var/www/html/sistge/img/escudooficio.png',2.5,1.3,3.3,4.5);
                        		
    $pdf->AddFont('SegoeUIBL','','seguibl.php');
    $pdf->AddFont('arialbi','','arialbi.php');
    $pdf->SetFont('SegoeUIBL','',19);
    $pdf->setXY(0,0);

    $pdf->setXY(5.5,2);
    $pdf->SetTextColor(74,68,67);
    $pdf->Cell(13, 0.8, 'SINDICATO DE MAESTROS',0, 1, 'C');
    $pdf->setX(5.5);
    $pdf->SetFont('SegoeUIBL','',19);
    $pdf->Cell(13, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
            
    $pdf->Ln(2);
    $pdf->setX(2.5);
    $pdf->SetFont('Arial','',11);
    setlocale(LC_ALL, 'es_MX');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(16.59, 0.8, utf8_decode("Toluca, México a  " .$fecha),0, 0, 'R');
			
	$pdf->Ln(2);
    $pdf->setX(2.5);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(9.59, 0.4, "ASUNTO:", 0, 0, 'R');
	$pdf->SetFont('Arial','',10);
    $pdf->Cell(7, 0.4, utf8_decode('ENTREGA DE EXPEDIENTES Y PÓLIZAS'), 0, 0, 'R');
            
    $pdf->Ln(2);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(16.59, 0.4, utf8_decode('C.P. FRANCISCO NEGRETE RODEA'), 0, 0, 'L');
    $pdf->Ln(0.4);
    
    $pdf->Cell(16.59, 0.4, utf8_decode('DIRECTOR DLE AREA DE CONTABILIDAD DEL SMSEM'), 0, 0, 'L');
    $pdf->Ln(0.4);
    
    $pdf->Cell(16.59, 0.4, utf8_decode('P R E S E N T E:'), 0, 0, 'L');
                  
    $pdf->SetFont('Arial','',11);
    $pdf->Ln(2);
    $pdf->setX(3.25);
    $pdf->Cell(15.84, 0.5, utf8_decode('SIRVA ESTE MEDIO  PARA ENVIARLE  UN  CORDIAL SALUDO Y  A LA VEZ REMITO A'), 0, 0, 'L');
            
	$pdf->Ln(0.5);
    $pdf->setX(2.5);
    $pdf->Cell(1.8, 0.5, utf8_decode('USTED'), 0, 0, 'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(1, 0.5,count($resultsCarpetas), 0, 0, 'C');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(4.5, 0.5, "  CARPETAS   DE   LA ", 0, 0, 'L');
	$pdf->Write(0.5,intval(substr($identr,4,2)));
	$pdf->SetFont('Arial','',7);
    $pdf->subWrite(0.5,'a','',7,2.5);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(8.94, 0.5, utf8_decode('     ENTREGA    DEL    FONDO   DE    RETIRO   Y'), 0, 0, 'L');
		
	$pdf->Ln(0.5);
	$pdf->Cell(9.6, 0.5, utf8_decode('FALLECIMIENTO,  QUE  SE LLEVÓ  ACABO  EL  DIA '), 0, 0, 'L');
	$pdf->SetFont('Arial','B',10);
    $pdf->Cell(5, 0.5, $fechaEntrega, 0, 0, 'C');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(1.99, 0.5, utf8_decode(', LA CUAL'), 0, 0, 'L');
			
	$pdf->Ln(0.5);
	$pdf->Cell(10.1, 0.5, utf8_decode('SE INTEGRA POR EL SIGUIENTE RANGO DE FOLIOS: '), 0, 0, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3.5, 0.5, $resultFolI[0]['folini'] . " - ". $resultFolF[0]['folfin'], 0, 0, 'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(2.99, 0.5, utf8_decode('. ASÍ MISMO SE'), 0, 0, 'L');
			           
    $pdf->Ln(0.5);
	$pdf->MultiCell(16.69,0.5,utf8_decode("ADJUNTAN A ESTE OFICIO LOS LISTADOS  IMPRESOS Y SE REMITEN EN MEDIO MAGNETICO.	"),0 ,'J');
            		
	$pdf->Ln(1);
	$pdf->SetFont('Arial','',11);
	$pdf->MultiCell(16.59,0.5,utf8_decode("       SIN MÁS POR EL MOMENTO ME DESPIDO DE USTED, QUEDANDO A SUS ÓRDENES PARA CUALQUIER ACLARACIÓN."),0 ,'J');
				
	$pdf->Ln(2);
	$pdf->setX(2.5);
	$pdf->SetFont('Arial','B',10);
	$pdf->cell(16.59,0.5,"A T E N T A M E N T E",0,0,'C');
	$pdf->Ln(2.5);
	$pdf->setX(2.5);
	$pdf->cell(16.59,0.5,utf8_decode("PROFR. HORACIO LÓPEZ SALINAS"),0,0,'C');
	$pdf->Ln(0.35);
	$pdf->setX(2.5);
	$pdf->SetFont('Arial','B',9);
	$pdf->cell(16.59,0.5,utf8_decode("DIRECTOR DEL FONDO DE RETIRO Y FALLECIMIENTO"),0,0,'C');
				
	$pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17,23.3,2.57,2.38);
			
   
    function fechactual(){
        $fecha = date("d-m-y ");
        $mes = intval(explode("-",$fecha)[1]);
        $mesesA = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        
        $fechaCompleta = explode("-",$fecha)[0] . " de ". $mesesA[$mes - 1] . " de 20" . explode("-",$fecha)[2];
        return $fechaCompleta;
    }
    
    //$pdf->AliasNbPages();
    //$pdf->AddPage();
    //$pdf->SetFont('Times','',12);
    
    $pdf->Output('5.OFICIO CONTABILIDAD','I');
	

?>
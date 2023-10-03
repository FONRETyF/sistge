<?php

	require('/var/www/html/sistge/fpdf/fpdf.php');
    include('/var/www/html/sistge/config/dbfonretyf.php');
    
    $pdf = new FPDF("L","cm",array(27.94,21.59));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(1,0.5,1,0.5);
    $pdf->SetAutoPageBreak(true,0.5); 
		
	 
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
	
	$idFol = 1;
	for($i=0 ;  $i < count($resultsCarpetas) ; $i++){
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
	
		$pdf->SetTextColor(74,68,67);
		$pdf->Cell(25.94, 0.5, 'ENTREGA No. '.$numEntrega.' DE FONDO DE RETIRO Y FALLECIMIENTO',0, 1, 'C');
		$pdf->Cell(25.94, 0.5, utf8_decode($fechaEntrega),0, 1, 'C');
		$pdf->SetTextColor(0,0,0);
		
		$resultFC = $db->prepare("SELECT folini,folfin FROM public.carpetas WHERE anioentrega=? AND numentrega=? AND numcarpeta=?");
		$resultFC->bindValue(1,$anioentrega);
		$resultFC->bindValue(2,$numEntrega);
		$resultFC->bindValue(3,$i+1);
		$resultFC->execute();
		$resultFC = $resultFC->fetchAll();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(22.94, 0.5,"E".substr($identr,4,2)."-".$anioentrega."-C".$i+1 ,0, 0, 'R');
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(3,0.5,"(".$resultFC[0]['folini']."-".$resultFC[0]['folfin'].")",0,1, 'R');
				
		$folioIniCarp = intval($resultFC[0]['folini']);
		$folioFinCarp = intval($resultFC[0]['folfin']);
		$pdf->Ln(0.3);
		
		$pdf->SetDrawColor(150,150,150);
        $pdf->SetLineWidth(0.01);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(1, 0.6,"ID",1, 0, 'C');
		$pdf->Cell(1.5, 0.6,"FOLIO",1, 0, 'C');
		$pdf->Cell(7.5, 0.6,"BENEFICIARIO",1, 0, 'C');
		$pdf->Cell(7, 0.6,"MAESTRO",1, 0, 'C');
		$pdf->Cell(2.1, 0.6,"MONTO",1, 0, 'C');
		$pdf->Cell(0.9, 0.6,"MOT",1, 0, 'C');
		$pdf->Cell(1.5, 0.6,"EST",1, 0, 'C');
		$pdf->Cell(4.24, 0.6,"OBSERVACIONES",1, 1, 'C');
		
		
		
		$a_get_folsCheqs_Carp = array();
		for($ic=$folioIniCarp ; $ic <= $folioFinCarp ; $ic++){            			
			$consultacheque = "SELECT tab1.identret,tab1.cvemae,tab1.folcheque,tab1.nombenef,tab1.montbenef,tab1.observcheque,tab3.nomcommae,tab3.motvret,tab1.estatcheque,tab1.chequeadeudo,tab1.adeudo,tab1.statedad,tab1.observreposcn,tab3.numadeds FROM public.beneficiarios_cheques as tab1";
            $consultacheque = $consultacheque . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret,tab1.numadeds FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret,tab1.numadeds";
            $consultacheque = $consultacheque . " FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE folcheque='00".$ic."';";
			$resultFolsCarp = $db->prepare($consultacheque);
			$resultFolsCarp->execute();
			$resultFolsCarp = $resultFolsCarp->fetchAll();
			
			if(count($resultFolsCarp) > 0){
				array_push($a_get_folsCheqs_Carp,array("cheque",$resultFolsCarp[0]));
			}else{
				$consultacheque = "SELECT tab1.identret,tab1.cvemae,tab1.folcheque,tab1.nombenef,tab1.montbenef,tab1.observcheque,tab3.nomcommae,tab3.motvret,tab1.estatcheque,tab1.chequeadeudo,tab1.adeudo,tab1.statedad,tab1.observreposcn,tab3.numadeds FROM public.beneficiarios_cheques_hist as tab1";
				$consultacheque = $consultacheque . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret,tab1.numadeds FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret,tab1.numadeds";
				$consultacheque = $consultacheque . " FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE folcheque='00".$ic."';";
				$resultFolsCarp = $db->prepare($consultacheque);
				$resultFolsCarp->execute();
				$resultFolsCarp = $resultFolsCarp->fetchAll();
				
				if(count($resultFolsCarp) > 0){
					array_push($a_get_folsCheqs_Carp,array("cheque",$resultFolsCarp[0]));
				}else{
					$consultacheque = "SELECT tab1.idret,tab1.cvemae,tab1.folcheque,tab1.nombenef,tab1.montbenef,tab1.observcancel,tab3.nomcommae,tab3.motvret,tab1.estatcheque FROM public.cheqs_cancelados as tab1";
					$consultacheque = $consultacheque . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab1.motvret";
					$consultacheque = $consultacheque . " FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE folcheque='00".$ic."';";
					$resultFolsCarp = $db->prepare($consultacheque);
					$resultFolsCarp->execute();
					$resultFolsCarp = $resultFolsCarp->fetchAll();
					
					if(count($resultFolsCarp) > 0){
						array_push($a_get_folsCheqs_Carp,array("cancelado",$resultFolsCarp[0]));
					}else{
						try{
							$consultacheque = "SELECT * FROM public.adm_chqs WHERE folio='00".$ic."';";
							$resultFolsCarp = $db->prepare($consultacheque);
							$resultFolsCarp->execute();
							$resultFolsCarp = $resultFolsCarp->fetchAll();
							
						}catch (\Throwable $th) {
							echo $th;
						}
						
						if(count($resultFolsCarp) > 0){
							array_push($a_get_folsCheqs_Carp,array("admincheque",$resultFolsCarp[0]));
						}else{
							
						}
					}
				}
			}
		}
		
		foreach($a_get_folsCheqs_Carp as $row){
			$id++;
			if($row[0]=="cheque"){
				$observacionesC = "";
				if($row[1]['chequeadeudo'] == "S"){
					$observacionesC="ADEUDO DE " . $row[1]['adeudo'];
				}else{
					if($row[1]['numadeds'] > 0){
					$observacionesC= $observacionesC . " TIENE ADEUDO";
					}
				}
				if($row[1]['statedad'] == 'N'){
					if (strlen($observacionesC)>0){
						$observacionesC= $observacionesC . ", MENOR DE EDAD";
					}else{
						$observacionesC="MENOR DE EDAD";
					}
				}
				
				if($row[1]['observcheque'] == 'REPOSICION'){
					if (strlen($observacionesC)>0){
						$observacionesC= $observacionesC . ", ".$row[1]['observreposcn'];
					}else{
						$observacionesC=$row[1]['observreposcn'];
					}
				}elseif( $row[1]['observcheque'] <> 'REPOSICION' && strlen($row[1]['observcheque'])>0 ){
					if (strlen($observacionesC)>0){
						$observacionesC= $observacionesC . ", ".$row[1]['observcheque'];
					}else{
						$observacionesC=$row[1]['observcheque'];
					}
				}		
				
				
				//$pdf->SetFont('Arial','',5);
				if(strlen($observacionesC) <=37){
					$largeCell = 0.5;
				}elseif(strlen($observacionesC) > 37 && strlen($observacionesC) <= 70){
					$largeCell = 1;
				}elseif(strlen($observacionesC) > 70){
					$largeCell = 1.5;
				}
				
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(1, $largeCell,$id,1, 0, 'C');
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(1.5, $largeCell,$row[1]['folcheque'],1, 0, 'C');
				$pdf->SetFont('Arial','',7.5);
				$pdf->Cell(7.5, $largeCell,utf8_decode($row[1]['nombenef']),1, 0, 'L');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(7, $largeCell,utf8_decode($row[1]['nomcommae']),1, 0, 'L');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(2.1, $largeCell,$row[1]['montbenef'],1, 0, 'C');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(0.9, $largeCell,$row[1]['motvret'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(1.5, $largeCell,$row[1]['estatcheque'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->MultiCell(4.24,0.5,utf8_decode($observacionesC),1 ,'J');			
				
				
			}elseif($row[0]=="cancelado"){
				if(strlen($row[1]['observcancel']) <= 35){
					$largeCell = 0.5;
				}elseif(strlen($row[1]['observcancel']) > 35 && strlen($row[1]['observcancel']) <= 70){
					$largeCell = 1;
				}elseif(strlen($row[1]['observcancel']) > 70){
					$largeCell = 1.5;
				}
				
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(1, $largeCell,$id,1, 0, 'C');
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(1.5, $largeCell,$row[1]['folcheque'],1, 0, 'C');
				$pdf->SetFont('Arial','',7.5);
				$pdf->Cell(7.5, $largeCell,utf8_decode($row[1]['nombenef']),1, 0, 'L');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(7, $largeCell,utf8_decode($row[1]['nomcommae']),1, 0, 'L');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(2.1, $largeCell,$row[1]['montbenef'],1, 0, 'C');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(0.9, $largeCell,$row[1]['motvret'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(1.5, $largeCell,$row[1]['estatcheque'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->MultiCell(4.24,0.5,utf8_decode($row[1]['observcancel']),1 ,'J',false);
			
			}elseif($row[0]=="admincheque"){
				$observacionesC = "";
				
				if(strlen($row[1]['observcnscheqs']) <= 35){
					$largeCell = 0.5;
				}elseif(strlen($row[1]['observcnscheqs']) > 35 && strlen($observacionesC) <= 70){
					$largeCell = 1;
				}elseif(strlen($row[1]['observcnscheqs']) > 70){
					$largeCell = 1.5;
				}
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(1, $largeCell,$id,1, 0, 'C');
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(1.5, $largeCell,$row[1]['folio'],1, 0, 'C');
				$pdf->SetFont('Arial','',7.5);
				$pdf->Cell(7.5, $largeCell,utf8_decode($row[1]['nombenef']),1, 0, 'L');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(7, $largeCell,utf8_decode($row[1]['nommae']),1, 0, 'L');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(2.1, $largeCell,$row[1]['montbenef'],1, 0, 'C');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(0.9, $largeCell,$row[1]['concepto'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(1.5, $largeCell,$row[1]['estatuscheq'],1, 0, 'C');
				$pdf->SetFont('Arial','',5);
				$pdf->MultiCell(4.24,0.5,utf8_decode($row[1]['observcnscheqs']),1 ,'J',false);
			}
		}	
	}
	
	function fechactual(){
        $fecha = date("d-m-y ");
        $mes = intval(explode("-",$fecha)[1]);
        $mesesA = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
		        
        $fechaCompleta = explode("-",$fecha)[0] . " de ". $mesesA[$mes - 1] . " de 20" . explode("-",$fecha)[2];
        return $fechaCompleta;
    }
      
    $pdf->Output('LISTADOS DE CARPETAS ENTREGA No ' . $numEntrega,'I');
?>
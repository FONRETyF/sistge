<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    include('/var/www/html/sistge/config/dbfonretyf.php');

    $pdf = new FPDF("P","cm",array(10.48,24.13));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(2.5,2,2.5);
    $pdf->SetAutoPageBreak(true,2.5); 

    $identrega = $_GET['identr'];
    $numentrega=intval(substr($identrega,4,2));

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();
	 
	 /* INHABILITADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab3.nomcommae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae FROM";
    $consultacheques = $consultacheques . " public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " WHERE tab1.identrega='".$identrega."' and tab1.motvret='FRI' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER  BY nomcommae ASC;";

    $arregloMaestrosCheques = array();

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsCheques as $key => $row) {
        $aux[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($resultsCheques as $key => $row1) {
            if ($row === $row1['nomcommae']) {
                //$arregloMaestrosCheques[$key] = $row1;
                array_push($arregloMaestrosCheques, $row1);
                break;
            } 
        }    
    }

     /* JUBILADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab3.nomcommae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae FROM";
    $consultacheques = $consultacheques . " public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " WHERE tab1.identrega='".$identrega."' and tab1.motvret='FRJ' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER  BY nomcommae ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsCheques as $key => $row) {
        $aux[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($resultsCheques as $key => $row1) {
            if ($row === $row1['nomcommae']) {
                array_push($arregloMaestrosCheques, $row1);
                break;
            } 
        }    
    }

     /* FALLECIMIENTOS */
    $consultachequesF = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae FROM public.tramites_fonretyf as tab1 LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1,";
    $consultachequesF = $consultachequesF . " public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2";
    $consultachequesF = $consultachequesF . " WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and (tab1.motvret='FRF' or tab1.motvret='FMJ') and tiptramne='0' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER  BY nomcommae ASC;";

    $statementChequesF = $db->prepare($consultachequesF);
    $statementChequesF->execute();
    $resultsChequesF = $statementChequesF->fetchAll(PDO::FETCH_ASSOC);
	
	$auxF = array();
    foreach ($resultsChequesF as $key => $row) {
        $auxF[$key]=$row["nomcommae"];
    }
    
    $collator = collator_create("es");
    $collator->sort($auxF);
   
    foreach ($auxF as $rowAuxF) {
        foreach ($resultsChequesF as $key => $rowF) {
            if ($rowAuxF === $rowF['nomcommae']) {
                $clave= $rowF['cvemae'];
                
                $consultachequesB = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab3.nomcommae from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
                $consultachequesB = $consultachequesB . "SELECT tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
                $consultachequesB = $consultachequesB . " WHERE tab1.identrega='".$identrega."' and tab1.cvemae='".$clave."' and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER BY nombenef ASC;";

                $statementChequesB = $db->prepare($consultachequesB);
                $statementChequesB->execute();
                $resultsChequesB = $statementChequesB->fetchAll(PDO::FETCH_ASSOC);

                if (count($resultsChequesB)>1) {
					$auxB = array();
                    foreach ($resultsChequesB as $keyB => $rowB) {
                        $auxB[$keyB]=$rowB["nombenef"];
                    }
                    $collator = collator_create("es");
                    $collator->sort($auxB);
                    
                    foreach ($auxB as $rowBB) {
                        foreach ($resultsChequesB as $keyBenef => $rowBenef) {
                            if ($rowBB === $rowBenef['nombenef']) {
                                array_push($arregloMaestrosCheques, $rowBenef);
                                break;
                            } 
                        }    
                    }
                }else {
                    array_push($arregloMaestrosCheques, $resultsChequesB[0]);
                }
                break;
            }
        }
    }

    
	/* MENORES DE EDAD */
    $consultachequesME = "SELECT DISTINCT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.cvemae=tab2.cvemae LEFT JOIN";
    $consultachequesME = $consultachequesME . " (SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1,";
    $consultachequesME = $consultachequesME . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and (tab1.motvret='FRF' or tab1.motvret='FMJ') and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' and tab2.statedad = 'N' ORDER  BY nomcommae ASC;";
            
    $statementChequesME = $db->prepare($consultachequesME);
    $statementChequesME->execute();
    $resultsChequesME = $statementChequesME->fetchAll(PDO::FETCH_ASSOC);
       
    $auxME = array();
    foreach ($resultsChequesME as $key => $row) {
        $auxME[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($auxME);

    foreach ($auxME as $rowAuxME) {
        $nomMae = $rowAuxME;
        foreach ($resultsChequesME as $rowF) {                  
            if ($nomMae === $rowF['nomcommae']) {
                $clave= $rowF['cvemae'];
                
                $consultachequesB = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab3.nomcommae from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
                $consultachequesB = $consultachequesB . "SELECT tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
                $consultachequesB = $consultachequesB . " WHERE tab1.identrega='".$identrega."' and tab1.cvemae='".$clave."' and tab2.statedad = 'N' and tab2.chequeadeudo = 'N' ORDER BY nombenef ASC;";

                $statementChequesB = $db->prepare($consultachequesB);
                $statementChequesB->execute();
                $resultsChequesB = $statementChequesB->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($resultsChequesB) > 1) {
                    $auxB = array();
                    foreach ($resultsChequesB as $keyB => $rowB) {
                        $auxB[$keyB]=$rowB["nombenef"];
                    }
                    $collator = collator_create("es");
                    $collator->sort($auxB);
                    foreach ($auxB as $rowBB) {
                        foreach ($resultsChequesB as $keyBenef => $rowBenef) {
                            if ($rowBB === $rowBenef['nombenef']) {
                                array_push($arregloMaestrosCheques, $rowBenef);
                            } 
                        }    
                    }   
                }else {
                    array_push($arregloMaestrosCheques, $resultsChequesB[0]);
                    
                }
            }
        }
    }

    /* ADEUDOS */
    $consultachequesA = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab3.nomcommae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultachequesA = $consultachequesA . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultachequesA = $consultachequesA . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab2.chequeadeudo = 'S' and tiptramne='0' ORDER  BY nombenef ASC;";
            
    $statementChequesA = $db->prepare($consultachequesA);
    $statementChequesA->execute();
    $resultsChequesA = $statementChequesA->fetchAll(PDO::FETCH_ASSOC);
            
    $auxA = array();
    if (!empty($resultsChequesA)) {
        foreach ($resultsChequesA as $key => $row) {
            $auxA[$key]=$row["nombenef"];
        }
        $collator = collator_create("es");
        $collator->sort($auxA);
        foreach ($auxA as $row) {
            foreach ($resultsChequesA as $key => $rowA) {
                if ($row === $rowA['nombenef']) {
                    array_push($arregloMaestrosCheques, $rowA);
                    break;
                } 
            }    
        }
    }  
	
	$numTramite=1;
   
    foreach ($arregloMaestrosCheques as $key => $row) {
        $numSobre = $key + 1;
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->setXy(6.3,2.5);
        $pdf->VCell(1,19,$numSobre . ".  " . utf8_decode($row['nombenef']),0,0,'C');
        $numTramite++;
    }

    $pdf->Output();


?>
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

    $consultacheques = "select tab1.identret,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') order by nomcommae asc, nombenef asc;";

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
    $consultacheques = "select tab1.identret,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') order by nomcommae asc, nombenef asc;";

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
    $consultacheques = "select tab1.identret, tab1.cvemae, tab1.motvret, tab3.nomcommae from public.tramites_fonretyf as tab1 left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') and (tab1.modretiro='C' or tab1.modretiro='D50') order by nomcommae asc;";

    $statementChequesF = $db->prepare($consultacheques);
    $statementChequesF->execute();
    $resultsChequesF = $statementChequesF->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsChequesF as $key => $row) {
        $auxF[$key]=$row["nomcommae"];
    }
    
    $collator = collator_create("es");
    $collator->sort($auxF);
   
    foreach ($auxF as $key => $row) {
        foreach ($resultsChequesF as $key => $rowF) {
            if ($row === $rowF['nomcommae']) {
                $clave= $rowF['cvemae'];
                
                $consultachequesB = "select tab1.identret,tab2.idbenefcheque,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
                $consultachequesB = $consultachequesB . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
                $consultachequesB = $consultachequesB . " where tab1.identrega='".$identrega."' and tab1.cvemae='".$clave."' order by nombenef asc;";

                $statementChequesB = $db->prepare($consultachequesB);
                $statementChequesB->execute();
                $resultsChequesB = $statementChequesB->fetchAll(PDO::FETCH_ASSOC);

                if (count($resultsChequesB)>1) {
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

    $numTramite=1;
   
    foreach ($arregloMaestrosCheques as $key => $row) {
        $numSobre = $key + 1;
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->setXy(6.3,2.5);
        $pdf->VCell(1,19,$numSobre . ".  " . $row['nombenef'],0,0,'C');
        $numTramite++;
    }

    $pdf->Output();


?>
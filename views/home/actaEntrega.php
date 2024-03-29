<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    include('/var/www/html/sistge/config/dbfonretyf.php');
    
    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(3,2,3);
    $pdf->SetAutoPageBreak(true,2.5); 

    $meses=[1=>"enero", 2=>"febrero", 3=>"marzo", 4=>"abril", 5=>"mayo", 6=>"junio", 7=>"julio", 8=>"agosto", 9=>"septiembre", 10=>"octubre", 11=>"noviembre", 12=>"diciembre"];

    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $identrega = $_GET['identr'];

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statementFechEntr = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
    $statementFechEntr->execute();
    $resultsFechentrega = $statementFechEntr->fetchAll(PDO::FETCH_ASSOC);  
    
    $A_Tramites_Benef_Actas = array();

    $fechaEntrega = substr($resultsFechentrega[0]['fechentrega'],8,2) . " de " . $meses[intval(substr($resultsFechentrega[0]['fechentrega'],5,2))] . " de " . substr($resultsFechentrega[0]['fechentrega'],0,4);

    /* INHABILITADOS */
    $consultacheques = "select tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.modretiro,tab1.montrettot,tab1.montretletra,tab1.montretentr,tab1.montretentrletra,tab1.foliotramite";
    $consultacheques = $consultacheques . " from public.tramites_fonretyf as tab1 left join (select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym)";
    $consultacheques = $consultacheques . " as tab3 on tab1.cvemae= tab3.cvemae where tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc;";
    $statement = $db->prepare($consultacheques);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $key => $row) {
        $aux[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($results as $key => $row1) {
            if ($row === $row1['nomcommae']) {
                //$arregloMaestrosCheques[$key] = $row1;
                array_push($A_Tramites_Benef_Actas, $row1);
                break;
            } 
        }    
    }

    /* JUBILADOS */
    $consultacheques = "select tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.modretiro,tab1.montrettot,tab1.montretletra,tab1.montretentr,tab1.montretentrletra,tab1.foliotramite";
    $consultacheques = $consultacheques . " from public.tramites_fonretyf as tab1 left join (select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym)";
    $consultacheques = $consultacheques . " as tab3 on tab1.cvemae= tab3.cvemae where tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc;";
    $statement = $db->prepare($consultacheques);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $key => $row) {
        $aux[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($results as $key => $row1) {
            if ($row === $row1['nomcommae']) {
                //$arregloMaestrosCheques[$key] = $row1;
                array_push($A_Tramites_Benef_Actas, $row1);
                break;
            } 
        }    
    }

    /* FALLECIDOS */
    $consultacheques = "select tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.modretiro,tab1.montrettot,tab1.montretletra,tab1.montretentr,tab1.montretentrletra,tab1.foliotramite";
    $consultacheques = $consultacheques . " from public.tramites_fonretyf as tab1 left join (select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym)";
    $consultacheques = $consultacheques . " as tab3 on tab1.cvemae= tab3.cvemae where tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc;";
    
    $statement = $db->prepare($consultacheques);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $key => $row) {
        $aux[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($results as $key => $row1) {
            if ($row === $row1['nomcommae']) {
                //$arregloMaestrosCheques[$key] = $row1;
                array_push($A_Tramites_Benef_Actas, $row1);
                break;
            } 
        }    
    }
  
    $numTramite = 1;

    foreach ($A_Tramites_Benef_Actas as $key => $regTraAct) {
        $pdf->Image('/var/www/html/sistge/img/escudosmsem.png',3,2,2,2.5);
        $pdf->SetFont('Arial','B',11);

        $motivo = $regTraAct['motvret'];
        if ($motivo== "I") {
            $motivoRetiro = "INHABILITACIÓN";
        } elseif ($motivo == "J") {
            $motivoRetiro = "JUBILACIÓN";
        }elseif ($motivo == "FA" || $motivo == "FJ") {
            $motivoRetiro = "FALLECIMIENTO";
        }

        if ($motivo == "I" || $motivo == "J") {
            $pdf->SetXY(17.59,1);
            $pdf->SetFont('Arial','',11);
            $pdf->SetTextColor(150,150,150);
            $pdf->cell(1,0.5,$numTramite,0,0, 'C');

            $pdf->SetXY(5,2.2);
            $pdf->SetTextColor(0,0,0);
            $pdf->cell(13.59,0.5,'SINDICATO DE MAESTROS ',0,0, 'C');

            $pdf->SetXY(5,2.7);
            $pdf->cell(13.59,0.5,utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'), 0,0,'C');

            $pdf->SetXY(5,3.8);
            $pdf->cell(13.59,0.5,utf8_decode('"IDENTIDAD Y VALOR MAGISTERIAL"'), 0,0,'C');

            $pdf->SetFont('Arial','',12);
            $pdf->Ln(1.3);
            $pdf->cell(8.89,0.5,utf8_decode('En Toluca México,  siendo las 9:00 hrs del día'),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(5.8,0.5,$fechaEntrega,0,0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->cell(0.9,0.5,',  se',0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('reunieron  el  Comité  Ejecutivo  y   El  Consejo  Directivo  del  Fonde  de  Retiro  y'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('Fallecimiento (FONRETyF)  del  Sindicato de  Maestros  al Servicio del  Estado de'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('México, con el propósito de hacer entrega del seguro que corresponde al: '),0,0, 'L');
            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(15.59,0.5,utf8_decode('Profr. (a) ' . $regTraAct["nomcommae"]),0,0, 'C');
            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(2.5,0.5,utf8_decode('quien acepto '),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(3.6,0.5,utf8_decode($motivoRetiro),0,0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->cell(6.55,0.5,utf8_decode(', correspondiéndole la cantidad de '),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(2.94,0.5,$regTraAct['montrettot'],0,0, 'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',9.5);
            $pdf->cell(15.59,0.5,'('.$regTraAct['montretletra'].')',0,0, 'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(15.59,0.5,utf8_decode('equivalente  al pago  acordado por  el Comité Ejecutivo  en  el  Trienio  2021-2024'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(15.59,0.5,utf8_decode('según consta en el acta del 14 de diciembre de 2021.'),0,0, 'L');

            $consultaNumcheques = "SELECT  COUNT(cvemae) as numcheques FROM public.beneficiarios_cheques WHERE cvemae='".$regTraAct["cvemae"]."';";
            $statementNumChqs = $db->prepare($consultaNumcheques);
            $statementNumChqs->execute();
            $resultsNumChqs = $statementNumChqs->fetchAll(PDO::FETCH_ASSOC);

            if ($resultsNumChqs[0]["numcheques"] == 1) {
                $pdf->Ln(1);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(15.59,0.5,utf8_decode('El acto se dio por terminado y firmado para su constancia los que intervinieron:'),0,0, 'L');

                $pdf->SetXY(3,12.5);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Profr. Marco Aurelio Carbajal Leyva',0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Presidente del Consejo Directivo',0,0, 'C');

                $pdf->Ln(2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Jesús Sotelo Sotelo'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profra. Cleotilde Castillo Méndez'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Secretario del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Tesorero del Consejo Directivo',0,0, 'C');

                $pdf->Ln(2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Horacio López Salinas'),0,0, 'C');
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Santiago Hernández Garduño'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,utf8_decode('Profr. José Merced Salinas Villa'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9.5);
                $pdf->cell(15.59,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(1.5);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Beneficiario',0,0, 'C');
                $pdf->Ln(2.5);
                $pdf->SetFont('Arial','B',9.5);
                $pdf->cell(15.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');

            }else if ($resultsNumChqs[0]["numcheques"] > 1) {
                $pdf->Ln(0.7);

                $numadeudos = $resultsNumChqs[0]["numcheques"] - 1;
                $pdf->SetFont('Arial','',12);
                $pdf->cell(9.5,0.5,'Al mismo tiempo se informa que el Profr. (a) tiene',0,0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->cell(0.7,0.5, $numadeudos,0,0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->cell(5.39,0.5,'adeudo (s) con  el SMSEM,',0,0, 'L');
                $pdf->Ln(0.5);
                $pdf->cell(9.7,0.5,utf8_decode('por lo tanto, el monto real que se entrega es de: '),0,0, 'L');
                $pdf->SetFont('Arial','B',12);
                $pdf->cell(4,0.5,$regTraAct['montretentr'],0,0, 'L');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',9.5);
                $pdf->cell(15.59,0.5,'('.$regTraAct['montretentrletra'].')',0,0, 'C');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(9.7,0.5,utf8_decode('y el (los) cheque (s):'),0,0, 'L');

                $pdf->SetXY(3.5,12.1);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(2,0.5,'Folio',0,0,'C');
                $pdf->cell(3.2,0.5,'Adeudo',0,0,'C');
                $pdf->cell(2.8,0.5,'Monto',0,0,'C');
                $pdf->cell(6.59,0.5,'Monto letra',0,0,'C');

                $pdf->SetDrawColor(150,150,150);
                $pdf->SetLineWidth(0.05);
                $pdf->Line(3.5, 12.6, 18.09, 12.6);

                $pdf->SetFillColor(240,240,240);
                $pdf->SetDrawColor(255,255,255);

                $consultachequesAdeds = "SELECT  montbenef,montbenefletra,folcheque,adeudo FROM public.beneficiarios_cheques WHERE cvemae='".$regTraAct["cvemae"]."' AND chequeadeudo='S';";
                $statementChequesAdeds = $db->prepare($consultachequesAdeds);
                $statementChequesAdeds->execute();
                $resultsChequesAdeds = $statementChequesAdeds->fetchAll(PDO::FETCH_ASSOC);


                foreach ($resultsChequesAdeds as $rowAd) {
                    $pdf->Ln(0.5);
                    $pdf->cell(0.5);
                    $pdf->SetFont('Arial','',11);
                    $pdf->cell(2,0.5,$rowAd["folcheque"],0,0,'C');
                    /*switch ($rowAd["adeudo"]) {
                        case 'adfajam':
                            $oficadeudo="FAJAM";
                            break;
                        case 'adts':
                            $oficadeudo="Tienda Sind";
                            break;
                        case 'adturismo':
                            $oficadeudo="Turismo";
                            break;
                        default:
                            # code...
                            break;
                    }*/
                    
                    $pdf->SetFont('Arial','',10);
                    $pdf->cell(3.2,0.5,$rowAd["adeudo"],0,0,'C');
                    $pdf->cell(2.8,0.5,$rowAd["montbenef"],0,0,'C');
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(6.59,0.5,$rowAd["montbenefletra"],0,'J');

                }
                $pdf->Ln(0.7);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(15.59,0.5,utf8_decode('se remite(n) a la oficina correspondiente para cubrir su deuda con esta institucion.'),0,0, 'L');

                $pdf->Ln(0.7);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(15.59,0.5,utf8_decode('El acto se dio por terminado y firmado para su constancia los que intervinieron:'),0,0, 'L');

                $pdf->SetXY(3,17);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Profr. Marco Aurelio Carbajal Leyva',0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Presidente del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1.2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Jesús Sotelo Sotelo'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profra. Cleotilde Castillo Méndez'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Secretario del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Tesorero del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1.2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Horacio López Salinas'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Santiago Hernández Garduño'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(1.2);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,utf8_decode('Profr. José Merced Salinas Villa'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(0.7);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Beneficiario',0,0, 'C');
                $pdf->Ln(2);
                $pdf->SetFont('Arial','B',9.5);
                $pdf->cell(15.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
            }
        }elseif ($regTraAct['motvret'] == "FA" || $regTraAct['motvret'] == "FJ") {
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(17.59,1);
            $pdf->SetFont('Arial','',11);
            $pdf->SetTextColor(150,150,150);
            $pdf->cell(1,0.5,$numTramite,0,0, 'C');

            $pdf->SetXY(5,2.2);
            $pdf->SetTextColor(0,0,0);
            $pdf->cell(13.59,0.5,'SINDICATO DE MAESTROS ',0,0, 'C');

            $pdf->SetXY(5,2.7);
            $pdf->cell(13.59,0.5,utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'), 0,0,'C');

            $pdf->SetXY(5,3.8);
            $pdf->cell(13.59,0.5,utf8_decode('"IDENTIDAD Y VALOR MAGISTERIAL"'), 0,0,'C');

            $pdf->SetFont('Arial','',12);
            $pdf->Ln(1.3);
            $pdf->cell(8.89,0.5,utf8_decode('En Toluca México,  siendo las 9:00 hrs del día'),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(5.8,0.5,$fechaEntrega,0,0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->cell(0.9,0.5,',  se',0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('reunieron  el  Comité  Ejecutivo  y   El  Consejo  Directivo  del  Fondo  de  Retiro  y'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('Fallecimiento (FONRETyF)  del  Sindicato de  Maestros  al Servicio del  Estado de'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(10.8,0.5,utf8_decode('México, con el propósito de hacer entrega del seguro por'),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(4,0.5,utf8_decode('FALLECIMIENTO'),0,0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->cell(0.79,0.5,utf8_decode('que'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(9,0.5,utf8_decode('corresponde al (los) beneficiario (s) del C. Profr. (a)'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',10.5);
            $pdf->cell(15.59,0.45,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(5.4,0.5,utf8_decode('por la  cantidad  referida de: '),0,0, 'L');
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(3.3,0.5,$regTraAct['montrettot'],0,0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->cell(6.89,0.5,utf8_decode(' equivalente  al  100%  de  la  suma'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(6.89,0.5,utf8_decode('acordada por el Comité Ejecutivo en el Trienio 2021-2024 según consta en el acta'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(6.89,0.5,utf8_decode('del 14 de diciembre de 2021.'),0,0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(15.59,0.5,utf8_decode('Con forme a la voluntad  manifestada  en  la carta  testamentaria  los beneficios se'),0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->cell(15.59,0.5,utf8_decode('asignan a: '),0,0, 'L');

            $resultsBenefs = array();

            $consultaBenefs = "SELECT  nombenef,montbenef,folcheque FROM public.beneficiarios_cheques WHERE cvemae='".$regTraAct["cvemae"]."';";
            $statementBenefs = $db->prepare($consultaBenefs);
            $statementBenefs->execute();
            $results = $statementBenefs->fetchAll(PDO::FETCH_ASSOC);
			
			$auxB1=array();
            foreach ($results as $key => $row) {
                $auxB1[$key]=$row["nombenef"];
            }
            $collator = collator_create("es");
            $collator->sort($auxB1);
        
            foreach ($auxB1 as $row) {
                foreach ($results as $key => $row1) {
                    if ($row === $row1['nombenef']) {
                        //$arregloMaestrosCheques[$key] = $row1;
                        array_push($resultsBenefs, $row1);
                        break;
                    } 
                }    
            }

            $pdf->Ln(0.4);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetX(5);
            $pdf->cell(8,0.5,'Beneficiario',0,0,'C');
            $pdf->cell(3.3,0.5,'Monto',0,0,'C');

            $pdf->SetDrawColor(150,150,150);
            $pdf->SetLineWidth(0.05);
            $pdf->Line(5, 11.15, 16.3, 11.15);

            $pdf->SetFillColor(240,240,240);
            $pdf->SetDrawColor(200,200,200);
            $pdf->Ln(0.5);

            foreach ($resultsBenefs as $rowBenef) {
                $pdf->SetX(5);
                $pdf->SetFont('Arial','',8);
                $pdf->SetLineWidth(0.02);
                $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(3.3,0.4,$rowBenef["montbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            if (count($resultsBenefs) < 7) {
                $pdf->SetXY(3,13.3);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(15.59,0.5,utf8_decode('El acto se dio por terminado y firmado para su constancia los que intervinieron:'),0,0, 'L');

                $pdf->SetXY(3,14.5);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Profr. Marco Aurelio Carbajal Leyva',0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Presidente del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Jesús Sotelo Sotelo'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profra. Cleotilde Castillo Méndez'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Secretario del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Tesorero del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Horacio López Salinas'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Santiago Hernández Garduño'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,utf8_decode('Profr. José Merced Salinas Villa'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $numbeneficiarios = count($resultsBenefs);
        
                switch ($numbeneficiarios) {
                    case '1':
                        $pdf->Ln(1.7);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(2);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        break;
                        
                    case '2':
                        $pdf->Ln(1.7);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(2);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        break;

                    case '3':
                        $pdf->Ln(0.7);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        break;

                    case '4':
                        $pdf->Ln(0.7);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        break;

                    case '5':
                        $pdf->Ln(0.4);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        break;

                    case '6':
                        $pdf->Ln(0.4);

                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');

                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
                        break;

                    default:
                        break;
                }
            }else {
                $pdf->SetXY(3,15.3);
                $pdf->SetFont('Arial','',12);
                $pdf->cell(15.59,0.5,utf8_decode('El acto se dio por terminado y firmado para su constancia los que intervinieron:'),0,0, 'L');

                $pdf->SetXY(3,16.5);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,'Profr. Marco Aurelio Carbajal Leyva',0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Presidente del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,utf8_decode('Profr. Jesús Sotelo Sotelo'),0,0, 'C');
                $pdf->cell(15.59,0.5,utf8_decode('Profra. Cleotilde Castillo Méndez'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Secretario del Consejo Directivo',0,0, 'C');
                $pdf->cell(15.59,0.5,'Secretaria del Consejo Directivo',0,0, 'C');

                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Horacio López Salinas'),0,0, 'C');
                $pdf->cell(7.795,0.5,utf8_decode('Profr. Santiago Hernández Garduño'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                $pdf->cell(7.795,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $pdf->Ln(1);

                $pdf->SetFont('Arial','B',10);
                $pdf->cell(15.59,0.5,utf8_decode('Profr. José Merced Salinas Villa'),0,0, 'C');
                $pdf->Ln(0.4);
                $pdf->SetFont('Arial','B',9);
                $pdf->cell(15.59,0.5,'Vocal del Consejo Directivo',0,0, 'C');
                
                $numbeneficiarios = count($resultsBenefs);

                switch ($numbeneficiarios) {
                    case '7':
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.5);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.5);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->AddPage();
                        $pdf->Image('/var/www/html/sistge/img/escudosmsem.png',3,2,2,2.5);
                        $pdf->Ln(3);
                        $pdf->cell(2.3,0.5,utf8_decode('C. Profr. (a)'),0,0, 'L');
                        $pdf->SetFont('Arial','B',11.5);
                        $pdf->cell(13.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
                        $pdf->Ln(1);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
                        $pdf->cell(1.8);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);   
                        break;

                    case '8':
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.5);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.5);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->AddPage();
                        $pdf->Image('/var/www/html/sistge/img/escudosmsem.png',3,2,2,2.5);
                        $pdf->Ln(3);
                        $pdf->cell(2.3,0.5,utf8_decode('C. Profr. (a)'),0,0, 'L');
                        $pdf->SetFont('Arial','B',11.5);
                        $pdf->cell(13.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
                        $pdf->Ln(1);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
                        $pdf->Ln(2.2);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[7]["nombenef"]),0,0, 'C');
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);  
                        break;

                    case '9':
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.5);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.5);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->AddPage();
                        $pdf->Image('/var/www/html/sistge/img/escudosmsem.png',3,2,2,2.5);
                        $pdf->Ln(3);
                        $pdf->cell(2.3,0.5,utf8_decode('C. Profr. (a)'),0,0, 'L');
                        $pdf->SetFont('Arial','B',11.5);
                        $pdf->cell(13.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
                        $pdf->Ln(1);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
                        $pdf->Ln(2.2);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[7]["nombenef"]),0,0, 'C');  
                        $pdf->Ln(1.8);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[8]["nombenef"]),0,0, 'C');   
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        break;

                    case '10':
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.5);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.5);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->AddPage();
                        $pdf->Image('/var/www/html/sistge/img/escudosmsem.png',3,2,2,2.5);
                        $pdf->Ln(3);
                        $pdf->cell(2.3,0.5,utf8_decode('C. Profr. (a)'),0,0, 'L');
                        $pdf->SetFont('Arial','B',11.5);
                        $pdf->cell(13.59,0.5,utf8_decode($regTraAct["nomcommae"]),0,0, 'C');
                        $pdf->Ln(1);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
                        $pdf->Ln(2.2);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');                     
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[7]["nombenef"]),0,0, 'C');  
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[8]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[9]["nombenef"]),0,0, 'C'); 
                        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
                        break;
                    
                    case '11':
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
    
                        $pdf->AddPage();
                        $pdf->Ln(2.2);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[7]["nombenef"]),0,0, 'C');  
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[8]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[9]["nombenef"]),0,0, 'C'); 
                        $pdf->Ln(1.8);
                        $pdf->cell(15.59,0.5,utf8_decode($resultsBenefs[10]["nombenef"]),0,0, 'C');  
                        break;
                    
                    case '12':
                        $pdf->Ln(0.7);
                            
                        $pdf->SetFont('Arial','B',10);
                        $pdf->cell(15.59,0.5,'Beneficiario (s)',0,0, 'C');
    
                        $pdf->Ln(1.8);
                        $pdf->SetFont('Arial','',10);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[0]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[1]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[2]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[3]["nombenef"]),0,0, 'C');
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[4]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[5]["nombenef"]),0,0, 'C');
    
                        $pdf->AddPage();
                        $pdf->Ln(2.2);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[6]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[7]["nombenef"]),0,0, 'C');  
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[8]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[9]["nombenef"]),0,0, 'C'); 
                        $pdf->Ln(1.8);
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[10]["nombenef"]),0,0, 'C');
                        $pdf->cell(7.795,0.5,utf8_decode($resultsBenefs[11]["nombenef"]),0,0, 'C');
                        break;
                    default:
                        break;

                }
            }
        }
      
        $numTramite++;
        $pdf->AddPage();

        $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',16.59,24.44,2,2);
    }
    
    $pdf->Output();
?>
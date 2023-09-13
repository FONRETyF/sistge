<?php

    require '/var/www/html/sistge/vendor/autoload.php'; //'vendor/autoload.php';
    require '/var/www/html/sistge/config/dbfonretyf.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\IOFactory;

    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    $notacionEtr=[
        1=>"PRIMER", 2=>"SEGUNDA", 3=>"TERCER", 4=>"CUARTA", 5=>"QUINTA", 6=>"SEXTA", 7=>"SEPTIMA", 8=>"OCTAVA", 9=>"NOVENA", 10=>"DECIMA",
        11=>"ONCEABA", 12=>"DECIMO SEGUNDA", 13=>"DECIMO TERCERA", 14=>"DECIMO CUARTA", 15=>"DECIMO QUINTA", 16=>"DECIMO SEXTA", 17=>"DECIMO SEPTIMA",
        18=>"DECIMO OCTAVA", 19=>"DECIMO NOVENA", 20=>"VIGESIMA", 21=>"VIGESIMO PRIMER", 22=>"VIGESIMO SEGUNDA", 23=>"VIGESIMO TERCER"
    ];
    $meses=[1=>"ENERO", 2=>"FEBRERO", 3=>"MARZO", 4=>"ABRIL", 5=>"MAYO", 6=>"JUNIO", 7=>"JULIO", 8=>"AGOSTO", 9=>"SEPTIEMBRE", 10=>"OCTUBRE", 11=>"NOVIEMBRE", 12=>"DICIEMBRE"];

    $identrega = $_GET['identr'];
    $numentrega=intval(substr($identrega,4,2));

    $activeWorksheet->setTitle("Hoja1");
    $activeWorksheet->getColumnDimension('A')->setWidth(30,'pt');
    $activeWorksheet->getColumnDimension('B')->setWidth(300,'pt');
    $activeWorksheet->getColumnDimension('C')->setWidth(300,'pt');
    $activeWorksheet->getColumnDimension('D')->setWidth(95,'pt');

    $activeWorksheet->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
    $activeWorksheet->mergeCells('A2:D2')->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
    
    $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF - CONTABILIDAD";

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statement = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $fechaEntrega = $results[0]["fechentrega"];
    //echo(strlen($fechaEntrega)) --> longitud 10 conformato 2022-01-01
    
    $fechaEntrega = '';//substr($fechaEntrega,8,2) . " DE " . $meses[intval(substr($fechaEntrega,5,2))] . " DE " . substr($fechaEntrega,0,4);

    $activeWorksheet->setCellValue('A3',$fechaEntrega);
    $activeWorksheet->mergeCells('A3:D3')->getStyle('A3:D3')->getAlignment()->setHorizontal('center');

    $activeWorksheet->getStyle('A5:D5')->getAlignment()->setHorizontal('center');
    $activeWorksheet->setCellValue('A5','NP');
    $activeWorksheet->setCellValue('B5','NOMBRE MAESTRO');
    $activeWorksheet->setCellValue('C5','NOMBRE BENEFICIARIO');
    $activeWorksheet->setCellValue('D5','CONCEPTO');

    /* INHABILITADOS */
    $consultacheques = "select DISTINCT tab1.identret,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc, nombenef asc;";

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
    $consultacheques = "select DISTINCT tab1.identret,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc, nombenef asc;";

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
    $consultacheques = "select distinct tab1.identret, tab1.cvemae, tab1.motvret, tab3.nomcommae from public.tramites_fonretyf as tab1 left join (";
    $consultacheques = $consultacheques . "select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
    $consultacheques = $consultacheques . " where tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') and (tab1.modretiro='C' or tab1.modretiro='D50') and tiptramne='0' order by nomcommae asc;";

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
                
                $consultachequesB = "select DISTINCT tab1.identret,tab3.nomcommae,tab1.motvret,tab2.nombenef from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
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
                                //break;
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
    
    $idcheque = 1;
    $numregExcel = 6;
    foreach ($arregloMaestrosCheques as $rowTB) {
        $activeWorksheet->setCellValue('A'. $numregExcel,$idcheque);
        $activeWorksheet->setCellValue('B'. $numregExcel,$rowTB["nomcommae"]);
        $activeWorksheet->setCellValue('C'. $numregExcel,$rowTB["nombenef"]);
        switch ($rowTB["motvret"]) {
            case 'I':
                $descmotivo = "INHABILITACION";
                break;
            case 'J':
                $descmotivo = "JUBILACION";
                break;
            case 'FA':
                $descmotivo = "FALLECIMIENTO";
                break;
            case 'FJ':
                $descmotivo = "FALLECIMIENTO";
                break;
            default:
                # code...
                break;
        }
        $activeWorksheet->setCellValue('D'. $numregExcel,$descmotivo);

        $idcheque++;
        $numregExcel++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=".$nombreArchivo .".xls");
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save('php://output');

    try {
        //$writer->save('hello world.xlsx');
        $writer = new Xls($spreadsheet);
        exit;
    } catch (\Throwable $th) {
        echo("hubo un error");
    }

?>
<?php

    require '/var/www/html/sistge/vendor/autoload.php'; 
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
    $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF - INFORMATICA";
    

    $activeWorksheet->setTitle("FONRETyF");
    $activeWorksheet->getColumnDimension('A')->setWidth(30,'pt');
    $activeWorksheet->getColumnDimension('B')->setWidth(63,'pt');
    $activeWorksheet->getColumnDimension('C')->setWidth(300,'pt');
    $activeWorksheet->getColumnDimension('D')->setWidth(95,'pt');
    $activeWorksheet->getColumnDimension('E')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('F')->setWidth(420,'pt');
    $activeWorksheet->getColumnDimension('G')->setWidth(120,'pt');

    $activeWorksheet->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
    $activeWorksheet->mergeCells('A2:F2')->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
    
    

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statement = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $fechaEntrega = $results[0]["fechentrega"];
    //echo(strlen($fechaEntrega)) --> longitud 10 conformato 2022-01-01
    
    $fechaEntrega = substr($fechaEntrega,8,2) . " DE " . $meses[intval(substr($fechaEntrega,5,2))] . " DE " . substr($fechaEntrega,0,4);

    $activeWorksheet->setCellValue('A3',$fechaEntrega);
    $activeWorksheet->mergeCells('A3:F3')->getStyle('A3:F3')->getAlignment()->setHorizontal('center');

    $activeWorksheet->getStyle('A5:F5')->getAlignment()->setHorizontal('center');
    $activeWorksheet->setCellValue('A5','NP');
    $activeWorksheet->setCellValue('B5','No. CHEQUE');
    $activeWorksheet->setCellValue('C5','NOMBRE');
    $activeWorksheet->setCellValue('D5','CONCEPTO');
    $activeWorksheet->setCellValue('E5','MONTO');
    $activeWorksheet->setCellValue('F5','MONTO CON LETRA');
    $activeWorksheet->setCellValue('G5','CHEQUE NO NEGOCIABLE');

    $A_Cheques_Informatica = array();
    $aux = array();

    /* INHABILITADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab1.modretiro,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo,tab2.adeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER  BY nombenef ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsCheques as $key => $row) {
        $aux[$key]=$row["nombenef"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($resultsCheques as $key => $row1) {
            if ($row === $row1['nombenef']) {
                array_push($A_Cheques_Informatica, $row1);
                break;
            } 
        }    
    }

    /* JUBILADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab1.modretiro,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo,tab2.adeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER  BY nombenef ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsCheques as $key => $row) {
        $aux[$key]=$row["nombenef"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($resultsCheques as $key => $row1) {
            if ($row === $row1['nombenef']) {
                array_push($A_Cheques_Informatica, $row1);
                break;
            } 
        }    
    }

    /* FALLECIMIENTOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab1.modretiro,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo,tab2.adeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') and (tab1.modretiro='C' or tab1.modretiro='D50') and tab2.statedad = 'M' and tab2.chequeadeudo = 'N' ORDER  BY nombenef ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsCheques as $key => $row) {
        $aux[$key]=$row["nombenef"];
    }

    $collator = collator_create("es");
    $collator->sort($aux);

    foreach ($aux as $row) {
        foreach ($resultsCheques as $key => $row1) {
            if ($row === $row1['nombenef']) {
                array_push($A_Cheques_Informatica, $row1);
                break;
            } 
        }    
    }

    /* MENORES DE EDAD */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab1.modretiro,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo,tab2.adeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab2.statedad = 'N' ORDER  BY nombenef ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultsCheques)) {
        foreach ($resultsCheques as $key => $row) {
            $aux[$key]=$row["nombenef"];
        }
    
        $collator = collator_create("es");
        $collator->sort($aux);
    
        foreach ($aux as $row) {
            foreach ($resultsCheques as $key => $row1) {
                if ($row === $row1['nombenef']) {
                    array_push($A_Cheques_Informatica, $row1);
                    break;
                } 
            }    
        }
    }
    

    /* ADEUDOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab1.modretiro,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo,tab2.adeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab2.chequeadeudo = 'S' ORDER  BY nombenef ASC;";
    
    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($resultsCheques)) {
        foreach ($resultsCheques as $key => $row) {
            $aux1[$key]=$row["nombenef"];
        }

        $collator = collator_create("es");
        $collator->sort($aux1);

        foreach ($aux1 as $row) {
            foreach ($resultsCheques as $key => $row1) {
                if ($row === $row1['nombenef']) {
                    array_push($A_Cheques_Informatica, $row1);
                    break;
                } 
            }    
        }
    }    

    $idcheque = 1;
    $numregExcel = 6;
    foreach ($A_Cheques_Informatica as $row) {
        $activeWorksheet->setCellValue('A'. $numregExcel,$idcheque);
        $activeWorksheet->setCellValue('B'. $numregExcel,$row["folcheque"]);
        $activeWorksheet->setCellValue('C'. $numregExcel,$row["nombenef"]);
        switch ($row["motvret"]) {
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
        $activeWorksheet->setCellValue('E'. $numregExcel,$row["montbenef"]);
        $activeWorksheet->setCellValue('F'. $numregExcel,$row["montbenefletra"]);

        if ($row["statedad"] === "N") {
            $activeWorksheet->setCellValue('G'. $numregExcel,"MENOR");
        }
        if ($row["chequeadeudo"] === "S") {
            $activeWorksheet->setCellValue('G'. $numregExcel,"ADEUDO ".$row["adeudo"]);
        }

        $idcheque++;
        $numregExcel++;
    }
    
    $consultaSum = "SELECT SUM (montbenef) as monttotentrega FROM public.beneficiarios_cheques WHERE anioentrega=".intval(substr($identrega,0,4))." AND numentrega=".intval(substr($identrega,4,2)).";";
    $statementSum = $db->prepare($consultaSum);
    $statementSum->execute();
    $resultsSum = $statementSum->fetchAll(PDO::FETCH_ASSOC);
    $activeWorksheet->setCellValue('E'. $numregExcel,$resultsSum[0]["monttotentrega"]);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=".$nombreArchivo .".xls");
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save('php://output');

    try {
        $writer = new Xls($spreadsheet);
        exit;
    } catch (\Throwable $th) {
        echo("ERROR NO SE GENERO EL ARCHIVO");
    }
    

?>
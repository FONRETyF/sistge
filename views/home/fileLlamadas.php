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

    $activeWorksheet->setTitle("FONRETyF");
    $activeWorksheet->getColumnDimension('A')->setWidth(30,'pt');
    $activeWorksheet->getColumnDimension('B')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('C')->setWidth(100,'pt');
    $activeWorksheet->getColumnDimension('D')->setWidth(280,'pt');
    $activeWorksheet->getColumnDimension('E')->setWidth(280,'pt');
    $activeWorksheet->getColumnDimension('F')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('G')->setWidth(80,'pt');

    $activeWorksheet->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
    $activeWorksheet->mergeCells('A2:G2')->getStyle('A2:G2')->getAlignment()->setHorizontal('center');

    $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF - llamadas";

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statement = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $fechaEntrega = $results[0]["fechentrega"];

    $activeWorksheet->mergeCells('A3:G3')->getStyle('A3:G3')->getAlignment()->setHorizontal('center');

    $activeWorksheet->getStyle('A5:G5')->getAlignment()->setHorizontal('center');
    $activeWorksheet->setCellValue('A5','NP');
    $activeWorksheet->setCellValue('B5','CLAVE');
    $activeWorksheet->setCellValue('C5','MOTIVO');
    $activeWorksheet->setCellValue('D5','NOMBRE MAESTRO');
    $activeWorksheet->setCellValue('E5','NOMBRE BENEFICIARIO');
    $activeWorksheet->setCellValue('F5','TELEFONO CELULAR');
    $activeWorksheet->setCellValue('G5','TELEFONO PART');

    $A_Cheques_Informatica = array();
    $aux = array();
    $aux1 = array();

    /* INHABILITADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae,tab2.nombenef,tab3.numcelmae,tab3.numfijmae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER  BY nombenef ASC;";

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
                array_push($A_Cheques_Informatica, $row1);
                break;
            } 
        }    
    }

    /* JUBILADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae,tab2.nombenef,tab3.numcelmae,tab3.numfijmae FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER  BY nombenef ASC;";

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
                array_push($A_Cheques_Informatica, $row1);
                break;
            } 
        }    
    }

    /* FALLECIMIENTOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae,tab3.numcelmae,tab3.numfijmae FROM public.tramites_fonretyf as tab1 ";
    $consultacheques = $consultacheques . " LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae FROM public.tramites_fonretyf as tab1,";
    $consultacheques = $consultacheques . " public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER  BY nomcommae ASC;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsChequesF = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultsChequesF as $key => $row) {
        
        $auxF[$key]=$row["nomcommae"];
    }

    $collator = collator_create("es");
    $collator->sort($auxF);

    foreach ($auxF as $key => $row) {
        foreach ($resultsChequesF as $key => $rowF) {
            if ($row === $rowF['nomcommae']) {
                $clave= $rowF['cvemae'];
                
                $consultachequesB = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab3.nomcommae,tab2.nombenef,tab3.numcelmae,tab3.numfijmae from public.tramites_fonretyf as tab1 left join public.beneficiarios_cheques as tab2 on tab1.identret = tab2.identret left join (";
                $consultachequesB = $consultachequesB . "select tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae,tab2.numcelmae,tab2.numfijmae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
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
                                array_push($A_Cheques_Informatica, $rowBenef);
                                //break;
                            } 
                        }    
                    }
                }else {
           
                    array_push($A_Cheques_Informatica, $resultsChequesB[0]);
                }
                break;
            }
        }  
    }

    
    $idcheque = 1;
    $numregExcel = 6;
    foreach ($A_Cheques_Informatica as $row) {
        $activeWorksheet->setCellValue('A'. $numregExcel,$idcheque);
        $activeWorksheet->setCellValue('B'. $numregExcel,$row["cvemae"]);
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
        $activeWorksheet->setCellValue('C'. $numregExcel,$descmotivo);
        $activeWorksheet->setCellValue('D'. $numregExcel,$row["nomcommae"]);
        $activeWorksheet->setCellValue('E'. $numregExcel,$row["nombenef"]);
        $activeWorksheet->setCellValue('F'. $numregExcel,$row["numcelmae"]);
        $activeWorksheet->setCellValue('G'. $numregExcel,$row["numfijmae"]);

        $idcheque++;
        $numregExcel++;
    }

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
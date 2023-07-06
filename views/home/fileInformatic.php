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
    $activeWorksheet->getColumnDimension('B')->setWidth(63,'pt');
    $activeWorksheet->getColumnDimension('C')->setWidth(300,'pt');
    $activeWorksheet->getColumnDimension('D')->setWidth(95,'pt');
    $activeWorksheet->getColumnDimension('E')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('F')->setWidth(420,'pt');

    $activeWorksheet->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
    $activeWorksheet->mergeCells('A2:F2')->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
    
    $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF - INFORMATICA";

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
    $activeWorksheet->setCellValue('G5','REGION');

    $consultacheques = "SELECT tab1.folcheque,tab1.nombenef,tab2.motvret,tab1.montbenef,tab1.montbenefletra,tab3.regescmae FROM public.beneficiarios_cheques as tab1";
    $consultacheques = $consultacheques . " LEFT JOIN public.tramites_fonretyf as tab2 ON tab1.cvemae = tab2.cvemae LEFT JOIN public.maestros_smsem as tab3 ON";
    $consultacheques = $consultacheques . " tab1.cvemae = tab3.csp WHERE tab1.anioentrega =".intval(substr($identrega,0,4))." AND tab1.numentrega =".intval(substr($identrega,4,2))." ORDER BY folcheque;";

    $statementCheques = $db->prepare($consultacheques);
    $statementCheques->execute();
    $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

    $idcheque = 1;
    $numregExcel = 6;
    foreach ($resultsCheques as $row) {
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
        $activeWorksheet->setCellValue('G'. $numregExcel,$row["regescmae"]);

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
        //$writer->save('hello world.xlsx');
        $writer = new Xls($spreadsheet);
        exit;
    } catch (\Throwable $th) {
        echo("hubiou un error");
    }

?>
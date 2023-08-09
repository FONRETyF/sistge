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
    $activeWorksheet->getColumnDimension('A')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('B')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('C')->setWidth(250,'pt');
    $activeWorksheet->getColumnDimension('D')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('E')->setWidth(70,'pt');
    $activeWorksheet->getColumnDimension('F')->setWidth(70,'pt');
    $activeWorksheet->getColumnDimension('G')->setWidth(250,'pt');
    $activeWorksheet->getColumnDimension('H')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('I')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('J')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('K')->setWidth(25,'pt');
    $activeWorksheet->getColumnDimension('L')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('M')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('N')->setWidth(80,'pt');
    $activeWorksheet->getColumnDimension('O')->setWidth(80,'pt');
    
    $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF - revision";

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $activeWorksheet->getStyle('A1:O1')->getAlignment()->setHorizontal('center');
    $activeWorksheet->setCellValue('A1','NP');
    $activeWorksheet->setCellValue('B1','CLAVE');
    $activeWorksheet->setCellValue('C1','NOMBRE MAESTRO');
    $activeWorksheet->setCellValue('D1','MOTV');
    $activeWorksheet->setCellValue('E1','NUM CEL');
    $activeWorksheet->setCellValue('F1','NUM PART');
    $activeWorksheet->setCellValue('G1','NOMBRE BENEF');
    $activeWorksheet->setCellValue('H1','%');
    $activeWorksheet->setCellValue('I1','PAR');
    $activeWorksheet->setCellValue('J1','ED');
    $activeWorksheet->setCellValue('K1','VID');
    $activeWorksheet->setCellValue('L1','BASE');
    $activeWorksheet->setCellValue('M1','BAJA');
    $activeWorksheet->setCellValue('N1','INI PSGS');
    $activeWorksheet->setCellValue('O1','FIN PSGS');


    $A_benefs_revision = array();
    $aux = array();
    $aux1 = array();

    /* INHABILITADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.montrettot,tab2.idbenef,tab2.cvemae,tab2.nombenef,tab2.montbenef,tab2.edadbenef,tab2.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef,tab3.fcbasemae,tab3.fbajamae,tab3.fechsinipsgs,tab3.fechsfinpsgs,tab3.diaspsgs FROM public.tramites_fonretyf as tab1 LEFT JOIN (SELECT tab1.identret,tab1.idbenef,tab1.cvemae,tab1.idbenefcheque,tab1.nombenef,tab1.montbenef,tab1.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef FROM public.beneficiarios_cheques as tab1";
    $consultacheques = $consultacheques . " LEFT JOIN public.beneficiarios_maestros as tab2 on tab1.cvemae = tab2.cvemae and tab1.idbenefcheque=tab2.idbenefcheque and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab2 on tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.*, tab2.fechsinipsgs,tab2.fechsfinpsgs,tab2.diaspsgs  FROM (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2));
    $consultacheques = $consultacheques . " UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab1 LEFT JOIN public.maestros_smsem as tab2 on tab1.cvemae=tab2.csp) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='I' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER BY nomcommae ASC;";

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
                array_push($A_benefs_revision, $row1);
                break;
            } 
        }    
    }

    /* JUBILADOS */
    $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.montrettot,tab2.idbenef,tab2.cvemae,tab2.nombenef,tab2.montbenef,tab2.edadbenef,tab2.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef,tab3.fcbasemae,tab3.fbajamae,tab3.fechsinipsgs,tab3.fechsfinpsgs,tab3.diaspsgs FROM public.tramites_fonretyf as tab1 LEFT JOIN (SELECT tab1.identret,tab1.idbenef,tab1.cvemae,tab1.idbenefcheque,tab1.nombenef,tab1.montbenef,tab1.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef FROM public.beneficiarios_cheques as tab1";
    $consultacheques = $consultacheques . " LEFT JOIN public.beneficiarios_maestros as tab2 on tab1.cvemae = tab2.cvemae and tab1.idbenefcheque=tab2.idbenefcheque and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab2 on tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.*, tab2.fechsinipsgs,tab2.fechsfinpsgs,tab2.diaspsgs  FROM (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2));
    $consultacheques = $consultacheques . " UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab1 LEFT JOIN public.maestros_smsem as tab2 on tab1.cvemae=tab2.csp) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.motvret='J' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER BY nomcommae ASC;";

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
                array_push($A_benefs_revision, $row1);
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
                
                $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret,tab1.numpartsolic,tab1.numcelsolic,tab1.montrettot,tab2.idbenef,tab2.cvemae,tab2.nombenef,tab2.montbenef,tab2.edadbenef,tab2.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef,tab3.fcbasemae,tab3.fbajamae,tab3.fechsinipsgs,tab3.fechsfinpsgs,tab3.diaspsgs FROM public.tramites_fonretyf as tab1 LEFT JOIN (SELECT tab1.identret,tab1.idbenef,tab1.cvemae,tab1.idbenefcheque,tab1.nombenef,tab1.montbenef,tab1.porcretbenef,tab2.parentbenef,tab2.edadbenef,tab2.vidabenef FROM public.beneficiarios_cheques as tab1";
                $consultacheques = $consultacheques . " LEFT JOIN public.beneficiarios_maestros as tab2 on tab1.cvemae = tab2.cvemae and tab1.idbenefcheque=tab2.idbenefcheque and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab2 on tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.*, tab2.fechsinipsgs,tab2.fechsfinpsgs,tab2.diaspsgs  FROM (SELECT tab1.cvemae,tab2.nomcommae,tab2.fcbasemae,tab2.fbajamae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 where tab1.cvemae = tab2.csp and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2));
                $consultacheques = $consultacheques . " UNION SELECT tab1.cvemae,tab2.nomcommae,tab2.fechbajamae,tab2.fcfallecmae FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym and tab1.anioentrega=".substr($identrega,0,4)." and tab1.numentrega=".intval(substr($identrega,4,2)).") as tab1 LEFT JOIN public.maestros_smsem as tab2 on tab1.cvemae=tab2.csp) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and tab1.cvemae='".$clave."' ORDER BY nombenef ASC;";

                $statementChequesB = $db->prepare($consultacheques);
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
                                array_push($A_benefs_revision, $rowBenef);
                                //break;
                            } 
                        }    
                    }
                }else {
        
                    array_push($A_benefs_revision, $resultsChequesB[0]);
                }
                break;
            }
        }  
    }


    $idcheque = 1;
    $numregExcel = 2;
    foreach ($A_benefs_revision as $row) {
        $activeWorksheet->setCellValue('A'. $numregExcel,$idcheque);
        $activeWorksheet->setCellValue('B'. $numregExcel,$row["cvemae"]);
        $activeWorksheet->setCellValue('C'. $numregExcel,$row["nomcommae"]);
        $activeWorksheet->setCellValue('D'. $numregExcel,$row["motvret"]);
        $activeWorksheet->setCellValue('E'. $numregExcel,$row["numcelsolic"]);
        $activeWorksheet->setCellValue('F'. $numregExcel,$row["numpartsolic"]);
        $activeWorksheet->setCellValue('G'. $numregExcel,$row["nombenef"]);
        $activeWorksheet->setCellValue('H'. $numregExcel,$row["porcretbenef"]);
        $activeWorksheet->setCellValue('I'. $numregExcel,$row["parentbenef"]);
        $activeWorksheet->setCellValue('J'. $numregExcel,$row["edadbenef"]);
        $activeWorksheet->setCellValue('K'. $numregExcel,$row["vidabenef"]);
        $activeWorksheet->setCellValue('L'. $numregExcel,$row["fcbasemae"]);
        $activeWorksheet->setCellValue('M'. $numregExcel,$row["fbajamae"]);
        $activeWorksheet->setCellValue('N'. $numregExcel,$row["fechsinipsgs"]);
        $activeWorksheet->setCellValue('O'. $numregExcel,$row["fechsfinpsgs"]);
        $activeWorksheet->setCellValue('P'. $numregExcel,$row["montbenef"]);
        $activeWorksheet->setCellValue('Q'. $numregExcel,$row["diaspsgs"]);
        
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
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
        $activeWorksheet->getColumnDimension('B')->setWidth(70,'pt');
        $activeWorksheet->getColumnDimension('C')->setWidth(300,'pt');
        $activeWorksheet->getColumnDimension('D')->setWidth(95,'pt');

        $activeWorksheet->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
        $activeWorksheet->mergeCells('A2:D2')->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
        
        $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF";

        $pdo = new dbfonretyf();
        $db=$pdo->conexfonretyf();

        $statement = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fechaEntrega = $results[0]["fechentrega"];
        
        $fechaEntrega = substr($fechaEntrega,8,2) . " DE " . $meses[intval(substr($fechaEntrega,5,2))] . " DE " . substr($fechaEntrega,0,4);

        $activeWorksheet->setCellValue('A3',$fechaEntrega);
        $activeWorksheet->mergeCells('A3:D3')->getStyle('A3:D3')->getAlignment()->setHorizontal('center');

        $activeWorksheet->getStyle('A5:D5')->getAlignment()->setHorizontal('center');
        $activeWorksheet->setCellValue('A5','NP');
        $activeWorksheet->setCellValue('B5','CSP');
        $activeWorksheet->setCellValue('C5','NOMBRE MAESTRO');
        $activeWorksheet->setCellValue('D5','CONCEPTO');

        $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret FROM public.tramites_fonretyf as tab1 left join (Select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2";
        $consultacheques = $consultacheques . " WHERE tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
        $consultacheques = $consultacheques . " WHERE identrega='".$identrega."' and (tab1.modretiro='C' or tab1.modretiro='D50') order by case when motvret='I' then 1 when motvret='J' then 2  when (motvret='FA' or motvret='FJ') then 3 end asc, nomcommae asc;";

        $statementCheques = $db->prepare($consultacheques);
        $statementCheques->execute();
        $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

        $idcheque = 1;
        $numregExcel = 6;
        foreach ($resultsCheques as $row) {
            $activeWorksheet->setCellValue('A'. $numregExcel,$idcheque);
            $activeWorksheet->setCellValue('B'. $numregExcel,$row["cvemae"]);
            $activeWorksheet->setCellValue('C'. $numregExcel,$row["nomcommae"]);
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

            $idcheque++;
            $numregExcel++;
        }
       
        $hoja2 = $spreadsheet->createSheet();
        $hoja2->setTitle("FONRETyF-FondPension");
        $hoja2->getColumnDimension('A')->setWidth(30,'pt');
        $hoja2->getColumnDimension('B')->setWidth(70,'pt');
        $hoja2->getColumnDimension('C')->setWidth(300,'pt');
        $hoja2->getColumnDimension('D')->setWidth(95,'pt');

        $hoja2->setCellValue('A2', $notacionEtr[$numentrega]." ENTREGA DEL FONDO DE RETIRO POR JUBILACION, INHABILITACION Y POR FALLECIMIENTO");
        $hoja2->mergeCells('A2:D2')->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
        
        $nombreArchivo = $notacionEtr[$numentrega]."_ENTREGA_FONRETyF";

        $pdo = new dbfonretyf();
        $db=$pdo->conexfonretyf();

        $statement = $db->prepare("SELECT fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identrega."'");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fechaEntrega = $results[0]["fechentrega"];
        
        $fechaEntrega = substr($fechaEntrega,8,2) . " DE " . $meses[intval(substr($fechaEntrega,5,2))] . " DE " . substr($fechaEntrega,0,4);

        $hoja2->setCellValue('A3',$fechaEntrega);
        $hoja2->mergeCells('A3:D3')->getStyle('A3:D3')->getAlignment()->setHorizontal('center');

        $hoja2->getStyle('A5:D5')->getAlignment()->setHorizontal('center');
        $hoja2->setCellValue('A5','NP');
        $hoja2->setCellValue('B5','CSP');
        $hoja2->setCellValue('C5','NOMBRE MAESTRO');
        $hoja2->setCellValue('D5','CONCEPTO');

        $consultacheques = "SELECT tab1.identret,tab1.cvemae,tab3.nomcommae,tab1.motvret FROM public.tramites_fonretyf as tab1 left join (Select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.maestros_smsem as tab2";
        $consultacheques = $consultacheques . " WHERE tab1.cvemae = tab2.csp union select tab1.cvemae,tab2.nomcommae from public.tramites_fonretyf as tab1, public.mutualidad as tab2 where tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae";
        $consultacheques = $consultacheques . " WHERE tab1.identrega='".$identrega."' and (tab1.motvret='FA' or tab1.motvret='FJ') order by nomcommae asc;";

        $statementCheques = $db->prepare($consultacheques);
        $statementCheques->execute();
        $resultsCheques = $statementCheques->fetchAll(PDO::FETCH_ASSOC);

        $idcheque = 1;
        $numregExcel = 6;
        foreach ($resultsCheques as $row) {
            $hoja2->setCellValue('A'. $numregExcel,$idcheque);
            $hoja2->setCellValue('B'. $numregExcel,$row["cvemae"]);
            $hoja2->setCellValue('C'. $numregExcel,$row["nomcommae"]);
            $descmotivo = "FALLECIMIENTO";
            $hoja2->setCellValue('D'. $numregExcel,$descmotivo);

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
            
        } catch (\Throwable $th) {
            echo("¡¡¡ALGO SALIO MAL!!!");
        }      
?>
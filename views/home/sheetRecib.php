<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');
    require("/var/www/html/sistge/model/cantidadLetras.php");

    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(1.5,1,1.5);

    $cantletra = new cantidadLetras();

    $meses=[1=>"enero", 2=>"febrero", 3=>"marzo", 4=>"abril", 5=>"mayo", 6=>"junio", 7=>"julio", 8=>"agosto", 9=>"septiembre", 10=>"octubre", 11=>"noviembre", 12=>"diciembre"];

    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $identretiro = $_GET['identret'];

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statementT = $db->prepare("SELECT * FROM public.tramites_fonretyf WHERE identret='".$identretiro."'");
    $statementT->execute();
    $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);

    $motivoRet = $resultsT[0]['motvret'];

    $montototal = str_replace("$","",(str_replace(",", "", $resultsT[0]['montrettot'])));
    $montoAdeudos = str_replace("$","",(str_replace(",", "", $resultsT[0]['montadeudos'])));
    $retsinadeudos = $montototal - $montoAdeudos; 
    $montoretsinAdeudos =  number_format($retsinadeudos,2);
                                    
    $montSinAdeudosLetra = $cantletra->cantidadLetras($retsinadeudos);

    if ($motivoRet == "FRF" || $motivoRet == "FFJ" || $motivoRet == "FMJ") {
        $motivoRetiroLower = "Fallecimiento";
        $motivoRetiro = "FALLECIMIENTO";
    }

    if ($motivoRet == "FFJ" || $motivoRet == "FMJ") {
        $statementMJ = $db->prepare("SELECT programfallec FROM public.jubilados_smsem WHERE cveissemym='".$resultsT[0]['cvemae']."'");
        $statementMJ->execute();
        $resultsMJ = $statementMJ->fetchAll(PDO::FETCH_ASSOC);

        if ($resultsMJ[0]["programfallec"] == "M") {
            $statementMJub = $db->prepare("SELECT cveissemym,nomcommae,curpmae,regmae,fechbajamae,fcfallecmae,fechafimutu,aniosjub FROM public.mutualidad WHERE cveissemym='".$resultsT[0]['cvemae']."'");
            $statementMJub->execute();
            $resultsMJub = $statementMJub->fetchAll(PDO::FETCH_ASSOC);
        } else {
            
        }
        
    } else {
        $statementM = $db->prepare("SELECT csp,nomcommae,curpmae,regescmae,fcbasemae,aservactmae,numpsgs,numcelmae,diaspsgs,fechsinipsgs,fechsfinpsgs FROM public.maestros_smsem WHERE csp='".$resultsT[0]['cvemae']."'");
        $statementM->execute();
        $resultsM = $statementM->fetchAll(PDO::FETCH_ASSOC);
    }
    
    $statementB = $db->prepare("SELECT tab1.*,tab2.montbenef FROM public.beneficiarios_maestros as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 ON tab1.idbenefcheque = tab2.idbenefcheque WHERE tab1.cvemae='".$resultsT[0]['cvemae']."' ORDER BY idbenef asc;");
    $statementB->execute();
    $resultsB = $statementB->fetchAll(PDO::FETCH_ASSOC);

    $llaves_array = array ("{", "}");
    $fechIniTemp=str_replace($llaves_array,"",$resultsM[0]["fechsinipsgs"]);
    $fechFinTemp=str_replace($llaves_array,"",$resultsM[0]["fechsfinpsgs"]);
   
    $fechasIni = explode("," , $fechIniTemp);
    $fechasFin = explode("," , $fechFinTemp);
    
    $fechasPSGS = '';
    $docTest = '';
    if ($resultsT[0]["docttestamnt"] == "CT") {
        $docTest = "la Carta Testamentaria";
    } elseif ($resultsT[0]["docttestamnt"] == 'J') {
        $docTest = "el Juicio";
    } elseif ($resultsT[0]["docttestamnt"] == 'SL') {
        $docTest = "la Sucesion Legítima";
    }
    

    for ($i=0; $i < count($fechasIni); $i++) { 
        $fechaI = explode(":" , $fechasIni[$i]);
        $fechaF = explode(":" , $fechasFin[$i]);
        if ($i == count($fechasIni) - 1 ) {
            $fechasPSGS =  $fechasPSGS . date("d-m-Y",strtotime($fechaI[1])) . " al " . date("d-m-Y",strtotime($fechaF[1]));
        }else {
            $fechasPSGS =  $fechasPSGS . date("d-m-Y",strtotime($fechaI[1])) . " al " . date("d-m-Y",strtotime($fechaF[1])) . " , ";
        }
    }
       

    if ($motivoRet == "FRF") {     
        $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,0.8,3.7,4.5);
        $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

        $pdf->AddFont('SegoeUIBL','','seguibl.php');
        $pdf->AddFont('arialbi','','arialbi.php');
        $pdf->SetFont('SegoeUIBL','',19);
                
        $pdf->Cell(2.7,1);
        $pdf->SetTextColor(43,43,43);
        $pdf->Cell(13.89, 0.5, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $pdf->Cell(2.7,0.2);
        $pdf->SetFont('SegoeUIBL','',19);
        $pdf->Cell(13.89, 0.7, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
                
        $pdf->Cell(2.7);
        $pdf->SetFont('arialbi','',12);
        $pdf->SetTextColor(43,43,43);
        $pdf->Cell(13.89, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $pdf->Ln(0.7);
        $pdf->Cell(2.7);
        $pdf->SetFont('Arial','B',12);
       
        $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
        $pdf->Ln(0.4);
        $pdf->Cell(2.7);
        $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $pdf->Ln(1.3);
        $pdf->Cell(2.7);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13.89, 0.5, utf8_decode('ACUERDO DE FONDO DE RETIRO POR '.$motivoRetiro), 0, 0, 'C');
        
        $pdf->Ln(0.9);
        $pdf->SetFont('Arial','B',11.5);
        $pdf->SetTextColor(15,83,183);
        $pdf->Cell(18.59, 0.5, "Folio:   ". $resultsT[0]['foliotramite'], 0, 0, 'R');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(0.8);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(18.7, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

        if ($resultsM[0]['numpsgs'] == 0) {
            if ($resultsM[0]['numadeds'] == 0) {
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.8,8.7,12.7,0.04);
                $pdf->Ln(2);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.2, 0.5, "Yo  C.", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(12.09, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(5.3, 0.5, "      con    numero     celular", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',1.5,9.2,3.3,0.04);
                $pdf->Cell(3.3, 0.5, utf8_decode( $resultsM[0]['numcelmae']), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(15.29, 0.5, ",    solicito    el   Fondo   de    Retiro     por     Fallecimiento    del  (la)    Profr (a).", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',1.5,9.7,10.59,0.04);
                $pdf->Cell(10.59, 0.5, utf8_decode($resultsM[0]['nomcommae']),0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(7, 0.5, "quien se encontraba en  activo, con Clave", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.8,10.2,2.8,0.04);
                $pdf->Cell(4.3, 0.5, utf8_decode("de   Servidor   Público"), 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(2.8, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.8, 0.5," y CURP  ", 0, 0, 'L');
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',10.4,10.2,5.2,0.04);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(5.2, 0.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(4.49, 0.5,utf8_decode("y laboraba en  la región"), 0, 0, 'L');

                $pdf->Ln(0.5);            
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.5,0.5, utf8_decode("sindical"), 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.05,10.7,1.0,0.04);
                $pdf->Cell(1, 0.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(6.2, 0.5,"como docente basificado (a) del", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(0.5, 0.5," al ", 0, 0, 'C');
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',10.2,10.7,2.8,0.04);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) , 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(3.79, 0.5,", periodo durante el", 0, 0, 'l');
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',13.5,10.7,2.8,0.04);

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.8, 0.5,"cual tuvo", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.3,11.2,1.1,0.04);
                $pdf->Cell(1.1, 0.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);   
                $pdf->Cell(12.9,0.5,utf8_decode("permisos sin  goce de sueldo, por lo  que se  realiza la  solicitud por"), 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',17.3,11.2,1.1,0.04);
                $pdf->Cell(1.09, 0.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.7,0.5,utf8_decode("años  de"),0, 0, 'L');

                $pdf->Ln(0.5);            
                $pdf->Cell(14.1, 0.5,utf8_decode("servicio cotizados al SMSEM, correspondiendole la candidad de:"), 0, 0, 'L');

                $pdf->Ln(0.65);
                $pdf->SetTextColor(15,83,183);
                $pdf->SetFont('Arial','B',11.5);
                $pdf->Cell(4, 0.5,"$".$montoretsinAdeudos,0, 0, 'R');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(13.9, 0.5,"(". $montSinAdeudosLetra .")",0, 0, 'L');
                
                $pdf->SetTextColor(0,0,0);
                $pdf->Ln(0.65);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(18.59, 0.5, utf8_decode("con fundamento en los artículos  30, 33, 34, 35, 36, 37, 38, 41, 43 y 44  del Reglamento del Fondo"), 0, 0, 'L');
                $pdf->Ln(0.5);
                $pdf->Cell(18.59, 0.5, utf8_decode("de Retiro y Fallecimiento (FONRETyF). Hago de su conocimiento que dicho monto sea  entregado"), 0, 0, 'L');
                $pdf->Ln(0.5);
                $pdf->Cell(3.5, 0.5,"como se indica en", 0, 0, 'L');
                $pdf->Cell(17.59, 0.5,$docTest.":", 0, 0, 'L');

                if (count($resultsB) < 7) {
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->SetX(4.5);
                    $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
                    $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
                    $pdf->cell(2,0.4,'Monto',0,0,'C');
                    $pdf->SetDrawColor(150,150,150);
                    $pdf->SetLineWidth(0.05);
                    $pdf->SetFillColor(200,200,200);
                    $pdf->SetDrawColor(100,100,100);
                    $pdf->Ln(0.35);

                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',8);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.4,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.4);
                    }

                    $pdf->SetY(17.3);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo, ratifico  que  los  datos  especificados  anteriormente  son  correctos  para el  calculo  y", 0,0, 'L');
                    $pdf->Ln(0.5);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');
                    
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial','',10);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');
                    
                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,22.9,10.5,0.04);
                    $pdf->SetY(22.9);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                    
                    $pdf->SetY(23);
                    $pdf->Ln(1.5);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.5,19.59,0.04);

                    $pdf->SetY(23.3);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                } elseif (count($resultsB) > 6 && count($resultsB) < 11) {
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->SetX(4.5);
                    $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
                    $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
                    $pdf->cell(2,0.4,'Monto',0,0,'C');
                    $pdf->SetDrawColor(150,150,150);
                    $pdf->SetLineWidth(0.05);
                    $pdf->SetFillColor(200,200,200);
                    $pdf->SetDrawColor(100,100,100);
                    $pdf->Ln(0.35);

                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',7.5);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.4,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.4);
                    }

                    $pdf->SetY(17.9);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo, ratifico  que  los  datos  especificados  anteriormente  son  correctos  para el  calculo  y", 0,0, 'L');
                    $pdf->Ln(0.5);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');
                    
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial','',10);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');
                    

                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.3,10.5,0.04);
                    $pdf->SetY(23.3);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                    
                    $pdf->SetY(23);
                    $pdf->Ln(1.5);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.5,19.59,0.04);

                    $pdf->SetY(23.3);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                }  elseif (count($resultsB) > 10) {
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->SetX(4.5);
                    $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
                    $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
                    $pdf->cell(2,0.4,'Monto',0,0,'C');
                    $pdf->SetDrawColor(150,150,150);
                    $pdf->SetLineWidth(0.05);
                    $pdf->SetFillColor(200,200,200);
                    $pdf->SetDrawColor(100,100,100);
                    $pdf->Ln(0.35);

                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',6.5);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(9,0.35,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(1.8,0.35,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.35,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.35);
                    }

                    $pdf->SetY(19.7);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo,  ratifico  que  los datos  especificados  anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
                    $pdf->Ln(0.6);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                    $pdf->Ln(0.7);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');
                    
                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.4,10.5,0.04);
                    $pdf->SetY(24.4);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
            
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.6,2.7,2.65);
                    
                    $pdf->SetY(24.05);
                    $pdf->Ln(1.5);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.4,19.59,0.04);

                    $pdf->SetY(24.2);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.4);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
                } 
            } else {
                # code...
            }      
        } 
        /*   ACUERDO POR FALLECIMEINTO ACTIVO CON PSGS   */
        else {
            if ($resultsM[0]['numadeds'] == 0) {
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.7,8.2,10.7,0.04);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.2, 0.5, "Yo C.", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(10.61, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(6.78, 0.5, ",  solicito   el  Fondo  de  Retiro  por", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',7.5,8.7,10.6,0.04);
                $pdf->Cell(6, 0.5, "Fallecimiento  del (la)  Profr (a).", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(10.61, 0.5, utf8_decode($resultsM[0]['nomcommae']), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(1.98, 0.5, " quien  se", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',14.55,9.2,3.2,0.04);
                $pdf->Cell(5.15, 0.5, "encontraba     en    activo,", 0, 0, 'L');
                $pdf->Cell(7.9, 0.5, utf8_decode("    con     Clave     de    Servidor    Público"), 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(3.2, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(2.34, 0.5,"   y    CURP", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',1.5,9.7,5.2,0.04);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(5.3, 0.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(6,0.5, utf8_decode("y laboraba en la región sindical"), 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',12.8,9.7,1.0,0.04);
                $pdf->Cell(1, 0.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(6.29, 0.5," como docente basificado (a)  del", 0, 0, 'L');
                
                $pdf->Ln(0.5);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',1.5,10.2,2.8,0.04);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(0.5, 0.5," al ", 0, 0, 'C');
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',4.8,10.2,2.8,0.04);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(5.8, 0.5,", periodo  durante  el cual tuvo", 0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',13.4,10.2,1,0.04);
                $pdf->Cell(1, 0.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(5.69,0.5,utf8_decode("permisos sin goce de sueldo:"),0, 0, 'L');

                $pdf->SetXY(2.3,10.5);
                $pdf->SetFont('Arial','',9);
                $pdf->SetDrawColor(150,150,150);
                $pdf->SetLineWidth(0.02);
                $pdf->MultiCell(17, 0.4, $fechasPSGS, 1, 'C');
                
                $pdf->Ln(1.2);
                $pdf->SetFont('Arial','',12);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',10.2,12.5,1.1,0.04);
                $pdf->Cell(8.7,0.5,utf8_decode(", por  lo   que   se   realiza   la   solicitud   por "),0, 0, 'L');
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(1.1, 0.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(8.79, 0.5,utf8_decode("  años   de   servicio   cotizados   al   SMSEM,"), 0, 0, 'L');
                $pdf->Ln(0.5);
                $pdf->Cell(18.59, 0.5,utf8_decode("correspondiendole la candidad de:"), 0, 0, 'L');

                $pdf->Ln(0.65);
                $pdf->SetTextColor(15,83,183);
                $pdf->SetFont('Arial','B',11.5);
                $pdf->Cell(4, 0.5,"$".$montoretsinAdeudos,0, 0, 'R');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(13.9, 0.5,"(". $montSinAdeudosLetra .")",0, 0, 'L');

                $pdf->Ln(0.65);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(18.59, 0.5, utf8_decode("con fundamento en los artículos 30, 33, 34, 35, 36, 37, 38, 41, 43 y 44 del Reglamento  del  Fondo"), 0, 0, 'L');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(18.59, 0.5, utf8_decode("de Retiro y Fallecimiento (FONRETyF). Hago de su conocimiento que dicho monto sea  entregado"), 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(3.5, 0.5,"como se indica en", 0, 0, 'L');
                $pdf->Cell(18.59, 0.5,$docTest.":", 0, 0, 'L');

                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',8);
                $pdf->SetX(4.5);
                $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
                $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
                $pdf->cell(2,0.4,'Monto',0,0,'C');
                $pdf->SetDrawColor(150,150,150);
                $pdf->SetLineWidth(0.05);
                $pdf->SetFillColor(200,200,200);
                $pdf->SetDrawColor(100,100,100);
                $pdf->Ln(0.4);

                if (count($resultsB) < 7) {
                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',7);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.35,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.4);
                    }

                    $pdf->SetY(18.5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo, ratifico  que  los  datos  especificados  anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
                    $pdf->Ln(0.5);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                    $pdf->Ln(1);
                    $pdf->SetFont('Arial','',10);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');
                    
                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.6,10.5,0.04);
                    $pdf->SetY(23.6);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
            
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                    
                    $pdf->SetY(23);
                    $pdf->Ln(1.7);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.5,19.59,0.04);

                    $pdf->SetY(23.3);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.5);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                } elseif (count($resultsB) > 6 && count($resultsB) < 11) {
                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',7.5);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.4,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.4);
                    }

                    $pdf->SetY(20);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo,  ratifico  que  los datos  especificados  anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
                    $pdf->Ln(0.5);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                    $pdf->Ln(0.8);
                    $pdf->SetFont('Arial','',10);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');
                    
                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.61,10.5,0.04);
                    $pdf->SetY(24.6);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
            
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.5,2.7,2.65);
                    
                    $pdf->SetY(23.5);
                    $pdf->Ln(1.95);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.25,19.59,0.04);

                    $pdf->SetY(24);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.4);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                    
                }  elseif (count($resultsB) > 10) {
                    foreach ($resultsB as $rowBenef) {
                        $pdf->SetX(4.5);
                        $pdf->SetFont('Arial','',6);
                        $pdf->SetLineWidth(0.01);
                        $pdf->cell(8,0.35,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                        $pdf->cell(3.3,0.35,$rowBenef["porcretbenef"],1,0,'C');
                        $pdf->cell(2,0.35,$rowBenef["montbenef"],1,0,'C');
                        $pdf->Ln(0.35);
                    }

                    $pdf->SetY(20.8);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(18.59, 0.5,"asi mismo,  ratifico  que  los  datos  especificados anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
                    $pdf->Ln(0.5);
                    $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                    $pdf->Ln(0.7);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(18.59, 0.4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');


                    $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,25,10.5,0.04);
                    $pdf->SetY(25);
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
            
                    $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.8,2.7,2.65);
                    
                    $pdf->SetY(23.8);
                    $pdf->Ln(1.95);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                
                    $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.65,19.59,0.04);

                    $pdf->SetY(24.4);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(18.5, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                    $pdf->Ln(0.35);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(18.5, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                    $pdf->Ln(0.3);
                    $pdf->Cell(18.5, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
                } 
            } else {
                # code...
            }   
        }

    /*   FORMATO DE ACUERDO PARA FONDO DE RETIRO POR MUTUALIDAD   */
    } elseif ($motivoRet == "FMJ") {
        $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,0.8,3.45,4.2);
        $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

        $pdf->AddFont('SegoeUIBL','','seguibl.php');
        $pdf->AddFont('arialbi','','arialbi.php');
        $pdf->SetFont('SegoeUIBL','',19);
                
        $pdf->SetY(1);
        $pdf->Cell(2.7);
        $pdf->SetTextColor(43,43,43);
        $pdf->Cell(13.89, 0.5, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $pdf->Cell(2.7,0.2);
        $pdf->SetFont('SegoeUIBL','',19);
        $pdf->Cell(13.89, 0.7, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
                
        $pdf->Cell(2.7);
        $pdf->SetFont('arialbi','',12);
        $pdf->SetTextColor(43,43,43);
        $pdf->Cell(13.89, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $pdf->Ln(0.7);
        $pdf->Cell(2.7);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
        $pdf->Ln(0.4);
        $pdf->Cell(2.7);
        $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $pdf->Ln(1.5);
        $pdf->Cell(18.59, 0.8, utf8_decode('ACUERDO DE FONDO DE RETIRO POR '.$motivoRetiro . '-MUTUALIDAD'),0, 0, 'C');
        
        $pdf->Ln(1);
        $pdf->SetFont('Arial','B',11.5);
        $pdf->SetTextColor(15,83,183);
        $pdf->Cell(18.59, 0.5, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(0.8);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(18.7, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.7,8.5,10.7,0.04);
        $pdf->Ln(1.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(1.2, 0.5, "Yo C.", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10.59, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(6.8, 0.5, ",  solicito  el   Fondo  de  Retiro  por", 0, 0, 'L');

        $pdf->Ln(0.5);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',7.4,9,10.6,0.04);
        $pdf->Cell(5.9, 0.5, "Fallecimiento  del (la) Profr (a).", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10.59, 0.5, utf8_decode($resultsMJub[0]['nomcommae']), 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(2.1, 0.5, "con  Clave", 0, 0, 'L');

        $pdf->Ln(0.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',4.4,9.5,2.8,0.04);
        $pdf->Cell(2.9, 0.5, utf8_decode("de  ISSEMYM"), 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(2.8, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(1.9, 0.5,",   CURP  ", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9.1,9.5,5.3,0.04);
        $pdf->Cell(5.3, 0.5,$resultsMJub[0]['curpmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(5.69, 0.5, utf8_decode(",  y   pertenecía  a   la   región"), 0, 0, 'L');

        $pdf->Ln(0.5);
        $pdf->Cell(1.6,0.5, utf8_decode("sindical"), 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.1,10,1.0,0.04);
        $pdf->Cell(1, 0.5,$resultsMJub[0]['regmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(8, 0.5," como maestro (a) jubilado (a) a partir del", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',12.1,10,3.75,0.04);
        $pdf->Cell(3.745, 0.5,$resultsMJub[0]['fechbajamae'],0 , 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0.5, 0.5," al",0 , 0, 'R');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',16.35,10,3.75,0.04);
        $pdf->Cell(3.745, 0.5,$resultsMJub[0]['fcfallecmae'],0 , 0, 'C');


        $pdf->Ln(0.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(18.59,0.5,utf8_decode(", por  lo  que  se  realiza  la  solicitud de  las  aportaciones  realizadas  al  programa de  Mutualidad"),0, 0, 'L');
        
        $pdf->Ln(0.5);
        $pdf->Cell(3.4, 0.5,utf8_decode("SMSEM  durante"), 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',4.9,11,1.1,0.04);
        $pdf->Cell(1.09, 0.5,$resultsMJub[0]['aniosjub'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(14.1, 0.5, utf8_decode(" años,  con   fundamento  en  los  artículos   30, 33, 34, 36, 38, 43 y 44  del"), 0, 0, 'L');        

        $pdf->Ln(0.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(17.59, 0.5, utf8_decode("Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

        $pdf->Ln(1);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(18.59, 0.5,"Hago de su  conocimiento que el equivalente al  Fondo de Retiro sea entregado como se indica en", 0, 0, 'L');
        
        $pdf->Ln(0.5);
        $pdf->Cell(18.59, 0.5,$docTest.":", 0, 0, 'L');

        if (count($resultsB) < 7) {

            $pdf->Ln(0.3);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(4.5);
            $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
            $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
            $pdf->cell(2,0.4,'Monto',0,0,'C');
            $pdf->SetDrawColor(150,150,150);
            $pdf->SetLineWidth(0.05);
            $pdf->SetFillColor(200,200,200);
            $pdf->SetDrawColor(100,100,100);
            $pdf->Ln(0.35);

            foreach ($resultsB as $rowBenef) {
                $pdf->SetX(4.5);
                $pdf->SetFont('Arial','',8);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->cell(2,0.4,$rowBenef["montbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            $pdf->SetY(16.9);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(18.59, 0.5,"asi mismo, ratifico  que  los  datos  especificados  anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23,10.5,0.04);
            $pdf->SetY(23);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                
            $pdf->SetY(22.8);
            $pdf->Ln(1.95);
            $pdf->SetFont('Arial','I',8);
            $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.5,19.59,0.04);

            $pdf->SetY(23.3);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
        
        } elseif (count($resultsB) > 6 && count($resultsB) < 11) {
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(4.5);
            $pdf->cell(9,0.4,'Beneficiario',0,0,'C');
            $pdf->cell(1.8,0.4,'Porcentaje',0,0,'C');
            $pdf->cell(2,0.4,'Monto',0,0,'C');
            $pdf->SetDrawColor(150,150,150);
            $pdf->SetLineWidth(0.05);
            $pdf->SetFillColor(200,200,200);
            $pdf->SetDrawColor(100,100,100);
            $pdf->Ln(0.35);

            foreach ($resultsB as $rowBenef) {
                $pdf->SetX(4.5);
                $pdf->SetFont('Arial','',7.5);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(9,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(1.8,0.4,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->cell(2,0.4,$rowBenef["montbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            $pdf->SetY(17.85);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(18.59, 0.5,"asi mismo,  ratifico  que  los  datos especificados  anteriormente  son  correctos  para  el  calculo y", 0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.5,10.5,0.04);
            $pdf->SetY(23.5);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.5,2.7,2.65);
                
            $pdf->SetY(23.55);
            $pdf->Ln(1.95);
            $pdf->SetFont('Arial','I',8);
            $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.3,19.59,0.04);

            $pdf->SetY(24.1);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.4);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                
        }  elseif (count($resultsB) > 10) {
            foreach ($resultsB as $rowBenef) {
                $pdf->SetX(5);
                $pdf->SetFont('Arial','',6);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(8,0.35,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(3.3,0.35,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->Ln(0.35);
            }

            $pdf->SetY(18.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(18.59, 0.5,"asi mismo,  ratifico  que  los  datos  especificados  anteriormente  son  correctos  para el  calculo y", 0,0, 'L');
            $pdf->Ln(0.5);
            $pdf->Cell(18.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.5,10.5,0.04);
            $pdf->SetY(23.5);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(18.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.3,2.7,2.65);
                
            $pdf->SetY(24);
            $pdf->Ln(1.3);
            $pdf->SetFont('Arial','I',8);
            $pdf->MultiCell(15.7, 0.25, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');

            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.1,19.59,0.04);

            $pdf->SetY(23.9);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
        }       
    }
    
    function fechactual(){
        $fecha = date("d-m-y ");
        $mes = intval(explode("-",$fecha)[1]);
        $mesesA = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        
        $fechaCompleta = explode("-",$fecha)[0] . " de ". $mesesA[$mes - 1] . " de 20" . explode("-",$fecha)[2];
        return $fechaCompleta;
    }

    $pdf->Output();
?>
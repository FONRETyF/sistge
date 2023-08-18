<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');


    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(2,1,2);
    //$pdf->SetAutoPageBreak(true,2); 

    $meses=[1=>"enero", 2=>"febrero", 3=>"marzo", 4=>"abril", 5=>"mayo", 6=>"junio", 7=>"julio", 8=>"agosto", 9=>"septiembre", 10=>"octubre", 11=>"noviembre", 12=>"diciembre"];

    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $identretiro = $_GET['identret'];

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statementT = $db->prepare("SELECT cvemae,motvret,fechbajfall,docttestamnt,nomsolic,modretiro,montrettot,montretletra,montretentr,montretentrletra,montretfall,montretfallletra,foliotramite FROM public.tramites_fonretyf WHERE identret='".$identretiro."'");
    $statementT->execute();
    $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);

    $motivoRet = $resultsT[0]['motvret'];

    if ($motivoRet == "I") {
        $motivoRetiro = "INHABILITACIÓN";
    } elseif ($motivoRet == "J") {
        $motivoRetiro = "JUBILACIÓN";
    }elseif ($motivoRet == "FA" || $motivoRet == "FJ") {
        $motivoRetiro = "FALLECIMIENTO";
    }

    if ($motivoRet == "FJ") {
        $statementMJ = $db->prepare("SELECT programfallec FROM public.jubilados_smsem WHERE cveissemym='".$resultsT[0]['cvemae']."'");
        $statementMJ->execute();
        $resultsMJ = $statementMJ->fetchAll(PDO::FETCH_ASSOC);

        if ($resultsMJ[0]["programfallec"] == "M") {
            $statementMJub = $db->prepare("SELECT cveissemym,nomcommae,curpmae,regmae,fechbajamae,fcfallecmae,fechafimutu,aniosjub FROM public.mutualidad WHERE cveissemym='".$resultsT[0]['cvemae']."'");
            $statementMJub->execute();
            $resultsMJub = $statementMJub->fetchAll(PDO::FETCH_ASSOC);
        } else {
            # code...
        }
        
    } else {
        $statementM = $db->prepare("SELECT csp,nomcommae,curpmae,regescmae,fcbasemae,aservactmae,numpsgs,diaspsgs,fechsinipsgs,fechsfinpsgs FROM public.maestros_smsem WHERE csp='".$resultsT[0]['cvemae']."'");
        $statementM->execute();
        $resultsM = $statementM->fetchAll(PDO::FETCH_ASSOC);
    }
    
    $statementB = $db->prepare("SELECT * FROM public.beneficiarios_maestros WHERE cvemae='".$resultsT[0]['cvemae']."' ORDER BY idbenef asc;");
    $statementB->execute();
    $resultsB = $statementB->fetchAll(PDO::FETCH_ASSOC);

    $llaves_array = array ("{", "}");
    $fechIniTemp=str_replace($llaves_array,"",$resultsM[0]["fechsinipsgs"]);
    $fechFinTemp=str_replace($llaves_array,"",$resultsM[0]["fechsfinpsgs"]);
   
    //fechas = array();
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
       

    if ($motivoRet === "I" || $motivoRet === "J") {
        
        $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,1.5,3.7,4.5);
        $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

        $pdf->AddFont('SegoeUIBL','','seguibl.php');
        $pdf->AddFont('arialbi','','arialbi.php');
        $pdf->SetFont('SegoeUIBL','',19);
                
        $pdf->SetXY(4.7,2);
        $pdf->SetTextColor(12.3,12.3,12.3);
        $pdf->Cell(13.89, 0.8, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $pdf->Cell(2.7,0.2);
        $pdf->SetFont('SegoeUIBL','',19);
        $pdf->Cell(13.89, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
                
        $pdf->Cell(2.7);
        $pdf->SetFont('arialbi','',12);
        $pdf->SetTextColor(21.3,21.3,21.3);
        $pdf->Cell(13.89, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $pdf->Ln(0.8);
        $pdf->Cell(2.7);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
        $pdf->Ln(0.5);
        $pdf->Cell(2.7);
        $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');
        $pdf->Ln(1.3);
          
        $pdf->Cell(17.59, 0.8, utf8_decode('ACUERDO DE RETIRO POR '.$motivoRetiro),0, 0, 'C');

        $pdf->Ln(1.5);
        
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(15,83,183);
        $pdf->Cell(17.5, 0.5, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(1.5);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(17.59, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

        $pdf->SetX(3);
        if ($resultsM[0]['numpsgs'] == 0) {
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.75,12,10.7,0.04);
            $pdf->Ln(2);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.8, "Yo ", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.8, $resultsT[0]['nomsolic'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2, 0.8, " con  clave  de  servidor  publico", 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,12.7,2.7,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.8,$resultsT[0]['cvemae'], 0, 0, 'C');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',6.8,12.7,5.4,0.04);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(2, 0.8," y  CURP  ", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(5.3, 0.8,$resultsM[0]['curpmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(7.49,0.8, utf8_decode(" quien  laboraba  en  la  región  sindical"), 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,13.4,0.9,0.04);
            $pdf->Cell(1, 0.8,$resultsM[0]['regescmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2, 0.8,"como docente basificado (a) del ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9.2,13.4,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.8,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.8," al ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',12.8,13.4,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.8,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(3.99, 0.8,", periodo  durante  el", 0, 0, 'L');
        
            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.9, 0.8,"cual tuve ",0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.9,14.1,1.1,0.04);
            $pdf->Cell(1.1, 0.8,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(13.5,0.8,utf8_decode("permisos sin goce de sueldo, por lo que solicito el Fondo  de Retiro por"),0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',18.55,14.1,1,0.04);
            $pdf->Cell(1.09, 0.8,$resultsM[0]['aservactmae'], 0, 0, 'C');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.8, utf8_decode("años de  servicio cotizados al  SMSEM, con  fundamento en  los artículos  33, 34, 35 y 38 del"), 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.8, utf8_decode("Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

            $pdf->Ln(1.3);
            $pdf->SetFont('Arial','',12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(17.59, 0.8,"Hago de  su  conocimiento  que  los  datos especificados  anteriormente  son  correctos  para", 0, 0, 'L');
            $pdf->Ln(0.7);
            $pdf->Cell(17.59, 0.8,"efectuar el  calculo y  entrega de  mi Fondo de Retiro, por lo  cual firmo de enterado (a)  y  de ", 0, 0, 'L');
            $pdf->Ln(0.7);
            $pdf->Cell(17.59, 0.8,"conformidad al respecto.", 0, 0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,22.05,10.5,0.04);
            $pdf->SetY(22.0);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(17.59, 0.7,"C. " . $resultsT[0]['nomsolic'],0, 0, 'C');
    
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23,2.7,2.65);
            
            $pdf->SetY(23);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $pdf->Ln(0.25);
            $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $pdf->Ln(0.25);
            $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
        
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.59,0.04);
            
            $pdf->SetY(23.6);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

            
           
        } else {
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.75,11.4,10.7,0.04);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.7, "Yo ", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.7, $resultsT[0]['nomsolic'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2, 0.7, " con  clave  de  servidor  publico", 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,12.1,2.7,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.7,$resultsT[0]['cvemae'], 0, 0, 'C');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',6.8,12.1,5.4,0.04);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(2, 0.7," y  CURP  ", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(5.3, 0.7,$resultsM[0]['curpmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(7.49,0.7, utf8_decode(" quien  laboraba  en  la  región  sindical"), 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,12.8,0.9,0.04);
            $pdf->Cell(1, 0.7,$resultsM[0]['regescmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2, 0.7,"como docente basificado (a) del ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9.2,12.8,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.7,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.7," al ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',12.8,12.8,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.7,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(3.99, 0.7,", periodo  durante  el", 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.9, 0.7,"cual tuve ",0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.9,13.5,1.1,0.04);
            $pdf->Cell(1.1, 0.7,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(14.59,0.7,utf8_decode("permiso (s) sin goce de sueldo: "),0, 0, 'L');
            $pdf->Ln(0.8);

            $pdf->SetXY(2.5,14);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetDrawColor(150,150,150);

            $pdf->SetLineWidth(0.02);
            $pdf->MultiCell(16.5, 0.4, $fechasPSGS, 1, 'C');
            
            $pdf->SetY(16);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(9,0.7,utf8_decode(", por  lo  que  solicito  el  Fondo   de Retiro por"),0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',11,16.5,1.1,0.04);
            $pdf->Cell(1.09, 0.7, $resultsM[0]['aservactmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(7.5, 0.7, utf8_decode("años de servicio  cotizados al SMSEM"), 0, 0, 'R');
            $pdf->Ln(0.7);
            $pdf->MultiCell(17.59, 0.7, utf8_decode("con fundamento en los artículos 33, 34, 35 y 38 del Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');

            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(17.59, 0.7,"Hago de  su  conocimiento  que  los  datos especificados  anteriormente  son  correctos  para", 0, 0, 'L');
            $pdf->Ln(0.7);
            $pdf->Cell(17.59, 0.7,"efectuar el  calculo y  entrega de  mi Fondo de Retiro, por lo  cual firmo de enterado (a)  y  de ", 0, 0, 'L');
            $pdf->Ln(0.7);
            $pdf->Cell(17.59, 0.7,"conformidad al respecto.", 0, 0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.55,10.5,0.04);
            $pdf->SetY(23.5);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(17.59, 0.7,"C. " . $resultsT[0]['nomsolic'],0, 0, 'C');
    
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23,2.7,2.65);
            
            $pdf->SetY(23);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $pdf->Ln(0.25);
            $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $pdf->Ln(0.25);
            $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
        
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.59,0.04);

            $pdf->SetY(23.6);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

        }


    } elseif ($motivoRet == "FA") {     
        $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,0.8,3.7,4.5);
        $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

        $pdf->AddFont('SegoeUIBL','','seguibl.php');
        $pdf->AddFont('arialbi','','arialbi.php');
        $pdf->SetFont('SegoeUIBL','',19);
                
        $pdf->Cell(2.7,1);
        $pdf->SetTextColor(12.3,12.3,12.3);
        $pdf->Cell(13.89, 0.5, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $pdf->Cell(2.7,0.2);
        $pdf->SetFont('SegoeUIBL','',19);
        $pdf->Cell(13.89, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
                
        $pdf->Cell(2.7);
        $pdf->SetFont('arialbi','',12);
        $pdf->SetTextColor(21.3,21.3,21.3);
        $pdf->Cell(13.89, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $pdf->Ln(0.7);
        $pdf->Cell(2.7);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
        $pdf->Ln(0.5);
        $pdf->Cell(2.7);
        $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $pdf->Ln(1.5);
        $pdf->Cell(17.59, 0.8, utf8_decode('ACUERDO DE FONDO DE RETIRO POR '.$motivoRetiro),0, 0, 'C');
        
        $pdf->Ln(0.9);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(15,83,183);
        $pdf->Cell(17.5, 0.5, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(0.9);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(17.59, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

        if ($resultsM[0]['numpsgs'] == 0) {
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.2,8.95,10.7,0.04);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.2, 0.5, "Yo C.", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5.8, 0.5, ",  quien  solicita  el  Fondo  de", 0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9,9.55,10.6,0.04);
            $pdf->Cell(7, 0.5, "Retiro por Fallecimiento del Profr (a).", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.5, utf8_decode($resultsM[0]['nomcommae']), 0, 0, 'C');

            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',14.8,10.15,2.8,0.04);
            $pdf->Cell(6.3, 0.5, "quien  se  encontraba  en  activo,", 0, 0, 'L');
            $pdf->Cell(6.5, 0.5, utf8_decode("  con  Clave  de  Servidor  Público"), 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.99, 0.5," y   CURP  ", 0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,10.75,5.2,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(5.3, 0.5,$resultsM[0]['curpmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2,0.5, utf8_decode("y  laboraba en  la región sindical"), 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',13.55,10.75,1.0,0.04);
            $pdf->Cell(1, 0.5,$resultsM[0]['regescmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5.09, 0.5," como  docente basificado", 0, 0, 'L');
            $pdf->Ln(0.6);

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.5,11.35,2.8,0.04);
            $pdf->Cell(1.5, 0.5,"(a) del", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.5," al ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',7.1,11.35,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6, 0.5,",  periodo  durante  el cual tuvo", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',15.9,11.35,1.1,0.04);
            $pdf->Cell(1.1, 0.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(2.59,0.5,utf8_decode(" permisos sin"),0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(11,0.5,utf8_decode("goce de sueldo, por lo que solicito el Fondo  de Retiro por"),0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',13,11.95,1.1,0.04);
            $pdf->Cell(1.09, 0.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5.5, 0.5,utf8_decode("años de servicio cotizados al "), 0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.6, 0.5, utf8_decode("SMSEM, con fundamento en los artículos  28, 30, 31, 32, 33, 34, 35, 37 y 38  del Reglamento"), 0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5, utf8_decode("del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

            $pdf->Ln(1);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5,"Hago de  su  conocimiento  que  el equivalente  al  Fondo de Retiro sea  entregado  como se", 0, 0, 'L');
            $pdf->Ln(0.6);
            $pdf->Cell(17.59, 0.5,"indica en " .$docTest.":", 0, 0, 'L');

            $pdf->Ln(0.5    );
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(5);
            $pdf->cell(8,0.4,'Beneficiario',0,0,'C');
            $pdf->cell(3.3,0.4,'Porcentaje',0,0,'C');
            $pdf->SetDrawColor(150,150,150);
            $pdf->SetLineWidth(0.05);
            $pdf->Line(5, 15.2, 16.3, 15.2);
            $pdf->SetFillColor(200,200,200);
            $pdf->SetDrawColor(100,100,100);
            $pdf->Ln(0.45);

            if (count($resultsB) < 7) {
                foreach ($resultsB as $rowBenef) {
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',8);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(18);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,22.5,10.5,0.04);
                $pdf->SetY(22.6);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                
                $pdf->SetY(23);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
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
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',7.5);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(19.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23.4,10.5,0.04);
                $pdf->SetY(23.4);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.8,2.7,2.65);
                
                $pdf->SetY(23);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
                $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.5,19.59,0.04);

                $pdf->SetY(23.3);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                $pdf->Ln(0.3);
                $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                
            }  elseif (count($resultsB) > 11) {
                foreach ($resultsB as $rowBenef) {
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(20.5);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.2,10.5,0.04);
                $pdf->SetY(24.2);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.3,2.7,2.65);
                
                $pdf->SetY(23.5);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
                $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26,19.59,0.04);

                $pdf->SetY(23.8);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                $pdf->Ln(0.3);
                $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

            }       
           
        } else {
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.2,8.9,10.7,0.04);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.2, 0.5, "Yo C.", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5.8, 0.5, ",  quien  solicita  el  Fondo  de", 0, 0, 'L');

            $pdf->Ln(0.5);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9,9.4,10.6,0.04);
            $pdf->Cell(7, 0.5, "Retiro por Fallecimiento del Profr (a).", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(10.59, 0.5, utf8_decode($resultsM[0]['nomcommae']), 0, 0, 'C');

            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',14.8,9.9,2.8,0.04);
            $pdf->Cell(6.3, 0.5, "quien  se  encontraba  en  activo,", 0, 0, 'L');
            $pdf->Cell(6.5, 0.5, utf8_decode("  con  Clave  de  Servidor  Público"), 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.99, 0.5," y   CURP  ", 0, 0, 'L');

            $pdf->Ln(0.5);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.1,10.41,5.2,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(5.3, 0.5,$resultsM[0]['curpmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6.2,0.5, utf8_decode("y  laboraba en  la región sindical"), 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',13.55,10.41,1.0,0.04);
            $pdf->Cell(1, 0.5,$resultsM[0]['regescmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5.09, 0.5," como  docente basificado", 0, 0, 'L');
            $pdf->Ln(0.5);

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.5,10.9,2.8,0.04);
            $pdf->Cell(1.5, 0.5,"(a) del", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0.8, 0.5," al ", 0, 0, 'L');
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',7.1,10.9,2.8,0.04);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(2.8, 0.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(6, 0.5,",  periodo  durante  el cual tuvo", 0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',15.9,10.9,1.1,0.04);
            $pdf->Cell(1.1, 0.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(2.59,0.5,utf8_decode(" permisos sin"),0, 0, 'L');
            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(11,0.5,utf8_decode("goce de sueldo:"),0, 0, 'L');

            $pdf->SetXY(2.5,11.6);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetDrawColor(150,150,150);

            $pdf->SetLineWidth(0.02);
            $pdf->MultiCell(16.5, 0.4, $fechasPSGS, 1, 'C');
            
            $pdf->Ln(1);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(8.1,0.5,utf8_decode(", por lo que solicito el Fondo  de Retiro por"),0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(1.09, 0.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(8.4, 0.5,utf8_decode("años de servicio  cotizados al  SMSEM, con"), 0, 0, 'L');

            $pdf->Ln(0.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.6, 0.5, utf8_decode("fundamento en los artículos  28, 30, 31, 32, 33, 34, 35, 37 y 38 del Reglamento del Fondo de"), 0, 0, 'L');

            $pdf->Ln(0.6);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5, utf8_decode("Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

            $pdf->Ln(0.8);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5,"Hago de  su  conocimiento  que  el equivalente  al  Fondo de Retiro sea  entregado  como se", 0, 0, 'L');
            $pdf->Ln(0.6);
            $pdf->Cell(17.59, 0.5,"indica en " .$docTest.":", 0, 0, 'L');

            $pdf->Ln(0.3);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(5);
            $pdf->cell(8,0.4,'Beneficiario',0,0,'C');
            $pdf->cell(3.3,0.4,'Porcentaje',0,0,'C');
            $pdf->SetDrawColor(150,150,150);
            $pdf->SetLineWidth(0.05);
            $pdf->Line(5, 16.6, 16.3, 16.6);
            $pdf->SetFillColor(200,200,200);
            $pdf->SetDrawColor(100,100,100);
            $pdf->Ln(0.45);

            if (count($resultsB) < 7) {
                foreach ($resultsB as $rowBenef) {
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(19);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,22.8,10.5,0.04);
                $pdf->SetY(22.8);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                
                $pdf->SetY(23);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
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
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',7.5);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(20.8);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.2,10.5,0.04);
                $pdf->SetY(24.2);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23,2.7,2.65);
                
                $pdf->SetY(23.5);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
                $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26,19.59,0.04);

                $pdf->SetY(23.8);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                $pdf->Ln(0.3);
                $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                
            }  elseif (count($resultsB) > 11) {
                foreach ($resultsB as $rowBenef) {
                    $pdf->SetX(5);
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetLineWidth(0.01);
                    $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                    $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                    $pdf->Ln(0.4);
                }

                $pdf->SetY(21.8);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
                $pdf->Ln(0.6);
                $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

                $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.8,10.5,0.04);
                $pdf->SetY(24.8);
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
                $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.8,2.7,2.65);
                
                $pdf->SetY(24);
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial','I',8);
                $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
                $pdf->Ln(0.25);
                $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
                $pdf->Ln(0.25);
                $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
                $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.5,19.59,0.04);

                $pdf->SetY(24.25);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
                $pdf->Ln(0.5);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
                $pdf->Ln(0.3);
                $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

            }    
        }

    } elseif ($motivoRet == "FJ") {
        $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,1,3.7,4.5);
        $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

        $pdf->AddFont('SegoeUIBL','','seguibl.php');
        $pdf->AddFont('arialbi','','arialbi.php');
        $pdf->SetFont('SegoeUIBL','',19);
                
        $pdf->SetY(1.5);
        $pdf->Cell(2.7);
        $pdf->SetTextColor(12.3,12.3,12.3);
        $pdf->Cell(13.89, 0.5, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $pdf->Cell(2.7,0.2);
        $pdf->SetFont('SegoeUIBL','',19);
        $pdf->Cell(13.89, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
                
        $pdf->Cell(2.7);
        $pdf->SetFont('arialbi','',12);
        $pdf->SetTextColor(21.3,21.3,21.3);
        $pdf->Cell(13.89, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $pdf->Ln(0.7);
        $pdf->Cell(2.7);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
        $pdf->Ln(0.5);
        $pdf->Cell(2.7);
        $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $pdf->Ln(1.5);
        $pdf->Cell(17.59, 0.8, utf8_decode('ACUERDO DE FONDO DE RETIRO POR '.$motivoRetiro),0, 0, 'C');
        
        $pdf->Ln(1.3);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(15,83,183);
        $pdf->Cell(17.5, 0.5, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(1.3);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(17.59, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.2,10.25,10.7,0.04);
        $pdf->Ln(1.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(1.2, 0.5, "Yo C.", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10.59, 0.5, utf8_decode( $resultsT[0]['nomsolic']), 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(5.8, 0.5, ",  quien  solicita  el  Fondo  de", 0, 0, 'L');

        $pdf->Ln(0.6);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9,10.82,10.6,0.04);
        $pdf->Cell(7, 0.5, "Retiro por Fallecimiento del Profr (a).", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10.59, 0.5, utf8_decode($resultsMJub[0]['nomcommae']), 0, 0, 'C');

        $pdf->Ln(0.6);
        $pdf->SetFont('Arial','',12);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',6.8,11.4,2.8,0.04);
        $pdf->Cell(4.8, 0.5, utf8_decode("con Clave de ISSEMYM"), 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(2.8, 0.5,$resultsT[0]['cvemae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(2, 0.5," ,   CURP  ", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',11.6,11.4,5.3,0.04);
        $pdf->Cell(5.3, 0.5,$resultsMJub[0]['curpmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(2.69, 0.5, utf8_decode(", y pertenecía"), 0, 0, 'L');

        $pdf->Ln(0.6);
        $pdf->Cell(3.7,0.5, utf8_decode("a la región sindical"), 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.7,12,1.0,0.04);
        $pdf->Cell(1, 0.5,$resultsMJub[0]['regmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(8.4, 0.5," como  maestro (a)  jubilado (a)  a partir del", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',15.1,12,4.5,0.04);
        $pdf->Cell(4.49, 0.5,$resultsMJub[0]['fechbajamae'],0 , 0, 'C');
        
        $pdf->Ln(0.6);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0.5, 0.5,"al ", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.5,12.6,4.5,0.04);
        $pdf->Cell(4.49, 0.5,$resultsMJub[0]['fcfallecmae'],0 , 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(8.2,0.5,utf8_decode(", por lo que solicito el Fondo  de Retiro por"),1, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',15.2,12.6,1.1,0.04);
        $pdf->Cell(1.09, 0.5,$resultsMJub[0]['aniosjub'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(3.31, 0.5,utf8_decode(" años  aportados"), 0, 0, 'L');

        $pdf->Ln(0.6);
        $pdf->Cell(6.7, 0.5,utf8_decode("al programa de Mutualidad SMSEM"), 0, 0, 'L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(10.89, 0.5, utf8_decode(", con fundamento  en los artículos  28, 30, 31, 32, 33, 34,"), 0, 0, 'L');

        $pdf->Ln(0.6);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(17.59, 0.5, utf8_decode("35, 37 y 38 del Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

        $pdf->Ln(1);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(17.59, 0.5,"Hago de  su  conocimiento  que  el equivalente  al  Fondo de Retiro sea  entregado  como se", 0, 0, 'L');
        $pdf->Ln(0.6);
        $pdf->Cell(17.59, 0.5,"indica en " .$docTest.":", 0, 0, 'L');

        $pdf->Ln(0.5    );
        $pdf->SetFont('Arial','B',8);
        $pdf->SetX(5);
        $pdf->cell(8,0.4,'Beneficiario',0,0,'C');
        $pdf->cell(3.3,0.4,'Porcentaje',0,0,'C');
        $pdf->SetDrawColor(150,150,150);
        $pdf->SetLineWidth(0.05);
        $pdf->Line(5, 15.9, 16.3, 15.9);
        $pdf->SetFillColor(200,200,200);
        $pdf->SetDrawColor(100,100,100);
        $pdf->Ln(0.45);

        if (count($resultsB) < 7) {
            foreach ($resultsB as $rowBenef) {
                $pdf->SetX(5);
                $pdf->SetFont('Arial','',8);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            $pdf->SetY(19);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
            $pdf->Ln(0.6);
            $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,23,10.5,0.04);
            $pdf->SetY(23);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.5,2.7,2.65);
                
            $pdf->SetY(23);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $pdf->Ln(0.25);
            $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $pdf->Ln(0.25);
            $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
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
                $pdf->SetX(5);
                $pdf->SetFont('Arial','',7.5);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            $pdf->SetY(20.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
            $pdf->Ln(0.6);
            $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,24.3,10.5,0.04);
            $pdf->SetY(24.3);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.5,2.7,2.65);
                
            $pdf->SetY(23.8);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $pdf->Ln(0.25);
            $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $pdf->Ln(0.25);
            $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.3,19.59,0.04);

            $pdf->SetY(24.1);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(17.9, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $pdf->Ln(0.4);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17.9, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $pdf->Ln(0.3);
            $pdf->Cell(17.9, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

                
        }  elseif (count($resultsB) > 11) {
            foreach ($resultsB as $rowBenef) {
                $pdf->SetX(5);
                $pdf->SetFont('Arial','',7);
                $pdf->SetLineWidth(0.01);
                $pdf->cell(8,0.4,utf8_decode($rowBenef["nombenef"]),1,0,'C');
                $pdf->cell(3.3,0.4,$rowBenef["porcretbenef"],1,0,'C');
                $pdf->Ln(0.4);
            }

            $pdf->SetY(21.5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.5,"asi mismo, ratifico que los datos especificados anteriormente  son correctos para el calculo y", 0,0, 'L');
            $pdf->Ln(0.6);
            $pdf->Cell(17.59, 0.5,"entrega del Fondo de Retiro, por lo cual firmo de conformidad al respecto.", 0,0, 'L');

            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',5.6,25,10.5,0.04);
            $pdf->SetY(25);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(17.59, 0.5,"C. " . utf8_decode($resultsT[0]['nomsolic']),0, 0, 'C');
        
            $pdf->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,23.8,2.7,2.65);
                
            $pdf->SetY(24);
            $pdf->Ln(1.5);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(17.59, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $pdf->Ln(0.25);
            $pdf->Cell(17.59, 0.5,utf8_decode("             en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $pdf->Ln(0.25);
            $pdf->Cell(10.59, 0.5,utf8_decode("             Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
            
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,26.5,19.59,0.04);

            $pdf->SetY(24.3);
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
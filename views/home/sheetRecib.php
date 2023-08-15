<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');


    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(2,2,2);
    //$pdf->SetAutoPageBreak(true,2); 

    $meses=[1=>"enero", 2=>"febrero", 3=>"marzo", 4=>"abril", 5=>"mayo", 6=>"junio", 7=>"julio", 8=>"agosto", 9=>"septiembre", 10=>"octubre", 11=>"noviembre", 12=>"diciembre"];

    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $identretiro = $_GET['identret'];

    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statementT = $db->prepare("SELECT cvemae,motvret,fechbajfall,nomsolic,modretiro,montrettot,montretletra,montretentr,montretentrletra,montretfall,montretfallletra,foliotramite FROM public.tramites_fonretyf WHERE identret='".$identretiro."'");
    $statementT->execute();
    $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);

    if ($resultsT[0]['motvret'] == "I") {
        $motivoRetiro = "INHABILITACIÓN";
    } elseif ($resultsT[0]['motvret'] == "J") {
        $motivoRetiro = "JUBILACIÓN";
    }elseif ($resultsT[0]['motvret'] == "FA" || $resultsT[0]['motvret'] == "FJ") {
        $motivoRetiro = "FALLECIMIENTO";
    }

    $statementM = $db->prepare("SELECT csp,curpmae,regescmae,fcbasemae,aservactmae,numpsgs,diaspsgs,fechsinipsgs,fechsfinpsgs FROM public.maestros_smsem WHERE csp='".$resultsT[0]['cvemae']."'");
    $statementM->execute();
    $resultsM = $statementM->fetchAll(PDO::FETCH_ASSOC);

    $pdf->Image('/var/www/html/sistge/img/escudooficio.png',1,1.5,3.7,4.5);
    $pdf->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);

    $pdf->AddFont('SegoeUIBL','','seguibl.php');
    $pdf->AddFont('arialbi','','arialbi.php');
    $pdf->SetFont('SegoeUIBL','',19);
            
    $pdf->Cell(2.7,2);
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
    $pdf->Cell(13.89, 0.8, utf8_decode('DIRECCIÓN DE FONDO DE RETIRO  Y FALLECIMIENTO'),0, 0, 'C');
    $pdf->Ln(0.5);
    $pdf->Cell(2.7);
    $pdf->Cell(13.89, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');
    $pdf->Ln(1.8);
    
    $pdf->SetFont('Arial','B',9.5);
    
    $pdf->Cell(17.59, 0.8, utf8_decode('ACUERDO DE ENTREGA Y RECEPCIÓN DEL TRAMITE DE SEGURO DE RETIRO POR '.$motivoRetiro),0, 0, 'C');

    $pdf->Ln(1.5);
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(15,83,183);
    $pdf->Cell(17.59, 0.5, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');

    setlocale(LC_ALL, 'es_MX');
    $fecha = fechactual();
    
    $pdf->SetTextColor(0,0,0);
    $pdf->Ln(1.5);
    $pdf->SetFont('Arial','I',11);
    $pdf->Cell(17.59, 0.5, utf8_decode("Toluca, México a  " . $fecha), 0, 0, 'R');

    if ($motivoRetiro === "INHABILITACIÓN" || $motivoRetiro === "JUBILACIÓN") {
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2.75,12,10.7,0.05);
        $pdf->Ln(1.5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0.8, 0.8, "Yo ", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10.59, 0.8, $resultsT[0]['nomsolic'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(6.2, 0.8, " con  clave  de  servidor  publico", 0, 0, 'L');

        $pdf->Ln(0.7);
        $pdf->SetFont('Arial','',12);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2,12.7,2.8,0.05);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(2.8, 0.8,$resultsT[0]['cvemae'], 0, 0, 'C');
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',6.8,12.7,5.4,0.05);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(2, 0.8," y  CURP  ", 0, 0, 'L');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(5.3, 0.8,$resultsM[0]['curpmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(7.49,0.8, utf8_decode(" quien  laboraba  en  la  región  sindical"), 0, 0, 'L');

        $pdf->Ln(0.7);
        $pdf->SetFont('Arial','B',11);
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2,13.4,1.0,0.05);
        $pdf->Cell(1, 0.8,$resultsM[0]['regescmae'], 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(6.2, 0.8,"como docente basificado (a) del ", 0, 0, 'L');
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',9.2,13.4,2.8,0.05);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(2.8, 0.8,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0.8, 0.8," al ", 0, 0, 'L');
        $pdf->Image('/var/www/html/sistge/img/lineafirma.png',12.8,13.4,2.8,0.05);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(2.8, 0.8,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(3.99, 0.8,", periodo  durante  el", 0, 0, 'L');

        if ($resultsM[0]['numpsgs'] == 0) {
            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.9, 0.8,"cual tuve ",0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.9,14.1,1.1,0.04);
            $pdf->Cell(1.1, 0.8,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(13.5,0.8,utf8_decode("permisos sin goce de sueldo, por lo que solicito el Seguro de Retiro por"),0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',18.5,14.1,1.1,0.04);
            $pdf->Cell(1.09, 0.8,$resultsM[0]['aservactmae'], 0, 0, 'C');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.8, utf8_decode("años de servicio cotizados al SMSEM, con fundamento en los artículos 31, 34, 35, 36, 37 38"), 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.8, utf8_decode(" y 43 del Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

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
        
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.59,0.05);
            
           
        } else {
            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(1.9, 0.8,"cual tuve ",0, 0, 'L');
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',3.9,14.1,1.1,0.04);
            $pdf->Cell(1.1, 0.8,$resultsM[0]['numpsgs'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(14.59,0.8,utf8_decode("permiso (s) sin goce de sueldo, por  lo  que  solicito  el  Seguro  de Retiro por"),0, 0, 'L');
            
            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','B',11);
            $pdf->Image('/var/www/html/sistge/img/lineafirma.png',2,14.8,1.1,0.04);
            $pdf->Cell(1.09, 0.8, $resultsM[0]['aservactmae'], 0, 0, 'C');
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(16.5, 0.8, utf8_decode("años de servicio  cotizados al SMSEM, con fundamento en  los artículos 31, 34, 35, 36,"), 0, 0, 'L');

            $pdf->Ln(0.7);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(17.59, 0.8, utf8_decode("37, 38 y 43 del Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

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
        
            $pdf->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.59,0.05);
        }
        

        # code...
    } elseif (condition) {
        # code...
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
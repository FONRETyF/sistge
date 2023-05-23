<?php
    
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');

    class PDF extends FPDF
    {
        private $db;

    function Header()
    {
        $cvemae = $_GET['identret'];
        
        $pdo = new dbfonretyf();
        $this->db=$pdo->conexfonretyf();

        $statementT = $this->db->prepare("SELECT cvemae,motvret,nomcommae,nomsolic,fechrecib,fechbajfall,fechinijuicio FROM public.tramites_pendientes WHERE cvemae='".$cvemae."'");
        $statementT->execute();
        $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);

        $this->Image('/var/www/html/sistge/img/escudooficio.png',1,0.3,3.7,4.5);
        $this->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',5,8.5,12.2,13);
        
        $this->AddFont('SegoeUIBL','','seguibl.php');
        $this->AddFont('arialbi','','arialbi.php');
        $this->SetFont('SegoeUIBL','',19);
        
        $this->Cell(2.5);
        $this->SetTextColor(12.3,12.3,12.3);
        $this->Cell(16, 0.8, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $this->Cell(2.5,0.2);
        $this->SetFont('SegoeUIBL','',19);
        $this->Cell(16, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
        
        $this->Cell(2.5);
        $this->SetFont('arialbi','',12);
        $this->SetTextColor(21.3,21.3,21.3);
        $this->Cell(15.5, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $this->Ln(0.8);
        $this->Cell(2.5);
        $this->SetFont('Arial','B',12);
        $this->SetTextColor(0,0,0);
        $this->Cell(16, 0.8, utf8_decode('DIRECCION DE FONDO DE RETIRO  Y FALLECIMIENTO'),0, 0, 'C');
        $this->Ln(0.5);
        $this->Cell(2.5);
        $this->Cell(16, 0.8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $this->Ln(1.5);
        $this->Cell(0.25);
        $this->SetFont('Arial','B',11);
        $this->SetTextColor(0,0,0);
        $this->Cell(19.05, 1, utf8_decode('PRORROGA PARA TRAMITE DE RETIRO POR FALLECIMIENTO'), 1, 0, 'C');

        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $this->SetTextColor(0,0,0);
        $this->Ln(1.4);
        $this->Cell(12);
        $this->SetFont('Arial','I',11);
        $this->Cell(7, 0.8, utf8_decode("Toluca, México a  " .$fecha), 0, 0, 'R');


        
        

        $this->Image('/var/www/html/sistge/img/lineafirma.png',27,106,110,0.4);
        $this->Ln(18);
        $this->Cell(10);
        $this->SetFont('Arial','',12);
        $this->Cell(8, 8, "Yo ", 0, 0, 'L');
        $this->SetFont('Arial','B',11);
        $this->Cell(108, 8, $resultsT[0]['nomsolic'], 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(54, 8, " con  clave  de  servidor  publico", 0, 0, 'L');

        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','',12);
        $this->Image('/var/www/html/sistge/img/lineafirma.png',21,113,28,0.4);
        $this->SetFont('Arial','B',11);
        $this->Cell(30, 8,$resultsT[0]['cvemae'], 0, 0, 'C');
        $this->Image('/var/www/html/sistge/img/lineafirma.png',69,113,54,0.4);
        $this->SetFont('Arial','',12);
        $this->Cell(17, 8," y  CURP  ", 0, 0, 'L');
        $this->SetFont('Arial','B',11);
        $this->Cell(55, 8,'', 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(99, 8, utf8_decode(" quien  laboraba  en  la  región  sindical"), 0, 0, 'L');

        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','B',11);
        $this->Image('/var/www/html/sistge/img/lineafirma.png',21,120,10,0.4);
        $this->Cell(11, 8,'', 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(62, 8,"como docente basificado (a) del ", 0, 0, 'L');
        $this->Image('/var/www/html/sistge/img/lineafirma.png',93,120,28,0.4);
        $this->SetFont('Arial','B',11);
        $this->Cell(28, 8,'', 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(8, 8," al ", 0, 0, 'L');
        $this->Image('/var/www/html/sistge/img/lineafirma.png',129,120,28,0.4);
        $this->SetFont('Arial','B',11);
        $this->Cell(28, 8,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(40, 8,", periodo  durante  el", 0, 0, 'L');

        

        $this->Image('/var/www/html/sistge/img/lineafirma.png',65,221,90,0.4);
        $this->SetY(220);
        $this->SetFont('Arial','B',11);
        $this->Cell(50);
        $this->Cell(100, 7,"C. " . $resultsT[0]['nomsolic'],0, 0, 'C');

        $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
        
        $this->SetY(245);
        $this->Ln(1);
        $this->Cell(10);
        $this->SetFont('Arial','I',8);
        $this->Cell(179, 5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
        $this->Ln(2.5);
        $this->Cell(10);
        $this->Cell(179, 5,utf8_decode("en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
        $this->Ln(2.5);
        $this->Cell(10);
        $this->Cell(179, 5,utf8_decode("Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
    
        $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
    }

    // Pie de página
    function Footer()
    {
        
        // Posición: a 1,5 cm del final
        $this->SetY(-21);
        // Arial italic 8
        
        // Número de página
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        $this->SetFont('Arial','B',8);
        $this->Cell(10);
        $this->Cell(179, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','B',7);
        $this->Cell(179, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
        $this->Ln(3);
        $this->Cell(10);
        $this->Cell(179, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');

        

    }
    }

    function fechactual(){
        $fecha = date("d-m-y ");
        $mes = intval(explode("-",$fecha)[1]);
        $mesesA = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        
        $fechaCompleta = explode("-",$fecha)[0] . " de ". $mesesA[$mes - 1] . " de 20" . explode("-",$fecha)[2];
        return $fechaCompleta;
    }
    

    // Creación del objeto de la clase heredada
    $pdf = new PDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);

    $pdf->Output();



    /*require('../../fpdf/fpdf.php');
    require('../../config/dbfonretyf.php');

    $pdf = new FPDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(3,2,3);
    $pdf->SetAutoPageBreak(true,2.5); 

    $cvemae = $_GET['cvemae'];
    
        
    $pdo = new dbfonretyf();
    $db=$pdo->conexfonretyf();

    $statementFechEntr = $db->prepare("SELECT cvemae,motvret,nomcommae,nomsolic,fechrecib,fechbajfall,fechinijuicio FROM public.tramites_pendientes WHERE cvemae='".$cvemae."'");
    $statementFechEntr->execute();
    $resultsFechentrega = $statementFechEntr->fetchAll(PDO::FETCH_ASSOC);
    
    /*  ENCABEZADO   
    $pdf->Image("/var/www/html/sistge/img/cancelado.png",10,3,37,45);
    $pdf->SetFont('Arial','B',11);
    
    
    
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'¡Hola, Mundo!');
    $pdf->Output();
    
    
    /* 

    
    /*
    $pdf->AddFont('SegoeUIBL','','seguibl.php');
    $pdf->AddFont('arialbi','','arialbi.php');
    $pdf->SetFont('SegoeUIBL','',19);
    // Movernos a la derecha
    $pdf->Cell(25);
    $pdf->SetTextColor(123,123,123);
    $pdf->Cell(160, 8, 'SINDICATO DE MAESTROS',0, 1, 'C');
    $pdf->Cell(25,2);
    $pdf->SetFont('SegoeUIBL','',19);
    $pdf->Cell(160, 10, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
    
    $pdf->Cell(25);
    $pdf->SetFont('arialbi','',12);
    $pdf->SetTextColor(213,213,213);
    $pdf->Cell(155, 3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');
/*
    $pdf->Ln(8);
    $pdf->Cell(25);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(160, 8, utf8_decode('DIRECCION DE FONDO DE RETIRO  Y FALLECIMIENTO'),0, 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(25);
    $pdf->Cell(160, 8, utf8_decode('"FONRETyF"'),0, 0, 'C');    */


    /*$pdf->SetY(-21);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10);
    $pdf->Cell(179, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
    $pdf->Ln(7);
    $pdf->Cell(10);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(179, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
    $pdf->Ln(3);
    $pdf->Cell(10);
    $pdf->Cell(179, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');


    $pdf->Cell(40,10,'¡Hola, Mundo!');
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);

    $pdf->Output();*/

?>
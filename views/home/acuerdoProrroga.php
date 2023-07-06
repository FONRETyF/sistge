<?php
    
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');

    class PDF extends FPDF
    {
        private $db;

        function Header()
        {
            $cvemae = $_GET['cvemae'];
            
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

            $this->Ln(2.5);
            $this->Cell(0.25);
            $this->SetFont('Arial','B',11);
            $this->SetTextColor(0,0,0);
            $this->Cell(19.05, 1, utf8_decode('PRORROGA PARA TRAMITE DE RETIRO POR FALLECIMIENTO'), 0, 0, 'C');

            setlocale(LC_ALL, 'es_MX');
            $fecha = fechactual();
            $this->SetTextColor(0,0,0);
            $this->Ln(2);
            $this->Cell(12);
            $this->SetFont('Arial','I',12);
            $this->Cell(7, 0.8, utf8_decode("Toluca, México a  " .$fecha), 0, 0, 'R');


            $this->SetY(11);
            $this->Cell(0.25);
            $this->SetFont('Arial','',11);
            $this->Cell(15.05, 0.8, 'Considerando que la vigencia para realizar el tramite de Seguro de Fallecimiento es de', 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(1.7, 0.8, utf8_decode(' un año '), 0, 0, 'L');
            $this->SetFont('Arial','',11);
            $this->Cell(2.3, 0.8, 'a partir de la', 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->SetFont('Arial','B',11);
            $this->Cell(7.3, 0.8, 'fecha de  fallecimiento  del maestro (a)', 0, 0, 'L');
            $this->SetFont('Arial','',11);
            $this->Cell(11.75, 0.8, utf8_decode(' y  que para  la  realizacion y  aceptacion del  trámite de  Seguro de'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(3.35, 0.8, 'Fallecimiento en el', 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(2.15, 0.8, ' FONRETyF', 0, 0, 'L');
            $this->SetFont('Arial','',11);
            $this->Cell(4.5, 0.8, ', se requiere presentar la', 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(5.95, 0.8, 'Carta Testamentaria del SMSEM', 0, 0, 'L');
            $this->SetFont('Arial','',11);
            $this->Cell(3.1, 0.8, ', y  en caso de la', 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(7.4, 0.8, 'inexistencia de esta, se debe presentar un', 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(11.65, 0.8, utf8_decode(' Juicio Sucesorio Intestamentario  o  Juicio de Designación de'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(2.45, 0.8, 'Beneficiarios', 0, 0, 'L');
            $this->SetFont('Arial','',11);
            $this->Cell(9.5, 0.8, ', el cual debe iniciarse durante el periodo de vigencia.', 0, 0, 'L');

            $this->Ln(2);
            $this->Cell(0.25);
            $this->Cell(2.9, 0.8, 'Por lo que yo C.', 0, 0, 'L');
            $this->SetFont('Arial','B',10);
            $this->Cell(10.4, 0.8, utf8_decode($resultsT[0]['nomsolic']), 0, 0, 'C');
            $this->Image('/var/www/html/sistge/img/lineafirma.png',4.15,16.75,10.5,0.04);
            $this->SetFont('Arial','',11);
            $this->Cell(5.75, 0.8, ', con fecha y lugar especificados', 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(19.05, 0.8, 'en este documento, presento en  el FONRETyF  el soporte  del inicio del  Juicio Sucesorio Intestamentario del', 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(2.2, 0.8, 'maestro (a)', 0, 0, 'L');
            $this->SetFont('Arial','B',10);
            $this->Cell(10.9, 0.8,  utf8_decode($resultsT[0]['nomcommae']), 0, 0, 'C');
            $this->SetFont('Arial','',11);
            $this->Image('/var/www/html/sistge/img/lineafirma.png',3.36,18.35,11.1,0.04);
            $this->Cell(5.95, 0.8, utf8_decode(', así como solicitud y documentos'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(19.05, 0.8, utf8_decode('del  trámite  de  seguro  por  fallecimiento,  comprometiéndome  a  concluir  el  trámite  completo (documentos'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(19.05, 0.8, utf8_decode('faltantes) en un lapso no mayor a tres meses a partir de la fecha en la que me sea entregada la resolución del'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->Cell(0.25);
            $this->Cell(19.05, 0.8, utf8_decode('mismo, de lo contrario el trámite no procederá.'), 0, 0, 'L');

            $this->Image('/var/www/html/sistge/img/lineafirma.png',6.5,23.6,9,0.04);
            $this->SetY(23.5);
            $this->SetFont('Arial','B',11);
            $this->Cell(5);
            $this->Cell(10, 0.7,"C. " . $resultsT[0]['nomsolic'],0, 0, 'C');

            $this->Image('/var/www/html/sistge/img/logoplanilla.png',17.4,22.3,2.7,2.65);
            
            $this->SetY(24.5);
            $this->Ln(0.1);
            $this->Cell(1);
            $this->SetFont('Arial','I',8);
            $this->Cell(17.9, 0.5,utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México,"),0,0,'L');
            $this->Ln(0.25);
            $this->Cell(1);
            $this->Cell(17.9, 0.5,utf8_decode("en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares."),0, 0, 'L');
            $this->Ln(0.25);
            $this->Cell(1);
            $this->Cell(17.9, 0.5,utf8_decode("Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."),0, 0, 'L');
        
            $this->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.7,0.05);
        }

        function Footer()
        {
            $this->SetY(-2.1);
            $this->SetFont('Arial','B',8);
            $this->Cell(1);
            $this->Cell(17.9, 0.5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $this->Ln(0.7);
            $this->Cell(1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17.9, 0.5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $this->Ln(0.3);
            $this->Cell(1);
            $this->Cell(17.9, 0.5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
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
?>
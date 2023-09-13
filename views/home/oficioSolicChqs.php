<?php
    
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');
    require('/var/www/html/sistge/model/cantidadLetras.php');
    require('/var/www/html/sistge/model/funcionesEsp.php');

    class PDF extends FPDF
    {
        private $db;
        private $CantLet;
        public $entregaOrdinal;

        function Header()
        {
            $identr = $_GET['identr'];
            
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();

            $cantidadLetras = new cantidadLetras;
            $funciones = new funcionesEsp;

            $meses=[1=>"ENERO", 2=>"FEBRERO", 3=>"MARZO", 4=>"ABRIL", 5=>"MAYO", 6=>"JUNIO", 7=>"JULIO", 8=>"AGOSTO", 9=>"SEPTIEMBRE", 10=>"OCTUBRE", 11=>"NOVIEMBRE", 12=>"DICIEMBRE"];

            $fecha = fechactual();

            $statementE = $this->db->prepare("SELECT numentrega,fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identr."'");
            $statementE->execute();
            $resultsE = $statementE->fetchAll(PDO::FETCH_ASSOC);
            $entrOfic = $resultsE[0]['numentrega'];
            $fechaEntrega = substr($resultsE[0]['fechentrega'],8,2) . " DE " . $meses[intval(substr($resultsE[0]['fechentrega'],5,2))] . " DE " . substr($resultsE[0]['fechentrega'],0,4);
            $this->entregaOrdinal= $funciones->numordinales([intval(substr($identr,4,2))]);

            $consulta = "SELECT COUNT(*), SUM(montbenef) FROM public.beneficiarios_cheques WHERE numentrega=".intval(substr($identr,4,2))." and anioentrega=".substr($identr,0,4)." and tipimpcheq='0'";
            $statementTChqs = $this->db->prepare($consulta);
            $statementTChqs->execute();
            $resultsTChqs = $statementTChqs->fetchAll(PDO::FETCH_ASSOC);

            $this->Image('/var/www/html/sistge/img/escudooficio.png',1.8,0.4,3.7,4.5);
            $this->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',4.8,7.3,12,11.5);
            
            $this->AddFont('SegoeUIBL','','seguibl.php');
            $this->AddFont('arialbi','','arialbi.php');
            $this->SetFont('SegoeUIBL','',19);
            $this->setXY(0,0);

            $this->setXY(5.5,1);
            $this->SetTextColor(74,68,67);
            $this->Cell(13, 0.8, 'SINDICATO DE MAESTROS',0, 1, 'C');
            $this->setX(5.5);
            $this->SetFont('SegoeUIBL','',19);
            $this->Cell(13, 1, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
            
            $this->setX(5.5);
            $this->SetFont('arialbi','',12);
            $this->SetTextColor(153,153,153);
            $this->Cell(13, 0.3, utf8_decode('"Por la Educación al Servicio del Pueblo"'),0, 0, 'C');

            $this->Ln(2);
            $this->setX(2.5);
            $this->SetFont('Arial','',11);
            setlocale(LC_ALL, 'es_MX');
            $this->SetTextColor(0,0,0);
            $this->Cell(16.59, 0.8, utf8_decode("Toluca, México a  " .$fecha),0, 0, 'R');

            $this->Ln(2.5);
            $this->setX(2.5);
            $this->SetFont('Arial','B',11);
            $this->SetTextColor(0,0,0);
            $this->Cell(16.59, 0.4, utf8_decode('PROFRA. CLEOTILDE CASTILLO MENDEZ'), 0, 0, 'L');
            $this->Ln(0.5);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('SECRETARIA DE FINANZAS'), 0, 0, 'L');
            $this->Ln(0.5);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('P R E S E N T E:'), 0, 0, 'L');
            
            $this->SetFont('Arial','',12);
            $this->Ln(1.3);
            
            $this->Cell(16.59, 0.5, utf8_decode('          Por  este  medio  solicito  a  usted  proporcione a  esta  Dirección  del  Fondo  de'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->setX(2.5);
            $this->Cell(7, 0.5, utf8_decode('Retiro y Fallecimiento la cantidad de '), 0, 0, 'L');
            $this->SetFont('Arial','B',12);
            $this->Cell(1.4, 0.5, $resultsTChqs[0]['count'], 0, 0, 'C');
            $this->Cell(1.75, 0.5, "cheques", 0, 0, 'L');

            $this->SetFont('Arial','',12);
            $this->Cell(6.45, 0.5, ", para cubrir  el pago  del Seguro", 0, 0, 'L');

            $this->Ln(0.8);
            $this->Cell(0.8, 0.5, "del", 0, 0, 'L');
            $this->SetFont('Arial','B',12);
            $this->Cell(2.2, 0.5, "FONRETyF", 0, 0, 'L');
            $this->SetFont('Arial','',12);
            $this->Cell(7.8, 0.5, utf8_decode(",  mismos que  serán  entregados  en  la "), 0, 0, 'L');
            $this->SetFont('Arial','B',12);
            $this->Write(0.5,$entrOfic);
            $this->subWrite(0.7,'a','',8,7);
            $this->SetX(14);
            $this->Cell(2.15, 0.5, "ceremonia", 0, 0, 'L');
            $this->SetFont('Arial','',12);
            $this->cell(2.95,0.5,",  programada",0,0 );   
            
            $this->Ln(0.8);
            $this->setX(2.5);
            $this->cell(16.59,0.5,"a partir del ".strtolower($fechaEntrega).".",0,0 );
            




            $this->Ln(1.5);
            $this->SetFont('Arial','',12);
            $this->MultiCell(16.59,0.5,utf8_decode("          Sin otro particular, agradezco la fineza de sus atenciones y me reitero a sus órdenes."),0 ,'J');
            
            $this->Ln(3);
            $this->setX(2.5);
            $this->SetFont('Arial','B',11);
            $this->cell(16.59,0.5,"A T E N T A M E N T E",0,0,'C');
            $this->Ln(3);
            $this->setX(2.5);
            $this->cell(16.59,0.5,utf8_decode("PROFR. HORACIO LÓPEZ SALINAS"),0,0,'C');
            $this->Ln(0.35);
            $this->setX(2.5);
            $this->SetFont('Arial','B',9);
            $this->cell(16.59,0.5,utf8_decode("DIRECTOR DEL FONDO DE RETIRO Y FALLECIMIENTO"),0,0,'C');

            
            $this->Image('/var/www/html/sistge/img/logoplanilla.png',17,23.3,2.57,2.38);
            
            $this->Image('/var/www/html/sistge/img/lineaoficio.png',1,25.8,19.7,0.05);
        }

        function Footer()
        {
            $this->SetY(-2.1);
            $this->SetFont('Arial','B',8);
            $this->setX(1.75);
            $this->Cell(17.9, 0.5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $this->Ln(0.7);
            
            $this->setX(1.4);
            $this->SetFont('Arial','B',7);
            $this->Cell(18.52, 0.5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $this->Ln(0.3);
            $this->setX(1.4);
            $this->Cell(18.52, 0.5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
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
    $pdf->SetMargins(2.5,1.02,2.5);

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $entregaOrdinal = $pdf->entregaOrdinal;
    $pdf->Output('6.OFICIO DE CHEQUES '.strtoupper($entregaOrdinal).' ENTREGA','I');
?>
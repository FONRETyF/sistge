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

            $statementT = $this->db->prepare("SELECT COUNT(*), SUM(montrettot) FROM public.tramites_fonretyf WHERE identrega='".$identr."'");
            $statementT->execute();
            $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);
            $fecha = fechactual();
            $montLetra= "(" . $this->CantLet = $cantidadLetras->cantidadLetras(str_replace("$","",str_replace(",","",$resultsT[0]['sum']))).")";

            $statementE = $this->db->prepare("SELECT numentrega,fechentrega FROM public.entregas_fonretyf WHERE identrega='".$identr."'");
            $statementE->execute();
            $resultsE = $statementE->fetchAll(PDO::FETCH_ASSOC);
            $entrOfic = $resultsE[0]['numentrega'];
            $fechaEntrega = substr($resultsE[0]['fechentrega'],8,2) . " DE " . $meses[intval(substr($resultsE[0]['fechentrega'],5,2))] . " DE " . substr($resultsE[0]['fechentrega'],0,4);
            $this->entregaOrdinal= $funciones->numordinales([intval(substr($identr,4,2))]);

            $statementTI = $this->db->prepare("SELECT COUNT(*), SUM(montrettot) FROM public.tramites_fonretyf WHERE identrega='".$identr."' and motvret='I'");
            $statementTI->execute();
            $resultsTI = $statementTI->fetchAll(PDO::FETCH_ASSOC);

            $statementTJ = $this->db->prepare("SELECT COUNT(*), SUM(montrettot) FROM public.tramites_fonretyf WHERE identrega='".$identr."' and motvret='J'");
            $statementTJ->execute();
            $resultsTJ = $statementTJ->fetchAll(PDO::FETCH_ASSOC);
            
            $statementTF = $this->db->prepare("SELECT COUNT(*), SUM(montrettot) FROM public.tramites_fonretyf WHERE identrega='".$identr."' and (motvret='FA' or motvret='FJ')");
            $statementTF->execute();
            $resultsTF = $statementTF->fetchAll(PDO::FETCH_ASSOC);

            $consulta="SELECT COUNT(*) FROM public.beneficiarios_cheques as tab1 INNER JOIN public.tramites_fonretyf as tab2 on tab1.cvemae=tab2.cvemae WHERE tab2.numentrega=".intval(substr($identr,4,2)). " and tab2.anioentrega=".substr($identr,0,4) ." and motvret<>'I' and motvret<>'J'";
            $statementTB = $this->db->prepare($consulta);
            $statementTB->execute();
            $resultsTB = $statementTB->fetchAll(PDO::FETCH_ASSOC);

            $consulta = "SELECT COUNT(*), SUM(montbenef) FROM public.beneficiarios_cheques WHERE numentrega=".intval(substr($identr,4,2))." and anioentrega=".substr($identr,0,4);
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

            $this->Ln(1.7);
            $this->setX(2.5);
            $this->SetFont('Arial','',11);
            setlocale(LC_ALL, 'es_MX');
            $this->SetTextColor(0,0,0);
            $this->Cell(16.59, 0.8, utf8_decode("Toluca, México a  " .$fecha),0, 0, 'R');

            $this->Ln(1);
            $this->setX(2.5);
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(0,0,0);
            $this->Cell(16.59, 0.4, utf8_decode('PROFR. MARCO AURELIO CARBAJAL LEYVA'), 0, 0, 'L');
            $this->Ln(0.4);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('SECRETARIO GENERAL DEL SMSEM'), 0, 0, 'L');
            $this->Ln(0.4);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('P R E S E N T E:'), 0, 0, 'L');

            $this->Ln(0.55);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode("EN AT'N."), 0, 0, 'R');
            $this->Ln(0.4);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('PROFRA. CLEOTILDE CASTILLO MENDEZ'), 0, 0, 'R');
            $this->Ln(0.4);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('SECRETARIA DE FINANZAS DEL SMSEM'),0, 0, 'R');
            $this->Ln(0.4);
            $this->setX(2.5);
            $this->Cell(16.59, 0.4, utf8_decode('P R E S E N T E:'), 0, 0, 'R');
            
            $this->SetFont('Arial','',11);
            $this->Ln(0.4);
            $this->setX(3.72);
            $this->Cell(15.37, 0.4, utf8_decode('Estimado Señor Secretario:'), 0, 0, 'L');
            $this->Ln(0.9);
            $this->setX(3.72);
            
            $this->Cell(15.37, 0.5, utf8_decode('Con el objeto de enviarle un fraternal saludo, así mismo informarle que a la fecha de hoy'), 0, 0, 'L');
            $this->Ln(0.5);
            $this->setX(2.5);
            $this->Cell(3.4, 0.5, utf8_decode('se tienen recibidas'), 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(1, 0.5, $resultsT[0]['count'], 0, 0, 'C');
            $this->SetFont('Arial','',11);
            $this->Cell(10, 0.5, "solicitudes de maestros que piden el pago del seguro del", 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(2.19, 0.5, "FONRETyF", 0, 0, 'C');
            $this->Ln(0.5);
            $this->SetFont('Arial','',11);
            $this->setX(2.5);
            $this->Cell(1.3, 0.5, "para la ", 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->SetX(3.9);
            $this->Write(0.5,$entrOfic);
            $this->subWrite(0.5,'a','',5,4);
            $this->SetFont('Arial','',11);
            $this->SetX(4.6);
            $this->Cell(14.475, 0.5, "entrega. Por tal  motivo solicito a  Usted gire  sus apreciables  instrucciones a quien", 0, 0, 'L');
            $this->Ln(0.5);
            $this->setX(2.5);
            $this->Cell(9.4, 0.5, "corresponda a efecto de que se realice el traspaso de", 0, 0, 'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(3.3, 0.5, $resultsT[0]['sum'], 0, 0, 'C');
            $this->Ln(0.5);
            $this->SetFont('Arial','',8);
            $this->Cell(16.59,0.5,$montLetra,0,0,'C');
            $this->Ln(0.5);
            $this->SetFont('Arial','',11);
            $this->MultiCell(16.59,0.5,"a la cuenta de cheques del Fondo de Retiro; cantidad destinada para el pago de los profesores Jubilados y por Fallecimiento.",0 ,'J');            

            $this->Ln(0.3);
            $this->setX(2.795);
            $this->SetFont('Arial','B',10);
            $this->SetFillColor(128,128,128);
            $this->cell(16,0.5,"CEREMONIA DEL ".$fechaEntrega,1,0,'C',true);
            $this->Ln(0.5);
            $this->SetFont('Arial','',10);
            $this->setX(2.795);
            $this->SetFillColor(191,191,191);
            $this->cell(4,0.5,"CONCEPTO",1,0,'C',true);
            $this->cell(4,0.5,"NUMERO",1,0,'C',true);
            $this->cell(4,0.5,"MONTO",1,0,'C',true);
            $this->cell(4,0.5,"BENEFICIARIOS",1,0,'C',true);
            $this->Ln(0.5);
            $this->setX(2.795);
            $this->cell(4,0.5,"INHABILITADOS",1,0,'C');
            $this->cell(4,0.5,$resultsTI[0]['count'],1,0,'C');
            $this->cell(4,0.5,$resultsTI[0]['sum'],1,0,'C');
            $this->cell(4,0.5,"",1,0,'C');
            $this->Ln(0.5);
            $this->setX(2.795);
            $this->cell(4,0.5,"JUBILADOS",1,0,'C');
            $this->cell(4,0.5,$resultsTJ[0]['count'],1,0,'C');
            $this->cell(4,0.5,$resultsTJ[0]['sum'],1,0,'C');
            $this->cell(4,0.5,"",1,0,'C');
            $this->Ln(0.5); 
            $this->setX(2.795);
            $this->cell(4,0.5,"FALLECIDOS",1,0,'C');
            $this->cell(4,0.5,$resultsTF[0]['count'],1,0,'C');
            $this->cell(4,0.5,$resultsTF[0]['sum'],1,0,'C');
            $this->cell(4,0.5,$resultsTB[0]['count'],1,0,'C');

            $this->Ln(1);
            $this->setX(2.795);
            $this->SetFont('Arial','B',10);
            $this->setX(2.795);
            $this->SetFillColor(191,191,191);
            $this->cell(4,0.5,"",1,0,'C',true);
            $this->cell(4,0.5, utf8_decode("TRÁMITES"),1,0,'C',true);
            $this->cell(4,0.5,"CHEQUES",1,0,'C',true);
            $this->cell(4,0.5,"MONTO",1,0,'C',true);
            $this->Ln(0.5);
            $this->setX(2.795);
            $this->cell(4,0.5,"TOTALES",1,0,'C');
            $this->cell(4,0.5,$resultsT[0]['count'],1,0,'C');
            $this->cell(4,0.5,$resultsTChqs[0]['count'],1,0,'C');
            $this->cell(4,0.5,$resultsT[0]['sum'],1,0,'C');
            
            $this->Ln(1);
            $this->SetFont('Arial','',11);
            $this->MultiCell(16.59,0.5,utf8_decode("Sin más por el momento me despido de usted, quedando a sus órdenes para cualquier aclaración."),0 ,'J');
            
            $this->Ln(0.2);
            $this->setX(2.5);
            $this->SetFont('Arial','B',10);
            $this->cell(16.59,0.5,"A T E N T A M E N T E",0,0,'C');
            $this->Ln(1.5);
            $this->setX(2.5);
            $this->cell(16.59,0.5,utf8_decode("PROFR. HORACIO LÓPEZ SALINAS"),0,0,'C');
            $this->Ln(0.35);
            $this->setX(2.5);
            $this->SetFont('Arial','B',9);
            $this->cell(16.59,0.5,utf8_decode("DIRECTOR DEL FONDO DE RETIRO Y FALLECIMIENTO"),0,0,'C');

            $this->Ln(1.5);
            $this->setX(2.5);
            $this->cell(16.59,0.5,utf8_decode("PROFR. JESÚS SOTELO SOTELO"),0,0,'C');
            $this->Ln(0.35);
            $this->setX(2.5);
            $this->SetFont('Arial','B',9);
            $this->cell(16.59,0.5,utf8_decode("SECRETARIO DE SEGURIDAD SOCIAL SINDICAL"),0,0,'C');

            $this->Ln(0.7);
            $this->setX(2.5);
            $this->cell(16.59,0.5,utf8_decode("AUTORIZÓ"),0,0,'C');
            $this->Ln(1.5);
            $this->setX(2.5);
            $this->cell(16.59,0.5,utf8_decode(" PROFR.  MARCO AURELIO CARBAJAL LEYVA"),0,0,'C');
            $this->Ln(0.35);
            $this->setX(2.5);
            $this->SetFont('Arial','B',9);
            $this->cell(16.59,0.5,utf8_decode("SECRETARIO GENERAL"),0,0,'C');
            
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
    $pdf->Output('5.OFICIO DE TRASPASO '.strtoupper($entregaOrdinal).' ENTREGA','I');
?>
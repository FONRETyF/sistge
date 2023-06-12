<?php
    
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');

    class PDF extends FPDF
    {
        private $db;

        function Header()
        {
            $folioAnt = $_GET['folio'];
            
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();

            $meses=[1=>"enero", 2=>"febrero", 3=>"marzo", 4=>"abril", 5=>"mayo", 6=>"junio", 7=>"julio", 8=>"agosto", 9=>"septiembre", 10=>"octubre", 11=>"noviembre", 12=>"dociembre"];
            $fecha = fechactual();

            $statementC = $this->db->prepare("SELECT anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, nombenef, montbenef, fechcheque, observreposcn FROM public.beneficiarios_cheques WHERE folanterior = ?");
            $statementC -> bindvalue(1,$folioAnt);
            $statementC->execute();
            $statementC = $statementC->fetchAll(PDO::FETCH_ASSOC);

            $fechCheque = substr($statementC[0]['fechcheque'],8,2) . " de " . $meses[intval(substr($statementC[0]['fechcheque'],5,2))] . " de " . substr($statementC[0]['fechcheque'],0,4);

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
            $this->Ln(1.1);
            
            $this->Cell(16.59, 0.5, utf8_decode('          Por  este medio  reciba un cordial  saludo y  al mismo  tiempo  solicito  su valiosa'), 0, 0, 'L');
            $this->Ln(0.8);
            $this->setX(2.5);
            $this->Cell(11.7, 0.5, utf8_decode('intervención  para proporcionar  la corrección  del cheque No. '), 0, 0, 'L');
            $this->SetFont('Arial','B',12);
            $this->Cell(2.3, 0.5,$folioAnt, 0, 0, 'C');
            $this->SetFont('Arial','',12);
            $this->Cell(2.59, 0.5, "a nombre de", 0, 0, 'R');
            $this->Ln(0.8);
            $this->setX(2.5);
            $this->SetFont('Arial','B',11);
            $this->Cell(14.4, 0.5, utf8_decode($statementC[0]["nombenef"]), 0, 0, 'C');
            $this->SetFont('Arial','',12);
            $this->Cell(2.19, 0.5, " emitido el", 0, 0, 'L');

            $this->Ln(0.8);
            
            $this->Cell(5.3, 0.5,$fechCheque. ",", 0, 0, 'C');
            $this->Cell(3.7, 0.5, " por la cantidad de", 0, 0, 'L');
            $this->Cell(3.1, 0.5, $statementC[0]["montbenef"].",", 0, 0, 'C');
            $this->Cell(4.49, 0.5, " ya  que  fue cancelado", 0, 0, 'C');

            $textMotvRepos = "debido a que " . $statementC[0]["observreposcn"] . ".";

            $this->Ln(0.8);
            $this->SetFont('Arial','',12);
            $this->MultiCell(16.59,0.8,utf8_decode($textMotvRepos),0 ,'J');

            $this->Ln(1.2);
            $this->SetFont('Arial','',12);
            $this->MultiCell(16.59,0.5,utf8_decode("      Sin otro particular, agradezco la fineza de sus atenciones y me reitero a sus órdenes."),0 ,'J');
                       
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
    
    $pdf = new PDF("P","cm",array(21.59,27.94));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(2.5,1.02,2.5);

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $pdf->Output('OFICIO DE REPOSICION','I');
?>
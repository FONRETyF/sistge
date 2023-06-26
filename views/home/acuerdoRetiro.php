<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');

    class PDF extends FPDF
    {
        private $db;
    // Cabecera de página
    function Header()
    {
        // Logo
        $identretiro = $_GET['identret'];
        
        $pdo = new dbfonretyf();
        $this->db=$pdo->conexfonretyf();

        $statementT = $this->db->prepare("SELECT cvemae,motvret,fechbajfall,nomsolic,modretiro,montrettot,montretletra,montretentr,montretentrletra,montretfall,montretfallletra,foliotramite FROM public.tramites_fonretyf WHERE identret='".$identretiro."'");
        $statementT->execute();
        $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);
        if ($resultsT[0]['motvret'] == "I") {
            $motivoRetiro = "INHABILITACIÓN";
        } elseif ($resultsT[0]['motvret'] == "J") {
            $motivoRetiro = "JUBILACIÓN";
        }
        

        $statementM = $this->db->prepare("SELECT csp,curpmae,regescmae,fcbasemae,aservactmae,numpsgs,diaspsgs,fechsinipsgs,fechsfinpsgs FROM public.maestros_smsem WHERE csp='".$resultsT[0]['cvemae']."'");
        $statementM->execute();
        $resultsM = $statementM->fetchAll(PDO::FETCH_ASSOC);
        

        $this->Image('/var/www/html/sistge/img/escudooficio.png',10,3,37,45);
        $this->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',50,85,122,130);
        // Arial bold 15
        $this->AddFont('SegoeUIBL','','seguibl.php');
        $this->AddFont('arialbi','','arialbi.php');
        $this->SetFont('SegoeUIBL','',19);
        // Movernos a la derecha
        $this->Cell(25);
        $this->SetTextColor(123,123,123);
        $this->Cell(160, 8, 'SINDICATO DE MAESTROS',0, 1, 'C');
        $this->Cell(25,2);
        $this->SetFont('SegoeUIBL','',19);
        $this->Cell(160, 10, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
        
        $this->Cell(25);
        $this->SetFont('arialbi','',12);
        $this->SetTextColor(213,213,213);
        $this->Cell(155, 3, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

        $this->Ln(8);
        $this->Cell(25);
        $this->SetFont('Arial','B',12);
        $this->SetTextColor(0,0,0);
        $this->Cell(160, 8, utf8_decode('DIRECCION DE FONDO DE RETIRO  Y FALLECIMIENTO'),0, 0, 'C');
        $this->Ln(5);
        $this->Cell(25);
        $this->Cell(160, 8, utf8_decode('"FONRETyF"'),0, 0, 'C');

        $this->Ln(15);
        $this->Cell(15);
        $this->SetFont('Arial','B',11);
        $this->SetTextColor(0,0,0);
        $this->Cell(165, 8, utf8_decode('ACUERDO DEL SEGURO DE RETIRO POR '.$motivoRetiro), 0, 0, 'C');

        $this->Ln(12);
        $this->Cell(130);
        $this->SetFont('Arial','B',12);
        $this->SetTextColor(15,83,183);
        $this->Cell(59, 8, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');
        



        setlocale(LC_ALL, 'es_MX');
        $fecha = fechactual();
        $this->SetTextColor(0,0,0);
        $this->Ln(14);
        $this->Cell(120);
        $this->SetFont('Arial','I',11);
        $this->Cell(70, 8, utf8_decode("Toluca, México a  " .$fecha), 0, 0, 'R');


        // Salto de línea
        

        

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
        $this->Cell(55, 8,$resultsM[0]['curpmae'], 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(99, 8, utf8_decode(" quien  laboraba  en  la  región  sindical"), 0, 0, 'L');

        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','B',11);
        $this->Image('/var/www/html/sistge/img/lineafirma.png',21,120,10,0.4);
        $this->Cell(11, 8,$resultsM[0]['regescmae'], 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(62, 8,"como docente basificado (a) del ", 0, 0, 'L');
        $this->Image('/var/www/html/sistge/img/lineafirma.png',93,120,28,0.4);
        $this->SetFont('Arial','B',11);
        $this->Cell(28, 8,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(8, 8," al ", 0, 0, 'L');
        $this->Image('/var/www/html/sistge/img/lineafirma.png',129,120,28,0.4);
        $this->SetFont('Arial','B',11);
        $this->Cell(28, 8,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(40, 8,", periodo  durante  el", 0, 0, 'L');

        $this->Ln(7);
        $this->Cell(10);
        //$this->Cell(109, 8," periodo durante el cual tuve   " .  ."   psgs,", 0, 0, 'L');
        $this->SetFont('Arial','',12);
        $this->Cell(19, 8,"cual tuve ",0, 0, 'L');
        $this->SetFont('Arial','B',11);
        $this->Image('/var/www/html/sistge/img/lineafirma.png',40,127,10,0.4);
        $this->Cell(11, 8,$resultsM[0]['numpsgs'], 0, 0, 'C');
        $this->SetFont('Arial','',12);
        $this->Cell(135, 8,utf8_decode("permisos sin goce de sueldo, por lo que solicito el Seguro de Retiro por"),0, 0, 'L');
        $this->SetFont('Arial','B',11);
        $this->Image('/var/www/html/sistge/img/lineafirma.png',185,127,13,0.4);
        $this->Cell(13, 8,$resultsM[0]['aservactmae'], 0, 0, 'C');

        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','',12);
        $this->Cell(178, 8, utf8_decode("años de servicio cotizados al SMSEM, con fundamento en los artículos       ,      ,      ,      ,      ,"), 0, 0, 'L');

        $this->Ln(7);
        $this->Cell(10);
        $this->SetFont('Arial','',12);
        $this->Cell(178, 8, utf8_decode("del Reglamento del Fondo de Retiro y Fallecimiento (FONRETyF)."), 0, 0, 'L');

        $this->Ln(9);
        $this->Cell(10);
        $this->SetFont('Arial','',12);
        $this->Cell(179, 8,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
        
        $this->Ln(9);
        $this->Cell(10);
        $this->SetFont('Arial','B',12);
        $this->SetTextColor(15,83,183);
        $this->Cell(40, 8,$resultsT[0]['montrettot'],0, 0, 'R');
        $this->SetFont('Arial','B',9.5);
        $this->Cell(139, 8,"(". $resultsT[0]['montretletra'] .")",0, 0, 'L');
        
        if ($resultsT[0]['modretiro'] == "C") {
            $this->Ln(9);
            $this->Cell(10);
            $this->SetFont('Arial','',12);
            $this->SetTextColor(0,0,0);
            $this->Cell(179, 8,"Hago de  su conocimiento que  el equivalente  al Fondo de Retiro  me sea  entregado de forma", 0, 0, 'L');
            $this->Ln(7);
            $this->Cell(10);
            $this->SetFont('Arial','B',12);
            $this->Cell(27, 8, "COMPLETA", 0, 0, 'C');
            $this->SetFont('Arial','',12);
            $this->Cell(179, 8,"y firmo de enterado (a) y de conformidad al respecto, que a partir de que me sea", 0, 0, 'L');
            $this->Ln(7);
            $this->Cell(10);
            $this->Cell(179, 8,utf8_decode("entergado, se rescinde toda obligación por  parte del  FONRETyF de contar con el beneficio de"),0, 0, 'L');
            $this->Ln(7);
            $this->Cell(10);
            $this->Cell(179, 8,utf8_decode("Fondo por Fallecimiento"),0, 0, 'L');
            
        } else {
            if ($resultsT[0]['modretiro'] == "D50") {
                $this->Ln(9);
                $this->Cell(10);
                $this->SetFont('Arial','',12);
                $this->SetTextColor(0,0,0);
                $this->Cell(179, 8,"Hago de  su conocimiento que  el equivalente al Fondo de Retiro  me sea  entregado de forma", 0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->SetFont('Arial','B',12);
                $this->Cell(28, 8, "DIFERIDA", 0, 0, 'C');
                $this->SetFont('Arial','',12);
                $this->Cell(179, 8,",  dejando  el  50%  del   monto  total  al  resguardo   del  FONRETyF  para  mis", 0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("beneficiarios, y llevándome el 50% restante y firmo de enterado (a) y conformidad al respecto,"),0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("y que a partir de este momento se me realice el descuento anual, correspondiente a un día de"),0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("mi pensión, para contar con el beneficio de Fondo por Fallecimiento."),0, 0, 'L');
            } elseif ($resultsT[0]['modretiro'] == "D100") {
                $this->Ln(9);
                $this->Cell(10);
                $this->SetFont('Arial','',12);
                $this->SetTextColor(0,0,0);
                $this->Cell(179, 8,"Hago de  su conocimiento que  el equivalente al Fondo de Retiro  me sea  entregado de forma", 0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->SetFont('Arial','B',12);
                $this->Cell(32.5, 8, "PRORROGADA", 0, 0, 'C');
                $this->SetFont('Arial','',12);
                $this->Cell(179, 8,", dejando el 100%  del  monto total  al  resguardo  del  FONRETyF  para  mis", 0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("beneficiarios, firmo de enterado (a) y  conformidad al respecto, y que a partir de este momento"),0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("se me realice el descuento anual, correspondiente a un día de mi pensión,  para contar con el"),0, 0, 'L');
                $this->Ln(7);
                $this->Cell(10);
                $this->Cell(179, 8,utf8_decode("beneficio de Fondo por Fallecimiento."),0, 0, 'L');
            }
        }

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
    $pdf = new PDF("P","mm","Letter");
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);

    $pdf->Output();


?>
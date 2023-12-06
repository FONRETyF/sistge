<?php
    require('/var/www/html/sistge/fpdf/fpdf.php');
    require('/var/www/html/sistge/config/dbfonretyf.php');
    require("/var/www/html/sistge/model/cantidadLetras.php");

    class PDF extends FPDF
    {
        private $db;
        private $cantletra;
       
        function Header()
        {
            $identretiro = $_GET['identret'];
            
            $pdo = new dbfonretyf();
            $this->cantletra = new cantidadLetras();

            $this->db=$pdo->conexfonretyf();

            $statementT = $this->db->prepare("SELECT cvemae,motvret,fechbajfall,nomsolic,modretiro,montrettot,montretletra,montretsinads,montretentr,montretentrletra,montretfall,montretfallletra,foliotramite,numadeds,montadeudos,adfajam,adts,adfondpension,adturismo,adceso FROM public.tramites_fonretyf WHERE identret='".$identretiro."'");
            $statementT->execute();
            $resultsT = $statementT->fetchAll(PDO::FETCH_ASSOC);
           if ($resultsT[0]['motvret'] == "FRI") {
                $motivoRetiroLower = "Inhabilitación";
                $motivoRetiro = "INHABILITACIÓN";
            } elseif ($resultsT[0]['motvret'] == "FRJ") {
                $motivoRetiroLower = "Jubilación";
                $motivoRetiro = "JUBILACIÓN";
            }elseif ($resultsT[0]['motvret'] == "FRR") {
                $motivoRetiroLower = "Renuncia";
                $motivoRetiro = "RENUNCIA";
            }elseif ($resultsT[0]['motvret'] == "FRD") {
                $motivoRetiroLower = "Rescisión";
                $motivoRetiro = "RESCISIÓN";
            }

            $montototal = str_replace("$","",(str_replace(",", "", $resultsT[0]['montrettot'])));
            $montoAdeudos = str_replace("$","",(str_replace(",", "", $resultsT[0]['montadeudos'])));
            $retsinadeudos = $montototal - $montoAdeudos; 
            $montoretsinAdeudos =  number_format($retsinadeudos,2);
                                    
            $montsindAdeudos = $this->cantletra->cantidadLetras($retsinadeudos);

            $statementM = $this->db->prepare("SELECT csp,curpmae,regescmae,fcbasemae,aservactmae,numpsgs,numcelmae,diaspsgs,fechsinipsgs,fechsfinpsgs FROM public.maestros_smsem WHERE csp='".$resultsT[0]['cvemae']."'");
            $statementM->execute();
            $resultsM = $statementM->fetchAll(PDO::FETCH_ASSOC);
            
            $llaves_array = array ("{", "}");
            $fechIniTemp=str_replace($llaves_array,"",$resultsM[0]["fechsinipsgs"]);
            $fechFinTemp=str_replace($llaves_array,"",$resultsM[0]["fechsfinpsgs"]);
    
            $fechasIni = explode("," , $fechIniTemp);
            $fechasFin = explode("," , $fechFinTemp);

            $fechasPSGS = '';
            for ($i=0; $i < count($fechasIni); $i++) { 
                $fechaI = explode(":" , $fechasIni[$i]);
                $fechaF = explode(":" , $fechasFin[$i]);
                if ($i == count($fechasIni) - 1 ) {
                    $fechasPSGS =  $fechasPSGS . date("d-m-Y",strtotime($fechaI[1])) . " al " . date("d-m-Y",strtotime($fechaF[1]));
                }else {
                    $fechasPSGS =  $fechasPSGS . date("d-m-Y",strtotime($fechaI[1])) . " al " . date("d-m-Y",strtotime($fechaF[1])) . " , ";
                }
            }

            $statementB = $this->db->prepare("SELECT * FROM public.beneficiarios_cheques WHERE cvemae='".$resultsT[0]['cvemae']."' and chequeadeudo='S';");
            $statementB->execute();
            $resultsB = $statementB->fetchAll(PDO::FETCH_ASSOC);

            $this->Image('/var/www/html/sistge/img/escudooficio.png',10,7,37,45);
            $this->Image('/var/www/html/sistge/img/escudofondoAcuerdo.jpg',50,85,122,130);
            
            $this->AddFont('SegoeUIBL','','seguibl.php');
            $this->AddFont('arialbi','','arialbi.php');
            $this->SetFont('SegoeUIBL','',19);
            
            $this->Cell(20,0.1);
            $this->SetTextColor(43,43,43);
            $this->Cell(160, 5, 'SINDICATO DE MAESTROS',0, 1, 'C');
            $this->Cell(20,0.3);
            $this->SetFont('SegoeUIBL','',19);
            $this->Cell(160, 7, utf8_decode('AL SERVICIO DEL ESTADO DE MÉXICO'),0, 1, 'C');
            
            $this->Ln(1);
            $this->Cell(20);
            $this->SetFont('arialbi','',12);
            $this->SetTextColor(43,43,43);
            $this->Cell(155, 4, utf8_decode('"Por la Educación al Servicio del Pueblo"'), 0, 0, 'C');

            $this->Ln(7);
            $this->Cell(20);
            $this->SetFont('Arial','B',12);
            $this->SetTextColor(0,0,0);
            $this->Cell(160, 8, utf8_decode('DIRECCION DE FONDO DE RETIRO Y FALLECIMIENTO'),0, 0, 'C');
            $this->Ln(5);
            $this->Cell(20);
            $this->Cell(160, 8, utf8_decode('"FONRETyF"'),0, 0, 'C');

            $this->Ln(15);
            $this->SetFont('Arial','B',12);
            $this->SetTextColor(0,0,0);
            $this->Cell(185.9, 5, utf8_decode('ACUERDO DE FONDO DE RETIRO POR   '.$motivoRetiro), 0, 0, 'C');

            $this->Ln(8);
            $this->Cell(127);
            $this->SetFont('Arial','B',11.5);
            $this->SetTextColor(15,83,183);
            $this->Cell(59, 8, "Folio:   ". $resultsT[0]['foliotramite'],0, 0, 'R');
            
            setlocale(LC_ALL, 'es_MX');
            $fecha = fechactual();
            $this->SetTextColor(0,0,0);
            $this->Ln(7);
            $this->Cell(117);
            $this->SetFont('Arial','',11);
            $this->Cell(70, 8, utf8_decode("Toluca, México a  " .$fecha), 0, 0, 'R');


           
            if ($resultsM[0]['numpsgs'] == 0) {
                if ($resultsT[0]['numadeds'] == 0) {
                    $sltL = 6;
                    $pXLs = 3;
                    $this->Ln(20 + $pXLs);
                    
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',22,90.5 + $pXLs,117,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(8, 7.5, "Yo ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(115, 7.5, $resultsT[0]['nomsolic'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62.9, 7.5, " con  clave  de  servidor   publico", 0, 0, 'L');

                    $this->Ln(6);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',16,96.5 + $pXLs,24,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25, 7.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',57,96.5 + $pXLs,55,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(17, 7.5,", CURP  ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(55, 7.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',145,96.5 + $pXLs,27,0.4);
                    $this->Cell(32, 7.5, utf8_decode("y número celular "), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(28, 7.5,$resultsM[0]['numcelmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(28.9, 7.5, utf8_decode(",  labore  en  la"), 0, 0, 'L');

                    $this->Ln(6);
                    $this->Cell(44, 7.5, utf8_decode("región sindical número"), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',59,102.5 + $pXLs,9,0.4);
                    $this->Cell(9, 7.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62, 7.5,"como docente basificado (a) del ", 0, 0, 'L');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',130,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(4, 7.5," al ", 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',159.5,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(15.9, 7.5,", periodo", 0, 0, 'C');

                    $this->Ln(6);
                    $this->SetFont('Arial','',12);
                    $this->Cell(38, 7.5,"durante el cual tuve ",0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',53,108.5 + $pXLs,10,0.4);
                    $this->Cell(10, 7.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(137.9, 7.5,utf8_decode("permisos  sin  goce de sueldo,  por lo cual  solicito el Fondo de Retiro  en"), 0, 0, 'L');
                    
                    $this->Ln(6);
                    $this->Cell(24, 7.5,utf8_decode("virtud de mi"), 0, 0, 'L');
                    $this->SetFont('Arial','I',12);
                    $this->Cell(29, 7.5,utf8_decode($motivoRetiroLower), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(12, 7.5,utf8_decode("con"), 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',80,117.5,11,0.5);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(11, 7.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(109.9, 7.5, utf8_decode("años de servicio cotizados al SMSEM, con fundamento en"), 0, 0, 'L');
                        
                    $this->Ln(6);
                    $this->SetFont('Arial','',12);
                    $this->MultiCell(185.9, 6, utf8_decode("los artículos 30, 31, 32, 35, 36, 37, 38, 39, 43 y 44 del  Reglamento del  Fondo  de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');         
                    
                    $this->Ln($sltL + 1);
                    $this->SetFont('Arial','',12);
                    $this->Cell(185.9, 7.5,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
                    
                    $this->Ln(8);
                    $this->SetFont('Arial','B',12);
                    $this->SetTextColor(15,83,183);
                    $this->Cell(40, 7.5,"$".$montoretsinAdeudos,0, 0, 'R');
                    $this->SetFont('Arial','B',9.5);
                    $this->Cell(139, 7.5,"(". $montsindAdeudos .")",0, 0, 'L');
                    
                    
                    if ($resultsT[0]['modretiro'] == "C") {
                        $this->Ln(9);
                        $this->SetFont('Arial','',12);
                        $this->SetTextColor(0,0,0);
                        $this->Cell(185.9, 7.5,"Hago  de  su  conocimiento  que  el  equivalente  al  Fondo de Retiro  me sea  entregado de  forma", 0, 0, 'L');
                        
                        $this->Ln($sltL);
                        $this->SetFont('Arial','B',12);
                        $this->Cell(27, 7.5, "COMPLETA", 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(158.9, 7.5,", asi  mismo, firmo  de enterado (a) y  de  conformidad  al  respecto, que  a  partir de", 0, 0, 'L');
                        
                        $this->Ln($sltL);
                        $this->Cell(185.9, 7.5,utf8_decode("que me  sea entregado, se  rescinde toda  obligación por  parte  del  FONRETyF de  contar con  el"),0, 0, 'L');
                        $this->Ln($sltL);
                        $this->Cell(185.9, 7.5,utf8_decode("beneficio de Fondo por Fallecimiento."),0, 0, 'L');

                        $this->Ln($sltL + 8);
                        $this->SetFont('Arial','',9);
                        $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                        $this->Image('/var/www/html/sistge/img/lineafirma.png',64,233,90,0.4);
                        $this->SetY(232);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                        $this->SetY(245);
                        
                        $this->SetFont('Arial','I',8);
                        $this->Ln(4);
                        $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                        
                        $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);

                    }   else {
                        
                        if ($resultsT[0]['modretiro'] == "D50") {
                            $this->Ln(8);
                            $this->SetFont('Arial','',12);
                            $this->SetTextColor(0,0,0);
                            $this->Cell(185.9, 7.5,"Hago  de  su  conocimiento  que  el equivalente al  Fondo de Retiro  me  sea  entregado  de  forma", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(28, 7.5, "DIFERIDA", 0, 0, 'C');
                            $this->SetFont('Arial','',12);
                            $this->Cell(33, 7.5,", dejando el 50% del monto total", 0, 0, 'L');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',9);
                            $this->Cell(185.9, 7.5,$resultsT[0]["montretfall"] . " (" . $resultsT[0]["montretfallletra"] . ")", 0, 0, 'C');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',12);
                            $this->Cell(185.9, 7.5,utf8_decode("al resguardo del FONRETyF  para mis beneficiarios, y llevándome el  50% restante"), 0, 0, 'L');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',9);
                            $this->Cell(185.9, 7.5,$resultsT[0]["montretentr"] . " (" . $resultsT[0]["montretentrletra"] . ")", 0, 0, 'C');
                            $this->Ln($sltL);
                            $this->SetFont('Arial','',12);
                            $this->MultiCell(185.9, 6, utf8_decode(", asi mismo, firmo de enterado (a) y conformidad  al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión, para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                            $this->Ln($sltL);
                            $this->SetFont('Arial','',9);
                            $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                            $this->Image('/var/www/html/sistge/img/lineafirma.png',64,238,90,0.4);
                            $this->SetY(237);
                            $this->SetFont('Arial','B',11);
                            $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                            $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                            $this->SetY(245);
                            
                            $this->SetFont('Arial','I',8);
                            $this->Ln(4);
                            $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                            
                            $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                        } 
                        
                        elseif ($resultsT[0]['modretiro'] == "D100") {
                            $this->Ln(9);
                            $this->SetFont('Arial','',12);
                            $this->SetTextColor(0,0,0);
                            $this->Cell(185.9, 7.5,"Hago  de  su conocimiento  que  el equivalente  al  Fondo de Retiro  me  sea  entregado de  forma", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(36, 7.5, "PRORROGADA", 0, 0, 'C');
                            $this->SetFont('Arial','',12);
                            $this->Cell(149.9, 7.5,", dejando  el  100%  del  monto  total  al  resguardo  del  FONRETyF  para  mis", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->MultiCell(185.9, 6, utf8_decode("beneficiarios, asi mismo, firmo de enterado (a) y  conformidad al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión,  para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                            $this->Ln($sltL);
                            $this->SetFont('Arial','',9);
                            $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                            $this->Image('/var/www/html/sistge/img/lineafirma.png',64,236,90,0.4);
                            $this->SetY(235);
                            $this->SetFont('Arial','B',11);
                            $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                            $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                            $this->SetY(245);
                            
                            $this->SetFont('Arial','I',8);
                            $this->Ln(4);
                            $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                            
                            $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                        }
                    } 
                } else {
                    /*   TIENEN ADEUDOS Y SIN PSGS   */
                    $sltL = 6;
                    $pXLs = 3;
                    $this->Ln(20 + $pXLs);
                    
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',22,90.5 + $pXLs,117,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(8, 7.5, "Yo ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(115, 7.5, $resultsT[0]['nomsolic'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62.9, 7.5, " con  clave  de  servidor   publico", 0, 0, 'L');

                    $this->Ln(6);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',16,96.5 + $pXLs,24,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25, 7.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',57,96.5 + $pXLs,55,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(17, 7.5,", CURP  ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(55, 7.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',145,96.5 + $pXLs,27,0.4);
                    $this->Cell(32, 7.5, utf8_decode("y número celular "), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(28, 7.5,$resultsM[0]['numcelmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(28.9, 7.5, utf8_decode(",  labore  en  la"), 0, 0, 'L');

                    $this->Ln(6);
                    $this->Cell(44, 7.5, utf8_decode("región sindical número"), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',59,102.5 + $pXLs,9,0.4);
                    $this->Cell(9, 7.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62, 7.5,"como docente basificado (a) del ", 0, 0, 'L');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',130,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(4, 7.5," al ", 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',159.5,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(15.9, 7.5,", periodo", 0, 0, 'C');

                    $this->Ln(6);
                    $this->SetFont('Arial','',12);
                    $this->Cell(38, 7.5,"durante el cual tuve ",0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',53,108.5 + $pXLs,10,0.4);
                    $this->Cell(10, 7.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(137.9, 7.5,utf8_decode("permisos  sin  goce de sueldo,  por lo cual  solicito el Fondo de Retiro  en"), 0, 0, 'L');
                    
                    $this->Ln(6);
                    $this->Cell(24, 7.5,utf8_decode("virtud de mi"), 0, 0, 'L');
                    $this->SetFont('Arial','I',12);
                    $this->Cell(29, 7.5,utf8_decode($motivoRetiroLower), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(12, 7.5,utf8_decode("con"), 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',80,117.5,11,0.5);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(11, 7.5,$resultsM[0]['aservactmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(109.9, 7.5, utf8_decode("años de servicio cotizados al SMSEM, con fundamento en"), 0, 0, 'L');
                        
                    $this->Ln(6);
                    $this->SetFont('Arial','',12);
                    $this->MultiCell(185.9, 6, utf8_decode("los artículos 30, 31, 32, 35, 36, 37, 38, 39, 43 y 44 del  Reglamento del  Fondo  de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');         
                    
                    $this->Ln($sltL - 5);
                    $this->SetFont('Arial','',12);
                    $this->Cell(108, 7.5,utf8_decode("Del  mismo  modo, reconozco  que tengo  un  adeudo de"),0, 0, 'L');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',123,136.5,30,0.5);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(30, 7.5,$resultsT[0]['montadeudos'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(47.9, 7.5,utf8_decode(" correspondiente  a la (s)"),0, 0, 'L');

                    $this->Ln(6);
                    $this->Cell(108, 7.5,utf8_decode("siguente (s) oficina (s) del SMSEM:"),0, 0, 'L');

                    /* DESGLOSE DE ADEUDOS*/
                    $this->Ln(6.5);
                    $this->SetX(70);
                    $this->SetFont('Arial','B',8);
                    $this->cell(50,4,'Oficina',0,0,'C');
                    $this->cell(28,4,'Monto de Adeudo',0,0,'C');
                    $this->SetDrawColor(150,150,150);
                    $this->SetLineWidth(0.5);
                    $this->SetFillColor(200,200,200);
                    $this->SetDrawColor(100,100,100);
                    $this->Ln(3.5);

                    foreach ($resultsB as $rowBenef) {
                        $this->SetX(70);
                        $this->SetFont('Arial','',8);
                        $this->SetLineWidth(0.01);
                        $this->cell(50,4,utf8_decode($rowBenef["adeudo"]),1,0,'C');
                        $this->cell(28,4,$rowBenef["montbenef"],1,0,'C');
                        $this->Ln(4);
                    }
                   
                    $this->Ln($sltL -4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(185.9, 7.5,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
                    
                    $this->Ln(8);
                    $this->SetFont('Arial','B',12);
                    $this->SetTextColor(15,83,183);
                    $this->Cell(40, 7.5,"$".$montoretsinAdeudos,0, 0, 'R');
                    $this->SetFont('Arial','B',9.5);
                    $this->Cell(139, 7.5,"(". $montsindAdeudos .")",0, 0, 'L');
                    
                    
                    if ($resultsT[0]['modretiro'] == "C") {
                        $this->Ln(9);
                        $this->SetFont('Arial','',12);
                        $this->SetTextColor(0,0,0);
                        $this->Cell(185.9, 7.5,"Hago  de  su  conocimiento  que  el  equivalente  al  Fondo de Retiro  me sea  entregado de  forma", 0, 0, 'L');
                        
                        $this->Ln($sltL);
                        $this->SetFont('Arial','B',12);
                        $this->Cell(27, 7.5, "COMPLETA", 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(158.9, 7.5,", asi  mismo, firmo  de enterado (a) y  de  conformidad  al  respecto, que  a  partir de", 0, 0, 'L');
                        
                        $this->Ln($sltL);
                        $this->Cell(185.9, 7.5,utf8_decode("que me  sea entregado, se  rescinde toda  obligación por  parte  del  FONRETyF de  contar con  el"),0, 0, 'L');
                        $this->Ln($sltL);
                        $this->Cell(185.9, 7.5,utf8_decode("beneficio de Fondo por Fallecimiento."),0, 0, 'L');

                        $this->Ln($sltL + 8);
                        $this->SetFont('Arial','',9);
                        $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                        $this->Image('/var/www/html/sistge/img/lineafirma.png',64,233,90,0.4);
                        $this->SetY(232);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                        $this->SetY(245);
                        
                        $this->SetFont('Arial','I',8);
                        $this->Ln(4);
                        $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                        
                        $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);

                    }   else {
                        
                        if ($resultsT[0]['modretiro'] == "D50") {
                            $this->Ln(8);
                            $this->SetFont('Arial','',12);
                            $this->SetTextColor(0,0,0);
                            $this->Cell(185.9, 7.5,"Hago  de  su  conocimiento  que  el equivalente al  Fondo de Retiro  me  sea  entregado  de  forma", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(28, 7.5, "DIFERIDA", 0, 0, 'C');
                            $this->SetFont('Arial','',12);
                            $this->Cell(33, 7.5,", dejando el 50% del monto total", 0, 0, 'L');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',9);
                            $this->Cell(185.9, 7.5,$resultsT[0]["montretfall"] . " (" . $resultsT[0]["montretfallletra"] . ")", 0, 0, 'C');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',12);
                            $this->Cell(185.9, 7.5,utf8_decode("al resguardo del FONRETyF  para mis beneficiarios, y llevándome el  50% restante"), 0, 0, 'L');
                            $this->Ln($sltL-1);
                            $this->SetFont('Arial','',9);
                            $this->Cell(185.9, 7.5,$resultsT[0]["montretentr"] . " (" . $resultsT[0]["montretentrletra"] . ")", 0, 0, 'C');
                            $this->Ln($sltL);
                            $this->SetFont('Arial','',12);
                            $this->MultiCell(185.9, 6, utf8_decode(", asi mismo, firmo de enterado (a) y conformidad  al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión, para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                            $this->Ln($sltL);
                            $this->SetFont('Arial','',9);
                            $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                            $this->Image('/var/www/html/sistge/img/lineafirma.png',64,238,90,0.4);
                            $this->SetY(237);
                            $this->SetFont('Arial','B',11);
                            $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                            $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                            $this->SetY(245);
                            
                            $this->SetFont('Arial','I',8);
                            $this->Ln(4);
                            $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                            
                            $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                        } 
                        
                        elseif ($resultsT[0]['modretiro'] == "D100") {
                            $this->Ln(9);
                            $this->SetFont('Arial','',12);
                            $this->SetTextColor(0,0,0);
                            $this->Cell(185.9, 7.5,"Hago  de  su conocimiento  que  el equivalente  al  Fondo de Retiro  me  sea  entregado de  forma", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(36, 7.5, "PRORROGADA", 0, 0, 'C');
                            $this->SetFont('Arial','',12);
                            $this->Cell(149.9, 7.5,", dejando  el  100%  del  monto  total  al  resguardo  del  FONRETyF  para  mis", 0, 0, 'L');
                            
                            $this->Ln($sltL);
                            $this->MultiCell(185.9, 6, utf8_decode("beneficiarios, asi mismo, firmo de enterado (a) y  conformidad al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión,  para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                            $this->Ln($sltL);
                            $this->SetFont('Arial','',9);
                            $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                            $this->Image('/var/www/html/sistge/img/lineafirma.png',64,236,90,0.4);
                            $this->SetY(235);
                            $this->SetFont('Arial','B',11);
                            $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                            $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                            $this->SetY(245);
                            
                            $this->SetFont('Arial','I',8);
                            $this->Ln(4);
                            $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                            
                            $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                        }
                    }
                }           
            } else {
                
                if ($resultsT[0]['modretiro'] == "C") {
                    $sltL = 6;
                    $pXLs = 3;
                    $this->Ln(20 + $pXLs);
                    
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',22,90.5 + $pXLs,117,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(8, 7.5, "Yo ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(115, 7.5, $resultsT[0]['nomsolic'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62.9, 7.5, " con  clave  de  servidor   publico", 0, 0, 'L');
    
                    $this->Ln(6);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',16,96.5 + $pXLs,24,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25, 7.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',57,96.5 + $pXLs,55,0.4);
                    $this->SetFont('Arial','',12);
                    $this->Cell(17, 7.5,", CURP  ", 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(55, 7.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',145,96.5 + $pXLs,27,0.4);
                    $this->Cell(32, 7.5, utf8_decode("y número celular "), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Cell(28, 7.5,$resultsM[0]['numcelmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(28.9, 7.5, utf8_decode(",  labore  en  la"), 0, 0, 'L');
    
                    $this->Ln($sltL);
                    $this->Cell(44, 7.5, utf8_decode("región sindical número"), 0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',59,102.5 + $pXLs,9,0.4);
                    $this->Cell(9, 7.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(62, 7.5,"como docente basificado (a) del ", 0, 0, 'L');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',130,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(4, 7.5," al ", 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',159.5,102.5 + $pXLs,25.5,0.4);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(15.9, 7.5,", periodo", 0, 0, 'C');
    
                    $this->Ln($sltL);
                    $this->SetFont('Arial','',12);
                    $this->Cell(38, 7.5,"durante el cual tuve ",0, 0, 'L');
                    $this->SetFont('Arial','B',11);
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',53,108.5 + $pXLs,10,0.4);
                    $this->Cell(10, 7.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(137.9, 7.5,utf8_decode("permisos  sin  goce de sueldo:"), 0, 0, 'L');
                    
                    $this->Ln($sltL+3);
                    $this->SetFont('Arial','',10);
                    $this->SetDrawColor(123,123,123);
                    $this->SetLineWidth(0.2);
                    $this->cell(10);
                    $this->MultiCell(170, 4, $fechasPSGS, 1, 'C');
                    
                    $this->SetXY(15,132.5);
                    $this->SetFont('Arial','',12);
                    $this->Cell(105, 7.5,utf8_decode("por lo cual solicito  el Fondo de Retiro  en virtud  de mi"), 0, 0, 'L');
                    $this->SetFont('Arial','I',12);
                    $this->Cell(30, 7.5,utf8_decode($motivoRetiroLower), 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(8, 7.5,utf8_decode("con"),0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/lineafirma.png',158,137.7,11,0.5);   
                    $this->SetFont('Arial','B',11);
                    $this->Cell(11, 7.5,$resultsM[0]['aservactmae'], 0, 0, 'C'); 
                    $this->SetFont('Arial','',12);
                    $this->Cell(31.9, 7.5, utf8_decode("años de servicio"), 0, 0, 'L');
    
                    $this->Ln($sltL);              
                    $this->SetFont('Arial','',12);
                    $this->MultiCell(185.9, 6, utf8_decode("cotizados al SMSEM, con fundamento en los artículos 30, 31, 32, 35, 36, 37, 38, 39, 43 y 44 del  Reglamento del  Fondo  de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');         
                    
                    $this->Ln(5);
                    $this->SetFont('Arial','',12);
                    $this->Cell(185.9, 7.5,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
                    
                    $this->Ln(8);
                    $this->SetFont('Arial','B',12);
                    $this->SetTextColor(15,83,183);
                    $this->Cell(40, 7.5,"$".$montoretsinAdeudos,0, 0, 'R');
                    $this->SetFont('Arial','B',9.5);
                    $this->Cell(139, 7.5,"(". $montsindAdeudos.")",0, 0, 'L');

                    $this->Ln(8);
                    $this->SetFont('Arial','',12);
                    $this->SetTextColor(0,0,0);
                    $this->Cell(185.9, 8,"Hago  de  su  conocimiento  que  el  equivalente  al  Fondo de Retiro  me sea  entregado de  forma", 0, 0, 'L');
                    
                    $this->Ln($sltL);
                    $this->SetFont('Arial','B',12);
                    $this->Cell(27, 8, "COMPLETA", 0, 0, 'C');
                    $this->SetFont('Arial','',12);
                    $this->Cell(158.9, 8,"y firmo  de enterado (a) y  de conformidad  al  respecto, que a partir de  que me  sea", 0, 0, 'L');
                    
                    $this->Ln($sltL);
                    $this->Cell(185.9, 8,utf8_decode("entregado, se  rescinde toda  obligación  por  parte del  FONRETyF  de contar  con el beneficio de"), 0, 0, 'L');
                    $this->Ln($sltL);
                    $this->Cell(185.9, 8,utf8_decode("Fondo por Fallecimiento."),0, 0, 'L');

                    $this->Ln($sltL+4);
                    $this->SetFont('Arial','',9);
                    $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                    $this->Image('/var/www/html/sistge/img/lineafirma.png',64,236,90,0.4);
                    $this->SetY(235);
                    $this->SetFont('Arial','B',11);
                    $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                    $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,223,27,26.5);
                    $this->SetY(245);
                        
                    $this->SetFont('Arial','I',8);
                    $this->Ln(4);
                    $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                        
                    $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                    
                } else {
                    if ($resultsT[0]['modretiro'] == "D50") {
                        $sltL = 6;
                        $pXLs = 3;
                        $this->Ln(20);
                        
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',22,90.5,117,0.4);
                        $this->SetFont('Arial','',12);
                        $this->Cell(8, 7.5, "Yo ", 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(115, 7.5, $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(62.9, 7.5, " con  clave  de  servidor   publico", 0, 0, 'L');
        
                        $this->Ln(6);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',16,96.5,24,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25, 7.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',57,96.5 ,55,0.4);
                        $this->SetFont('Arial','',12);
                        $this->Cell(17, 7.5,", CURP  ", 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(55, 7.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',145,96.5 ,27,0.4);
                        $this->Cell(32, 7.5, utf8_decode("y número celular "), 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(28, 7.5,$resultsM[0]['numcelmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(28.9, 7.5, utf8_decode(",  labore  en  la"), 0, 0, 'L');
        
                        $this->Ln($sltL);
                        $this->Cell(44, 7.5, utf8_decode("región sindical número"), 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',59,102.5 ,9,0.4);
                        $this->Cell(9, 7.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(62, 7.5,"como docente basificado (a) del ", 0, 0, 'L');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',130,102.5 ,25.5,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(4, 7.5," al ", 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',159.5,102.5 ,25.5,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(15.9, 7.5,", periodo", 0, 0, 'C');
        
                        $this->Ln($sltL);
                        $this->SetFont('Arial','',12);
                        $this->Cell(38, 7.5,"durante el cual tuve ",0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',53,108.5 ,10,0.4);
                        $this->Cell(10, 7.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(137.9, 7.5,utf8_decode("permisos  sin  goce de sueldo:"), 0, 0, 'L');
                        
                        $this->Ln($sltL+3);
                        $this->SetFont('Arial','',10);
                        $this->SetDrawColor(123,123,123);
                        $this->SetLineWidth(0.2);
                        $this->cell(10);
                        $this->MultiCell(170, 4, $fechasPSGS, 1, 'C');
                        
                        $this->SetXY(15,132.5);
                        $this->SetFont('Arial','',12);
                        $this->Cell(105, 7.5,utf8_decode("por lo cual solicito  el Fondo de Retiro  en virtud  de mi"), 0, 0, 'L');
                        $this->SetFont('Arial','I',12);
                        $this->Cell(30, 7.5,utf8_decode($motivoRetiroLower), 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(8, 7.5,utf8_decode("con"),0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',158,137.7,11,0.5);   
                        $this->SetFont('Arial','B',11);
                        $this->Cell(11, 7.5,$resultsM[0]['aservactmae'], 0, 0, 'C'); 
                        $this->SetFont('Arial','',12);
                        $this->Cell(31.9, 7.5, utf8_decode("años de servicio"), 0, 0, 'L');
        
                        $this->Ln($sltL);              
                        $this->SetFont('Arial','',12);
                        $this->MultiCell(185.9, 6, utf8_decode("cotizados al SMSEM, con fundamento en los artículos 30, 31, 32, 35, 36, 37, 38, 39, 43 y 44 del  Reglamento del  Fondo  de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');         
                        
                        $this->Ln(5);
                        $this->SetFont('Arial','',12);
                        $this->Cell(185.9, 7.5,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
                        
                        $this->Ln(8);
                        $this->SetFont('Arial','B',12);
                        $this->SetTextColor(15,83,183);
                        $this->Cell(40, 7.5,"$".$montoretsinAdeudos,0, 0, 'R');
                        $this->SetFont('Arial','B',9.5);
                        $this->Cell(139, 7.5,"(". $montsindAdeudos .")",0, 0, 'L');

                        $this->Ln(8);
                        $this->SetFont('Arial','',12);
                        $this->SetTextColor(0,0,0);
                        $this->Cell(185.9, 7.5,"Hago  de  su  conocimiento  que  el equivalente al  Fondo de Retiro  me  sea  entregado  de  forma", 0, 0, 'L');
                        
                        $this->Ln($sltL);
                        $this->SetFont('Arial','B',12);
                        $this->Cell(28, 7.5, "DIFERIDA", 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(33, 7.5,", dejando el 50% del monto total", 0, 0, 'L');
                        $this->Ln($sltL-1);
                        $this->SetFont('Arial','',9);
                        $this->Cell(185.9, 7.5,$resultsT[0]["montretfall"] . " (" . $resultsT[0]["montretfallletra"] . ")", 0, 0, 'C');
                        $this->Ln($sltL-1);
                        $this->SetFont('Arial','',12);
                        $this->Cell(185.9, 7.5,utf8_decode("al resguardo del FONRETyF  para mis beneficiarios, y llevándome el  50% restante"), 0, 0, 'L');
                        $this->Ln($sltL-1);
                        $this->SetFont('Arial','',9);
                        $this->Cell(185.9, 7.5,$resultsT[0]["montretentr"] . " (" . $resultsT[0]["montretentrletra"] . ")", 0, 0, 'C');
                        $this->Ln($sltL);
                        $this->SetFont('Arial','',12);
                        $this->MultiCell(185.9, 6, utf8_decode(", asi mismo, firmo de enterado (a) y conformidad  al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión, para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                        $this->Ln(1);
                        $this->SetFont('Arial','',9);
                        $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                        $this->Image('/var/www/html/sistge/img/lineafirma.png',64,241,90,0.4);
                        $this->SetY(240);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,230,27,26.5);
                        $this->SetY(245);
                        
                        $this->SetFont('Arial','I',8);
                        $this->Ln(5);
                        $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                        
                        $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);

                    } elseif ($resultsT[0]['modretiro'] == "D100") {
                        $sltL = 6;
                        $pXLs = 3;
                        $this->Ln(20);
                        
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',22,90.5,117,0.4);
                        $this->SetFont('Arial','',12);
                        $this->Cell(8, 7.5, "Yo ", 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(115, 7.5, $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(62.9, 7.5, " con  clave  de  servidor   publico", 0, 0, 'L');
        
                        $this->Ln(6);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',16,96.5,24,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25, 7.5,$resultsT[0]['cvemae'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',57,96.5 ,55,0.4);
                        $this->SetFont('Arial','',12);
                        $this->Cell(17, 7.5,", CURP  ", 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(55, 7.5,$resultsM[0]['curpmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',145,96.5 ,27,0.4);
                        $this->Cell(32, 7.5, utf8_decode("y número celular "), 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Cell(28, 7.5,$resultsM[0]['numcelmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(28.9, 7.5, utf8_decode(",  labore  en  la"), 0, 0, 'L');
        
                        $this->Ln($sltL);
                        $this->Cell(44, 7.5, utf8_decode("región sindical número"), 0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',59,102.5 ,9,0.4);
                        $this->Cell(9, 7.5,$resultsM[0]['regescmae'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(62, 7.5,"como docente basificado (a) del ", 0, 0, 'L');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',130,102.5 ,25.5,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsM[0]['fcbasemae'])), 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(4, 7.5," al ", 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',159.5,102.5 ,25.5,0.4);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(25.5, 7.5,date("d-m-Y",strtotime($resultsT[0]['fechbajfall'])) ,0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(15.9, 7.5,", periodo", 0, 0, 'C');
        
                        $this->Ln($sltL);
                        $this->SetFont('Arial','',12);
                        $this->Cell(38, 7.5,"durante el cual tuve ",0, 0, 'L');
                        $this->SetFont('Arial','B',11);
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',53,108.5 ,10,0.4);
                        $this->Cell(10, 7.5,$resultsM[0]['numpsgs'], 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(137.9, 7.5,utf8_decode("permisos  sin  goce de sueldo:"), 0, 0, 'L');
                        
                        $this->Ln($sltL+3);
                        //$this->SetXY(25,120);
                        $this->SetFont('Arial','',10);
                        $this->SetDrawColor(123,123,123);
                        $this->SetLineWidth(0.2);
                        $this->cell(10);
                        $this->MultiCell(170, 4, $fechasPSGS, 1, 'C');
                        
                        $this->SetXY(15,132.5);
                        $this->SetFont('Arial','',12);
                        $this->Cell(105, 7.5,utf8_decode("por lo cual solicito  el Fondo de Retiro  en virtud  de mi"), 0, 0, 'L');
                        $this->SetFont('Arial','I',12);
                        $this->Cell(30, 7.5,utf8_decode($motivoRetiroLower), 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(8, 7.5,utf8_decode("con"),0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/lineafirma.png',158,137.7,11,0.5);   
                        $this->SetFont('Arial','B',11);
                        $this->Cell(11, 7.5,$resultsM[0]['aservactmae'], 0, 0, 'C'); 
                        $this->SetFont('Arial','',12);
                        $this->Cell(31.9, 7.5, utf8_decode("años de servicio"), 0, 0, 'L');
        
                        $this->Ln($sltL);              
                        $this->SetFont('Arial','',12);
                        $this->MultiCell(185.9, 6, utf8_decode("cotizados al SMSEM, con fundamento en los artículos 30, 31, 32, 35, 36, 37, 38, 39, 43 y 44 del  Reglamento del  Fondo  de Retiro y Fallecimiento (FONRETyF)."), 0, 'J');         
                        
                        $this->Ln(5);
                        $this->SetFont('Arial','',12);
                        $this->Cell(185.9, 7.5,utf8_decode("Habiendo especificado lo anterior, acepto el monto que me corresponde por la cantidad de: "),0, 0, 'L');
                        
                        $this->Ln(8);
                        $this->SetFont('Arial','B',12);
                        $this->SetTextColor(15,83,183);
                        $this->Cell(40, 7.5,"$".$montoretsinAdeudos,0, 0, 'R');
                        $this->SetFont('Arial','B',9.5);
                        $this->Cell(139, 7.5,"(". $montsindAdeudos .")",0, 0, 'L');

                        $this->Ln(8);
                        $this->SetFont('Arial','',12);
                        $this->SetTextColor(0,0,0);
                        $this->Cell(185.9, 7.5,"Hago  de  su conocimiento  que  el equivalente  al  Fondo de Retiro  me  sea  entregado de  forma", 0, 0, 'L');
                        
                        $this->Ln(6);
                        $this->SetFont('Arial','B',12);
                        $this->Cell(36, 7.5, "PRORROGADA", 0, 0, 'C');
                        $this->SetFont('Arial','',12);
                        $this->Cell(149.9, 7.5,", dejando  el  100%  del  monto  total  al  resguardo  del  FONRETyF  para  mis", 0, 0, 'L');
                        
                        $this->Ln(7.5);
                        $this->MultiCell(185.9, 6, utf8_decode("beneficiarios, asi mismo, firmo de enterado (a) y  conformidad al respecto, y que a partir de este momento se me realice el descuento anual, correspondiente a un día de mi pensión,  para contar con el beneficio de Fondo por Fallecimiento."), 0, 'J');

                        $this->Ln(1);
                        $this->SetFont('Arial','',9);
                        $this->MultiCell(185.9, 4, utf8_decode("NOTA: El monto aquí especificado está sujeto a cambios posteriores, ya que se realiza una exhaustiva y detallada revisión del expediente para la solicitud del Fondo de Retiro."), 0, 'J');

                        $this->Image('/var/www/html/sistge/img/lineafirma.png',64,241,90,0.4);
                        $this->SetY(240);
                        $this->SetFont('Arial','B',11);
                        $this->Cell(185.9, 7,"C. " . $resultsT[0]['nomsolic'], 0, 0, 'C');
                        $this->Image('/var/www/html/sistge/img/logoplanilla.png',174,230,27,26.5);
                        $this->SetY(245);
                        
                        $this->SetFont('Arial','I',8);
                        $this->Ln(5);
                        $this->MultiCell(157, 2.5, utf8_decode("AVISO: Sus datos personales, serán tratados y protegidos por el Sindicato de Maestros al Servicio del Estado de México, en apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.Consulte de aviso de privacidad integral en la página: www.ipomex.org.mx/ipo/lgt/indice/smsem/otrainfo.web."), 0, 'J');
                        
                        $this->Image('/var/www/html/sistge/img/lineaoficio.png',10,258,197,0.5);
                    }
                }
            }
        }
            
        function Footer(){
            $this->SetY(-21);
            $this->cell(-5);
            $this->SetFont('Arial','B',8);
            $this->Cell(196.5, 5,utf8_decode("IGNACIO LÓPEZ RAYÓN No. 602           ESQ. FRANCISCO MURGUÍA          COL. CUAUHTÉMOC          C.P. 50130          TOLUCA, MÉXICO"),0,0,'C');
            $this->Ln(5.5);
            $this->cell(-5);
            $this->SetFont('Arial','B',7);
            $this->Cell(196.5, 5,utf8_decode("TELS.: (722)       2-12-10-72               2-12-25-00                2-12-25-07              2-12-25-09               2-12-25-14               2-12-25-21               2-12-25-28               2-12-25-78"),0,0,'C');
            $this->Ln(3);
            $this->cell(-5);
            $this->Cell(196.5, 5,utf8_decode("                            2-12-79-09               2-70-13-45               2-70-28-32               2-70-28-33               2-70-28-34               2-70-43-80               2-70-66-97               2-70-78-37"),0,0,'C');
        }
    
    }

    function fechactual(){
        $fecha = date("d-m-y ");
        $mes = intval(explode("-",$fecha)[1]);
        $mesesA = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        $fechaCompleta = explode("-",$fecha)[0] . " de ". $mesesA[$mes - 1] . " de 20" . explode("-",$fecha)[2];
        return $fechaCompleta;
    }

    $pdf = new PDF("P","mm","letter");
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(15,10,15);
    
    $pdf->Output();

?>
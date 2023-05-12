<?php

use function PHPSTORM_META\type;
    session_start();

    class Tramite{
        private $db;
        private $cantidadLetra;
        private $retiros;
        
        public function __construct(){
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            require_once("/var/www/html/sistge/model/cantidadLetras.php");
            $this->cantidadLetra = new cantidadLetras();
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
            $this->retiros = array();
        }

        public function get_Retiro($aniosserv,$fechBaja){
            $statement = $this->db->prepare("SELECT aportprom FROM public.parametros_retiro WHERE estatparam='ACTIVO'");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row){
                $montprom = substr($row['aportprom'],1,strlen($row['aportprom'])-1);
            }
            $result = $this->calculaRet($aniosserv,$montprom,$fechBaja);
            return $result;
        }  

        public function get_RetiroJub($aniosserv,$programfallec){
            $retiroJub = array();
            if ($programfallec == "M") {
                $montretJub = $aniosserv * 1000;
                $retiroJub[] = [
                    "montRet" => $montretJub
                ];
            } else {
                # code...
            }
            
            return $retiroJub;
        }  

        public function validaFechas($clavemae,$motret,$diasInacPsgs,$NumPersgs,$fechRecibido,$fechDictamen,$fechBaseMae,$fechBajaMae){
            $validesFechs = array();
            $dias_Serv = array();
            $i=0;

            if ($fechRecibido > $fechDictamen && $fechRecibido > $fechBaseMae && $fechRecibido > $fechBajaMae) {
                if ($fechDictamen > $fechBaseMae && $fechDictamen < $fechBajaMae) {
                    if ($fechBaseMae < $fechBajaMae) {
                        $vigenciaTram = $this -> validaVigencia($fechBajaMae,$fechRecibido);
                        if (($vigenciaTram/365) < 1) {
                            $dias_Serv["descResult"] = "vigenciaVal";
                            $dias_Serv["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            return $dias_Serv;
                        } else {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        }
                    } else {
                        $validesFechs["descResult"] = "errorFecha";
                        $validesFechs["diasServ"] = "La fecha de BASE no puede ser mayor a la fecha de BAJA";
                        $validesFechs["excepcion"] = "NO";
                        $validesFechs["prorroga"] = "NO";
                        return $validesFechs;
                    }
                } else {
                    if ($fechDictamen < $fechBaseMae) {
                        $validesFechs["descResult"] = "errorFecha";
                        $validesFechs["diasServ"] = "La fecha del DICTAMEN no puede ser menor a la fecha de Base";
                        $validesFechs["excepcion"] = "NO";
                        $validesFechs["prorroga"] = "NO";
                        return $validesFechs;
                    }
                    if ($fechDictamen > $fechBajaMae){
                        $vigenciaTram = $this -> validaVigencia($fechBajaMae,$fechRecibido);
                        if (($vigenciaTram/365) > 1 && ($vigenciaTram/365) <= 3){
                            $statement = $this->db->prepare("SELECT * FROM public.prorrogas WHERE cvemae=? and estatuspro='ACTIVA'");
                            $statement->bindValue(1,$clavemae);
                            $statement->execute();
                            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (count($results) > 0) {
                                $validesFechs["descResult"] = "vigenciaCadD";
                                $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                                $validesFechs["excepcion"] = "SI";
                                $validesFechs["prorroga"] = "SI";
                                return $validesFechs;
                            }else {
                                $validesFechs["descResult"] = "vigenciaCadD";
                                $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                                $validesFechs["excepcion"] = "SI";
                                $validesFechs["prorroga"] = "NO";
                                return $validesFechs;
                            }
                        }else{
                            $validesFechs["descResult"] = "noProcede";
                            $validesFechs["diasServ"] = "Tramite no procede, excede el limite de apoyo por oficio";
                            $validesFechs["excepcion"] = "NO";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        }
                    }
                }
            }else {
                $validesFechs["descResult"] = "errorFecha";
                $validesFechs["diasServ"] = "La fecha de RECIBIDO no puede ser menor a las demas";
                $validesFechs["excepcion"] = "NO";
                $validesFechs["prorroga"] = "NO";
                return $validesFechs;
            }
                
        }

        public function validaFechasFA($clavemae,$motret,$diasInacPsgs,$NumPersgs,$fechRecibido,$fechBaseMae,$fechBajaMae,$fechIniJ,$fechaInicJuic,$tiptest,$fechJuiCTL){
            $validesFechs = array();
            $dias_Serv = array();
            $i=0;

            if ($fechRecibido > $fechBaseMae && $fechRecibido > $fechBajaMae) {
                if ($fechBaseMae < $fechBajaMae) {
                    $vigenciaTramBR = $this -> validaVigencia($fechBajaMae,$fechRecibido);
                    if (($vigenciaTramBR/365) <= 1) {
                        $dias_Serv["descResult"] = "vigenciaVal";
                        $dias_Serv["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                        return $dias_Serv;
                    } elseif(($vigenciaTramBR/365) > 1 && $fechIniJ == 1) {
                        $vigenciaTramBInJuic = $this -> validaVigencia($fechBajaMae,$fechaInicJuic);
                        $vigenciaTramJuiR = $this -> validaVigencia($fechJuiCTL,$fechRecibido);
                        if (($vigenciaTramBInJuic/365) <= 1 && ($vigenciaTramJuiR/365) <= 1) {
                            $dias_Serv["descResult"] = "vigenciaVal";
                            $dias_Serv["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            return $dias_Serv;
                        } elseif (($vigenciaTramBInJuic/365) > 1 && ($vigenciaTramJuiR/365) <= 1) {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        } elseif (($vigenciaTramBInJuic/365) <= 1 && ($vigenciaTramJuiR/365) > 1) {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        }
                    }elseif (($vigenciaTramBR/365) > 1 && $fechIniJ == 0) {
                        $vigenciaTramBJuic = $this -> validaVigencia($fechBajaMae,$fechJuiCTL);
                        $vigenciaTramJuiR = $this -> validaVigencia($fechJuiCTL,$fechRecibido);

                        if (($vigenciaTramBJuic/365) <= 1 && ($vigenciaTramJuiR/365) <= 1){
                            $dias_Serv["descResult"] = "vigenciaVal";
                            $dias_Serv["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            return $dias_Serv;
                        }elseif (($vigenciaTramBJuic/365) > 1 && ($vigenciaTramJuiR/365) <= 1) {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        } elseif (($vigenciaTramBJuic/365) <= 1 && ($vigenciaTramJuiR/365) > 1) {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        }
                    }
                } else {
                    $validesFechs["descResult"] = "errorFecha";
                    $validesFechs["diasServ"] = "La fecha de BASE no puede ser mayor a la fecha de BAJA";
                    $validesFechs["excepcion"] = "NO";
                    $validesFechs["prorroga"] = "NO";
                    return $validesFechs;
                }
            }else {
                $validesFechs["descResult"] = "errorFecha";
                $validesFechs["diasServ"] = "La fecha de RECIBIDO no puede ser menor a las demas";
                $validesFechs["excepcion"] = "NO";
                $validesFechs["prorroga"] = "NO";
                return $validesFechs;
            }
        }

        public function validaFechasFJ($clavemae,$motret,$fechRecibido,$fechBaseMae,$fechBajaMae){
            $validesFechs = array();
            $dias_Serv = array();
            $i=0;

            if ($fechRecibido > $fechBaseMae && $fechRecibido > $fechBajaMae) {
                if ($fechBaseMae < $fechBajaMae) {
                    $vigenciaTram = $this -> validaVigencia($fechBajaMae,$fechRecibido);
                    if (($vigenciaTram/365) < 1) {
                        $dias_Serv["descResult"] = "vigenciaVal";
                        $dias_Serv["diasJub"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,0);
                        return $dias_Serv;
                    } else {
                        $validesFechs["descResult"] = "vigenciaCad";
                        $validesFechs["diasJub"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae,0);
                        $validesFechs["excepcion"] = "SI";
                        $validesFechs["prorroga"] = "NO";
                        return $validesFechs;
                    }
                } else {
                    $validesFechs["descResult"] = "errorFecha";
                    $validesFechs["diasJub"] = "La fecha de BASE no puede ser mayor a la fecha de BAJA";
                    $validesFechs["excepcion"] = "NO";
                    $validesFechs["prorroga"] = "NO";
                    return $validesFechs;
                }
            }else {
                $validesFechs["descResult"] = "errorFecha";
                $validesFechs["diasJub"] = "La fecha de RECIBIDO no puede ser menor a las demas";
                $validesFechs["excepcion"] = "NO";
                $validesFechs["prorroga"] = "NO";
                return $validesFechs;
            }
        }
        
        public function validaInicioJuic($fecharecibido,$fechabaja,$fechainiciojuicio,$fechCTJuic){
            $validesFechs = array();
            
            $vigBajIniJuic = $this -> validaVigencia($fechabaja,$fechainiciojuicio);

            if ($fechainiciojuicio < $fechCTJuic && $fechainiciojuicio > $fechabaja) {
                if (($vigBajIniJuic / 365) <= 1) {
                    $validesFechs["descResult"] = "validVal";
                    $validesFechs["diasServ"] = $vigBajIniJuic;
                    return $validesFechs;
                } else {
                    $validesFechs["descResult"] = "noProcede";
                    $validesFechs["diasServ"] = "Tramite no pocedente, Juicio se tramito fuera el limite de vigencia del retiro";
                    return $validesFechs;
                }
            } else {
                $validesFechs["descResult"] = "errorFecha";
                $validesFechs["diasServ"] = "La fecha de inicio del JUICIO debe ser mayor a la de fallecimiento y menor a la del termino del JUICIO";
                return $validesFechs;
            }
            
        }

        public function validVigTramFA($tipoTestamento,$clavemae,$fechabase,$fechBaja,$fechJuicio,$fechRecibido){
            $a_validVigTram = array();

            $VigBajaRecib = $this -> validaVigencia($fechBaja,$fechRecibido);
            $vigJuicRecib = $this -> calculaDifFechas($fechJuicio,$fechRecibido);
            $vigBajaJuic = $this -> calculaDifFechas($fechBaja,$fechJuicio);    
            
            $statement = $this->db->prepare("SELECT * FROM public.prorrogas WHERE cvemae=? and estatuspro='ACTIVA'");
            $statement->bindValue(1,$clavemae);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            switch ($tipoTestamento) {
                case 'CT':
                    if ( ($VigBajaRecib / 365) > 1) {
                        $a_validVigTram['resulValidVig'] = "vigenciaCad";
                        $a_validVigTram['diasVig'] = $VigBajaRecib;
                        return $a_validVigTram;
                    } else {
                        $a_validVigTram['resulValidVig'] = "vigenciaVal";
                        $a_validVigTram['diasVig'] = $VigBajaRecib;
                        return $a_validVigTram;
                    }                    
                    break;

                case 'SL':
                    if ( ($VigBajaRecib / 365) > 1) {
                        $a_validVigTram['resulValidVig'] = "vigenciaCad";
                        $a_validVigTram['diasVig'] = $VigBajaRecib;
                        return $a_validVigTram;
                    } else {
                        $a_validVigTram['resulValidVig'] = "vigenciaVal";
                        $a_validVigTram['diasVig'] = $VigBajaRecib;
                        return $a_validVigTram;
                    } 
                    break;

                case 'J':
                    if (($VigBajaRecib / 365) <= 1) {
                        $a_validVigTram['resulValidVig'] = "vigenciaVal";
                        $a_validVigTram['diasVig'] = $VigBajaRecib;
                        return $a_validVigTram;
                    } elseif (($VigBajaRecib / 365) > 1) {
                        if (($vigBajaJuic / 365) <= 1 && ($vigJuicRecib / 365) <= 1) {
                            $a_validVigTram['resulValidVig'] = "vigenciaVal";
                            $a_validVigTram['diasVig'] = $VigBajaRecib;
                            return $a_validVigTram;
                        } elseif (($vigBajaJuic / 365) <= 1 && ($vigJuicRecib / 365) > 1) {
                            $a_validVigTram['resulValidVig'] = "vigenciaCad";
                            $a_validVigTram['diasVig'] = $vigBajaJuic;
                            return $a_validVigTram;
                        } elseif (($vigBajaJuic / 365) > 1 && ($vigJuicRecib / 365) <= 1) {
                            $a_validVigTram['resulValidVig'] = "fechaIni";
                            $a_validVigTram['diasVig'] = $vigBajaJuic;
                            return $a_validVigTram;
                        }elseif (($vigBajaJuic / 365) > 1 && ($vigJuicRecib / 365) > 1) {
                            $a_validVigTram['resulValidVig'] = "vigenciaCad";
                            $a_validVigTram['diasVig'] = $vigBajaJuic;
                            return $a_validVigTram;
                        }
                    }
                    break;

                default:
                    break;
            }
        }
        
        public function validaVigencia($fechBajaMae,$fechRecibido){
            $diasValid = $this->calculaDifFechas($fechBajaMae,$fechRecibido);      
            return $diasValid;
        }

        public function calculaDiasServ($fechBaseMae,$fechBajaMae,$diasInacPsgs){
            $diasServPre = $this->calculaDifFechas($fechBaseMae,$fechBajaMae);
            $diasServ = $diasServPre - $diasInacPsgs;
            return $diasServ;
        }

        public function calculaDifFechas($fechIni,$fechFin){
            $FechaI = date_create($fechIni);
            $FechaF = date_create($fechFin);
            $difFechas = date_diff($FechaI,$FechaF);
            return $difFechas->format("%a");
        }

        public function tiempoPSGS($contPSGS,$fechaIni,$fechaFin){
            $diasPSGS = array();
            for ($i=0 ; $i<$contPSGS ; $i++){
                $FechLicIni = new DateTime($fechaIni[$i]);
                $FechLicFin = new DateTime($fechaFin[$i]);
                $tiempoDiff = $FechLicIni->diff($FechLicFin);
                $diasPSGS[$i]= $tiempoDiff->format('%a');
            }
            return $diasPSGS;
        }

        public function validFechaCTJuic($tipotestamento,$fechabase,$fechabaja,$fechactjuic,$fecharecibido){
            $resultValidCTJuic = array();

            switch ($tipotestamento) {
                case 'CT':
                    if ($fechactjuic > $fechabase && $fechactjuic < $fechabaja && $fechactjuic < $fecharecibido) {
                        $resultValidCTJuic['resultValid'] = "correcto";
                        $resultValidCTJuic['descValid'] = "correcto";
                        return $resultValidCTJuic;
                    } else {
                        if ($fecharecibido > $fechabase && $fecharecibido > $fechactjuic && $fecharecibido > $fechabaja){
                            if ($fechactjuic > $fechabase && $fechactjuic > $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de BAJA no puede ser menor a la de la CT";
                                return $resultValidCTJuic;
                            }elseif ($fechactjuic < $fechabase && $fechactjuic < $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de BASE no puede ser mayor a la de la CT";
                                return $resultValidCTJuic;
                            }elseif ($fechactjuic < $fechabase && $fechactjuic > $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de CT no puede ser menor a la Base y mayor a la Baja, las fechas son incorrectas";
                                return $resultValidCTJuic;
                            }
                        }else {
                            $resultValidCTJuic['resultValid'] = "errorFecha";
                            $resultValidCTJuic['descValid'] = "La fecha de RECIBIDO no puede ser menor a las demas";
                            return $resultValidCTJuic;
                        }
                    }
                    break;

                case 'J':
                    if ($fechactjuic > $fechabase && $fechactjuic > $fechabaja && $fechactjuic < $fecharecibido) {
                        $resultValidCTJuic['resultValid'] = "correcto";
                        $resultValidCTJuic['descValid'] = "correcto";
                        return $resultValidCTJuic;
                    } else {
                        if ($fecharecibido > $fechabase && $fecharecibido > $fechactjuic && $fecharecibido > $fechabaja){
                            if ($fechactjuic < $fechabase && $fechactjuic < $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de JUICIO no puede ser menor a la base y baja";
                                return $resultValidCTJuic;
                            }elseif ($fechactjuic > $fechabase && $fechactjuic < $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha del JUICIO no puede ser menor a la Baja";
                                return $resultValidCTJuic;
                            }elseif ($fechactjuic < $fechabase && $fechactjuic > $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de JUICIO no pued ser menor a la Base y mayor a la Baja, las fechas son incorrectas";
                                return $resultValidCTJuic;
                            }elseif ($fechactjuic == $fechabase || $fechactjuic == $fechabaja) {
                                $resultValidCTJuic['resultValid'] = "errorFecha";
                                $resultValidCTJuic['descValid'] = "La fecha de JUICIO es incorrecta no puede ser igual a la base o baja";
                                return $resultValidCTJuic;
                            }
                        } else {
                            $resultValidCTJuic['resultValid'] = "errorFecha";
                            $resultValidCTJuic['descValid'] = "La fecha de RECIBIDO no puede ser menor a las demas";
                            return $resultValidCTJuic;
                        }
                    }
                    break;

                default:
                    break;
            }
        }

        private function calculaRet($aniosserv,$montprom,$fechBaja){
            $fechaLimCalAnt = '30-06-2023';
            $tabuladorRetiros = [1=>1498.5,2=>2997,3=>4500,4=>5998.5,5=>7497,6=>9000,7=>10498.5,8=>11997,9=>13500,10=>14998.5,11=>16497,12=>18000,13=>19498.5,14=>20997,15=>22500,16=>23998.5,17=>25497,18=>27000,19=>28498.5,20=>29997,21=>31500,22=>32998.5,23=>34497,24=>36000,25=>37498.5,26=>38997,27=>40500,28=>41998.5,29=>43497];

            if ($fechBaja < $fechaLimCalAnt) {
                $dats_ret = array();
                if ($aniosserv > 29) {
                    $retiro = 45000;
                } else {
                    $retiro = $tabuladorRetiros[$aniosserv];
                }                
                $dats_ret[] = [
                    "montRet" => $retiro
                ];
            } else {
                if ($aniosserv >= 30) {
                    $retiro = ((($montprom * 24) * 30) * 0.4) * .99;
                    $dats_ret = array();
                    $dats_ret[] = [
                        "montRet" => $retiro
                    ];
                } else {
                    $retiro = ((($montprom * 24) * $aniosserv) * 0.4) * .99;
                    $dats_ret = array();
                    $dats_ret[] = [
                        "montRet" => $retiro
                    ];
                }
            }   
            return $dats_ret;            
        }

        public function addTramiteJI($anioentr,$numentre,$identr,$cvemae,$cveissemym,$estatlaboral,$motvret,$apepat,$apemat,$nombre,$nomcom,$region,$numdictam,$fechdictam,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$fechinipsgs,$fechfinpsgs,$numpsgs,$diaspsgs,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$montretfall,$fechrecib,$numoficautori,$fechautori,$imgautori,$numbenefs,$diaHaber,$curpmae,$rfcmae,$cveusu){
            require_once("/var/www/html/sistge/model/Entregas.php");
            $entrega = new Entrega();

            $a_resultAddTram = array();
            $get_ret = $this->get_retiro_Id($cvemae);
            $get_entrega = $entrega -> get_entrega_id($identr);

            if (count($get_ret)>0) {
                $a_resultAddTram["insertTramite"] = "Existente";
                return $a_resultAddTram;
            } else {
                $fecha = "";
                $fecha = date("Y-m-d H:i:s");

                $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
                $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);
                $montretfallLet = $this->cantidadLetra->cantidadLetras($montretfall);
                
                $idretiro = $this->obtenMax($identr);
                $identregRet = $this->obteIdEntrRet($identr,$idretiro);
            
                if(empty($numoficautori)){
                    $exception = 0;
                    $fechautori = '1900-01-01';
                }else {
                    $exception = 1;
                }

                $folioTram = $this->obtenFolioTram($motvret,$numentre,$idretiro,$estatlaboral);

                try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                    $consultaAdd = "INSERT INTO public.tramites_fonretyf(";
                    $consultaAdd = $consultaAdd . "anioentrega, numentrega, identrega, idret, identret, cvemae,motvret, numdictam, fechdictam,fechbajfall, nomsolic, numcelsolic, numpartsolic, docttestamnt, fechdocttestmnt, numjuicio, numbenef, numbeneffall, modretiro, montrettot, montretletra, montretsinads, montretentr, montretentrletra, montretfall, montretfallletra, observtrami, fechrecib, fechentrega, estattramite, tramtexcepcion, numoficioaut, fechautori,imgautori, proceautori, numadeds, montadeudos, adfajam, adts, adfondpension, adturismo, montretnepfall, foliotramite, cveusureg, fechreg, montsalmin, imgacuerdo, estatacuerdo, cveusumodif, fechmodif, histmodif)";
                    $consultaAdd = $consultaAdd . " VALUES (".$anioentr.", ".$numentre.",'".$identr."', ".$idretiro.", '".$identregRet."', '".$cvemae."', '".$motvret."', '".$numdictam."', '".$fechdictam."', '".$fechbajfall."', '".$nomsolic."', '".$numcel."', '".$numpart."', '', '1900-01-01','', 0, 0, '".$modret."', ".$montrettot.",'".$montrettotLet."',".$montrettot.", ".$montretentr.", '".$montretentrLet."', ".$montretfall.", '".$montretfallLet."', '', '".$fechrecib."', '1900-01-01', 'PROCESADO', ".$exception.", '".$numoficautori."', '".$fechautori."', '".$imgautori."', '', 0, 0, 0, 0, 0, 0,0, '".$folioTram."', '".$cveusu."','".$fecha."',".$diaHaber.",'',0,'','1900-01-01','')";
                    $consultaAdd = $this->db->prepare($consultaAdd);
                    $consultaAdd->execute();
                    $results = $consultaAdd->fetchAll(PDO::FETCH_ASSOC);              
                    $a_resultAddTram["insertTramite"] = "Agregado";
                    
                } catch (\Throwable $th) {
                    $a_resultAddTram["insertTramite"] = "Fallo";
                }

            
                $actualizaMae = $this->actualizaMaestroAct($cvemae,$cveissemym,$region,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$fechbajfall,$numpsgs,$diaspsgs,$fechinipsgs,$fechfinpsgs,$motvret,$modret,$cveusu,$fecha,$curpmae,$rfcmae);
                $a_resultAddTram["updateMaestro"] = $actualizaMae;

                if ($modret == "C") {
                    $nombreBenef[] =$nomsolic; 
                    $estatEdad[] = "M";
                    $porcsBenef[] = "100";
                    $insertaCheque = $this->insertCheque($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombreBenef,$numbenefs,$montretentr,$estatEdad,$porcsBenef,$cveusu,$fecha,$motvret);
                    $a_resultAddTram["insertCheque"] = $insertaCheque;

                    if ($a_resultAddTram["insertTramite"] == "Agregado" && $a_resultAddTram["updateMaestro"] == "Actualizado" && $a_resultAddTram["insertCheque"] == "Agregado" ) {
                        if ($motvret=="I") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". $get_entrega[0][12] + 1 .", numtraminha=". $get_entrega[0][13] + 1 .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } elseif ($motvret=="J") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". ($get_entrega[0][12] + 1) .", numtramjub=". ($get_entrega[0][14] + 1) .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr ."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } 
                    }
                    return $a_resultAddTram; 
                    
                } else {
                    if ($modret == "D50") {
                        $nombreBenef[] =$nomsolic; 
                        $estatEdad[] = "M";
                        $porcsBenef[] = "100";
                        $insertaCheque = $this->insertCheque($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombreBenef,$numbenefs,$montretentr,$estatEdad,$porcsBenef,$cveusu,$fecha,$motvret);
                        $a_resultAddTram["insertCheque"] = $insertaCheque;
                        
                        $programfalle = "FF";
                        $insertaMaeJub = $this->insertJubiladoSmsem($cveissemym,$apepat,$apemat,$nombre,$nomcom,$programfalle,$cveusu,$fecha);
                        $a_resultAddTram["insertJubilado"] = $insertaMaeJub;

                        $insertaFondFallec = $this->insertFondoFallecimiento($identregRet,$cveissemym,$montrettot,$modret,$montretentr,$montretfall,$fechbajfall,$fechrecib,$diaHaber,$cveusu,$fecha);
                        $a_resultAddTram["insertFondFallec"] = $insertaFondFallec;
                    }elseif ($modret == "D100") {
                        $programfalle = "FF";
                        $insertaMaeJub = $this->insertJubiladoSmsem($cveissemym,$apepat,$apemat,$nombre,$nomcom,$programfalle,$cveusu,$fecha);
                        $a_resultAddTram["insertJubilado"] = $insertaMaeJub;

                        $insertaFondFallec = $this->insertFondoFallecimiento($identregRet,$cveissemym,$montrettot,$modret,$montretentr,$montretfall,$fechbajfall,$fechrecib,$diaHaber,$cveusu,$fecha);
                        $a_resultAddTram["insertFondFallec"] = $insertaFondFallec;
                    }        
                    
                    if ($a_resultAddTram["insertTramite"] == "Agregado" && $a_resultAddTram["updateMaestro"] == "Actualizado" && $a_resultAddTram["insertJubilado"] == "Agregado" && $a_resultAddTram["insertFondFallec"] == "Agregado") {
                        if ($motvret=="I") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". $get_entrega[0][12] + 1 .", numtraminha=". $get_entrega[0][13] + 1 .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } elseif ($motvret=="J") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". ($get_entrega[0][12] + 1) .", numtramjub=". ($get_entrega[0][14] + 1) .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr ."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } 
                    }elseif ($a_resultAddTram["insertTramite"] == "Agregado" && $a_resultAddTram["updateMaestro"] == "Actualizado" && $a_resultAddTram["insertCheque"] == "Agregado" && $a_resultAddTram["insertJubilado"] == "Agregado" && $a_resultAddTram["insertFondFallec"] == "Agregado") {
                        if ($motvret=="I") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". $get_entrega[0][12] + 1 .", numtraminha=". $get_entrega[0][13] + 1 .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } elseif ($motvret=="J") {
                            $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". ($get_entrega[0][12] + 1) .", numtramjub=". ($get_entrega[0][14] + 1) .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr ."'";
                            $statementActEntr = $this->db->prepare($statementActEntr);
                            $statementActEntr->execute();
                            $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);     
                        } 
                    }
                    return $a_resultAddTram; 
                }
            }
        }

        public function addtramiteF($anioentr,$numentre,$identr,$cvemae,$cveissemym,$estatlaboral,$motvret,$region,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$fechinipsgs,$fechfinpsgs,$numpsgs,$diaspsgs,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$fechrecib,$numoficautori,$fechautori,$imgautori,$numbenefs,$testamento,$nomsbenefs,$curpsbenefs,$parentsbenefs,$porcsbenefs,$edadesbenefs,$vidabenefs,$fechtestamnt,$curpmae,$rfcmae,$cveusu){
            require_once("/var/www/html/sistge/model/Entregas.php");
            $entrega = new Entrega();

            $a_resultAddTramF = array();

            $get_ret = $this->get_retiro_Id($cvemae);
            $get_benefs = $this->get_benef_cvemae($cvemae);

            $get_entrega = $entrega->get_entrega_id($identr);

            if (count($get_ret)>0 || count($get_benefs)>0) {
                $a_resultAddTramF["insertTramite"] = "Existente";
                return $a_resultAddTramF;
            } else {
                $fecha = "";
                $fecha = date("Y-m-d H:i:s");

                $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
                $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);

                $idretiro = $this->obtenMax($identr);
                $identregRet = $this->obteIdEntrRet($identr,$idretiro);
            
                if(empty($numoficautori)){
                    $exception = 0;
                    $fechautori = '1900-01-01';
                }else {
                    $exception = 1;
                }

                $folioTram = $this->obtenFolioTram($motvret,$numentre,$idretiro,$estatlaboral);
                $benefsFallcs = 0;
                foreach ($vidabenefs as $row) {
                    if ($row == "F") {
                        $benefsFallcs++;
                    }                   
                }

                try {
                    $consultaAdd = "INSERT INTO public.tramites_fonretyf(";
                    $consultaAdd = $consultaAdd . "anioentrega, numentrega, identrega, idret, identret, cvemae, motvret, numdictam, fechdictam, fechbajfall, nomsolic, numcelsolic, numpartsolic, docttestamnt, fechdocttestmnt, numjuicio, numbenef, numbeneffall, modretiro, montrettot, montretletra, montretsinads, montretentr, montretentrletra, montretfall, montretfallletra, observtrami, fechrecib, fechentrega, estattramite, tramtexcepcion, numoficioaut, fechautori, imgautori, proceautori, numadeds, montadeudos, adfajam, adts, adfondpension, adturismo, montretnepfall, foliotramite, cveusureg, fechreg, montsalmin, imgacuerdo, estatacuerdo, cveusumodif, fechmodif, histmodif)";
                    $consultaAdd = $consultaAdd . " VALUES (".$anioentr.", ".$numentre.",'".$identr."', ".$idretiro.", '".$identregRet."', '".$cvemae."', '".$motvret."', '', '1900-01-01', '".$fechbajfall."', '".$nomsolic."', '".$numcel."', '".$numpart."', '".$testamento."', '".$fechtestamnt."', '', ".$numbenefs.", ".$benefsFallcs.", '".$modret."', ".$montrettot.",'".$montrettotLet."', ".$montretentr.", ".$montretentr.", '".$montretentrLet."', 0, '', '', '".$fechrecib."', '1900-01-01', 'PROCESADO', ".$exception.", '".$numoficautori."', '".$fechautori."', '".$imgautori."', '', 0, 0, 0, 0, 0, 0, 0, '".$folioTram."', '".$cveusu."','".$fecha."',0,'', 0,'','1900-01-01','');";
                    $consultaAdd = $this->db->prepare($consultaAdd);
                    $consultaAdd->execute();
                    $results = $consultaAdd->fetchAll(PDO::FETCH_ASSOC);
                    
                    $a_resultAddTramF["insertTramite"] = "Agregado";
                    
                } catch (\Throwable $th) {
                    $a_resultAddTramF["insertTramite"] = "Existente";
                }
                
                $actualizaMae = $this->actualizaMaestroAct($cvemae,$cveissemym,$region,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$fechbajfall,$numpsgs,$diaspsgs,$fechinipsgs,$fechfinpsgs,$motvret,$modret,$cveusu,$fecha,$curpmae,$rfcmae);
                $a_resultAddTramF["updateMaestro"] = $actualizaMae;
                
                $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nomsbenefs,$numbenefs,$montretentr,$edadesbenefs,$porcsbenefs,$vidabenefs,$cveusu,$fecha,$motvret);
                $a_resultAddTramF["insertCheque"] = $insertaCheques;

                $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nomsbenefs,$numbenefs,$montretentr,$curpsbenefs,$parentsbenefs,$edadesbenefs,$porcsbenefs,$vidabenefs,$cveusu,$fecha,$motvret);
                $a_resultAddTramF["insertBenefs"] = $insertaBenefsMae;
            }

            if ($a_resultAddTramF["insertTramite"] == "Agregado" && $a_resultAddTramF["updateMaestro"] == "Actualizado" && $a_resultAddTramF["insertCheque"] == "Agregado" && $a_resultAddTramF["insertBenefs"] == "Agregado" ) {
                $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". $get_entrega[0][12] + 1 .", numtramfall=". $get_entrega[0][15] + 1 .", numtramfallact=".$get_entrega[0][16] + 1 .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr."'";
                $statementActEntr = $this->db->prepare($statementActEntr);
                $statementActEntr->execute();
                $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);      
            }

            return $a_resultAddTramF;
        }
        
        public function addtramiteFJ($anioentr,$numentre,$identr,$cveissemym,$estatlaboral,$motvret,$region,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$fechrecib,$numoficautori,$fechautori,$imgautori,$numbenefs,$testamento,$nomsbenefs,$curpsbenefs,$parentsbenefs,$porcsbenefs,$edadesbenefs,$vidabenefs,$programfallec,$curpmae,$rfcmae,$fechtestamnt,$cveusu){
            require_once("/var/www/html/sistge/model/Entregas.php");
            $entrega = new Entrega();

            $get_entrega = $entrega -> get_entrega_id($identr);

            if ($programfallec == "M") {
                $a_resultAddTramFJ = array();

                $get_ret = $this->get_retiro_Id($cveissemym);
                $get_benefs = $this->get_benef_cvemae($cveissemym);
                if (count($get_ret)>0 || count($get_benefs)>0) {
                    $a_resultAddTramFJ["insertTramite"] = "Existente";
                    return $a_resultAddTramFJ;
                } else {
                    $fecha = "";
                    $fecha = date("Y-m-d H:i:s");

                    $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
                    $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);

                    $idretiro = $this->obtenMax($identr);
                    $identregRet = $this->obteIdEntrRet($identr,$idretiro);
                
                    if(empty($numoficautori)){
                        $exception = 0;
                        $fechautori = '1900-01-01';
                    }else {
                        $exception = 1;
                    }

                    $folioTram = $this->obtenFolioTram($motvret,$numentre,$idretiro,$estatlaboral);
                    $benefsFallcs = 0;
                    foreach ($vidabenefs as $row) {
                        if ($row == "F") {
                            $benefsFallcs++;
                        }                   
                    }

                    try {
                        $consultaAdd = "INSERT INTO public.tramites_fonretyf(";
                        $consultaAdd = $consultaAdd . "anioentrega, numentrega, identrega, idret, identret, cvemae, motvret, numdictam,                                             fechdictam, fechbajfall, nomsolic, numcelsolic, numpartsolic, docttestamnt, fechdocttestmnt, numjuicio, numbenef, numbeneffall, modretiro, montrettot,                                                  montretletra, montretsinads, montretentr, montretentrletra, montretfall, montretfallletra, observtrami, fechrecib, fechentrega, estattramite, tramtexcepcion, numoficioaut, fechautori, imgautori, proceautori, numadeds, montadeudos, adfajam, adts, adfondpension, adturismo, montretnepfall, foliotramite, cveusureg, fechreg, montsalmin, imgacuerdo, estatacuerdo, cveusumodif, fechmodif, histmodif)";
                        $consultaAdd = $consultaAdd . " VALUES (".$anioentr.", ".$numentre.",'".$identr."', ".$idretiro.", '".$identregRet."', '".$cveissemym."', '".$motvret."', '', '1900-01-01', '".$fechbajfall."', '".$nomsolic."', '".$numcel."', '".$numpart."', '".$testamento."', '".$fechtestamnt."', '', ".$numbenefs.", ".$benefsFallcs.", '".$modret."', ".$montrettot.",'". $montrettotLet . "', ".$montrettot.", ".$montretentr.",'". $montretentrLet."', 0, '', '', '".$fechrecib."', '1900-01-01', 'PROCESADO', ".$exception.", '".$numoficautori."', '".$fechautori."', '".$imgautori."', '', 0, 0, 0, 0, 0, 0, 0, '".$folioTram."', '".$cveusu."','".$fecha."',0,'',0,'','1900-01-01','');";
                        $consultaAdd = $this->db->prepare($consultaAdd);
                        $consultaAdd->execute();
                        $results = $consultaAdd->fetchAll(PDO::FETCH_ASSOC);
                        
                        $a_resultAddTramFJ["insertTramite"] = "Agregado";
                        
                    } catch (\Throwable $th) {
                        $a_resultAddTramFJ["insertTramite"] = "Existente";
                    }
                    
                    $actualizaMae = $this->actualizaMaestroMut($cveissemym,$curpmae,$rfcmae,$region,$numcel,$numpart,$aniosserv,$fechbajfall,$motvret,$modret,$diasserv,$cveusu,$fecha);
                    $a_resultAddTramFJ["updateMaestro"] = $actualizaMae;
                    
                    $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nomsbenefs,$numbenefs,$montretentr,$edadesbenefs,$porcsbenefs,$vidabenefs,$cveusu,$fecha,$motvret);
                    $a_resultAddTramFJ["insertCheque"] = $insertaCheques;

                    $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nomsbenefs,$numbenefs,$montretentr,$curpsbenefs,$parentsbenefs,$edadesbenefs,$porcsbenefs,$vidabenefs,$cveusu,$fecha,$motvret);
                    $a_resultAddTramFJ["insertBenefs"] = $insertaBenefsMae;
                }
                
                if ($a_resultAddTramFJ["insertTramite"] == "Agregado" && $a_resultAddTramFJ["updateMaestro"] == "Actualizado" && $a_resultAddTramFJ["insertCheque"] == "Agregado" && $a_resultAddTramFJ["insertBenefs"] == "Agregado" ) {
                    $statementActEntr = "UPDATE public.entregas_fonretyf SET numtramites=". $get_entrega[0][12] + 1 .", numtramfall=". $get_entrega[0][15] + 1 .", numtramfalljubm=".$get_entrega[0][17] + 1 .", monttotentr=". str_replace(",","",str_replace("$","",$get_entrega[0][29])) + $montrettot ."  WHERE identrega='".$identr."'";
                    $statementActEntr = $this->db->prepare($statementActEntr);
                    $statementActEntr->execute();
                    $resultsActEntr = $statementActEntr->fetchAll(PDO::FETCH_ASSOC);      
                }

                return $a_resultAddTramFJ;
                
            } elseif ($programfallec == "FF") {
                # code...
            }
        }

        public function validaDatos($cveissemym,$apepat,$apemat,$nombre,$region,$numdictam,$numcel,$numpart,$fechbase,$numpsgs,$diaspsgs,$diasserv,$aniosserv,$modret,$montrettot,$montretsinads,$montretentr,$montretfall,$fechrecib,$numoficautori,$fechautori,$imgautori,$adfajam,$adts,$adfonpen,$adturismo,$cveusu,$numbenefs,$diaHaber){
            $erroresV = array();
            $mensajesErrorV = array();

            if ($cveissemym == "" || empty($cveissemym)){
                $erroresV["errorDato"] = "clave de issemym";
                $mensajesErrorV["descerror"] = "Clave vacia";
            }
            if ($apepat == "" || empty($apepat)){
                $erroresV["errorDato"] = "apellido parterno";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($apemat == "" || empty($apemat)){
                $erroresV["errorDato"] = "apellido materno";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($nombre == "" || empty($nombre)){
                $erroresV["errorDato"] = "nombre";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($region == "" || empty($region)){
                $erroresV["errorDato"] = "region";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($numdictam == "" || empty($numdictam)){
                $erroresV["errorDato"] = "numero de dictamen";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($numdictam == "" || empty($nomsolic)){
                $erroresV["errorDato"] = "nombre del solicitante";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($numdictam == "" || empty($numdictam)){
                $erroresV["errorDato"] = "numero de dictamen";
                $mensajesErrorV["descerror"] = "dato vacio";
            }
            if ($numdictam == "" || empty($numdictam)){
                $erroresV["errorDato"] = "numero de dictamen";
                $mensajesErrorV["descerror"] = "dato vacio";
            }            
        }

        public function get_retiro_Id($cvemae){
            $statement = $this->db->prepare("SELECT * FROM public.tramites_fonretyf WHERE cvemae='". $cvemae."'");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_Tram_Id($identret){
            $statement = $this->db->prepare("SELECT * FROM public.tramites_fonretyf WHERE identret='". $identret."'");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_benef_cvemae($cvemae){
            $statement = $this->db->prepare("SELECT cvemae FROM public.beneficiarios_maestros WHERE cvemae= ?");
            $statement->bindValue(1,$cvemae);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function obtenMax($identrega){
            $statementIdRet = $this->db->prepare("SELECT MAX(idret) as numtram from public.tramites_fonretyf where identrega='" . $identrega . "'");
            $statementIdRet->execute();
            $results = $statementIdRet->fetchAll();
            if (is_null($results[0]['numtram'])) {
                $idret = 1;
            } else {
                $idret = $results[0]['numtram'] + 1;
            }
            return $idret;
        }

        public function obteIdEntrRet($identrega,$idretiro){
            if ($idretiro <10) {
                $identret = $identrega . "000". $idretiro;
            }elseif ($idretiro > 9 && $idretiro < 100) {
                $identret = $identrega . "00". $idretiro;
            }elseif ($idretiro > 99 && $idretiro < 1000) {
                $identret = $identrega . "0". $idretiro;
            }elseif ($idretiro > 999) {
                $identret = $identrega . $idretiro;
            }
            return $identret;
        }

        public function obtenFolioTram($motvret,$numentre,$numret,$estatlaboral){
            if ($numret < 10) {
                $numRetiro = "000" . $numret;
            }elseif ($numret > 9 && $numret < 100) {
                $numRetiro = "00" . $numret;
            }elseif ($numret > 99 && $numret < 1000) {
                $numRetiro = "0" . $numret;
            }elseif ($numret > 999 && $numret < 10000) {
                $numRetiro = $numret;
            }
            
            if ($motvret == "I" || $motvret == "J") {
                $numfolio = "FR" . $motvret . "2124" . $numentre . $numRetiro;
            }
            if ($motvret == "FA") {
                $numfolio = "FFA" . "2124" . $numentre . $numRetiro;
            }
            if ($motvret == "FJ") {
                if ($estatlaboral == "JUBILADO FF") {
                    $numfolio = "FFJ" . "2124" . $numentre . $numRetiro;
                }elseif ($estatlaboral == "JUBILADO M") {
                    $numfolio = "FMU" . "2124" . $numentre . $numRetiro;
                }
            }
            return $numfolio;
        }  
        
        public function actualizaMaestroAct($cvemae,$cveissemym,$region,$numcelmae,$numpartmae,$fechbase,$diasserv,$aniosserv,$fechbaja,$numpsgs,$diaspsgs,$fechinipsgs,$fechfinpsgs,$motivret,$modalret,$usuario,$fecha,$curpmae,$rfcmae){
            if ($modalret == "C") {
                $afifonfall = 0;
            }else {
                $afifonfall = 1;
            }

            $fechaini=str_replace('"','',$fechinipsgs);
            $fechafin=str_replace('"','',$fechfinpsgs);

            if ($motivret == "J" || $motivret == "I") {
                try {
                    $statementUpdate = "UPDATE public.maestros_smsem";
                    $statementUpdate = $statementUpdate . " SET cveissemym='".$cveissemym."', curpmae='".$curpmae."', rfcmae='".$rfcmae."', regescmae= ".$region ." , numcelmae='" .$numcelmae."', numfijmae='".$numpartmae."', fcbasemae='".$fechbase."', aservactmae=".$aniosserv.", fbajamae='".$fechbaja."', numpsgs=".$numpsgs.", diaspsgs=".$diaspsgs.", estatlabmae='". $motivret ."', cveusu='".$usuario."', fechmodif='".$fecha."', diaservactmae=".$diasserv.", afiprogfondfalle=".$afifonfall.", fechsinipsgs=' " .$fechaini."', fechsfinpsgs='".$fechafin."'";
                    $statementUpdate = $statementUpdate . " WHERE csp='" . $cvemae."';";
                    $statementUpdate = $this->db->prepare($statementUpdate);
                    $statementUpdate->execute();
                    $results = $statementUpdate->fetchAll(PDO::FETCH_ASSOC);
                    $resultUpdMaestro = "Actualizado";
                    return $resultUpdMaestro;
                } catch (\Throwable $th) {
                    $resultUpdMaestro = "Fallo";
                    return $resultUpdMaestro;
                }
            } else {
                try {
                    $statementUpdate = "UPDATE public.maestros_smsem";
                    $statementUpdate = $statementUpdate . " SET cveissemym='".$cveissemym."', regescmae= ".$region ." , numcelmae='" .$numcelmae."', numfijmae='".$numpartmae."', fcbasemae='".$fechbase."', aservactmae=".$aniosserv.", fbajamae='".$fechbaja."', numpsgs=".$numpsgs.", diaspsgs=".$diaspsgs.",fechfallecmae='".$fechbaja."', estatlabmae='". $motivret ."', cveusu='".$usuario."', fechmodif='".$fecha."', diaservactmae=".$diasserv.", afiprogfondfalle=".$afifonfall.", fechsinipsgs=' " .$fechaini."', fechsfinpsgs='".$fechafin."'";
                    $statementUpdate = $statementUpdate . " WHERE csp='" . $cvemae."';";
                    $statementUpdate = $this->db->prepare($statementUpdate);
                    $statementUpdate->execute();
                    $results = $statementUpdate->fetchAll(PDO::FETCH_ASSOC);
                    $resultUpdMaestro = "Actualizado";
                    return $resultUpdMaestro;
                } catch (\Throwable $th) {
                    $resultUpdMaestro = "Fallo";
                    return $resultUpdMaestro;
                }
            }
        }

        public function actualizaMaestroMut($cveissemym,$curpmae,$rfcmae,$region,$numcel,$numpart,$aniosserv,$fechbajfall,$motvret,$modret,$diasserv,$cveusu,$fecha){
            try {
                $statementUpdate = "UPDATE public.mutualidad";
                $statementUpdate = $statementUpdate . " SET curpmae='".$curpmae."' ,rfcmae='".$rfcmae."', regmae= ".$region ." , numcelmae='" .$numcel."', numfijmae='".$numpart."', fcfallecmae='".$fechbajfall."', estatmutual='F', aniosjub=".$aniosserv.", cveusu='".$cveusu."', fechmodif='".$fecha."', estatusmae='F', diasjub=".$diasserv;
                $statementUpdate = $statementUpdate . " WHERE cveissemym='" . $cveissemym."';";

                $statementUpdate = $this->db->prepare($statementUpdate);
                $statementUpdate->execute();
                $results = $statementUpdate->fetchAll(PDO::FETCH_ASSOC);
                $resultUpdMaestro = "Actualizado";
                return $resultUpdMaestro;
            } catch (\Throwable $th) {
                $resultUpdMaestro = "Fallo";
                return $resultUpdMaestro;
            }
        }

        public function insertCheque($anioentr,$numentr,$idret,$identreret,$cvemae,$nombenefs,$numbenef,$montretentr,$estatEdad,$porcsBenef,$usuario,$fecha,$motivret){
            for ($i=0; $i < $numbenef; $i++) { 
                $idbenef = $i + 1;
                if ($idbenef < 10) {
                    $idbenefcheque = $identreret . "0" . $idbenef;
                }elseif ($idbenef > 9) {
                    $idbenefcheque = $identreret . $idbenef;
                }
                $montbenefletra = $this->cantidadLetra->cantidadLetras($montretentr);
                    try {
                        $statementInsertCheque = "INSERT INTO public.beneficiarios_cheques(";
                        $statementInsertCheque = $statementInsertCheque . "anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, nombenef, montbenef, montbenefletra, folcheque, fechcheque, fechreposcn, folanterior, oficsolrepofinan, usureposcn, observreposcn, fechentrega, estatcheque, observcheque, movimtscheque, motvcancel, fechcancel, porcretbenef, statedad, chequeadeudo, adeudo, cveusu, fechmodif)";
                        $statementInsertCheque = $statementInsertCheque . " VALUES (".$anioentr.", ".$numentr.", ".$idret.", '".$identreret."', ".$idbenef.", '".$idbenefcheque."', '".$cvemae."', '".$nombenefs[$i]."', ".$montretentr.", '".$montbenefletra."', '', '1900-01-01', '1900-01-01', '', '', '', '', '1900-01-01', '', '', '', 0, '1900-01-01', ".$porcsBenef[$i].", '".$estatEdad[$i]."', 'N', '', '".$usuario."', '".$fecha."');";
                        $statementInsertCheque = $this->db->prepare($statementInsertCheque);
                        $statementInsertCheque->execute();
                        $results = $statementInsertCheque->fetchAll(PDO::FETCH_ASSOC);
                        $resultInsertCheque = "Agregado";
                        return $resultInsertCheque;
                    } catch (\Throwable $th) {
                        $resultInsertCheque = "Fallo";
                        return $resultInsertCheque;
                    }            
            }
        }

        public function insertChequeF($anioentr,$numentr,$idret,$identreret,$cvemae,$nombenefs,$numbenef,$montretentr,$estatEdad,$porcsBenef,$estatVida,$usuario,$fecha,$motivret){
            $validInsertCorrect = 0;
            $validInserError = 0;
            $numbeneffall = 0;
            for ($i=0; $i < $numbenef; $i++) { 
                $idbenef = $i + 1;
                if ($idbenef < 10) {
                    $idbenefcheque = $identreret . "0" . $idbenef;
                }elseif ($idbenef > 9) {
                    $idbenefcheque = $identreret . $idbenef;
                }
                if ($estatVida[$i] == "V") {
                    $montbenef = round((($montretentr * $porcsBenef[$i]) / 100),2);
                    $montbenefletra = $this->cantidadLetra->cantidadLetras($montbenef);
                    try {
                        $statementInsertCheque = "INSERT INTO public.beneficiarios_cheques(";
                        $statementInsertCheque = $statementInsertCheque . "anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, nombenef, montbenef, montbenefletra, folcheque, fechcheque, fechreposcn, folanterior, oficsolrepofinan, usureposcn, observreposcn, fechentrega, estatcheque, observcheque, movimtscheque, motvcancel, fechcancel, porcretbenef, statedad, chequeadeudo, adeudo, cveusu, fechmodif)";
                        $statementInsertCheque = $statementInsertCheque . " VALUES (".$anioentr.", ".$numentr.", ".$idret.", '".$identreret."', ".$idbenef.", '".$idbenefcheque."', '".$cvemae."', '".$nombenefs[$i]."', ".$montbenef.", '".$montbenefletra."', '', '1900-01-01', '1900-01-01', '', '', '', '', '1900-01-01', '', '', '', 0, '1900-01-01', ".$porcsBenef[$i].", '".$estatEdad[$i]."','N', '', '".$usuario."', '".$fecha."');";
                        $statementInsertCheque = $this->db->prepare($statementInsertCheque);
                        $statementInsertCheque->execute();
                        $results = $statementInsertCheque->fetchAll(PDO::FETCH_ASSOC);
                        $validInsertCorrect++;
                    } catch (\Throwable $th) {
                        $validInserError++;
                    }
                }else {
                    $numbeneffall++;
                }              
            }

            if (($validInsertCorrect + $numbeneffall) == $numbenef) {
                $resultInsertCheque = "Agregado";
                return $resultInsertCheque;
            } elseif ($validInserError > 0) {
                $resultInsertCheque = "Fallo";
                return $resultInsertCheque;
            }
        }

        public function insertBeneficiaroisMae($anioentr,$numentr,$idret,$identreret,$cvemae,$nombenefs,$numbenef,$montretentr,$curps,$parentescos,$estatEdad,$porcsBenef,$estatvida,$usuario,$fecha,$motivret){
            $validInsertCorrect = 0;
            $validInserError = 0;
            for ($i=0; $i < $numbenef; $i++) { 
                $idbenef = $i + 1;
                if ($idbenef < 10) {
                    $idbenefcheque = $identreret . "0" . $idbenef;
                }elseif ($idbenef > 9) {
                    $idbenefcheque = $identreret . $idbenef;
                }

                try {
                    $statementInsertCheque = "INSERT INTO public.beneficiarios_maestros(";
                    $statementInsertCheque = $statementInsertCheque . "anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, motvret, nombenef, curpbenef, parentbenef, porcretbenef, edadbenef, vidabenef, cveusureg, fechreg)";
                    $statementInsertCheque = $statementInsertCheque . " VALUES (".$anioentr.", ".$numentr.", ".$idret.", '".$identreret."', ".$idbenef.", '".$idbenefcheque."', '".$cvemae."', '".$motivret."', '".$nombenefs[$i]."', '".$curps[$i]."', '".$parentescos[$i]."' , ".$porcsBenef[$i].",'".$estatEdad[$i]."','".$estatvida[$i]."', '".$usuario."', '".$fecha."');";
                    $statementInsertCheque = $this->db->prepare($statementInsertCheque);
                    $statementInsertCheque->execute();
                    $results = $statementInsertCheque->fetchAll(PDO::FETCH_ASSOC);
                    $validInsertCorrect++;
                } catch (\Throwable $th) {
                    $validInserError++;
                }              
            }
            if ($validInsertCorrect == $numbenef) {
                $resultInsertCheque = "Agregado";
                return $resultInsertCheque;
            } elseif ($validInserError > 0) {
                $resultInsertCheque = "Fallo";
                return $resultInsertCheque;
            }
        }

        public function insertJubiladoSmsem($cvejub,$apepatjub,$apematjub,$nomjub,$nomcomjub,$progfallec,$usuario,$fecha){
            try {
                $statementInsertJub = "INSERT INTO public.jubilados_smsem(";
                $statementInsertJub = $statementInsertJub . "cveissemym, apepatjub, apematjub, nomjub, nomcomjub, programfallec, cveusureg, fechreg, histmodif, cveusumodif, fechmodif)";
                $statementInsertJub = $statementInsertJub . " VALUES ('".$cvejub."', '".$apepatjub."', '".$apematjub."', '".$nomjub."', '".$nomcomjub."', '".$progfallec."', '".$usuario."', '".$fecha."', '', '', '1900-01-01');";
                $statementInsertJub = $this->db->prepare($statementInsertJub);
                $statementInsertJub->execute();
                $results = $statementInsertJub->fetchAll(PDO::FETCH_ASSOC);
                $resultInsertJub = "Agregado";
                return $resultInsertJub;
            } catch (\Throwable $th) {
                $resultInsertJub = "Fallo";
                return $resultInsertJub;
            }
        }

        public function insertFondoFallecimiento($identrret,$cvejub,$montretT,$modret,$montretentr,$montretfall,$fechbaja,$fechrecib,$diahaber,$usuario,$fecha){
            try {                
                $statementInsertFF = "INSERT INTO public.fondo_fallecimiento(";
                $statementInsertFF = $statementInsertFF . "identret, anioemifondfalle, numemifondfalle, idmae, cveissemym, montret, modalretiro, montretentr, montretfondfall, fechbajamae, docacuerdo, fechacuerdo, montdiahaber, estatfondfall, fechafifondfalle, fechfallemae, estatusmae, cveusureg, fechreg)";
                $statementInsertFF = $statementInsertFF . " VALUES ('".$identrret."', 0, 0, 0, '".$cvejub."', ".$montretT.", '".$modret."', ".$montretentr.", ".$montretfall.", '".$fechbaja."', '', '".$fechrecib."', ".$diahaber.", 'P', '1900-01-01', '1900-01-01', 'JA', '".$usuario."', '".$fecha."');";
                $statementInsertFF = $this->db->prepare($statementInsertFF);
                $statementInsertFF->execute();
                $results = $statementInsertFF->fetchAll(PDO::FETCH_ASSOC);
                $resultInsertFF = "Agregado";
                return $resultInsertFF;
            } catch (\Throwable $th) {
                $resultInsertFF = "Fallo";
                return $resultInsertFF;
            }
        }

        public function obtenMaxCheque($identret,$cvemae){
            $statementIdBenef = $this->db->prepare("SELECT MAX(idbenef) as numbenef FROM public.beneficiarios_cheques WHERE cvemae='".$cvemae."' AND identret='".$identret."';");
            $statementIdBenef->execute();
            $results = $statementIdBenef->fetchAll();
            if (is_null($results[0]['numbenef'])) {
                $idBenef = 1;
            } else {
                $idBenef = $results[0]['numbenef'];
            }
            return $idBenef;
        }

        public function updateCheqJI($identregret,$cvemae,$nombenef,$montretentr,$usuario,$fecha,$motivret){
            $montbenefletra = $this->cantidadLetra->cantidadLetras($montretentr);
            try {
                $statementUpdCheque = "UPDATE public.beneficiarios_cheques";
                $statementUpdCheque = $statementUpdCheque . " SET nombenef='".$nombenef."', montbenef=".$montretentr.", montbenefletra='".$montbenefletra."', porcretbenef='100', statedad='M', cveusu='".$usuario."', fechmodif='".$fecha."'";
                $statementUpdCheque = $statementUpdCheque . " WHERE cvemae='".$cvemae."' AND identret='".$identregret."';;";

                $statementUpdCheque = $this->db->prepare($statementUpdCheque);
                $statementUpdCheque->execute();
                $results = $statementUpdCheque->fetchAll(PDO::FETCH_ASSOC);
                $resultUpdCheque = "Actualizado";
                return $resultUpdCheque;
            } catch (\Throwable $th) {
                $resultUpdCheque = "Fallo";
                return $resultUpdCheque;
            }
        }

        public function insertaChequeAdeudo($anioentr,$numentr,$idret,$identreret,$cvemae,$numadeds,$estatEdadAd,$porcsBenefAd,$nomBenefAd,$montBenefAd,$usuario,$fecha,$motivret,$adeudoOfic){
            $idbenef = 1;
            for ($i=0; $i < $numadeds; $i++) { 
                $idbenef = $idbenef + 1;
                if ($idbenef < 10) {
                    $idbenefcheque = $identreret . "0" . $idbenef;
                }elseif ($idbenef > 9) {
                    $idbenefcheque = $identreret . $idbenef;
                }

                $montbenefletra = $this->cantidadLetra->cantidadLetras($montBenefAd[$i]);
                    try {
                        $statementInsertCheqAd = "INSERT INTO public.beneficiarios_cheques(";
                        $statementInsertCheqAd = $statementInsertCheqAd . "anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, nombenef, montbenef, montbenefletra, folcheque, fechcheque, fechreposcn, folanterior, oficsolrepofinan, usureposcn, observreposcn, fechentrega, estatcheque, observcheque, movimtscheque, motvcancel, fechcancel, porcretbenef, statedad, chequeadeudo, adeudo, cveusu, fechmodif)";
                        $statementInsertCheqAd = $statementInsertCheqAd . " VALUES (".$anioentr.", ".$numentr.", ".$idret.", '".$identreret."', ".$idbenef.", '".$idbenefcheque."', '".$cvemae."', '".$nomBenefAd[$i]."', ".$montBenefAd[$i].", '".$montbenefletra."', '', '1900-01-01', '1900-01-01', '', '', '', '', '1900-01-01', '', '', '', 0, '1900-01-01', ".$porcsBenefAd[$i].", '".$estatEdadAd[$i]."','S', '".$adeudoOfic[$i]."', '".$usuario."', '".$fecha."');";
                        $statementInsertCheqAd = $this->db->prepare($statementInsertCheqAd);
                        $statementInsertCheqAd->execute();
                        $results = $statementInsertCheqAd->fetchAll(PDO::FETCH_ASSOC);

                        $resultInsertChequeAd = "Agregado";
                    } catch (\Throwable $th) {
                        $resultInsertChequeAd = "Fallo";
                    }            
            }
            return $resultInsertChequeAd;
        }

        public function insertaChequeAdeudoF($anioentr,$numentr,$idret,$identreret,$cvemae,$numadeds,$estatEdadAd,$porcsBenefAd,$nomBenefAd,$montBenefAd,$usuario,$fecha,$motivret,$adeudoOfic){
            $idbenef = $this->obtenMaxCheque($identreret,$cvemae);
            for ($i=0; $i < $numadeds; $i++) { 
                $idbenef = $idbenef + 1;
                if ($idbenef < 10) {
                    $idbenefcheque = $identreret . "0" . $idbenef;
                }elseif ($idbenef > 9) {
                    $idbenefcheque = $identreret . $idbenef;
                }

                $montbenefletra = $this->cantidadLetra->cantidadLetras($montBenefAd[$i]);
                    try {
                        $statementInsertCheqAd = "INSERT INTO public.beneficiarios_cheques(";
                        $statementInsertCheqAd = $statementInsertCheqAd . "anioentrega, numentrega, idret, identret, idbenef, idbenefcheque, cvemae, nombenef, montbenef, montbenefletra, folcheque, fechcheque, fechreposcn, folanterior, oficsolrepofinan, usureposcn, observreposcn, fechentrega, estatcheque, observcheque, movimtscheque, motvcancel, fechcancel, porcretbenef, statedad, chequeadeudo, adeudo, cveusu, fechmodif)";
                        $statementInsertCheqAd = $statementInsertCheqAd . " VALUES (".$anioentr.", ".$numentr.", ".$idret.", '".$identreret."', ".$idbenef.", '".$idbenefcheque."', '".$cvemae."', '".$nomBenefAd[$i]."', ".$montBenefAd[$i].", '".$montbenefletra."', '', '1900-01-01', '1900-01-01', '', '', '', '', '1900-01-01', '', '', '', 0, '1900-01-01', ".$porcsBenefAd[$i].", '".$estatEdadAd[$i]."','S', '".$adeudoOfic[$i]."', '".$usuario."', '".$fecha."');";
                        $statementInsertCheqAd = $this->db->prepare($statementInsertCheqAd);
                        $statementInsertCheqAd->execute();
                        $results = $statementInsertCheqAd->fetchAll(PDO::FETCH_ASSOC);

                        $resultInsertChequeAd = "Agregado";
                    } catch (\Throwable $th) {
                        $resultInsertChequeAd = "Fallo";
                    }            
            }
            return $resultInsertChequeAd;
        }

        public function deleteChequesAds($identret,$cvemae){
            try {
                $statementDeleteCheqAd = "DELETE FROM public.beneficiarios_cheques WHERE identret = '".$identret."' AND cvemae='".$cvemae."' AND chequeadeudo='S';";
                $statementDeleteCheqAd = $this->db->prepare($statementDeleteCheqAd);
                $statementDeleteCheqAd->execute();
                $results =$statementDeleteCheqAd->fetchAll(PDO::FETCH_ASSOC);
                $resultDelCheqAdS = "Eliminado";
                return $resultDelCheqAdS ;
            } catch (\Throwable $th) {
                $resultDelCheqAdS = "Fallo";
                return $resultDelCheqAdS ;
            }
        }

        public function updateJubInha($anioentr,$numentre,$identr,$idretiro,$identregRet,$cvemae,$cveissemym,$estatlaboral,$motvret,$apepat,$apemat,$nombre,$nomcom,$region,$numdictam,$fechdictam,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$fechinipsgs,$fechfinpsgs,$numpsgs,$diaspsgs,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$montretfall,$fechrecib,$numoficautori,$fechautori,$imgautori,$diaHaber,$adedfajam,$adedts,$adedfondpens,$adedturismo,$montadeds,$montretsnadeds,$numadeds,$curpmae,$rfcmae,$cveusu){
            $a_resultUpdTram = array();
                        
            $fecha = "";
            $fecha = date("Y-m-d H:i:s");

            $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
            $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);
            $montretfallLet = $this->cantidadLetra->cantidadLetras($montretfall);
                
            if(empty($numoficautori)){
                $exception = 0;
                $fechautori = '1900-01-01';
            }else {
                $exception = 1;
            }

            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdate = "UPDATE public.tramites_fonretyf";
                $consultaUpdate = $consultaUpdate . " SET numdictam='".$numdictam."', fechdictam='".$fechdictam."', fechbajfall='".$fechbajfall."', nomsolic='".$nomsolic."', numcelsolic='".$numcel."', numpartsolic='".$numpart."', modretiro='".$modret."', montrettot=".$montrettot.", montretletra='".$montrettotLet."', montretsinads=".$montretsnadeds.", montretentr=".$montretentr.", montretentrletra='".$montretentrLet."', montretfall=".$montretfall.", montretfallletra='".$montretfallLet."',";
                $consultaUpdate = $consultaUpdate . " fechrecib='".$fechrecib."', tramtexcepcion=".$exception.", numoficioaut='".$numoficautori."', fechautori='".$fechautori."', imgautori='".$imgautori."', adfajam=".$adedfajam.", adts=".$adedts.", adfondpension=".$adedfondpens.", adturismo=".$adedturismo.", montadeudos=".$montadeds.", montsalmin=".$diaHaber.", cveusumodif='".$cveusu."', fechmodif='".$fecha."', numadeds=".$numadeds." WHERE cvemae='".$cvemae."' AND identret='".$identregRet."';";
                $consultaUpdate = $this->db->prepare($consultaUpdate);
                $consultaUpdate->execute();
                $results = $consultaUpdate->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdTram["updateTramite"] = "Actualizado";
            } catch (\Throwable $th) {
                $a_resultUpdTram["updateTramite"] = "Fallo";
            }

            $actualizaMae = $this->actualizaMaestroAct($cvemae,$cveissemym,$region,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$fechbajfall,$numpsgs,$diaspsgs,$fechinipsgs,$fechfinpsgs,$motvret,$modret,$cveusu,$fecha,$curpmae,$rfcmae);
            $a_resultUpdTram["updateMaestro"] = $actualizaMae;

            $a_get_cheques = $this->obtenMaxCheque($identregRet,$cvemae);

            if ($a_get_cheques == 1 && $numadeds == 0) {
                $a_get_updateCheq = $this->updateCheqJI($identregRet,$cvemae,$nomcom,$montretentr,$cveusu,$fecha,$motvret);
                $a_resultUpdTram["updateCheque"] = $a_get_updateCheq;
            } else if ($a_get_cheques == 1 && $numadeds > 0) {
                $a_get_updateCheq = $this->updateCheqJI($identregRet,$cvemae,$nomcom,$montretentr,$cveusu,$fecha,$motvret);
                $a_resultUpdTram["updateCheque"] = $a_get_updateCheq;

                if ($adedfajam > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedfajam;
                    $adeudoOfic[] ="FAJAM";
                } 
                if ($adedts > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedts;
                    $adeudoOfic[] ="TIENDA SINDICAL";
                }
                if ($adedturismo > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedturismo;
                    $adeudoOfic[] ="TURISMO";
                }

                $insertaChequeAd = $this->insertaChequeAdeudo($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$numadeds,$estatEdadAd,$porcsBenefAd,$nombreBenefAd,$montBenefAd,$cveusu,$fecha,$motvret,$adeudoOfic);
                $a_resultUpdTram["insertChequeA"] = $insertaChequeAd;

            }elseif ($a_get_cheques > 1 && $numadeds == 0) {
                $a_get_deleteCheqAd = $this->deleteChequesAds($identregRet,$cvemae);
                $a_resultUpdTram["deleteCheqAd"] = $a_get_deleteCheqAd;

                $a_get_updateCheq = $this->updateCheqJI($identregRet,$cvemae,$nomcom,$montretentr,$cveusu,$fecha,$motvret);
                $a_resultUpdTram["updateCheque"] = $a_get_updateCheq;
            }elseif ($a_get_cheques > 1 && $numadeds > 0) {
                $a_get_deleteCheqAd = $this->deleteChequesAds($identregRet,$cvemae);
                $a_resultUpdTram["deleteCheqAd"] = $a_get_deleteCheqAd;

                $a_get_updateCheq = $this->updateCheqJI($identregRet,$cvemae,$nomcom,$montretentr,$cveusu,$fecha,$motvret);
                $a_resultUpdTram["updateCheque"] = $a_get_updateCheq;
                
                if ($adedfajam > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedfajam;
                    $adeudoOfic[] ="FAJAM";
                } 
                if ($adedts > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedts;
                    $adeudoOfic[] ="TIENDA SINDICAL";
                }
                if ($adedturismo > 0) {
                    $nombreBenefAd[] =$nomcom; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedturismo;
                    $adeudoOfic[] ="TURISMO";
                }

                $insertaChequeAd = $this->insertaChequeAdeudo($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$numadeds,$estatEdadAd,$porcsBenefAd,$nombreBenefAd,$montBenefAd,$cveusu,$fecha,$motvret,$adeudoOfic);
                $a_resultUpdTram["insertChequeA"] = $insertaChequeAd;
            }

            return $a_resultUpdTram;
        }

        public function updateFA($anioentr,$numentre,$identr,$idretiro,$identregRet,$cvemae,$cveissemym,$estatlaboral,$motvret,$apepat,$apemat,$nombre,$nomcom,$region,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$fechinipsgs,$fechfinpsgs,$numpsgs,$diaspsgs,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$fechrecib,$numoficautori,$fechautori,$imgautori,$numbenefs,$adedfajam,$adedts,$adedfondpens,$adedturismo,$montadeds,$montretsnadeds,$numadeds,$nomsbenefs,$curpsbenefs,$parentsbenefs,$porcsbenefs,$edadesbenefs,$vidasbenefs,$testamento,$fechtestamnt,$curpmae,$rfcmae,$cveusu){
            $a_resultUpdTramFA = array();
            $fecha = "";
            $fecha = date("Y-m-d H:i:s");

            $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
            $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);
                
            if(empty($numoficautori)){
                $exception = 0;
                $fechautori = '1900-01-01';
            }else {
                $exception = 1;
            }

            $benefsFallcs = 0;
            foreach ($vidasbenefs as $row) {
                if ($row == "F") {
                    $benefsFallcs++;
                }                   
            }

            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdate = "UPDATE public.tramites_fonretyf";
                $consultaUpdate = $consultaUpdate . " SET fechbajfall='".$fechbajfall."', nomsolic='".$nomsolic."', numcelsolic='".$numcel."', numpartsolic='".$numpart."', modretiro='".$modret."', montrettot=".$montrettot.", montretletra='".$montrettotLet."', montretsinads=".$montretsnadeds.", montretentr=".$montretentr.", montretentrletra='".$montretentrLet."', docttestamnt='".$testamento."', fechdocttestmnt='".$fechtestamnt."', numbenef=".$numbenefs.",";
                $consultaUpdate = $consultaUpdate . " fechrecib='".$fechrecib."', tramtexcepcion=".$exception.", numoficioaut='".$numoficautori."', fechautori='".$fechautori."', imgautori='".$imgautori."', adfajam=".$adedfajam.", adts=".$adedts.", adfondpension=".$adedfondpens.", adturismo=".$adedturismo.", montadeudos=".$montadeds.", cveusumodif='".$cveusu."', fechmodif='".$fecha."', numadeds=".$numadeds." WHERE cvemae='".$cvemae."' AND identret='".$identregRet."';";
                $consultaUpdate = $this->db->prepare($consultaUpdate);
                $consultaUpdate->execute();
                $results = $consultaUpdate->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdTramFA["updateTramite"] = "Actualizado";
            } catch (\Throwable $th) {
                echo($th);
                $a_resultUpdTramFA["updateTramite"] = "Fallo";
            }

            $actualizaMae = $this->actualizaMaestroAct($cvemae,$cveissemym,$region,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$fechbajfall,$numpsgs,$diaspsgs,$fechinipsgs,$fechfinpsgs,$motvret,$modret,$cveusu,$fecha,$curpmae,$rfcmae);
            $a_resultUpdTramFA["updateMaestro"] = $actualizaMae;

            $nombresB = explode(",",$nomsbenefs);
            $curpsB = explode(",",$curpsbenefs);
            $parentsB = explode(",",$parentsbenefs);
            $porcentsB = explode(",",$porcsbenefs);
            $edadesB = explode(",",$edadesbenefs);
            $vidasB = explode(",",$vidasbenefs);

            if ($numadeds == 0) {
                $deleteCheqsFA = $this->deleteChequesFA($identregRet,$cvemae);
                $a_resultUpdTramFA["deleteCheques"] = $deleteCheqsFA;

                $deleteBenefsFA = $this->deleteBenefs($identregRet,$cvemae);
                $a_resultUpdTramFA["deleteBenefs"] = $deleteBenefsFA;

                $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombresB,$numbenefs,$montretentr,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFA["insertCheques"] = $insertaCheques;

                $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombresB,$numbenefs,$montretentr,$curpsB,$parentsB,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFA["insertBenefs"] = $insertaBenefsMae;

            } else {
                $deleteCheqsFA = $this->deleteChequesFA($identregRet,$cvemae);
                $a_resultUpdTramFA["deleteCheques"] = $deleteCheqsFA;

                $deleteBenefsFA = $this->deleteBenefs($identregRet,$cvemae);
                $a_resultUpdTramFA["deleteBenefs"] = $deleteBenefsFA;

                $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombresB,$numbenefs,$montretentr,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFA["insertCheques"] = $insertaCheques;

                $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$nombresB,$numbenefs,$montretentr,$curpsB,$parentsB,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFA["insertBenefs"] = $insertaBenefsMae;

                if ($adedfajam > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedfajam;
                    $adeudoOfic[] ="FAJAM";
                } 
                if ($adedts > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedts;
                    $adeudoOfic[] ="TIENDA SINDICAL";
                }
                if ($adedturismo > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedturismo;
                    $adeudoOfic[] ="TURISMO";
                }

                $insertaChequeAd = $this->insertaChequeAdeudoF($anioentr,$numentre,$idretiro,$identregRet,$cvemae,$numadeds,$estatEdadAd,$porcsBenefAd,$nombreBenefAd,$montBenefAd,$cveusu,$fecha,$motvret,$adeudoOfic);
                $a_resultUpdTramFA["insertChequeA"] = $insertaChequeAd;
            }

            return $a_resultUpdTramFA;
        }

        
        public function updateFJ($anioentr,$numentre,$identr,$idretiro,$identregRet,$cveissemym,$estatlaboral,$motvret,$apepat,$apemat,$nombre,$nomcom,$region,$fechbajfall,$nomsolic,$numcel,$numpart,$fechbase,$diasserv,$aniosserv,$modret,$montrettot,$montretentr,$fechrecib,$numoficautori,$fechautori,$imgautori,$numbenefs,$adedfajam,$adedts,$adedfondpens,$adedturismo,$montadeds,$montretsnadeds,$numadeds,$nomsbenefs,$curpsbenefs,$parentsbenefs,$porcsbenefs,$edadesbenefs,$vidasbenefs,$testamento,$fechtestamnt,$curpmae,$rfcmae,$cveusu){
            $a_resultUpdTramFJ = array();
            $fecha = "";
            $fecha = date("Y-m-d H:i:s");

            $montrettotLet = $this->cantidadLetra->cantidadLetras($montrettot);
            $montretentrLet = $this->cantidadLetra->cantidadLetras($montretentr);
                
            if(empty($numoficautori)){
                $exception = 0;
                $fechautori = '1900-01-01';
            }else {
                $exception = 1;
            }

            $benefsFallcs = 0;
            foreach ($vidasbenefs as $row) {
                if ($row == "F") {
                    $benefsFallcs++;
                }                   
            }

            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdate = "UPDATE public.tramites_fonretyf";
                $consultaUpdate = $consultaUpdate . " SET fechbajfall='".$fechbajfall."', nomsolic='".$nomsolic."', numcelsolic='".$numcel."', numpartsolic='".$numpart."', modretiro='".$modret."', montrettot=".$montrettot.", montretletra='".$montrettotLet."', montretsinads=".$montretsnadeds.", montretentr=".$montretentr.", montretentrletra='".$montretentrLet."', docttestamnt='".$testamento."', fechdocttestmnt='".$fechtestamnt."', numbenef=".$numbenefs.",";
                $consultaUpdate = $consultaUpdate . " fechrecib='".$fechrecib."', tramtexcepcion=".$exception.", numoficioaut='".$numoficautori."', fechautori='".$fechautori."', imgautori='".$imgautori."', adfajam=".$adedfajam.", adts=".$adedts.", adfondpension=".$adedfondpens.", adturismo=".$adedturismo.", montadeudos=".$montadeds.", cveusumodif='".$cveusu."', fechmodif='".$fecha."', numadeds=".$numadeds." WHERE cvemae='".$cveissemym."' AND identret='".$identregRet."';";
                $consultaUpdate = $this->db->prepare($consultaUpdate);
                $consultaUpdate->execute();
                $results = $consultaUpdate->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdTramFJ["updateTramite"] = "Actualizado";
            } catch (\Throwable $th) {
                $a_resultUpdTramFJ["updateTramite"] = "Fallo";
            }

            $actualizaMae = $this->actualizaMaestroMut($cveissemym,$curpmae,$rfcmae,$region,$numcel,$numpart,$aniosserv,$fechbajfall,$motvret,$modret,$diasserv,$cveusu,$fecha);
            $a_resultUpdTramFJ["updateMaestro"] = $actualizaMae;

            $nombresB = explode(",",$nomsbenefs);
            $curpsB = explode(",",$curpsbenefs);
            $parentsB = explode(",",$parentsbenefs);
            $porcentsB = explode(",",$porcsbenefs);
            $edadesB = explode(",",$edadesbenefs);
            $vidasB = explode(",",$vidasbenefs);

            if ($numadeds == 0) {
                $deleteCheqsFJ = $this->deleteChequesFA($identregRet,$cveissemym);
                $a_resultUpdTramFJ["deleteCheques"] = $deleteCheqsFJ;

                $deleteBenefsFJ = $this->deleteBenefs($identregRet,$cveissemym);
                $a_resultUpdTramFJ["deleteBenefs"] = $deleteBenefsFJ;

                $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nombresB,$numbenefs,$montretentr,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFJ["insertCheques"] = $insertaCheques;

                $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nombresB,$numbenefs,$montretentr,$curpsB,$parentsB,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFJ["insertBenefs"] = $insertaBenefsMae;

            } else {
                $deleteCheqsFJ = $this->deleteChequesFA($identregRet,$cveissemym);
                $a_resultUpdTramFJ["deleteCheques"] = $deleteCheqsFJ;

                $deleteBenefsFJ = $this->deleteBenefs($identregRet,$cveissemym);
                $a_resultUpdTramFJ["deleteBenefs"] = $deleteBenefsFJ;

                $insertaCheques = $this->insertChequeF($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nombresB,$numbenefs,$montretentr,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFJ["insertCheques"] = $insertaCheques;

                $insertaBenefsMae = $this -> insertBeneficiaroisMae($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$nombresB,$numbenefs,$montretentr,$curpsB,$parentsB,$edadesB,$porcentsB,$vidasB,$cveusu,$fecha,$motvret);
                $a_resultUpdTramFJ["insertBenefs"] = $insertaBenefsMae;

                if ($adedfajam > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedfajam;
                    $adeudoOfic[] ="FAJAM";
                } 
                if ($adedts > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedts;
                    $adeudoOfic[] ="TIENDA SINDICAL";
                }
                if ($adedturismo > 0) {
                    $nombreBenefAd[] =$nombresB[0]; 
                    $estatEdadAd[] = "M";
                    $porcsBenefAd[] = "100";
                    $montBenefAd[] = $adedturismo;
                    $adeudoOfic[] ="TURISMO";
                }

                $insertaChequeAd = $this->insertaChequeAdeudoF($anioentr,$numentre,$idretiro,$identregRet,$cveissemym,$numadeds,$estatEdadAd,$porcsBenefAd,$nombreBenefAd,$montBenefAd,$cveusu,$fecha,$motvret,$adeudoOfic);
                $a_resultUpdTramFJ["insertChequeA"] = $insertaChequeAd;
            }

            return $a_resultUpdTramFJ;
        }

        public function deleteChequesFA($identret,$cvemae){
            try {

                $statementDeleteCheq = "DELETE FROM public.beneficiarios_cheques WHERE identret = '".$identret."' AND cvemae='".$cvemae."';";
                $statementDeleteCheq = $this->db->prepare($statementDeleteCheq);
                $statementDeleteCheq->execute();
                $results =$statementDeleteCheq->fetchAll(PDO::FETCH_ASSOC);
                $resultDelCheqs = "Eliminado";
                return $resultDelCheqs ;
            } catch (\Throwable $th) {
                $resultDelCheqs = "Fallo";
                return $resultDelCheqs ;
            }
        }

        public function deleteBenefs($identret,$cvemae){
            try {
                $statementDeleteBenefs = "DELETE FROM public.beneficiarios_maestros WHERE identret = '".$identret."' AND cvemae='".$cvemae."';";
                $statementDeleteBenefs = $this->db->prepare($statementDeleteBenefs);
                $statementDeleteBenefs->execute();
                $results =$statementDeleteBenefs->fetchAll(PDO::FETCH_ASSOC);
                $resultDelBenefs = "Eliminado";
                return $resultDelBenefs ;
            } catch (\Throwable $th) {
                $resultDelBenefs = "Fallo";
                return $resultDelBenefs ;
            }
        }

    }

?>

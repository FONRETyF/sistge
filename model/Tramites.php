<?php
    session_start();

    class Tramite{
        private $db;
        private $retiros;
        
        public function __construct(){
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
            $this->retiros = array();
        }

        public function validaFechas($clavemae,$motret,$fechRecibido,$fechDictamen,$fechBaseMae,$fechBajaMae){
            $validesFechs = array();
            $dias_Serv = array();
            $i=0;

            if ($fechRecibido > $fechDictamen && $fechRecibido > $fechBaseMae && $fechRecibido > $fechBajaMae) {
                if ($fechDictamen > $fechBaseMae && $fechDictamen < $fechBajaMae) {
                    if ($fechBaseMae < $fechBajaMae) {
                        $vigenciaTram = $this -> validaVigencia($fechBajaMae,$fechRecibido);
                        if (($vigenciaTram/365) < 1) {
                            $dias_Serv["descResult"] = "vigenciaVal";
                            $dias_Serv["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae);
                            return $dias_Serv;
                        } else {
                            $validesFechs["descResult"] = "vigenciaCad";
                            $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae);
                            $validesFechs["excepcion"] = "SI";
                            $validesFechs["prorroga"] = "NO";
                            return $validesFechs;
                        }
                    } else {
                        $validesFechs["descResult"] = "errorFecha";
                        $validesFechs["diasServ"] = "La fecha de BASE no puede ser mayor  ala fecah de BAJA";
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
                        if (($vigenciaTram/365) > 1){
                            $statement = $this->db->prepare("SELECT * FROM public.prorrogas WHERE cvemae=? and estatuspro='ACTIVA'");
                            $statement->bindValue(1,$clavemae);
                            $statement->execute();
                            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (count($results) > 0) {
                                $validesFechs["descResult"] = "vigenciaCadD";
                                $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae);
                                $validesFechs["excepcion"] = "SI";
                                $validesFechs["prorroga"] = "SI";
                                return $validesFechs;
                            }else {
                                $validesFechs["descResult"] = "vigenciaCadD";
                                $validesFechs["diasServ"] = $this -> calculaDiasServ($fechBaseMae,$fechBajaMae);
                                $validesFechs["excepcion"] = "SI";
                                $validesFechs["prorroga"] = "NO";
                                return $validesFechs;
                            }
                        }else{}
                    }
                }
            }
                
        }

        public function validaVigencia($fechBajaMae,$fechRecibido){
            $diasValid = $this->calculaDifFechas($fechBajaMae,$fechRecibido);      
            return $diasValid;
        }

        public function calculaDiasServ($fechBaseMae,$fechBajaMae){
            $diasServ = $this->calculaDifFechas($fechBaseMae,$fechBajaMae); 
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

        public function get_Retiro($aniosserv){
            $statement = $this->db->prepare("SELECT aportprom FROM public.parametros_retiro WHERE estatparam='ACTIVO'");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row){
                $montprom = substr($row['aportprom'],1,strlen($row['aportprom'])-1);
            }
            $result = $this->calculaRet($aniosserv,$montprom);

            return $result;
        }    

        private function calculaRet($aniosserv,$montprom){
            $retiro = ((($montprom * 24) * $aniosserv) * 0.4) * .99;
            $dats_ret = array();
            $dats_ret[] = [
                "montRet" => $retiro
            ];
            
            return $dats_ret;            
        }
    }   

?>

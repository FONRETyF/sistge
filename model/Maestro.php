<?php

    session_start();

    class Maestro{
        private $db;
        private $entregas;
        
        public function __construct(){
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
            $this->entregas = array();
        }
        
        public function get_maestro($clavemae){
            $statement = $this->db->prepare('SELECT csp,cveissemym,apepatmae,apematmae,nommae,nomcommae,curpmae,rfcmae,estatlabmae FROM public.maestros_smsem WHERE csp=?');
            $statement->bindValue(1,$clavemae);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        
        public function update_nomMae($apepatmae,$apematmae,$nommae,$nomcommae,$cveusu,$cvemae){
            $fecha = date("Y-m-d");
            $nomcommae = $apepatmae . " " . $apematmae . " " . $nommae;
            $datsInsert=array($apepatmae, $apematmae, $nommae, $nomcommae, $cveusu, $fecha,$cvemae);
            $statement = $this->db->prepare("UPDATE public.maestros_smsem SET apepatmae=?, apematmae=?, nommae=?, nomcommae=?, cveusu=?, fechmodif= ?  WHERE csp=?");
            $statement->execute($datsInsert);
            return $result = $statement->fetchAll();
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
            $retiro = (($montprom * 24) * $aniosserv) * 0.4;
            $dats_ret =array();
            $dats_ret[] = [
                "montRet" => $retiro
            ];
            
            return $dats_ret;            
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

        public function buscaTrsmitesHist($clavemae){
            $statement = $this->db->prepare('SELECT identrega,identret,cvemae,motvret,fechentrega FROM public.tramites_fonretyf_hist WHERE cvemae=?');
            $statement->bindValue(1,$clavemae);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    }
?>
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
        
        public function get_maestroJub($claveIssemym){
            $statement = $this->db->prepare('SELECT cveissemym,programfallec FROM public.jubilados_smsem WHERE cveissemym=?');
            $statement->bindValue(1,$claveIssemym);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_maestroMutualidad($claveisemym){
            $statement = $this->db->prepare('SELECT cveissemym,apepatmae,apematmae,nommae,nomcommae,curpmae,rfcmae,fechbajamae,estatmutual FROM public.mutualidad WHERE cveissemym=?');
            $statement->bindValue(1,$claveisemym);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_maestroFondoFallec($claveisemym){
            $consultaMFF = "SELECT cveissemym,fechafifondfalle,estatfondfall, (SELECT apepatmae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as apepatmae,(SELECT apematmae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as apematmae,";
            $consultaMFF = $consultaMFF . "(SELECT nommae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as nommae,(SELECT nomcommae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as nomcommae,(SELECT curpmae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as curpmae,";
            $consultaMFF = $consultaMFF . "(SELECT rfcmae FROM public.maestros_smsem WHERE cveissemym = '".$claveisemym."') as rfcmae FROM public.fondo_fallecimiento WHERE cveissemym = '".$claveisemym."';";
            $statement = $this->db->prepare($consultaMFF);
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
        
        public function buscaTrsmitesHist($clavemae){
            $statement = $this->db->prepare('SELECT identrega,identret,cvemae,motvret,fechentrega FROM public.tramites_fonretyf_hist WHERE cvemae=?');
            $statement->bindValue(1,$clavemae);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    }
?>
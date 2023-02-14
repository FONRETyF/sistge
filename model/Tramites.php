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

        public function validaFechas($fechRecibido,$fechDictamen,$fechBaseMae,$fechBajaMae){
            $validesFechs = array();
            $i=0;

            if ($fechRecibido > $fechDictamen && $fechRecibido > $fechBaseMae && $fechRecibido > $fechBajaMae) {
               
            }else{
                if($fechDictamen < $fechRecibido){

                }else{
                    $validesFechs[$i] = "La fecha del DICTAMEN no puede ser mayor a la fecha de recibido";
                    $i++;
                }
    
                if ($fechBaseMae < $fechBajaMae) {
                    if ($fechBaseMae < $fechRecibido) {
    
                    } else {
                        $validesFechs[$i]  = "La fecha de BASE no puede ser mayor a la fecha de recibido";
                        $i++;
                    }
                    
                } else {
                    if ($fechBaseMae < $fechRecibido) {
                        $validesFechs[$i]  = "La fecha de BASE no puede ser mayor a la fecha de baja";
                        $i++;
                    } else {
                        $validesFechs[$i]  = "La fecha de BASE no puede ser mayor a la fecha de baja y de recibido";
                        $i++;
                    }
                }
    
                if ($fechBajaMae > $fechBaseMae) {
                    if ($fechBajaMae < $fechRecibido) {
    
                    } else {
                        $validesFechs[$i]  = "La fecha de BAJA no puede ser mayor a la fecha de recibido";
                        $i++;
                    }
                } else {
                    if ($fechBajaMae < $fechRecibido) {
                        $validesFechs[$i]  = "La fecha de BAJA no puede ser menor a la fecha de base";
                        $i++;
                    } else {
                        $validesFechs[$i]  = "La fecha de BAJA no puede ser mayor a la fecha de recibido y menor a la fecha de base";
                        $i++;
                    }
                }
            }
            
            
            return $validesFechs;
        }
    }   
?>

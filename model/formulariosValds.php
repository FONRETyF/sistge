<?php


    class formulariosValds
    {
        private $db;
        
        public function __construct(){
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
        }

        public function validFormNE($datsNE){
            $resultValidFormNE = array();

            if (preg_match('/^[0-9]+$/', $datsNE[0]) && preg_match('/^[0-9]+$/', $datsNE[1]) && preg_match('/^[a-zA-Z0-9ñ\s]+$/', $datsNE[2]) && preg_match('/^[a-zA-Z0-9ñ\s]+$/', $datsNE[3])) {
                $statement = $this->db->prepare("SELECT max(numentrega) AS maxentrega FROM entregas_fonretyf WHERE anioentrega=(SELECT max(anioentrega) FROM entregas_fonretyf)");
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                if ($datsNE[1] == $results[0]["maxentrega"] + 1) {
                    $resultValidFormNE["resVNE"] = "Correcto";
                    $resultValidFormNE["msgVNE"] = "Validación correcta";
                } else {
                    $resultValidFormNE["resVNE"] = "Error";
                    $resultValidFormNE["msgVNE"] = "El numero de la entrega es incorrecto";
                }
                
            } else {
                $resultValidFormNE["resVNE"] = "Error";
                $resultValidFormNE["msgVNE"] = "Uno de los datos proporcionados no es correcto";

            }
            return $resultValidFormNE;
        }

        
        
    }
    


?>
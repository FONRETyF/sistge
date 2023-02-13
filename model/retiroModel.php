<?php
    session_start();
    
    class retirosModel{
        private $db;
        private $retiros;

        public function __construct()
        {
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db = $pdo->conexfonretyf();
            $this->retiros = array();
        }

        public function get_retiros($cvemae)
        {
            $statement = $this->db->prepare('SELECT identrega,identret,motvret,cvemae,montret,nomsolic,fechrecib,fechentrega,estattramite FROM public.tramites_fonretyf_hist where cvemae= ?');
            $statement->bindParam(1,$cvemae);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            //foreach ($result as $row) {
                //echo $row['identrega']." ".$row['identret']." ".$row['cvemae']." ".$row['montret']." ".$row['nomsolic']." ".$row['fechrecib']."</br>";
            //}
            return $result;
            //return($statement->execute()) ? $statement->fetchAll(PDO::FETCH_ASSOC)['password'] : false;
        }
    }

?>
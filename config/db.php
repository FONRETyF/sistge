<?php
    class db{
        /*private $host="localhost";
        private $dbname="acup";
        private $user="postgres";
        private $password="admin*&";*/
        
        public function conexion(){
            try {
                //code...
                $PDO = new PDO("pgsql:host=localhost; port=5432; dbname=acup; user=postgres; password='admin*&'");
                return $PDO;  
            } catch (PDOException $error) {
                //throw $th;
                die("Error en la conexion, error: ".$error->getMessage());
            }
        }
    }
    
?>
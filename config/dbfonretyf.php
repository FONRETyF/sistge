<?php
    class dbfonretyf{
        protected $dbh;

        public function conexfonretyf(){
            try {
                $conectar = $this->dbh= new PDO("pgsql:host=localhost; port=5432; dbname=fonretyf; user=postgres; password='admin*&'");
                //echo('alert("se conecto");');
                return $conectar;  

            } catch (PDOException $error) {
                die("Error en la conexion, error: ".$error->getMessage());
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf-8'");
        }
    }
?>
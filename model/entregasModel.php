<?php
    session_start();

    class Entrega{
        private $db;
        private $entregas;
        
        public function __construct()
        {
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
            $this->entregas = array();
        }
        
        public function get_entregas()
        {
            $statement = $this->db->prepare('SELECT identrega,numentrega,anioentrega,descentrega,fechentrega,estatentrega,numtramites FROM public.entregas_fonretyf ORDER BY identrega desc limit 5');
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        
        public function get_entrega_id($identrega){
            
            $statement = $this->db->prepare('SELECT * FROM public.entregas_fonretyf WHERE identrega= ?');
            $statement->bindValue(1,$identrega);
            $statement->execute();
            return $result = $statement->fetchAll();
        }
        
        public function delete_entrega($identrega){
            $statement = $this->db->prepare('DELETE FROM public.entregas_fonretyf WHERE identrega = ?');
            $statement->bindValue(1,$identrega);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
        
        public function insert_entrega($numentrega,$anioentrega,$descentrega,$cveusu,$fechentrega,$observaciones){
            $fecha = date("Y-m-d");
            $id_entrega = $anioentrega . $numentrega;
            $datsInsert=array($id_entrega, $anioentrega, $numentrega, $descentrega, 'ACTIVA', $fechentrega, '0', '0','0', 0, 0, 0, 0, 0, $fecha, $cveusu, $fecha, $cveusu, $observaciones, $cveusu, $fecha);
            $statement = $this->db->prepare("INSERT INTO public.entregas_fonretyf(identrega, anioentrega, numentrega, descentrega, estatentrega, fechentrega, folioinicial, foliofinal, folios, numcarpetas, numtramites, numtraminha, numtramjub, numtramfall, fechapertura, cveusuapert, fechcierre, cveusucierre, observaciones, cveusu, fechmodif) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $statement->execute($datsInsert);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        
        public function update_entrega($numentrega,$anioentrega,$descentrega,$fechentrega,$observaciones,$cveusu,$identrega){
            $fecha = date("Y-m-d");
            $datsInsert=array($numentrega, $anioentrega, $descentrega, $fechentrega, $observaciones, $cveusu, $fecha, $identrega);
            print_r($datsInsert);
            $statement = $this->db->prepare("UPDATE public.entregas_fonretyf SET numentrega=?, anioentrega=?, descentrega=?, fechentrega=?, observaciones=?, cveusu=?, fechmodif= ?  WHERE identrega=?");
            $statement->execute($datsInsert);
            return $result = $statement->fetchAll();
        }
    }

?>
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
            try {
                $statement = $this->db->prepare('SELECT identrega,numentrega,anioentrega,descentrega,fechentrega,estatentrega,numtramites,monttotentr FROM public.entregas_fonretyf ORDER BY identrega desc');
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (\Throwable $th) {
                echo $th;
            }
            
        }
        
        public function get_entrega_id($identrega){
            try {
                $statement = $this->db->prepare("SELECT * FROM public.entregas_fonretyf WHERE identrega= ?");
                $statement->bindValue(1,$identrega);
                $statement->execute();
                return $result = $statement->fetchAll();
            } catch (\Throwable $th) {
                echo $th;
            }
        }
        
        public function delete_entrega($identrega){
            try {
                $statement = $this->db->prepare('DELETE FROM public.entregas_fonretyf WHERE identrega = ?');
                $statement->bindValue(1,$identrega);
                $statement->execute();
                $result = $statement->fetchAll();
                return $result;
            } catch (\Throwable $th) {
                echo $th;
            }
            
        }
        
        public function insert_entrega($identrega,$numentrega,$anioentrega,$descentrega,$cveusu,$fechentrega,$observaciones){
            $fecha = date("Y-m-d");
            try {
                $consultaInsertEntr = "INSERT INTO public.entregas_fonretyf(identrega, anioentrega, numentrega, descentrega, estatentrega, fechentrega, folioinicial, foliofinal, folios, numcheques, numcarpetas, numtramites, numtraminha, numtramjub, numtramfall, numtramfallact, numtramfalljubm, numtramfalljubff, fechapertura, usuapert, fechcierre, usucierre, cheqsentre, cheqscancel, traspaso, soliccheqs, impcheqs,";
                $consultaInsertEntr = $consultaInsertEntr . " statarchivo, monttotentr, totadeds, adedsfajam, montadedsf, adedsts, montadedsts, adedsfondpen, montadedsfp, adedsturismo, montadedst, observaciones, cveusu, fechmodif) VALUES ('".$identrega."', '".$anioentrega."', '".$numentrega."', '".$descentrega."', 'ACTIVA', '".$fechentrega."', '0', '0', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, '".$fecha."', '".$cveusu."',";
                $consultaInsertEntr = $consultaInsertEntr . " '".$fecha."', '".$cveusu."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '".$observaciones."', '".$cveusu."', '".$fecha."');";
                $statement = $this->db->prepare($consultaInsertEntr);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (\Throwable $th) {
                echo $th;
            }
        }
        
        public function update_entrega($numentrega,$anioentrega,$descentrega,$fechentrega,$observaciones,$cveusu,$identrega){
            try {
                $fecha = date("Y-m-d");
                $datsInsert=array($numentrega, $anioentrega, $descentrega, $fechentrega, $observaciones, $cveusu, $fecha, $identrega);
                $statement = $this->db->prepare("UPDATE public.entregas_fonretyf SET numentrega=?, anioentrega=?, descentrega=?, fechentrega=?, observaciones=?, cveusu=?, fechmodif= ?  WHERE identrega=?");
                $statement->execute($datsInsert);
                return $result = $statement->fetchAll();
            } catch (\Throwable $th) {
                echo $th;
            }
            
        }

        public function updateFechEntrega($identrega,$fechaEntrega,$usuario){
            $a_resultUpdFechEntrega = array();
            $fecha = "";
            $fecha = date("Y-m-d H:i:s");
            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdateEntr = "UPDATE public.entregas_fonretyf SET fechentrega='".$fechaEntrega."', cveusu='".$usuario."', fechmodif='".$fecha."' WHERE identrega='".$identrega."';";
                $consultaUpdateEntr = $this->db->prepare($consultaUpdateEntr);
                $consultaUpdateEntr->execute();
                $results = $consultaUpdateEntr->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdFechEntrega["updateEntrega"] = "Actualizado";
            } catch (\Throwable $th) {
                $a_resultUpdFechEntrega["updateEntrega"] = "Fallo";
            }

            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdateTram = "UPDATE public.tramites_fonretyf SET fechentrega='".$fechaEntrega."' WHERE identrega='".$identrega."';";
                $consultaUpdateTram = $this->db->prepare($consultaUpdateTram);
                $consultaUpdateTram->execute();
                $results = $consultaUpdateTram->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdFechEntrega["updateTramites"] = "Actualizado";
            } catch (\Throwable $th) {
                $a_resultUpdFechEntrega["updateTramites"] = "Fallo";
            }

            try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                $consultaUpdateCheqs = "UPDATE public.beneficiarios_cheques SET fechentrega='".$fechaEntrega."', fechcheque='".$fechaEntrega."' WHERE anioentrega=".substr($identrega,0,4)." and numentrega=".substr($identrega,4,2).";";
                $consultaUpdateCheqs = $this->db->prepare($consultaUpdateCheqs);
                $consultaUpdateCheqs->execute();
                $results = $consultaUpdateCheqs->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdFechEntrega["updateCheques"] = "Actualizado";
            } catch (\Throwable $th) {
                $a_resultUpdFechEntrega["updateCheques"] = "Fallo";
            }

            return $a_resultUpdFechEntrega;
        }

        public function get_parametros()
        {
            try {
                $statement = $this->db->prepare('SELECT * FROM public.parametros_retiro');
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (\Throwable $th) {
                echo $th;
            }
            
        }

        public function updateEntrImpCheques($identrega){
            try {
                $consultaUpdateEntr = "UPDATE public.entregas_fonretyf SET impcheques=1, cheqsentre=1 WHERE identrega='".$identrega."';";
                $consultaUpdateEntr = $this->db->prepare($consultaUpdateEntr);
                $consultaUpdateEntr->execute();
                $results = $consultaUpdateEntr->fetchAll(PDO::FETCH_ASSOC);              
                $a_resultUpdFechEntrega["updateEntrega"] = "Actualizado";
            } catch (\Throwable $th) {
                //throw $th;
                echo $th;
            }
        }
    }

?>
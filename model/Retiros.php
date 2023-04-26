<?php
    session_start();

    class Retiros{
        private $db;
        private $tramite;
        
        public function __construct()
        {
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
            //$this->retiros = array();
        }
        
        public function get_retiros($identrega)
        {
            $estatentr = $this->get_EntRet($identrega);
            if ($estatentr =="CERRADA") {
                $statement = $this->db->prepare('SELECT identrega,numentrega,anioentrega,identret,cvemae,motvret,nomsolic,montrettot,estattramite FROM public.tramites_fonretyf_hist where identrega= ? ORDER BY identret ASC');
                $statement->bindValue(1,$identrega);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } else if ($estatentr=="ACTIVA") {
                $statement = $this->db->prepare('SELECT identrega,numentrega,anioentrega,identret,cvemae,motvret,nomsolic,montrettot,estattramite FROM public.tramites_fonretyf where identrega= ? ORDER BY identret DESC');
                $statement->bindValue(1,$identrega);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            }            
        }
        
        public function get_EntRet($identrega){
            $statementEstEnt = $this->db->prepare('SELECT estatentrega FROM public.entregas_fonretyf WHERE identrega= ?');
            $statementEstEnt->bindValue(1,$identrega);
            $statementEstEnt->execute();
            $results = $statementEstEnt->fetchAll(PDO::FETCH_ASSOC);
            $estatusE = $results[0]['estatentrega'];
            return $estatusE;
        }
        
        public function deleteTram($identreret,$clavemae,$cveusu){
            $a_resultDeleteTram = array();

            $fecha = "";
            $fecha = date("Y-m-d H:i:s");

            require_once("/var/www/html/sistge/model/Tramites.php");
            $retiros = new Tramite();
            $tramite = $retiros->get_retiro_Id($clavemae);
            
            if ($tramite[0]['motvret'] == "J" || $tramite[0]['motvret'] == "I") {
                $modoretiro = $tramite[0]['modretiro'];
                switch ($modoretiro) {
                    case 'C':
                        $eliminaCheque = $this->deleteCheque($identreret);
                        $a_resultDeleteTram["eliminaCheque"] = $eliminaCheque;

                        $actualizaMaestro = $this->actualizaMaeAct($clavemae,$cveusu,$fecha);
                        $a_resultDeleteTram["actualizaMaestro"] = $actualizaMaestro;

                        $eliminaTramite = $this->eliminaTramite($identreret);
                        $a_resultDeleteTram["eliminaTramite"] = $eliminaTramite;

                        return $a_resultDeleteTram;
                        break;

                    case 'D50':
                        require_once("/var/www/html/sistge/model/Maestro.php");
                        $maestros = new Maestro();
                        $maestro = $maestros->get_maestro($clavemae);
                        $claveissemym = $maestro[0]['cveissemym'];

                        $eliminaFondFallec = $this->eliminaMaeFondFallec($claveissemym);
                        $a_resultDeleteTram["eliminaFondFallec"] = $eliminaFondFallec;

                        $eliminaMaeJub = $this->eliminaMaeJubilado($claveissemym);
                        $a_resultDeleteTram["eliminaMaeJub"] = $eliminaMaeJub;

                        $eliminaCheque = $this->deleteCheque($identreret);
                        $a_resultDeleteTram["eliminaCheque"] = $eliminaCheque;

                        $actualizaMaestro = $this->actualizaMaeAct($clavemae,$cveusu,$fecha);
                        $a_resultDeleteTram["actualizaMaestro"] = $actualizaMaestro;

                        $eliminaTramite = $this->eliminaTramite($identreret);
                        $a_resultDeleteTram["eliminaTramite"] = $eliminaTramite;

                        return $a_resultDeleteTram;
                        break;

                    case 'D100':
                        require_once("/var/www/html/sistge/model/Maestro.php");
                        $maestros = new Maestro();
                        $maestro = $maestros->get_maestro($clavemae);
                        $claveissemym = $maestro[0]['cveissemym'];

                        $eliminaFondFallec = $this->eliminaMaeFondFallec($claveissemym);
                        $a_resultDeleteTram["eliminaFondFallec"] = $eliminaFondFallec;

                        $eliminaMaeJub = $this->eliminaMaeJubilado($claveissemym);
                        $a_resultDeleteTram["eliminaMaeJub"] = $eliminaMaeJub;

                        $actualizaMaestro = $this->actualizaMaeAct($clavemae,$cveusu,$fecha);
                        $a_resultDeleteTram["actualizaMaestro"] = $actualizaMaestro;

                        $eliminaTramite = $this->eliminaTramite($identreret);
                        $a_resultDeleteTram["eliminaTramite"] = $eliminaTramite;

                        return $a_resultDeleteTram;
                        break;

                    default:
                        
                        break;
                }

            } else {
                if ($tramite[0]['motvret'] == "FA" ) {
                    $eliminaBenefsMae = $this->eliminaBenefsMae($clavemae);
                    $a_resultDeleteTram["eliminaBenefs"] = $eliminaBenefsMae;

                    $eliminaCheque = $this->deleteCheque($identreret);
                    $a_resultDeleteTram["eliminaCheque"] = $eliminaCheque;

                    $actualizaMaestro = $this->actualizaMaeAct($clavemae,$cveusu,$fecha);
                    $a_resultDeleteTram["actualizaMaestro"] = $actualizaMaestro;

                    $eliminaTramite = $this->eliminaTramite($identreret);
                    $a_resultDeleteTram["eliminaTramite"] = $eliminaTramite;

                    return $a_resultDeleteTram;
                } elseif ($tramite[0]['motvret'] == "FJ") {
                    $programaFallec = substr($tramite[0]['foliotramite'],0,3);
                    if ($programaFallec == "FMU") {
                        $eliminaBenefsMae = $this->eliminaBenefsMae($clavemae);
                        $a_resultDeleteTram["eliminaBenefs"] = $eliminaBenefsMae;
    
                        $eliminaCheque = $this->deleteCheque($identreret);
                        $a_resultDeleteTram["eliminaCheque"] = $eliminaCheque;
    
                        $actualizaMaestro = $this->actualizaMaeActJubMut($clavemae,$cveusu,$fecha);
                        $a_resultDeleteTram["actualizaMaestro"] = $actualizaMaestro;
    
                        $eliminaTramite = $this->eliminaTramite($identreret);
                        $a_resultDeleteTram["eliminaTramite"] = $eliminaTramite;
                        
                        return $a_resultDeleteTram;
                    } elseif ($programaFallec == "FFJ") {
                        echo("es fondo fallecimiento");
                    } 
                    
                }
            }
            
        }

        public function deleteCheque($identreret){
            try {
                $statementDelete = "DELETE FROM public.beneficiarios_cheques WHERE identret='".$identreret."';";
                $statementDelete = $this->db->prepare($statementDelete);
                $statementDelete->execute();
                $results = $statementDelete->fetchAll(PDO::FETCH_ASSOC);
                $resultDeleteCheq = "Eliminado";
                return $resultDeleteCheq;
            } catch (\Throwable $th) {
                $resultDeleteCheq = "Fallo";
                return $resultDeleteCheq;
            }
        }

        public function actualizaMaeAct($clavemae,$cveusu,$fecha){
            try {
                $statementUpdate = "UPDATE public.maestros_smsem";
                $statementUpdate = $statementUpdate . " SET cveissemym='', regescmae=0 , numcelmae='', numfijmae='', fcbasemae='1900-01-01', aservactmae=0, fbajamae='1900-01-01', numpsgs=0, diaspsgs=0, estatlabmae='A', cveusu='".$cveusu."', fechmodif='".$fecha."', diaservactmae=0, afiprogfondfalle=0, fechsinipsgs='{}', fechsfinpsgs='{}'";
                $statementUpdate = $statementUpdate . " WHERE csp='" . $clavemae."';";

                $statementUpdate = $this->db->prepare($statementUpdate);
                $statementUpdate->execute();
                $results = $statementUpdate->fetchAll(PDO::FETCH_ASSOC);
                $resultUpdMaestro = "Actualizado";
                return $resultUpdMaestro;
            } catch (\Throwable $th) {
                $resultUpdMaestro = "Fallo";
                return $resultUpdMaestro;
            }
        }

        public function actualizaMaeActJubMut($cvamae,$cveusu,$fecha){
            try {
                $statementUpdate = "UPDATE public.mutualidad";
                $statementUpdate = $statementUpdate . " SET curpmae='' ,rfcmae='', regmae=0, numcelmae='', numfijmae='', fcfallecmae='1900-01-01', estatmutual='A', aniosjub=0, cveusu='".$cveusu."', fechmodif='".$fecha."', estatusmae='J'";
                $statementUpdate = $statementUpdate . " WHERE cveissemym='" . $cvamae."';";

                $statementUpdate = $this->db->prepare($statementUpdate);
                $statementUpdate->execute();
                $results = $statementUpdate->fetchAll(PDO::FETCH_ASSOC);
                $resultUpdMaestro = "Actualizado";
                return $resultUpdMaestro;
            } catch (\Throwable $th) {
                $resultUpdMaestro = "Fallo";
                return $resultUpdMaestro;
            }
        }

        public function eliminaTramite($identret){
            try {
                $statementDelete = "DELETE FROM public.tramites_fonretyf WHERE identret='".$identret."';";
                $statementDelete = $this->db->prepare($statementDelete);
                $statementDelete->execute();
                $results = $statementDelete->fetchAll(PDO::FETCH_ASSOC);
                $resultDeleteTram = "Eliminado";
                return $resultDeleteTram;
            } catch (\Throwable $th) {
                $resultDeleteTram = "Fallo";
                return $resultDeleteTram;
            }
        }

        public function eliminaMaeJubilado($cveissemym){
            try {
                $statementDelete = "DELETE FROM public.jubilados_smsem WHERE cveissemym='".$cveissemym."';";
                $statementDelete = $this->db->prepare($statementDelete);
                $statementDelete->execute();
                $results = $statementDelete->fetchAll(PDO::FETCH_ASSOC);
                $resultDeleteTram = "Eliminado";
                return $resultDeleteTram;
            } catch (\Throwable $th) {
                $resultDeleteTram = "Fallo";
                return $resultDeleteTram;
            }
        }

        public function eliminaMaeFondFallec($cveissemym){
            try {
                $statementDelete = "DELETE FROM public.fondo_fallecimiento WHERE cveissemym='".$cveissemym."';";
                $statementDelete = $this->db->prepare($statementDelete);
                $statementDelete->execute();
                $results = $statementDelete->fetchAll(PDO::FETCH_ASSOC);
                $resultDeleteTram = "Eliminado";
                return $resultDeleteTram;
            } catch (\Throwable $th) {
                $resultDeleteTram = "Fallo";
                return $resultDeleteTram;
            }
        }

        public function eliminaBenefsMae($clavemae){
            try {
                $statementDelete = "DELETE FROM public.beneficiarios_maestros WHERE cvemae='".$clavemae."';";
                $statementDelete = $this->db->prepare($statementDelete);
                $statementDelete->execute();
                $results = $statementDelete->fetchAll(PDO::FETCH_ASSOC);
                $resultDeleteCheq = "Eliminado";
                return $resultDeleteCheq;
            } catch (\Throwable $th) {
                $resultDeleteCheq = "Fallo";
                return $resultDeleteCheq;
            }
        }

        public function get_infoDTJI($identret,$modretiro,$cvemae,$motivoRet){
            if ($motivoRet == "J" || $motivoRet == "I") {
                if ($modretiro == "D100") {
                    $statementDT = "SELECT tab1.*,tab2.nomcommae,tab2.curpmae,tab2.rfcmae,tab2.regescmae,tab2.fcbasemae,tab2.aservactmae,tab2.fbajamae,tab2.numpsgs,tab2.diaspsgs,tab2.fechfallecmae,tab2.estatlabmae,";
                    $statementDT = $statementDT . " tab2.cveissemym, tab2.apepatmae, tab2.apematmae, tab2.nommae, tab2.fechsinipsgs, tab2.fechsfinpsgs, tab2.diaservactmae";
                    $statementDT = $statementDT . " FROM public.tramites_fonretyf as tab1 LEFT JOIN public.maestros_smsem as tab2 ON tab1.cvemae=tab2.csp";
                    $statementDT = $statementDT . " where tab1.cvemae='".$cvemae."' and tab1.identret='".$identret."';";
                    $statementDT = $this->db->prepare($statementDT);
                    $statementDT->execute();
                    $results = $statementDT->fetchAll(PDO::FETCH_ASSOC);

                }else {
                    $statementDT = "SELECT tab1.*,tab2.nomcommae,tab2.curpmae,tab2.rfcmae,tab2.regescmae,tab2.fcbasemae,tab2.aservactmae,tab2.fbajamae,tab2.numpsgs,tab2.diaspsgs,tab2.fechfallecmae,tab2.estatlabmae,tab3.identret,tab3.idbenef,tab3.idbenefcheque,tab3.nombenef,";
                    $statementDT = $statementDT . "tab3.montbenef,tab3.folcheque,tab3.fechcheque,tab3.fechentrega,tab3.estatcheque,tab3.porcretbenef,tab3.statedad, tab2.cveissemym, tab2.apepatmae, tab2.apematmae, tab2.nommae, tab2.fechsinipsgs, tab2.fechsfinpsgs, tab2.diaservactmae";
                    $statementDT = $statementDT . " FROM public.tramites_fonretyf as tab1 LEFT JOIN public.maestros_smsem as tab2 ON tab1.cvemae=tab2.csp LEFT JOIN public.beneficiarios_cheques as tab3";
                    $statementDT = $statementDT . " ON tab1.cvemae=tab3.cvemae where tab1.cvemae='".$cvemae."' and tab1.identret='".$identret."';";
                    $statementDT = $this->db->prepare($statementDT);
                    $statementDT->execute();
                    $results = $statementDT->fetchAll(PDO::FETCH_ASSOC);
                }
            }elseif ($motivoRet == "FA") {
                $statementDT = "SELECT tab1.*,tab2.nomcommae,tab2.curpmae,tab2.rfcmae,tab2.regescmae,tab2.fcbasemae,tab2.aservactmae,tab2.fbajamae,tab2.numpsgs,tab2.diaspsgs,tab2.fechfallecmae,tab2.estatlabmae,tab2.cveissemym, tab2.apepatmae, tab2.apematmae, tab2.nommae, tab2.fechsinipsgs, tab2.fechsfinpsgs, tab2.diaservactmae";
                $statementDT = $statementDT . " FROM public.tramites_fonretyf as tab1 LEFT JOIN public.maestros_smsem as tab2 ON tab1.cvemae=tab2.csp";
                $statementDT = $statementDT . " WHERE tab1.cvemae='".$cvemae."' and tab1.identret='".$identret."';";
                $statementDT = $this->db->prepare($statementDT);
                $statementDT->execute();
                $results = $statementDT->fetchAll(PDO::FETCH_ASSOC);
            }
            return $results;
        }

        public function get_infoDTFJ($identret,$modretiro,$cvemae,$motivoRet){
            $statementDT = "SELECT tab1.*,tab2.cveissemym,tab2.apepatmae,tab2.apematmae,tab2.nommae,tab2.nomcommae,tab2.curpmae,tab2.rfcmae,tab2.regmae,tab2.fechbajamae,tab2.fcfallecmae,tab2.aniosjub,";
            $statementDT = $statementDT . " tab2.estatusmae, tab2.diasjub FROM public.tramites_fonretyf as tab1 LEFT JOIN public.mutualidad as tab2 ON tab1.cvemae=tab2.cveissemym";
            $statementDT = $statementDT . " WHERE tab1.cvemae='".$cvemae."' and tab1.identret='".$identret."';";
            $statementDT = $this->db->prepare($statementDT);
            $statementDT->execute();
            $results = $statementDT->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_infoDTFA($identret,$cvemae){
            $statementDTFA = "SELECT tab1.*,tab2.nomcommae,tab2.curpmae,tab2.rfcmae,tab2.regescmae,tab2.fcbasemae,tab2.aservactmae,tab2.fbajamae,tab2.numpsgs,tab2.diaspsgs,tab2.fechfallecmae,tab2.estatlabmae,tab3.identret,tab3.idbenef,tab3.idbenefcheque,tab3.nombenef,";
            $statementDTFA = $statementDTFA . "tab3.montbenef,tab3.folcheque,tab3.fechcheque,tab3.fechentrega,tab3.estatcheque,tab3.porcretbenef,tab3.statedad, tab2.cveissemym, tab2.apepatmae, tab2.apematmae, tab2.nommae, tab2.fechsinipsgs, tab2.fechsfinpsgs, tab2.diaservactmae";
            $statementDTFA = $statementDTFA . " FROM public.tramites_fonretyf as tab1 LEFT JOIN public.maestros_smsem as tab2 ON tab1.cvemae=tab2.csp LEFT JOIN public.beneficiarios_cheques as tab3";
            $statementDTFA = $statementDTFA . " ON tab1.cvemae=tab3.cvemae where tab1.cvemae='".$cvemae."' and tab1.identret='".$identret."';";
            $statementDTFA = $this->db->prepare($statementDTFA);
            $statementDTFA->execute();
            $results = $statementDTFA->fetchAll(PDO::FETCH_ASSOC);
            
            return $results;
        }

        public function get_benefs($cvemae){
            $statementDTBenefs = "SELECT tab1.idbenef,tab1.idbenefcheque,tab1.cvemae,tab1.nombenef,tab1.curpbenef,tab1.parentbenef,tab1.porcretbenef,tab1.edadbenef,tab1.vidabenef,tab2.folcheque,tab2.estatcheque,tab2.fechcheque";
            $statementDTBenefs = $statementDTBenefs . " FROM public.beneficiarios_maestros as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 ";
            $statementDTBenefs = $statementDTBenefs . " ON tab1.idbenefcheque=tab2.idbenefcheque WHERE tab1.cvemae='".$cvemae."'  ORDER BY idbenef ASC;";
            $statementDTBenefs = $this->db->prepare($statementDTBenefs);
            $statementDTBenefs->execute();
            $results = $statementDTBenefs->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

        public function get_datsBenefs($identret,$cvemae){
            $statementDTBenefs = "SELECT tab1.*, tab2.* FROM public.beneficiarios_cheques as tab1 LEFT JOIN public.beneficiarios_maestros as tab2 ON tab1.idbenefcheque = tab2.idbenefcheque WHERE tab1.cvemae = '".$cvemae."' and tab1.identret = '".$identret."'  ORDER BY tab1.idbenef ASC;";
            $statementDTBenefs = $this->db->prepare($statementDTBenefs);
            $statementDTBenefs->execute();
            $results = $statementDTBenefs->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        
        public function updateFolsCheques($identrega,$numfolini){
            $a_resultsUpdtFols = array();
            $statementGetRetirosEntr = "SELECT tab1.identret,tab1.cvemae,tab1.motvret,tab2.idbenefcheque,tab2.nombenef,tab2.montbenef,tab2.montbenefletra,tab2.statedad,tab2.chequeadeudo FROM public.tramites_fonretyf as tab1 LEFT JOIN public.beneficiarios_cheques as tab2 ON tab1.identret = tab2.identret LEFT JOIN (SELECT tab1.cvemae,tab2.nomcommae FROM public.tramites_fonretyf as tab1, public.maestros_smsem as tab2 WHERE tab1.cvemae = tab2.csp UNION SELECT tab1.cvemae,tab2.nomcommae ";
            $statementGetRetirosEntr = $statementGetRetirosEntr . "FROM public.tramites_fonretyf as tab1, public.mutualidad as tab2 WHERE tab1.cvemae = tab2.cveissemym) as tab3 on tab1.cvemae= tab3.cvemae WHERE tab1.identrega='".$identrega."' and (tab1.modretiro='C' or tab1.modretiro='D50') ORDER BY statedad ASC, CASE WHEN motvret='I' THEN 1 WHEN motvret='J' THEN 2 WHEN motvret='FA' THEN 3 WHEN motvret='FJ' THEN 4 END ASC, nomcommae ASC, nombenef ASC;";
            
            $statementGetRetirosEntr = $this->db->prepare($statementGetRetirosEntr);
            $statementGetRetirosEntr->execute();
            $results = $statementGetRetirosEntr->fetchAll(PDO::FETCH_ASSOC);

            $folio = $numfolini -1;
            foreach ($results as $row) {
                $folcheque = "00" . $folio +1;
                try {                                                                                                                                                                                                                                                                                                                                                                                                                                      
                    $consultaUpdateTram = "UPDATE public.beneficiarios_cheques SET folcheque='".$folcheque."' WHERE idbenefcheque='".$row["idbenefcheque"]."';";
                    $consultaUpdateTram = $this->db->prepare($consultaUpdateTram);
                    $consultaUpdateTram->execute();
                    $results = $consultaUpdateTram->fetchAll(PDO::FETCH_ASSOC);    
                    $a_resultsUpdtFols[$row["idbenefcheque"]] = "Actualizado";       
                } catch (\Throwable $th) {
                    $a_resultsUpdtFols[$row["idbenefcheque"]] = "Fallo";
                }
                $folio++;
            }
            return $a_resultsUpdtFols;
        }


    }

?>
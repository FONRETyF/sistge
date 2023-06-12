<?php

use function PHPSTORM_META\type;

    require_once("/var/www/html/sistge/model/Entregas.php");

    class carpetas extends Entrega{

        public function __construct(){
            require_once("/var/www/html/sistge/config/dbfonretyf.php");
            $pdo = new dbfonretyf();
            $this->db=$pdo->conexfonretyf();
        }

        public function mostrarCarpetas(){
            $identrega = $_GET["identr"];
            $anioentrega=substr($identrega,0,4);
            $numentrega=intval(substr($identrega,4,2));
            $resultCarpNueva = array();

            try {
                $statement = $this->db->prepare("SELECT * FROM public.carpetas WHERE anioentrega= ? and numentrega= ?");
                $statement->bindValue(1,$anioentrega);
                $statement->bindValue(2,$numentrega);
                $statement->execute();
                $result = $statement->fetchAll();
            } catch (\Throwable $th) {
                echo $th;
            }
            
            if (count($result) == 0) {
                try {
                    $consulta = "SELECT MIN(tab4.folcheque) as folini, MAX(tab4.folcheque) as folfin FROM(SELECT tab1.anioentrega,tab1.numentrega,tab1.idbenefcheque,tab1.folcheque,tab1.nombenef,tab2.motvret,tab1.montbenef,tab1.montbenefletra,tab3.regescmae FROM public.beneficiarios_cheques as tab1";
                    $consulta = $consulta . " LEFT JOIN public.tramites_fonretyf as tab2 ON tab1.cvemae = tab2.cvemae LEFT JOIN public.maestros_smsem as tab3 ON tab1.cvemae = tab3.csp WHERE tab1.anioentrega =? AND tab1.numentrega =? ORDER BY folcheque) as tab4";
                    $statement = $this->db->prepare($consulta);
                    $statement->bindValue(1,$anioentrega);
                    $statement->bindValue(2,$numentrega);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    
                    $folIni = intval($result[0]["folini"]);
                    $folFin = intval($result[0]["folfin"]) ;
                    $numcheques = (intval($result[0]["folfin"]) - intval($result[0]["folini"])) + 1;
                    $numcarpetas = $numcheques / 30;
                    $partedecimal = explode(".",$numcarpetas);

                    if (substr($partedecimal[1],0,3) > 0) {
                        $numcarpetas = intval($numcarpetas) + 1;
                    } else {
                        $numcarpetas = intval($numcarpetas);
                    }
                    
                    $foliniCarp = $folIni;
                    $folfinCarp = 0;
                    
                    for ($i=0; $i < $numcarpetas ; $i++) { 
                        $resultCarpNueva[$i]["numcarpeta"] =  $i + 1;
                        $resultCarpNueva[$i]["folini"] = "00" . $foliniCarp; 
                        if ((($foliniCarp + 30) - 1) >= $folFin) {
                            $folfinCarp = $folFin;
                        } else {
                            $folfinCarp = ($foliniCarp + 30) - 1;
                        }              
                        $resultCarpNueva[$i]["folfin"] = "00" . $folfinCarp;
                        $foliniCarp = $foliniCarp + 30;
                    }
                    return $resultCarpNueva;

                } catch (\Throwable $th) {
                    echo $th;
                }
            } else {
                return $result;
            }
            
        }

    
    }

?>
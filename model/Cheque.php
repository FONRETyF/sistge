<?php

    require_once("/var/www/html/sistge/model/Retiros.php");

    class Cheque extends Retiros
        {
            public function __construct(){
                require_once("/var/www/html/sistge/config/dbfonretyf.php");
                $pdo = new dbfonretyf();
                $this->db=$pdo->conexfonretyf();
            }

            public function buscachequeC($folio){
                $resultadoFallo = array();
                try {
                    $statement = $this->db->prepare("SELECT tab1.*,tab2.motivcancel FROM public.beneficiarios_cheques as tab1 LEFT JOIN public.cat_motivcance as tab2 ON tab1.motvcancel = tab2.cvemotvcancel WHERE folcheque = ? and estatcheque = 'CANCELADO'");
                    $statement -> bindValue(1,$folio);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    
                    if (count($result) > 0) {
                        $resultadoFallo[0] = $result;
                        $resultadoFallo[1] = "Actual";
                        return $resultadoFallo;
                    }else{
                        $statement = $this->db->prepare("SELECT tab1.identret,tab1.idbenefcheque,tab1.cvemae,tab1.nombenef,tab1.montbenef,tab1.estatcheque,tab1.motvcancel, tab2.motivcancel FROM public.beneficiarios_cheques_hist as tab1 LEFT JOIN public.cat_motivcance as tab2 ON tab1.motvcancel = tab2.cvemotvcancel WHERE folcheque = ? and estatcheque = 'CANCELADO'");
                        $statement -> bindValue(1,$folio);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        
                        if (count($result) > 0) {
                            $resultadoFallo[0] = $result;
                            $resultadoFallo[1] = "Historico";
                            return $resultadoFallo;
                        } else {
                            return $resultadoFallo;
                        }
                    }
                } catch (\Throwable $th) {
                    echo $th;
                }
            }

            public function reponerCheque($folioAnt,$folionuevo,$fechaRepos,$observRepos,$cveusu){
                $resultRepos = array();
                $resultTemp = array();

                $folio = $folioAnt;

                $resultadoCheqC = $this -> buscachequeC($folio);

                $fecha = "";
                $fecha = date("Y-m-d");
                $fechaH = date("Y-m-d H:i:s");

                if ($resultadoCheqC[1] == "Actual") {
                    try {
                        $consultaInsert = "INSERT INTO public.cheqs_cancelados(anioentrega, numentrega, idret, idbenef, cvemae, nombenef, montbenef, folcheque, fechcheque, fechcancel, motvcancel, folanterior, folreposcn, usucancel, ";
                        $consultaInsert = $consultaInsert . "fechentrega, estatcheque, movimtscheque, observcancel, oficreposcn, cveusu, fechmodif) VALUES (".$resultadoCheqC[0][0]['anioentrega'].",".$resultadoCheqC[0][0]['numentrega'].",".$resultadoCheqC[0][0]['idret'].",".$resultadoCheqC[0][0]['idbenef'];
                        $consultaInsert = $consultaInsert . ",'".$resultadoCheqC[0][0]['cvemae']."','".$resultadoCheqC[0][0]['nombenef']."','".$resultadoCheqC[0][0]['montbenef']."','".$resultadoCheqC[0][0]['folcheque']."','".$resultadoCheqC[0][0]['fechcheque']."','".$resultadoCheqC[0][0]['fechcancel']."'";
                        $consultaInsert = $consultaInsert . ",".$resultadoCheqC[0][0]['motvcancel'].",'".$resultadoCheqC[0][0]['folanterior']."','".$resultadoCheqC[0][0]['folreposcn']."','".$resultadoCheqC[0][0]['usucalcel']."','".$resultadoCheqC[0][0]['fechentrega']."','".$resultadoCheqC[0][0]['estatcheque']."'";
                        $consultaInsert = $consultaInsert . ",'".$resultadoCheqC[0][0]['movimtscheque']."','".$resultadoCheqC[0][0]['observcheque']."','".$resultadoCheqC[0][0]['oficsolrepofinan']."','".$cveusu."','".$fechaH."');";
                        $statement = $this->db->prepare($consultaInsert);
                        $statement->execute();
                        $result = $statement->fetchAll();

                        $resultRepos["insertCancel"] = "Agregado";
                    } catch (\Throwable $th) {
                        $resultRepos["insertCancel"] = "Fallo";
                        echo $th;
                        return $resultRepos;
                        
                    }

                    if ($resultRepos["insertCancel"] == "Agregado") {
                        try {
                            $statement = $this->db->prepare("UPDATE public.beneficiarios_cheques SET folcheque=?, fechreposcn=?, folanterior=?, usureposcn=?, observreposcn=?, estatcheque=?, movimtscheque=?, observcheque='REPOSICION', motvcancel=0, fechcancel='1900-01-01' WHERE folcheque=?");
                            $statement -> bindValue(1,$folionuevo);
                            $statement -> bindValue(2,$fechaRepos);
                            $statement -> bindValue(3,$folioAnt);
                            $statement -> bindValue(4,$cveusu);
                            $statement -> bindValue(5,$observRepos);
                            $statement -> bindValue(6,"ENTREGADO");
                            $statement -> bindValue(7,$resultadoCheqC[0][0]['movimtscheque']);
                            $statement -> bindValue(8,$folioAnt);

                            $statement->execute();
                            $result = $statement->fetchAll();

                            $resultRepos["actualCheque"] = "Actualizado";
                        } catch (\Throwable $th) {
                            $resultRepos["actualCheque"] = "Fallo";
                            echo $th;
                            return $resultRepos;
                        }
                        
                    } else {
                        # code...
                    }
                    
                    return $resultRepos;           

                } elseif ($resultadoCheqC[1] == "Historico") {
                    try {
                        $consultaInsert = "INSERT INTO public.cheqs_cancelados(anioentrega, numentrega, idret, idbenef, cvemae, nombenef, montbenef, folcheque, fechcheque, fechcancel, motvcancel, folanterior, folreposcn, usucancel, ";
                        $consultaInsert = $consultaInsert . "fechentrega, estatcheque, movimtscheque, observcancel, oficreposcn, cveusu, fechmodif) VALUES (".$resultadoCheqC[0][0]['anioentrega'].",".$resultadoCheqC[0][0]['numentrega'].",".$resultadoCheqC[0][0]['idret'].",".$resultadoCheqC[0][0]['idbenef'];
                        $consultaInsert = $consultaInsert . ",'".$resultadoCheqC[0][0]['cvemae']."','".$resultadoCheqC[0][0]['nombenef']."','".$resultadoCheqC[0][0]['montbenef']."','".$resultadoCheqC[0][0]['folcheque']."','".$resultadoCheqC[0][0]['fechcheque']."','".$resultadoCheqC[0][0]['fechcancel']."'";
                        $consultaInsert = $consultaInsert . ",".$resultadoCheqC[0][0]['motvcancel'].",'".$resultadoCheqC[0][0]['folanterior']."','".$resultadoCheqC[0][0]['folreposcn']."','".$resultadoCheqC[0][0]['usucalcel']."','".$resultadoCheqC[0][0]['fechentrega']."','".$resultadoCheqC[0][0]['estatcheque']."'";
                        $consultaInsert = $consultaInsert . ",'".$resultadoCheqC[0][0]['movimtscheque']."','".$resultadoCheqC[0][0]['observcheque']."','".$resultadoCheqC[0][0]['oficsolrepofinan']."','".$cveusu."','".$fechaH."');";
                        $statement = $this->db->prepare($consultaInsert);
                        $statement->execute();
                        $result = $statement->fetchAll();

                        $resultRepos["insertCancel"] = "Agregado";
                    } catch (\Throwable $th) {
                        $resultRepos["insertCancel"] = "Fallo";
                        echo $th;
                        return $resultRepos;
                        
                    }

                    if ($resultRepos["insertCancel"] == "Agregado") {
                        try {
                            $statement = $this->db->prepare("UPDATE public.beneficiarios_cheques_hist SET folcheque=?, fechreposcn=?, folanterior=?, usureposcn=?, observreposcn=?, estatcheque=?, movimtscheque=?, observcheque='REPOSICION', motvcancel=0, fechcancel='1900-01-01' WHERE folcheque=?");
                            $statement -> bindValue(1,$folionuevo);
                            $statement -> bindValue(2,$fechaRepos);
                            $statement -> bindValue(3,$folioAnt);
                            $statement -> bindValue(4,$cveusu);
                            $statement -> bindValue(5,$observRepos);
                            $statement -> bindValue(6,"ENTREGADO");
                            $statement -> bindValue(7,$resultadoCheqC[0][0]['movimtscheque']);
                            $statement -> bindValue(8,$folioAnt);

                            $statement->execute();
                            $result = $statement->fetchAll();

                            $resultRepos["actualCheque"] = "Actualizado";
                        } catch (\Throwable $th) {
                            $resultRepos["actualCheque"] = "Fallo";
                            echo $th;
                            return $resultRepos;
                        }
                        
                    } else {
                        # code...
                    }
                    
                    return $resultRepos;
                }           
            }

        }



?>

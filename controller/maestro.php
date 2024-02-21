<?php

    session_start();
    require_once "/var/www/html/sistge/model/Maestro.php";
    $maestro = new Maestro();
    $maestroTramites = new Maestro();
    
    switch ($_GET["op"]) {
        case "buscar":
            $a_get_maestro = $maestro->get_maestro($_POST["clavemae"]);
            if (count($a_get_maestro)>0){
                $a_get_maestro = $maestro->get_maestro($_POST["clavemae"]);
                $a_get_TrmtsHist = $maestroTramites->buscaTrsmitesHist($_POST["clavemae"]);
				
                if(is_array($a_get_maestro)==true and count($a_get_TrmtsHist)==0){
                    foreach($a_get_maestro as $row){
                        $estatLabMae = $row["estatlabmae"];
                    }
                    if ($estatLabMae == "A"){
                        foreach($a_get_maestro as $row){
                            $output["motivo"] = "nuevo";
                            $output["csp"] = $row["csp"];
                            $output["cveissemym"] = $row["cveissemym"];
                            $output["apepatmae"] = $row["apepatmae"];
                            $output["apematmae"] = $row["apematmae"];
                            $output["nommae"] = $row["nommae"];
                            $output["nomcommae"] = $row["nomcommae"];
                            $output["curpmae"] = $row["curpmae"];
                            $output["rfcmae"] = $row["rfcmae"];
                            $output["estatlabmae"] = $row["estatlabmae"];
                        }   
                    }else{
                        foreach($a_get_maestro as $row){
                            $output["motivo"] = "inconsistencia";
                            $output["csp"] = $row["csp"];
                            $output["cveissemym"] = $row["cveissemym"];
                            $output["apepatmae"] = $row["apepatmae"];
                            $output["apematmae"] = $row["apematmae"];
                            $output["nommae"] = $row["nommae"];
                            $output["nomcommae"] = $row["nomcommae"];
                            $output["curpmae"] = $row["curpmae"];
                            $output["rfcmae"] = $row["rfcmae"];
                            $output["estatlabmae"] = $row["estatlabmae"];
                        }   
                    }
                    echo json_encode($output, JSON_FORCE_OBJECT); 
                }else if(count($a_get_TrmtsHist)>0){
                    foreach($a_get_TrmtsHist as $row){
                        $output["motivo"] = "existente";
                        $output["cvemae"] = $row["cvemae"];
                        $output["motvret"] = $row["motvret"];
                        $output["fechentrega"] = $row["fechentrega"];
                    }
                    echo json_encode($output, JSON_FORCE_OBJECT); 
                }
            }
            break;

        case 'buscarJub':
            $a_get_maestroJub = $maestro->get_maestroJub($_POST["claveisemym"]);
            if (count($a_get_maestroJub)>0){
                $a_get_maestro = $maestro->get_maestroJub($_POST["claveisemym"]);
                $a_get_TrmtsHist = $maestroTramites->buscaTrsmitesHist($_POST["claveisemym"]);
                
                if(is_array($a_get_maestro)==true and count($a_get_TrmtsHist)==0){
                    foreach($a_get_maestro as $row){
                        $programFallec = $row["programfallec"];
                    }
                    switch ($programFallec){
                        case 'M':
                            $a_getMaeJubMutualidad = $maestro->get_maestroMutualidad($_POST["claveisemym"]);
                            foreach($a_getMaeJubMutualidad as $row){
                                $output["motivo"] = "nuevo";
                                $output["cveissemym"] = $row["cveissemym"];
                                $output["apepatmae"] = $row["apepatmae"];
                                $output["apematmae"] = $row["apematmae"];
                                $output["nommae"] = $row["nommae"];
                                $output["nomcommae"] = $row["nomcommae"];
                                $output["curpmae"] = $row["curpmae"];
                                $output["rfcmae"] = $row["rfcmae"];
                                $output["fechbajamae"] = $row["fechbajamae"];
                                $output["estatusjub"] = $row["estatmutual"];
                                $output["programafallec"] = "M";
                            } 
                            echo json_encode($output, JSON_FORCE_OBJECT);
                            break;

                        case 'FF':
                            $a_getMaeJubFondFallec = $maestro->get_maestroFondoFallec($_POST["claveisemym"]);
                            foreach($a_getMaeJubFondFallec as $row){
                                $output["motivo"] = "nuevo";
                                $output["cveissemym"] = $row["cveissemym"];
                                $output["apepatmae"] = $row["apepatmae"];
                                $output["apematmae"] = $row["apematmae"];
                                $output["nommae"] = $row["nommae"];
                                $output["nomcommae"] = $row["nomcommae"];
                                $output["curpmae"] = $row["curpmae"];
                                $output["rfcmae"] = $row["rfcmae"]; 
                                $output["fechbajamae"] = $row["fechafifondfalle"];
                                $output["estatusjub"] = $row["estatfondfall"];
                                $output["programafallec"] = "FF";
                            } 
                            echo json_encode($output, JSON_FORCE_OBJECT);
                            break;
                            
                        default:
                            break;
                    }
                }else if(count($a_get_TrmtsHist)>0){
                    foreach($a_get_TrmtsHist as $row){
                        $output["motivo"] = "existente";
                        $output["cvemae"] = $row["cvemae"];
                        $output["motvret"] = $row["motvret"];
                        $output["fechentrega"] = $row["fechentrega"];
                    }
                    echo json_encode($output, JSON_FORCE_OBJECT); 
                }
            }
            break;
        
        case "mostrarNom":
            $clavemae = $_POST["clavemae"];
            if (strlen( $clavemae) === 9) {
                $a_get_nomMae = $maestro->get_maestro($clavemae);
                if(is_array($a_get_nomMae)==true and count($a_get_nomMae)>0){
                    foreach($a_get_nomMae as $row){
                        $output["csp"] = $row["csp"];
                        $output["apepatmae"] = $row["apepatmae"];
                        $output["apematmae"] = $row["apematmae"];
                        $output["nommae"] = $row["nommae"];
                        $output["nomcommae"] = $row["nomcommae"];
                    }
                    echo json_encode($output, JSON_FORCE_OBJECT);
                }
            } else {
                $a_get_nomMae = $maestro->get_maestroJub($clavemae);
                if(is_array($a_get_nomMae)==true and count($a_get_nomMae)>0){
                    foreach($a_get_nomMae as $row){
                        $output["csp"] = $row["cveissemym"];
                        $output["apepatmae"] = $row["apepatjub"];
                        $output["apematmae"] = $row["apematjub"];
                        $output["nommae"] = $row["nomjub"];
                        $output["nomcommae"] = $row["nomcomjub"];
                    }
                    echo json_encode($output, JSON_FORCE_OBJECT);
                }
            }            
            break;
        
        case "actNomMae":
            $a_getupdatenommae = $maestro->update_nomMae($_POST["apepatModif"],$_POST["apematModif"],$_POST["nommaeModif"],$_POST["nomcomModif"],$_POST["cvemae"],$_SESSION['usuario']);
			echo json_encode($a_getupdatenommae, JSON_FORCE_OBJECT);
            break;
            
        case 'buscaEdoCta':
            $a_get_EdoCta = $maestro->busca_EdoCta($_POST["criterioBusq"],$_POST["valCriBusq"]);
            $a_EdoCta_Jub = Array();
            foreach ($a_get_EdoCta as $row) {
                $a_output = array();
                $a_output["cveissemym"] = $row["EdoCta"][0]["cveissemym"];
                $a_output["nomcommae"] = $row["EdoCta"][0]["nomcommae"];
                $a_output["programa"] = $row["programa"][0];
                $a_output["estatmutual"] = $row["EdoCta"][0]["estatmutual"];
                $a_output["fechbajamae"] = $row["EdoCta"][0]["fechbajamae"];
                $a_output["anioiniaport"] = $row["EdoCta"][0]["anioiniaport"];
                $a_output["anioultaport"] = $row["EdoCta"][0]["anioultaport"];
                $a_output["numaport"] = $row["EdoCta"][0]["numaport"];
                $a_EdoCta_Jub[] = $a_output;
            }
            echo json_encode($a_EdoCta_Jub, JSON_FORCE_OBJECT);
            break;

        case 'insertMae':
            $a_get_insertMae = $maestro->insertMae($_POST['csp'],$_POST['cveissemym'],$_POST['apepat'],$_POST['apemat'],$_POST['nombre'],$_POST['nomcom'],$_POST['curp'],$_POST['rfc'],$_POST['region'],$_SESSION['usuario']);
            echo json_encode($a_get_insertMae, JSON_FORCE_OBJECT);
            break;
            
        default:
            # code...
            break;
    }

?>
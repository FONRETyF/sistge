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
                //echo(is_array($a_get_maestro) . "---" . count($a_get_TrmtsHist));
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
        
        case "mostrarNom":
            $a_get_nomMae = $maestro->get_maestro($_POST["clavemae"]);
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
            break;
        
        case "actNomMae":
            echo ($_POST["cvemae"]);
            echo ($_POST["apepatModif"]);
            echo ($_POST["apematModif"]);
            echo ($_POST["nommaeModif"]);
            echo ($_POST["nomcomModif"]);
            $maestro->update_nomMae($_POST["apepatModif"],$_POST["apematModif"],$_POST["nommaeModif"],$_POST["nomcomModif"],$_SESSION['usuario'],$_POST["cvemae"]);
            break;
            
        case 'obtenRetiro':
            $a_get_paramRet = $maestro->get_Retiro($_POST["aniosserv"]);
            if(is_array($a_get_paramRet)==true and count($a_get_paramRet)>0){
                foreach($a_get_paramRet as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;
            
        case 'aniosPSGS':
            $fechI=array();
            $fechF=array();
            for ($i=0;$i<$_POST["numsPSGS"];$i++){
                $fechI[$i] = $_POST["fechaIni"][$i];
                $fechF[$i] = $_POST["fechaFin"][$i];
            }
            $get_aniosPSGS = $maestro -> tiempoPSGS($_POST["numsPSGS"],$fechI,$fechF);
            $diasPSGS = 0;
            if(is_array($get_aniosPSGS)==true and count($get_aniosPSGS)>0){
                foreach($get_aniosPSGS as $row){
                    $diasPSGS =$diasPSGS + $row;
                }
                $output["numPSGS"] = $_POST["numsPSGS"];
                $output["diasPSGS"] = $diasPSGS;
                $output["fechIni"] = $fechI;
                $output["fechFin"] = $fechF;
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;
        
        case 'registrar':
            
            break;
            
        default:
            # code...
            break;
    }

?>
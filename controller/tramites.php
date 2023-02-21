<?php
    session_start();

    require_once "/var/www/html/sistge/model/Tramites.php";
    $tramite = new Tramite();

    switch ($_GET["op"]) {
        case 'validaFechs':
            $a_validFechas = $tramite->validaFechas($_POST["clavemae"],$_POST["motret"],$_POST["fechRecibido"],$_POST["fechDictamen"],$_POST["fechBaseMae"],$_POST["fechBajaMae"]);
            //echo($a_validFechas["descResult"] . "---" . $a_validFechas["diasServ"] );
            echo json_encode($a_validFechas, JSON_FORCE_OBJECT);
            break;
        
        case 'diasPSGS':
            $fechI=array();
            $fechF=array();
            for ($i=0;$i<$_POST["numsPSGS"];$i++){
                $fechI[$i] = $_POST["fechaIni"][$i];
                $fechF[$i] = $_POST["fechaFin"][$i];
            }
            $get_aniosPSGS = $tramite -> tiempoPSGS($_POST["numsPSGS"],$fechI,$fechF);
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

        case 'obtenRetiro':
            $a_get_paramRet = $tramite->get_Retiro($_POST["aniosserv"]);
            if(is_array($a_get_paramRet)==true and count($a_get_paramRet)>0){
                foreach($a_get_paramRet as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;
        
        case 'mostrarPSGS':
            echo"entro en controler mostra fechas";
            echo($_POST["fechaIn"]);
            
            break;

        default:
            
            break;
    }

?>
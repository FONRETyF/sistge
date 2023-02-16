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
        
        case 'calculaDiasServ':
            
            break;

        default:
            
            break;
    }

?>
<?php
    session_start();

    require_once "/var/www/html/sistge/model/Tramites.php";
    $tramite = new Tramite();

    switch ($_GET["op"]) {
        case 'validaFechs':
            $fechajsks = $_POST["fechRecibido"];
            echo"entro en controler de tramites";
            $a_validFechas = $tramite->validaFechas($_POST["fechRecibido"],$_POST["fechDictamen"],$_POST["fechBaseMae"],$_POST["fechBajaMae"]);
            break;
        
        default:
            
            break;
    }

?>
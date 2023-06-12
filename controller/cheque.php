<?php

    session_start();

    require_once "/var/www/html/sistge/model/Cheque.php";
    $cheque = new Cheque();
    
    switch ($_GET["opcion"]) {
        case 'buscaCheqC':
            $resultBusq = $cheque->buscachequeC($_POST["folio"]);
            
            echo json_encode($resultBusq, JSON_FORCE_OBJECT);
            break;

        case 'reponerCheque':
            $resultOperac = $cheque->reponerCheque($_POST["folioAnt"],$_POST["folioNuevo"],$_POST["fechRepos"],$_POST["observRepos"],$_SESSION["usuario"]);
            echo json_encode($resultOperac, JSON_FORCE_OBJECT);
            break;
        
        default:
            # code...
            break;
    }

?>
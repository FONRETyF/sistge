<?php

    session_start();
    require_once "/var/www/html/sistge/model/Retiros.php";
    $retiro = new Retiros();

    require_once "/var/www/html/sistge/model/Tramites.php";
    $tramite = new Tramite();

    switch ($_GET["op"]) {        
        case "listar":
            $a_get_retiros = $retiro->get_retiros($_GET["identrega"]);
            $a_Retiros = Array();
            $a_get_statentr = $retiro->get_EntRet($_GET["identrega"]);
            if ($a_get_statentr=="CERRADA") {
                $estatEntrega= "disabled";
            }else {
                $estatEntrega= "enabled";
            }
            foreach($a_get_retiros as $row){
                $a_prep_retiros = array();
                $a_prep_retiros[] = $row["numentrega"];
                $a_prep_retiros[] = $row["anioentrega"];
                $a_prep_retiros[] = $row["cvemae"];
                $a_prep_retiros[] = $row["motvret"];
                $a_prep_retiros[] = $row["nomsolic"];
                $a_prep_retiros[] = $row["montrettot"];
                $a_prep_retiros[] = $row["estattramite"];
                $a_prep_retiros[] = "<button type='button' onclick='editar(".$row['identret'].");' id='".$row['identret']."'class='BtIcEdit' ".$estatEntrega."><div><img src='../../img/editarTram.png' alt='edita' title='editar' height='20' width='20'></div></button>";
                //$a_prep_retiros[] = "<button type='button' onclick='detallar(".$row['identret'].",".$row['cvemae'].");'  id='".$row['identret']."'class='BtIcDetail' ".$estatEntrega."><div><i class='fa fa-eye'></i></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='mostrar(".$row['identret'].",".$row["cvemae"].")'  id='".$row['identret']."'class='BtIcDetail' ".$estatEntrega."><div><img src='../../img/file.png' alt='muestra' title='mostrar' height='23' width='23'></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='eliminarT(".$row['identret'].",".$row['cvemae'].");'  id='".$row['identret']."'class='BtIcDelete' ".$estatEntrega."><div><img src='../../img/goma-de-borrar.png' alt='eliminar' title='eliminar' height='21' width='21'></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='imprimir(".$row['identret'].",".$row["cvemae"].");'  id='".$row['identret']."'class='BtIcPrint' ".$estatEntrega."><div><img src='../../img/imprimeAcuerdo.png' alt='acuerdo' title='imprime acuerdo' height='25' width='25'></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='updstatT(".$row['identret'].",".$row["cvemae"].");'  id='".$row['identret']."'class='BtUpdStatT' ".$estatEntrega."><div><img src='../../img/statusEntregado.png' alt='entregado' title='cheque entregado' height='25' width='25'></div></button>";
                $a_Retiros[] = $a_prep_retiros;  
            }
            $a_result_retiros_DT = array(
                "sEcho"=> 1,
                "iTotalRecords"=>count($a_Retiros),
                "iTotalDisplayRecords"=>count($a_Retiros),
                "aaData"=>$a_Retiros);
            echo json_encode($a_result_retiros_DT);
            break;

        case 'buscaEnt':
            $a_get_EntRet = $retiro->get_EntRet($_POST["identrega"]);
            $a_estatentrega ["EstatEnt"] = $a_get_EntRet;
            echo json_encode($a_estatentrega);
            break;

        case "deleteTramite":
            $a_deleteTram = $retiro->deleteTram($_POST["identret"],$_POST["cvemae"],$_SESSION['usuario']);
            echo json_encode($a_deleteTram);
            break;    
        
        case 'getTram':
            $a_get_tram_id = $tramite->get_Tram_Id($_POST['identret']);
            foreach($a_get_tram_id as $row){
                $output["motvret"] = $row["motvret"];
                $output["modretiro"] = $row["modretiro"];
                $output["cvemae"] = $row["cvemae"];
            }
            echo json_encode($output, JSON_FORCE_OBJECT);
            break;

        case 'mostrarJI':
            $a_get_DT_JI = $retiro->get_infoDTJI($_POST['identret'],$_POST['modretiro'],$_POST['cvemae'],$_POST['motivoRet']);
            foreach ($a_get_DT_JI as $row) {
                $output["cvemae"] =$row["cvemae"];
                $output["motvret"] =$row["motvret"];
                $output["fechbajfall"] =$row["fechbajfall"];
                $output["nommae"] =$row["nomcommae"];
                $output["modretiro"] =$row["modretiro"];
                $output["montrettot"] =$row["montrettot"];
                $output["montretentr"] =$row["montretentr"];
                $output["montretfall"] =$row["montretfall"];
                $output["fechrecib"] =$row["fechrecib"];
                $output["fechentrega"] =$row["fechentrega"];
                $output["estattramite"] =$row["estattramite"];
                $output["fcbasemae"] =$row["fcbasemae"];
                $output["numpsgs"] =$row["numpsgs"];
                $output["diaspsgs"] =$row["diaspsgs"];
                $output["aservactmae"] =$row["aservactmae"];
                $output["folcheque"] =$row["folcheque"];
                $output["estatcheque"] =$row["estatcheque"];

            }

            echo json_encode($output, JSON_FORCE_OBJECT);
            break;
        case 'busqbenefs':
            $a_get_benefs =  $retiro->get_benefs($_POST['cvemae']);
            $a_beneficiarios = array();
            foreach ($a_get_benefs as $row) {
                $a_prep_benefs = array();
                $a_prep_benefs[] = $row["idbenef"];
                $a_prep_benefs[] = $row["nombenef"];
                $a_prep_benefs[] = $row["curpbenef"];
                $a_prep_benefs[] = $row["parentbenef"];
                $a_prep_benefs[] = $row["porcretbenef"];
                $a_prep_benefs[] = $row["edadbenef"];
                $a_prep_benefs[] = $row["vidabenef"];
                $a_prep_benefs[] = $row["folcheque"];
                $a_prep_benefs[] = $row["estatcheque"];
                $a_prep_benefs[] = $row["fechcheque"];
                $a_beneficiarios[] = $a_prep_benefs;
            }
            echo json_encode( $a_beneficiarios,JSON_FORCE_OBJECT);
            break;
        
        case 'updateTram':
            $a_get_DT_JIFA = $retiro->get_infoDTJI($_POST['identret'],$_POST['modretiro'],$_POST['cvemae'],$_POST['motivoRet']);
            echo json_encode($a_get_DT_JIFA,JSON_FORCE_OBJECT);
            break;
        
        case 'updateTramFJ':
            $a_get_DT_FJ = $retiro->get_infoDTFJ($_POST['identret'],$_POST['modretiro'],$_POST['cvemae'],$_POST['motivoRet']);
            echo json_encode($a_get_DT_FJ,JSON_FORCE_OBJECT);
            break;

        case 'updateBenefs':
            $a_get_DT_Benefs = $retiro->get_datsBenefs($_POST['identret'],$_POST['cvemae']);
            echo json_encode($a_get_DT_Benefs,JSON_FORCE_OBJECT);
            break;

        case 'asignaFolios':
            $a_get_asigFols = $retiro->updateFolsCheques($_POST['identrega'],$_POST['folioini']);
            echo json_encode($a_get_asigFols,JSON_FORCE_OBJECT);
            break;
        
        default:
            break;
    }

?>
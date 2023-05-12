<?php
    session_start();
    require_once "/var/www/html/sistge/model/Entregas.php";
    $entrega = new Entrega();

    switch ($_GET["op"]) {
        case "listar":
            $a_get_entregas = $entrega->get_entregas();
            $a_Entregas = Array();
            foreach($a_get_entregas as $row){
                $a_prep_entregas = array();
                $a_prep_entregas[] = $row["numentrega"];
                $a_prep_entregas[] = $row["anioentrega"];
                $a_prep_entregas[] = $row["descentrega"];
                $a_prep_entregas[] = $row["estatentrega"];
                if ($row["estatentrega"]=="CERRADA") {
                    $estatEntrega= "disabled";
                }else {
                    $estatEntrega= "enabled";
                }
                $a_prep_entregas[] = $row["fechentrega"];
                $a_prep_entregas[] = $row["numtramites"];
                $a_prep_entregas[] = $row["monttotentr"];
                $a_prep_entregas[] = "<button type='button' onclick='editar(".$row['identrega'].");' id='".$row['identrega']."'class='BtIcEdit' ".$estatEntrega."><div><img src='../../img/file.png' alt='modificar' title='modificar' height='20' width='20'></div></button>";
                $a_prep_entregas[] = "<button type='button' onclick='detalleRetiros(".$row['identrega'].");'  id='".$row['identrega']."'class='BtIcDetail'><div><img src='../../img/lapiz.png' alt='editar-capturar' title='editar-capturar' height='18' width='18'></div></button>";
                $a_prep_entregas[] = "<button type='button' onclick='eliminar(".$row['identrega'].");'  id='".$row['identrega']."'class='BtIcDelete'".$estatEntrega."><div><img src='../../img/goma-de-borrar.png' alt='eliminar' title='eliminar' height='17' width='17'></div></button>";
                $a_Entregas[] = $a_prep_entregas;  
            }
            $a_result_entregas_DT = array(
                "sEcho"=> 1,
                "iTotalRecords"=>count($a_Entregas),
                "iTotalDisplayRecords"=>count($a_Entregas),
                "aaData"=>$a_Entregas);
            echo json_encode($a_result_entregas_DT);
            break;
        
        case "guardaryeditar":
            $a_get_entrega = $entrega->get_entrega_id($_POST["identrega"]);
            if(empty($a_get_entrega)){
                if(is_array($a_get_entrega)==true and count($a_get_entrega)==0){
                    $entrega->insert_entrega($_POST["identrega"],$_POST["numentrega"],$_POST["Anioentrega"],$_POST["descentrega"],$_SESSION['usuario'],$_POST["fechentrega"],$_POST["observaciones"]);
                }
            }else{
                $entrega->update_entrega($_POST["numentrega"],$_POST["Anioentrega"],$_POST["descentrega"],$_POST["fechentrega"],$_POST["observaciones"],$_SESSION['usuario'],$_POST["identrega"]);
            }
            break;
            
        case "mostrar":
            $a_get_entrega = $entrega->get_entrega_id($_POST["identrega"]);
            if(is_array($a_get_entrega)==true and count($a_get_entrega)>0){
                foreach($a_get_entrega as $row){
                    $output["identrega"] = $row["identrega"];
                    $output["numentrega"] = $row["numentrega"];
                    $output["anioentrega"] = $row["anioentrega"];
                    $output["descentrega"] = $row["descentrega"];
                    $output["fechentrega"] = $row["fechentrega"];
                    $output["observaciones"] = $row["observaciones"];
                }
                echo json_encode($output);
            }
            break;

        case "eliminar":
            $entrega->delete_entrega($_POST["identrega"]);
            break;
        
        case 'updateFech':
            $a_get_updateFech = $entrega->updateFechEntrega($_POST["identrega"],$_POST['fechEntrega'],$_SESSION['usuario']);
            echo json_encode($a_get_updateFech, JSON_FORCE_OBJECT);
            break;
    }

?>
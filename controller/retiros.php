<?php

    session_start();
    require_once "/var/www/html/sistge/model/Retiros.php";
    $retiro = new Retiros();

    switch ($_GET["op"]) {        
        case "listar":
            $a_get_retiros = $retiro->get_retiros($_GET["identrega"]);
            $a_Retiros = Array();
            foreach($a_get_retiros as $row){
                $a_prep_retiros = array();
                $a_prep_retiros[] = $row["numentrega"];
                $a_prep_retiros[] = $row["anioentrega"];
                $a_prep_retiros[] = $row["cvemae"];
                $a_prep_retiros[] = $row["motvret"];
                if ($row["estat"]=="CERRADA") {
                    $estatEntrega= "disabled";
                }else {
                    $estatEntrega= "enabled";
                }
                $a_prep_retiros[] = $row["nomsolic"];
                $a_prep_retiros[] = $row["montret"];
                $a_prep_retiros[] = $row["estattramite"];
                $a_prep_retiros[] = "<button type='button' onclick='editar(".$row['identret'].");' id='".$row['identret']."'class='BtIcEdit' ><div><i class='fa-solid fa-pen-to-square'></i></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='detalleRetiros(".$row['identret'].",".$row['estatentrega'].");'  id='".$row['identret']."'class='BtIcDetail'><div><i class='fa fa-eye'></i></div></button>";
                $a_prep_retiros[] = "<button type='button' onclick='eliminar(".$row['identret'].");'  id='".$row['identret']."'class='BtIcDelete'><div><i class='fa-solid fa-eraser'></i></div></button>";
                $a_Retiros[] = $a_prep_retiros;  
            }
            $a_result_retiros_DT = array(
                "sEcho"=> 1,
                "iTotalRecords"=>count($a_Retiros),
                "iTotalDisplayRecords"=>count($a_Retiros),
                "aaData"=>$a_Retiros);
            echo json_encode($a_result_retiros_DT);
            break;
        case 'buscar':
            $a_get_EntRet = $retiro->get_EntRet($_GET["identrega"]);
            break;
        /* case "guardaryeditar":
            $a_get_entrega = $entrega->get_entrega_id($_POST["identrega"]);
            
            if(empty($_POST["identrega"])){
                if(is_array($a_get_entrega)==true and count($a_get_entrega)==0){
                    $entrega->insert_entrega($_POST["numentrega"],$_POST["Anioentrega"],$_POST["descentrega"],$_SESSION['usuario'],$_POST["fechentrega"],$_POST["observaciones"]);
                }
            }else{
                echo ($_POST["numentrega"]);
                echo ($_POST["Anioentrega"]);
                echo ($_POST["descentrega"]);
                echo ($_POST["fechentrega"]);
                echo ($_POST["observaciones"]);
                echo ($_SESSION['usuario']);
                echo ($_POST["identrega"]);
                

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
      */  
    }

?>
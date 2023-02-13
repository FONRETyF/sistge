<?php

session_start();

require_once "/var/www/html/sistge/model/retiroModel.php";

$retiros = new retirosModel();

$clavemae = $_POST['CveServPub'];
$datos = $retiros->get_retiros($clavemae);
if(is_array($datos)==true and count($datos)>0){
    /*foreach ($datos as $row) {
        echo $row['identrega']." ".$row['identret']." ".$row['cvemae']." ".$row['montret']." ".$row['nomsolic']." ".$row['fechrecib']." ".$row['fechentrega']." ".$row['estattramite']."</br>";
    }*/
    //header("Location: ../views/home/resultRetiros.php?$datos");
   // 
   

}

require_once "/var/www/html/sistge/views/home/resultRetiros.php";
?>
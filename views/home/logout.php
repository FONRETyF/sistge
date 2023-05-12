<?php  

    require_once("/var/www/html/sistge/model/Entregas.php");
  
    $obj = new Entrega();

    $vartempo=$obj->get_entregas();
    print_r(count($vartempo));
    echo "oooooooooooooooooo";
    foreach($vartempo as $row){
        echo $row['numentrega']." ".$row['anioentrega']." ".$row['descentrega']." ".$row['fechentrega']." ".$row['estatentrega']." ".$row['numtramites']."</br>";
        }
    //echo $vartempo;
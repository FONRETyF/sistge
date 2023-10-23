<?php
require_once "/var/www/html/sistge/model/TramitesHist.php";
$tramiteHist = new TramitesHist;

switch ($_GET['op']) {
    case 'agregarHist':
        $a_addtramHist = $tramiteHist -> addTramiteJI_Hist($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Imotret'],$_POST['Iapepat'],$_POST['Iapemat'],$_POST['Inombre'],$_POST['Inomcom'],$_POST['IRegMae'],$_POST['InumDictam'],$_POST['IfechDictam'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['Ifechbase'],$_POST['IaniosServ'],$_POST['Imonttotret'],$_POST['IfechRecibido'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_POST["IObserv"],$_POST["IFolCheq"],$_SESSION['usuario']);
        echo json_encode($a_addtramHist, JSON_FORCE_OBJECT);
        break;

    case 'agregarFHist':
        $a_addTramFHist = $tramiteHist -> addtramiteF_Hist($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['Ifechbase'],$_POST['IaniosServ'],$_POST['Imonttotret'],$_POST['IfechRecibido'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Ifechtestamnt'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_POST["IObserv"],$_POST["IfolBenefs"],$_POST["ImonstBenefs"],$_SESSION['usuario']);
        echo json_encode($a_addTramFHist, JSON_FORCE_OBJECT);
        break;

    case 'agregarFJHist':
        $a_addTramFJHist = $tramiteHist -> addtramiteFJ_Hist($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icveissemym'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['Ifechbase'],$_POST['IaniosServ'],$_POST['Imonttotret'],$_POST['IfechRecibido'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Icurpmae'],$_POST['Irfcmae'],$_POST['Ifechtestamnt'],$_POST["IObserv"],$_POST["IfolBenefs"],$_POST["ImonstBenefs"],$_SESSION['usuario']);
        echo json_encode($a_addTramFJHist, JSON_FORCE_OBJECT);
        break;

    default:
        # code...
        break;
}

?>
<?php
    session_start();

    require_once "/var/www/html/sistge/model/Tramites.php";
    $tramite = new Tramite();

    switch ($_GET["op"]) {
        case 'validaFechs':
            $a_validFechas = $tramite->validaFechas($_POST["clavemae"],$_POST["motret"],$_POST["diasInacPsgs"],$_POST["NumPersgs"],$_POST["fechRecibido"],$_POST["fechDictamen"],$_POST["fechBaseMae"],$_POST["fechBajaMae"]);
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
            $a_get_paramRet = $tramite->get_Retiro($_POST["aniosserv"],$_POST["fechBaja"]);
            if(is_array($a_get_paramRet)==true and count($a_get_paramRet)>0){
                foreach($a_get_paramRet as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;
        
        case 'obtenRetiroJub':
            $a_get_paramRetJub = $tramite->get_RetiroJub($_POST["aniosserv"],$_POST["programfallec"]);
            if(is_array($a_get_paramRetJub)==true and count($a_get_paramRetJub)>0){
                foreach($a_get_paramRetJub as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;

        case 'agregar':
            $a_addTram = $tramite -> addTramiteJI($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['Iapepat'],$_POST['Iapemat'],$_POST['Inombre'],$_POST['Inomcom'],$_POST['IRegMae'],$_POST['InumDictam'],$_POST['IfechDictam'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IfechInipsgs'],$_POST['IfechFinpsgs'],$_POST['Inumpsgs'],$_POST['Idiaspsgs'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['ImontRetFF'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['IdiaHaber'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_SESSION['usuario']);
            echo json_encode($a_addTram, JSON_FORCE_OBJECT);
            break;

        case 'agregarF':
            $a_addTramF = $tramite -> addtramiteF($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IfechInipsgs'],$_POST['IfechFinpsgs'],$_POST['Inumpsgs'],$_POST['Idiaspsgs'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Ifechtestamnt'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_SESSION['usuario']);
            echo json_encode($a_addTramF, JSON_FORCE_OBJECT);
            break;
        
        case 'agregarFJ':
            $a_addTramFJ = $tramite -> addtramiteFJ($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Iprogramfallec'],$_POST['Icurpmae'],$_POST['Irfcmae'],$_POST['Ifechtestamnt'],$_SESSION['usuario']);
            echo json_encode($a_addTramFJ, JSON_FORCE_OBJECT);
            break;

        case 'validFechaCTJuic':
            $a_getValidMayor = $tramite -> validFechaCTJuic($_POST['tipoTestamento'],$_POST['FBase'],$_POST['FBaja'],$_POST['FCTJuicio'],$_POST['FRecibido']);
            echo json_encode($a_getValidMayor, JSON_FORCE_OBJECT);
            break;

        case 'validVigTramFA':
            $a_getVigTramJuicio = $tramite -> validVigTramFA($_POST['tipoTestamento'],$_POST['ClaveMae'],$_POST['FBase'],$_POST['FBaja'],$_POST['FCTJuicio'],$_POST['FRecibido']);
            
            echo json_encode($a_getVigTramJuicio, JSON_FORCE_OBJECT);
            break;

        case 'validaFechsFA':
            $a_validFechas = $tramite->validaFechasFA($_POST["clavemae"],$_POST['motret'],$_POST['diasInacPsgs'],$_POST['NumPersgs'],$_POST["fechRecibido"],$_POST["fechBaseMae"],$_POST["fechBajaMae"]);
            echo json_encode($a_validFechas, JSON_FORCE_OBJECT);
            break;

        case 'validaFechsFJ':
            $a_validFechasFJ = $tramite->validaFechasFJ($_POST["clavemae"],$_POST['motret'],$_POST["fechRecibido"],$_POST["fechBaseMae"],$_POST["fechBajaMae"]);
            echo json_encode($a_validFechasFJ, JSON_FORCE_OBJECT);
            break;

        case 'validaVigFechas':
            $a_validFechIniJuic = $tramite->validaInicioJuic($_POST['fechRecibido'],$_POST['fechBaja'],$_POST['fechIniJuic']);
            echo json_encode($a_validFechIniJuic, JSON_FORCE_OBJECT);
            break;

        case 'updateJunInha':
            $a_update_JubInha = $tramite->updateJubInha($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucvemae'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['UnumDictam'],$_POST['UfechDictam'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UfechInipsgs'],$_POST['UfechFinpsgs'],$_POST['Unumpsgs'],$_POST['Udiaspsgs'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UmontRetFF'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['UdiaHaber'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_SESSION['usuario']);
            echo json_encode($a_update_JubInha, JSON_FORCE_OBJECT);
            break;

        case 'updateFA':
            $a_update_FA = $tramite->updateFA($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucvemae'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UfechInipsgs'],$_POST['UfechFinpsgs'],$_POST['Unumpsgs'],$_POST['Udiaspsgs'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['Unumbenefs'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Unombenefs'],$_POST['Ucurpbenefs'],$_POST['Uparentbenefs'],$_POST['Uporcbenefs'],$_POST['Uedadbenefs'],$_POST['Uvidabenefs'],$_POST['Udoctestamnt'],$_POST['Ufechdoctestmnt'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_SESSION['usuario']);
            echo json_encode($a_update_FA, JSON_FORCE_OBJECT);
            break;
        
        case 'updateFJ':
            $a_update_FJ = $tramite->updateFJ($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['Unumbenefs'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Unombenefs'],$_POST['Ucurpbenefs'],$_POST['Uparentbenefs'],$_POST['Uporcbenefs'],$_POST['Uedadbenefs'],$_POST['Uvidabenefs'],$_POST['Udoctestamnt'],$_POST['Ufechdoctestmnt'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_SESSION['usuario']);
            echo json_encode($a_update_FJ, JSON_FORCE_OBJECT);
            break;

        default:            
            break;
    }

?>
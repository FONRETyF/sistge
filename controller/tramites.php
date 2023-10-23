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
            if(is_array($get_aniosPSGS)==true && count($get_aniosPSGS)>0){
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
            if(is_array($a_get_paramRet)==true && count($a_get_paramRet)>0){
                foreach($a_get_paramRet as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;
        
        case 'obtenRetiroJub':
            $a_get_paramRetJub = $tramite->get_RetiroJub($_POST["aniosserv"],$_POST["programF"]);
            if(is_array($a_get_paramRetJub)==true && count($a_get_paramRetJub)>0){
                foreach($a_get_paramRetJub as $row){
                    $output["montret"] = $row["montRet"];
                }
                echo json_encode($output, JSON_FORCE_OBJECT);
            }
            break;

        case 'agregar':
            $a_addTram = $tramite -> addTramiteJI($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['Iapepat'],$_POST['Iapemat'],$_POST['Inombre'],$_POST['Inomcom'],$_POST['IRegMae'],$_POST['InumDictam'],$_POST['IfechDictam'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IfechInipsgs'],$_POST['IfechFinpsgs'],$_POST['Inumpsgs'],$_POST['Idiaspsgs'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['ImontRetFF'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['IdiaHaber'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_POST["IObserv"],$_POST["ItipTram"],$_POST["IFolCheq"],$_SESSION['usuario'],);
            echo json_encode($a_addTram, JSON_FORCE_OBJECT);
            break;

        case 'agregarF':
            $a_addTramF = $tramite -> addtramiteF($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icvemae'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IfechInipsgs'],$_POST['IfechFinpsgs'],$_POST['Inumpsgs'],$_POST['Idiaspsgs'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Ifechtestamnt'],$_POST["Icurpmae"],$_POST["Irfcmae"],$_POST["IObserv"],$_POST["ItipTram"],$_POST["IfolBenefs"],$_SESSION['usuario']);
            echo json_encode($a_addTramF, JSON_FORCE_OBJECT);
            break;
        
        case 'agregarFJ':
            $a_addTramFJ = $tramite -> addtramiteFJ($_POST['Ianioentr'],$_POST['Inumentr'],$_POST['Iidentr'],$_POST['Icveissemym'],$_POST['Iestatusmae'],$_POST['Imotret'],$_POST['IRegMae'],$_POST['Ifechbaj'],$_POST['InomSolic'],$_POST['INumCel'],$_POST['InumPart'],$_POST['Ifechbase'],$_POST['IdiasServ'],$_POST['IaniosServ'],$_POST['ImodRet'],$_POST['Imonttotret'],$_POST['ImontretEntr'],$_POST['IfechRecibido'],$_POST['InumOficTarj'],$_POST['IfechOficAut'],$_POST['IimageOficTarj'],$_POST['Inumbenefs'],$_POST['Idoctestamnt'],$_POST['Inomsbenefs'],$_POST['Icurpsbenefs'],$_POST['Iparentsbenefs'],$_POST['Iporcnsbenefs'],$_POST['Iedadesbenefs'],$_POST['Ividabenefs'],$_POST['Iprogramfallec'],$_POST['Icurpmae'],$_POST['Irfcmae'],$_POST['Ifechtestamnt'],$_POST["IObserv"],$_POST["ItipTram"],$_POST["IfolBenefs"],$_SESSION['usuario']);
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
            $a_validFechas = $tramite->validaFechasFA($_POST["clavemae"],$_POST['motret'],$_POST['diasInacPsgs'],$_POST['NumPersgs'],$_POST["fechRecibido"],$_POST["fechBaseMae"],$_POST["fechBajaMae"],$_POST['fecInicioJuic'],$_POST['fechaIniJuic'],$_POST['tiptest'],$_POST['fechJuiCTL']);
            echo json_encode($a_validFechas, JSON_FORCE_OBJECT);
            break;

        case 'validaFechsFJ':
            $a_validFechasFJ = $tramite->validaFechasFJ($_POST["clavemae"],$_POST['motret'],$_POST["fechRecibido"],$_POST["fechBaseMae"],$_POST["fechBajaMae"],$_POST["opTest"],$_POST["fechCTJuic"],$_POST["fechIniJuic"]);
            echo json_encode($a_validFechasFJ, JSON_FORCE_OBJECT);
            break;

        case 'validaVigFechas':
            $a_validFechIniJuic = $tramite->validaInicioJuic($_POST['fechRecibido'],$_POST['fechBaja'],$_POST['fechIniJuic'],$_POST['fechCTJuic']);
            echo json_encode($a_validFechIniJuic, JSON_FORCE_OBJECT);
            break;

        case 'updateJunInha':
            $a_update_JubInha = $tramite->updateJubInha($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucvemae'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['UnumDictam'],$_POST['UfechDictam'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UfechInipsgs'],$_POST['UfechFinpsgs'],$_POST['Unumpsgs'],$_POST['Udiaspsgs'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UmontRetFF'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['UdiaHaber'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_POST['UtipTram'],$_POST['UfolCheqBenef'],$_SESSION['usuario']);
            echo json_encode($a_update_JubInha, JSON_FORCE_OBJECT);
            break;

        case 'updateFA':
            $a_update_FA = $tramite->updateFA($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucvemae'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UfechInipsgs'],$_POST['UfechFinpsgs'],$_POST['Unumpsgs'],$_POST['Udiaspsgs'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['Unumbenefs'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Unombenefs'],$_POST['Ucurpbenefs'],$_POST['Uparentbenefs'],$_POST['Uporcbenefs'],$_POST['Uedadbenefs'],$_POST['Uvidabenefs'],$_POST['Udoctestamnt'],$_POST['Ufechdoctestmnt'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_POST['UtipTram'],$_POST['UfolCheqBenef'],$_SESSION['usuario']);
            echo json_encode($a_update_FA, JSON_FORCE_OBJECT);
            break;
        
        case 'updateFJ':
            $a_update_FJ = $tramite->updateFJ($_POST['Uanioentr'],$_POST['Unumentr'],$_POST['Uidentr'],$_POST['Uidret'],$_POST['Uidentrret'],$_POST['Ucveissemym'],$_POST['Uestatusmae'],$_POST['Umotret'],$_POST['Uapepat'],$_POST['Uapemat'],$_POST['Unombre'],$_POST['Unomcom'],$_POST['URegMae'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['Ufechbase'],$_POST['UdiasServ'],$_POST['UaniosServ'],$_POST['UmodRet'],$_POST['Umonttotret'],$_POST['UmontretEntr'],$_POST['UfechRecibido'],$_POST['UnumOficTarj'],$_POST['UfechOficAut'],$_POST['UimageOficTarj'],$_POST['Unumbenefs'],$_POST['UadedFajam'],$_POST['UadedTS'],$_POST['UadedFondPens'],$_POST['UadedTurismo'],$_POST['UmontAdeds'],$_POST['UmontretSnAdeds'],$_POST['Unumadeds'],$_POST['Unombenefs'],$_POST['Ucurpbenefs'],$_POST['Uparentbenefs'],$_POST['Uporcbenefs'],$_POST['Uedadbenefs'],$_POST['Uvidabenefs'],$_POST['Udoctestamnt'],$_POST['Ufechdoctestmnt'],$_POST['Ucurpmae'],$_POST['Urfcmae'],$_POST['Urfcmae'],$_POST['UtipTram'],$_SESSION['usuario']);
            echo json_encode($a_update_FJ, JSON_FORCE_OBJECT);
            break;

        case 'validaFechsTramPend':
            $a_validFchsTramPend = $tramite->validFchsTramPend($_POST["FchFallec"],$_POST["FchIniJuic"]);
            echo json_encode($a_validFchsTramPend, JSON_FORCE_OBJECT);
            break;
        
        case 'agregaTramPend':
            $a_add_trampend = $tramite->agregaTramPend($_POST["Icvemae"],$_POST["Iestatusmae"],$_POST["Imotret"],$_POST["Inomcommae"],$_POST["InomSolic"],$_POST["INumCel"],$_POST["InumPart"],$_POST["IfechRecibido"],$_POST["Ifechbaj"],$_POST["Ifechinijuic"],$_SESSION['usuario']);
            echo json_encode($a_add_trampend, JSON_FORCE_OBJECT);
            break;

        case 'subirImgSoporte':
            if(isset($_FILES['imageOficTarj'])) {
                $imageName = $_FILES['imageOficTarj']["name"];
                $nomtempora = explode(".",$_FILES['imageOficTarj']["name"]);
                $valid_extensions = array("jpeg", "jpg", "png");
                $filextension = strtolower(end($nomtempora));
                if((($_FILES["imageOficTarj"]["type"] == "image/png") || ($_FILES["imageOficTarj"]["type"] == "image/jpg") || ($_FILES["imageOficTarj"]["type"] == "image/jpeg")) && in_array($filextension, $valid_extensions)){
                    $sourcePath = $_FILES['imageOficTarj']['tmp_name'];
                    $targetPath = "/var/www/html/sistge/imgaut/".$imageName;
                    try {
                        move_uploaded_file($_FILES['imageOficTarj']['tmp_name'], $targetPath);
                        $uploadimage = $imageName;
                    } catch (\Throwable $th) {
                        echo $th;
                    }
                }
            }
            $output["imagen"] = $uploadimage;
            echo json_encode($output, JSON_FORCE_OBJECT); 
            break;
            
		case 'listarProrg':
			$a_get_tramsProrg = $tramite->getTramsProrg();
			
			$a_Prorrogas = Array();
            $contador=0;
            foreach($a_get_tramsProrg as $row){
                $a_prep_prorrogas = array();
                $contador++;
                $a_prep_prorrogas[] = $contador;
                $a_prep_prorrogas[] = $row["motivret"];
                $a_prep_prorrogas[] = $row["cvemae"];
                $a_prep_prorrogas[] = $row["nomcommae"];
                $a_prep_prorrogas[] = $row["fechsolic"];
                switch ($row["estatprorg"]) {
                    case 'R':
                        $estatProrg= "REVISION";
                        $estatProrroga = "enabled";
                        break;

                    case 'NA':
                        $estatProrg= "NO AUTORIZADA";
                        $estatProrroga = "disabled";
                        break;

                    case 'A':
                        $estatProrg= "AUTORIZADA";
                        $estatProrroga = "disabled";
                        break;
                }
                $a_prep_prorrogas[] = $estatProrg;
                $a_prep_prorrogas[] = "<button type='button' onclick='editProrg(".$row['id'].");'  id='".$row['id']."'class='BtIcDetail'><div><img src='../../img/lapiz.png' alt='editar-capturar' title='editar-capturar' height='18' width='18'></div></button>";
                $a_prep_prorrogas[] = "<button type='button' onclick='detalProrg(".$row['id'].");' id='".$row['id']."'class='BtIcEdit' ".$estatProrroga."><div><img src='../../img/file.png' alt='modificar' title='modificar' height='20' width='20'></div></button>";
                $a_prep_prorrogas[] = "<button type='button' onclick='deleteProrg(".$row['id'].");'  id='".$row['id']."'class='BtIcDelete'".$estatProrroga."><div><img src='../../img/goma-de-borrar.png' alt='eliminar' title='eliminar' height='17' width='17'></div></button>";
                $a_Prorrogas[] = $a_prep_prorrogas;  
            }
            $a_result_prorrogas_DT = array(
                "sEcho"=> 1,
                "iTotalRecords"=>count($a_Prorrogas),
                "iTotalDisplayRecords"=>count($a_Prorrogas),
                "aaData"=>$a_Prorrogas);
            echo json_encode($a_result_prorrogas_DT);
			break;
		
		case 'agregarProrg':
			$a_get_addProrg = $tramite->addProrroga($_POST["Icvemae"],$_POST["Imotret"],$_POST["Ifechbaj"],$_POST["InomSolic"],$_POST["INumCel"],$_POST["InumPart"],$_POST["IfechRecibido"],$_POST["Idescmotprorg"],$_POST["Isoporte"],$_SESSION["usuario"]);
			echo json_encode($a_get_addProrg, JSON_FORCE_OBJECT);
			break;

		case 'getProrg':
            $a_getProrroga = $tramite->getProrroga($_POST['idProrg']);
            echo json_encode($a_getProrroga, JSON_FORCE_OBJECT);
            break;

        case 'subirImgAut':
            $target_directory = "/var/www/html/sistge/imgaut/"; 
            $target_file = $target_directory . basename($_FILES["imageOficTExc"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (file_exists($target_file)) {
                echo "4. El archivo ya existe.";
                $uploadOk = 0;
            }

            if ($_FILES["imageOficTExc"]["size"] > 5 * 1024 * 1024) {
                echo "5. El archivo es demasiado grande.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "6. Solo se permiten archivos JPG, JPEG, PNG y GIF.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "3. No se pudo subir el archivo.";
            } else {
                if (move_uploaded_file($_FILES["imageOficTExc"]["tmp_name"], $target_file)) {
                    echo "1. El archivo ". basename( $_FILES["imageOficTExc"]["name"]). " se ha subido correctamente.";
                } else {
                    echo "2. Hubo un error al subir el archivo.";
                }
            }
            break;
        
        case 'updateProrg':
            $a_get_updProrroga = $tramite->updateProrroga($_POST['Uidprorg'],$_POST['Ucvemae'],$_POST['Ufechbaj'],$_POST['UnomSolic'],$_POST['UNumCel'],$_POST['UnumPart'],$_POST['UfechRecibido'],$_POST['Udescmotprorg'],$_POST['Usoporte'],$_POST['UoficAut'],$_POST['UdocOficAut'],$_POST['UestatAut'],$_POST['UestatProrg'],$_SESSION['usuario']);
            echo json_encode($a_get_updProrroga, JSON_FORCE_OBJECT);
            break;
        
        case 'value':
            $a_get_deleteProrg = $tramite->deleteProrroga($_POST['idprorg']);
            echo json_encode($a_get_updProrroga, JSON_FORCE_OBJECT);
            break;
        default:            
            break;
    }

?>
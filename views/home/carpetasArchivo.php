<?php

    require_once("/var/www/html/sistge/views/head/header.php");
    require_once("/var/www/html/sistge/controller/carpetas.php");
        
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }

    $carpeta = new carpetas();

    $rangoCarpetas = $carpeta -> mostrarRango();
    $resultcarp = $carpeta -> mostrarCarpetas();
	
	if(count($resultcarp) > 0){
		$identrega = $_GET["identr"];
		$anioEntrega = intval(substr($identrega,0,4));
		$numEntrega = intval(substr($identrega,4,2));
	
		$folIniFinCarpetas = $carpeta -> getFolsIniFinCaprs($anioEntrega,$numEntrega);
	}
		
?>

<section class="contenidoGral">        
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_CarpetasArchivo" enctype="multipart/form-data">
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar"  id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivBtnInicio">
                    <button type="button" class="BtnsNav" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>
        <section id="secCarpetasArchivo">
            <div id="divTitleCarpetas">ASIGNACION DE CARPETAS:  <input type="text" id="InputIdentrega" value="<?php echo $_GET["identr"];?>"></div>
            <div id="divInfoNumCarpetas">
                <div id="divTitleNumCar"># CARPETAS: </div>
                <div id="divNumCarpetas"><input type="text" id="inputNumCarp" name="inputNumCarp" value="<?php echo count($resultcarp);?>"></div>
                <input type="hidden" id="NumCarpetas" name="NumCarpetas" value="<?php echo count($resultcarp);?>">
                <div><button id="addCarp" value="agregar"><img src="../../img/add-psgs.png" alt="Agregar Carpeta" height="25" width="25"></button></div>
            </div>
            <div id="titleDetalleCarps">Detalle de carpetas para archivo</div>
            <section id="secRangFolios">
                <div>
					<div id="divtitleRang">Rango de folios de entrega: </div>
					<div id="divRanCarpetas">
						<input type="text" id="inpRangIniCarps" class="inptsRangCarps" value="<?php echo($rangoCarpetas[0]['folioinicial']);?>" disabled>
						<input type="text" id="inpRangFinCarps" class="inptsRangCarps" value="<?php echo($rangoCarpetas[0]['foliofinal']);?>" disabled>
					</div>
				</div>
                <div id="diveditaRang"><button id="editRang" value="editarang"><img src="../../img/lapiz.png" alt="Editar Rango" height="25" width="25"></button></div>
                <div id="diveupdateRang"><button id="updateCarp" value="updatenumcarps"><img src="../../img/actualizaRet.png" alt="Actualiza" height="25" width="25"></button></div>
            </section>
			<section id="secRangFolsCarps">
				<div>
					<div id="divtitleRang">Rango de folios de carpetas: </div>
					<div id="divRanCarpetas">
						<input type="text" id="inpFolIniCarps" class="inptsRangCarps" value="<?php echo($folIniFinCarpetas['foliniCarps']);?>" disabled>-
						<input type="text" id="inpFolFinCarps" class="inptsRangCarps" value="<?php echo($folIniFinCarpetas['folfinCarps']);?>" disabled>
					</div>
				</div>
			</section>
			
            <section id="sectDetalleCarpetas">
                <div class="folios">
                    <div id="divtitlesCarp">
                        <div class="divNumCarp"># carpeta</div>
                        <div class="divFolIni">Folio inicial</div>
                        <div class="divFolFin">Folio final</div>
                        <div class="divObserv">Observaciones</div>
                        <div class="divIconDelete"></div>
                    </div>
                </div>
                <div class="folios" id="inputsFols">
                    <div id="divsDetallesCarpetas">
                    <?php 
                        $index = 0;
                        foreach ($resultcarp as $row) { ?> 
                            <div id="divdetalleCarpeta" class="divdetalleCarpeta">
                                <div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta[]" name="numcarpeta[]" value="<?php echo($row["numcarpeta"]);?>"></div>
                                <div class="divFolIni"><input type="text" class="inputfolini" id="folinicial[]" name="folinicial[]" value="<?php echo($row["folini"]);?>"></div>
                                <div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal[]" name="folfinal[]" value="<?php echo($row["folfin"]);?>"></div>
                                <div class="divObserv"><input type="text" class="inputobserv" id="observcarp[]" name="observcarp[]" value="<?php echo($row["observaciones"]);?>"></div>
                                <div class="divIconDelete"><a href="#" class="delete_Carpeta"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></a></div>
                            </div>
                            <?php 
                                $index = $index + 1;
                        }   ?>
                    </div>
                </div>
            </section>
        </section>
        <section class="secAddFolCheqs">
            <div class="validCmplChqs">
                <button type="submit" id="generateCarps" name="generateCarps">CARPETAS</button>
				<button type="submit" id="validChqs" name="validChqs">VALIDAR</button>
				<input type="hidden" id="validExistFols" name="validExistFols">
            </div>
            <div class="divAddFlsChqs"> 
                <p>Folios para agregar</p>
				<div>Folios inexistentes:<input type="text" class="folsinexs" id="folsInexs" name="folsInexs"></div>
                <div class="divDatsFlsNw">
                    <div id="divFolChqNew">Num. Fol: <input type="text" class="folsinexs" id="numFolNew" name="numFolNew"></div>
					<div id="divFolChqNew">Clave Mae: <input type="text" class="folsinexs" id="cvemaeNew" name="cvemaeNew"></div>
					<div id="divFolChqNew">Nom. Benef.: <input type="text" class="folsinexs" id="nomBenNew" name="nomBenNew"></div>
					<div id="divFolChqNew">Mont. Benef.: <input type="text" class="folsinexs" id="montBenNew" name="montBenNew"></div>					
					<div id="divFolChqNew">Observ: <input type="text" class="folsinexs" id="observNew" name="observNew"></div>					
                    <div id="divMotChq">Motivo:    
                        <select class="opcMotvChq" id="opcMotvChq" name="opcMotvChq">
							<option value="I">INHA</option>
                            <option value="J">JUB</option>
                            <option value="FA">F ACT</option>
							<option value="FJ">F MUT</option>
							<option value="FJ">F FF</option>
                            <option value="NA">NO ASIG</option>
                        </select>
                    </div>
					<div id="divStatChq">Estatus:    
                        <select class="opcMotvChq" id="opcMotvChq" name="opcMotvChq">
							<option value="E">ENTR</option>
                            <option value="C">CANC</option>
                        </select>
                    </div>
					<div id="divButtonFolChqNew"> <button id="addChq" name="addChq"><img src="../../img/subir.png" alt="Agregar" height="25" width="25"></button></div>
                </div>
            </div>
        </section>
        <section class="secGnrtCrps">
            <div class="divGnrtCrpts">
                <button type="submit" id="gnrtCrpts" name="gnrtCrpts">GENERAR</button>
            </div>
        </section>
    </form>
</section>

<script src="../../libs/datatables/jquery-3.6.0.js"></script>
<script src="../../libs/datatables/jquery-3.6.0.min.js"></script>  

<script src="../../libs/datatables/moment.js"></script> 
<script src="../../libs/datatables/jquery-ui.js"></script>
<script src="../../libs/datatables/jquery.peity.js"></script>
<script src="../../libs/datatables/jquery.dataTables.js"></script>
<script src="../../libs/datatables/jquery.dataTables.min.js"></script>

<script src="../../libs/datatables-responsive/dataTables.responsive.js"></script>
<script src="../../libs/datatables/dataTables.buttons.min.js"></script>
<script src="../../libs/datatables/buttons.html5.min.js"></script>
<script src="../../libs/datatables/buttons.colVis.min.js"></script>
<script src="../../libs/datatables/jszip.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../libs/datatables/select2.min.js"></script>

<script type="text/javascript" src="../../asset/js/carpetas.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
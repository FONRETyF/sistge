<?php

    require_once("/var/www/html/sistge/views/head/header.php");
    require_once("/var/www/html/sistge/controller/carpetas.php");
        
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }

    $carpeta = new carpetas();

    //$validProcCarpts = $carpeta -> 
    $rangoCarpetas = $carpeta -> mostrarRango();
    
    $resultcarp = $carpeta -> mostrarCarpetas();

    
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
                <div><div id="divtitleRang">Rango de carpetas: </div><div id="divRanCarpetas"><input type="text" id="inpRangIniCarps" class="inptsRangCarps" value="<?php echo($rangoCarpetas[0]['folioinicial'])?>" disabled><input type="text" id="inpRangFinCarps" class="inptsRangCarps" value="<?php echo($rangoCarpetas[0]['foliofinal'])?>" disabled></div></div>
                <div id="diveditaRang"><button id="editRang" value="editarang"><img src="../../img/lapiz.png" alt="Editar Rango" height="25" width="25"></button></div>
                <div id="diveupdateRang"><button id="updateCarp" value="updatenumcarps"><img src="../../img/actualizaRet.png" alt="Actualiza" height="25" width="25"></button></div>
            </section>
            <section id="sectDetalleCarpetas">
                <div class="folios">
                    <div id="divtitlesCarp">
                        <div class="divNumCarp"># carpeta</div>
                        <div class="divFolIni">Folio inicial</div>
                        <div class="divFolFin">Folio final</div>
                        <div class="divEstat">Estatus</div>
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
                                <div class="divEstat"><select class="opcestat" id="estatcomplet[]" name="estatcomplet[]" value="<?php echo($row["estatcomplet"]);?>"><option value="COMPLETA">COMPLETA</option><option value="INCOMPLETA">INCOMPLETA</option></select></div>
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
                <button type="submit" id="validChqs" name="validChqs">VALIDAR</button>
            </div>
            <div class="divAddFlsChqs"> 
                <p>Folios para agregar</p>
                <div class="divDatsFlsNw">
                    <div id="divFolChqNew">Num. Folio: <input type="text" class="numFolNew" id="numFolNew[]" name="numFolNew[]"></div>
                    <div id="divStatChq">Estatus:    
                        <select class="opcStatChq" id="opcStatChq[]" name="opcStatChq[]">
                            <option value="E">ENTREGADO</option>
                            <option value="C">CANCELADO</option>
                            <option value="NA">NO ASIGNADO</option>
                        </select>
                    </div>
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
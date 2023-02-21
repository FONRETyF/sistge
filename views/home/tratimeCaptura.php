<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }

?>

<section class="contenidoGral">        
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_captTramite">
        <section id="SecCapturaTram">
            <div class="DatsEntr">
                <div id="DvNumEntrNuev">Num. Entrega:
                    <input type="text" class="InptsEntrDetalle" id="InputNumEntr" value="<?php echo substr($_GET["identrega"],4,2);?>" disabled>
                </div> 
                <div id="DvAnioEntrNuev">Año:
                    <input type="text" class="InptsEntrDetalle" id="InputAnioEntr" value="<?php echo substr($_GET["identrega"],0,4);?>" disabled>
                </div>  
                <input type="hidden" id="identrega" name="identrega"> 
            </div>
            <div id="causaRetiro">Causa Retiro: &nbsp
                        <select name="OpcCauRetiro" id="OpcCauRetiro" placeholder="MOTIVO RET" onchange="">
                            <option selected="true" disabled="disabled">Seleccione Motivo</option>
                            <option value="I">INHABILITACION</option>
                            <option value="J">JUBILACION</option>
                            <option value="FA">FALLECIMIENTO ACT</option>
                            <option value="FJ">FALLECIMIENTO JUB</option>
                        </select>
            </div>
            <div class="CapturaJub">
            <section id="SecDatPerson">
                <div class="DatosCaptura">
                    <p>D A T O S &nbsp &nbsp P E R S O N A L E S</p>                  
                    <div id="DivCveServPub">
                        <div>C.S.P: &nbsp<input type="text" id="cspMaeBusq" class="CSPMae" name="cspMaeBusq" placeholder="999999999" change="" disabled required></div>
                        <div>&nbsp &nbsp &nbsp Clave de ISSEMyM: &nbsp<input type="text" id="cveIMaeBusq" class="cveissemym" name="cveIMaeBusq" placeholder="000000" disabled></div>
                        <div id="EstatLaboral">Estatus: &nbsp<input type="text" id="estLaboral" name="estLaboral" placeholder="Estatus laboral" disabled></div>
                    </div>
                    <!--<div id="DivInforTram"><h1 id="infoTramite"></h1></div>  -->
                    <div id="DivNomMae">Nombre del maestro: 
                        <div id="DivDatsNomMae">
                            <input type="text" class="DatsNomMae" id="apePatMae" name="apePatMae" disabled>
                            <input type="text" class="DatsNomMae" id="apeMatMae" name="apeMatMae" disabled>
                            <input type="text" class="DatsNomMae" id="nombreMae" name="nombreMae" disabled>
                            <button id="EditaNombre" disabled><img src="../../img/lapiz.png" alt="Editar nombre" title="Editar nombre" height="20" width="20" disabled></button>
                            <!--<a id="EditaNombre" href="" disabled><img src="../../img/lapiz.png" alt="Editar nombre" title="Editar nombre" height="20" width="20" disabled></a>-->
                        </div>    
                        <div id="textnommae">
                            <div class="nomMaestro" id="TextApePat" disabled>Apellido Paterno</div>
                            <div class="nomMaestro" id="TextApeMat" disabled>Apellido Materno</div>
                            <div class="nomMaestro" id="TextNom" disabled>Nombre (s)</div>
                        </div>
                        <div>
                            <input type="text" class="nomComplMae" id="nomComplMae" name="nomComplMae" disabled>
                        </div> 
                        <div id="curpRfcMae">
                            <div class="curpRfcMae">CURP: <input type="text" id="CURPMae" class="inputCURP" name="CURPMae" placeholder="000000000000000000" disabled></div>
                            <div class="curpRfcMae">RFC: <input type="text" id="RFCMae" name="RFCMae" placeholder="0000000000000" ></div>
                            <div class="curpRfcMae">Region: 
                                <select name="OpcRegSind" id="OpcRegSind" placeholder="Region Sind" onchange="">
                                    <option selected="true" disabled="disabled">Region</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                </select>
                                <!--<input type="text" id="RegionMae" name="RegionMae" placeholder="" required>-->
                            </div>
                        </div>   
                        <div id="DivTelsMae">
                            <div id="DivTelPartic">Tel particular: <input type="text" id="TelPartiMae" name="TelPartiMae" placeholder="7229999999" disabled></div>
                            <div id="DivTelCel">Tel celular: <input type="text" id="TelCelMae" name="TelCelMae" placeholder="7229999999" disabled></div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="SecDatTramite">
                <div class="DatosCapTram">
                    <p>D A T O S &nbsp D E L &nbsp T R A M I T E &nbsp D E &nbsp R E T I R O</p>
                    <div id="DivDatSolic">
                        <div class="divNomSolic">Solicitante: <input type="text" id="nomSolic" name="nomSolic" disabled></div>
                        <div class="divFechSolic">Fecha de recibido: <input type="date" id="fechRecibido" name="fechRecibido" min="2000-01-01" max="2050-12-31" pattern="\d{4}-\d{2}-\d{2}" required disabled></div>
                    </div>
                    <div id="DivDictBasePsgs">
                        <div id="DivDictamen"><p>DICTAMEN</p> 
                            <div >Fecha: <input type="date" id="fechDictamen" name="fechDictamen" min="2000-01-01" max="2050-12-31" pattern="\d{4}-\d{2}-\d{2}" required disabled></div></br>
                            <div>Folio: &nbsp &nbspCP<input type="text" id="folioDictamen" name="folioDictamen" placeholder="00000/00" disabled ></div>
                        </div>
                        <div id="DivBaseBaja"><p>BASE Y BAJA</p> 
                            <div>Base: <input type="date" id="fechBaseMae" name="fechBaseMae" min="1930-01-01" max="2050-12-31"  pattern="\d{4}-\d{2}-\d{2}" disabled required></div></br>   
                            <div>Baja: <input type="date" id="fechBajaMae" name="fechBajaMae" min="2000-01-01" max="2050-12-31"  pattern="\d{4}-\d{2}-\d{2}" disabled required></div>
                        </div>
                        <div id="DivPsgs"><p>P.S.G.S:</p> 
                            <input type="hidden" id="fechsIniPSGS" name="fechsIniPSGS" value="">
                            <input type="hidden" id="fechsFinPSGS" name="fechsFinPSGS" value="">
                            <input type="hidden" id="DiasServOriginal" name="DiasServOriginal" value="">
                            <input type="hidden" id="numModal" name="numModal">
                            <div id="DivCalcAddPB">Agregar: 
                                <div><button id="calcDiasAnios" disabled><img src="../../img/calcula.png" alt="Calcula dias y años" title="Calcula dias y años" height="23" width="24" disabled></button></div>    
                                <div><button id="editaPSGS" disabled><img src="../../img/editar.png" alt="Editar PSGS" title="Editar PSGS" height="25" width="25" disabled></button></div>
                                <div><button id="editaBefens" onclick="agregaBenefs()" disabled><img src="../../img/agrega_benef.png" alt="Edita beneficiarios" title="Editar beneficiarios" height="28" width="28" disabled></button></div>
                                <div id="DivsinPSGS"><label for="sinPSGS"><input type="checkbox" id="sinPSGS" value="sinPSGS" disabled>Sin P.S.G.S</label></div>
                            </div>
                                <!--<a id="EditaNombre" href="#" disabled><img src="../../img/editar.png" alt="Editar nombre" title="Editar nombre" height="25" width="25" disabled></a> -->
                            <div id="divPeriLaboral">
                                <div class="DatsPeriodLab"><input type="text" id="numPsgs" name="numPsgs" disabled ></div>
                                <div class="DatsPeriodLab"><input type="text" id="tiempoPsgs" name="tiempoPsgs" disabled></div>
                                <div class="DatsPeriodLab"><input type="text" id="aniosServMae" name="aniosServMae"  disabled></div>
                            </div>
                            <div id="textPrioLaboral">
                                <div class="textPeriodLab">num psgs</div>
                                <div class="textPeriodLab">dias</div>
                                <div class="textPeriodLab">años de servicio</div>
                            </div>
                        </div>
                        <div id="DivCalculaRetiro">

                        </div>
                        <div id="DivExcepciones"><p>EXCEPCIONES</p> 
                            <div class="DatsExcepciones"># Oficio o Tarjeta: &nbsp;<input type="text" id="numOficTarj" name="numOficTarj"></div>
                        </div>
                    </div>              
                </div>
            </section>
            <section id="SecDatModRet">
                <p>S E G U R O &nbsp; D E &nbsp; R E T I R O &nbsp; </p>
                <div id="divAdeudos">
                    <div id="DivCheckAdeudos">
                        <label><input type="checkbox" id="CheckAdeudos" value="CheckAdeudos"> Adeudos</label for="CheckAdeudos"><br>
                    </div>
                    <!--<div id="Adeudos"><label for="CheckAdeudos"><input type="checkbox" id="CheckAdeudos" value="CheckAdeudos" disabled>Adeudo</label></div>-->
                    <div id="DivDatsAdeudos">
                        <div id="DivProcdAdeudo">
                            <div id="AdeudoFajam" class="procedAdeudo"><label for="AdedFajam">FAJAM</label>$<input type="text" id="AdedFajam" name="AdedFajam"value="0" onchange="obtenAdeudoF()"></div>
                            <!--<div id="AdeudoTS" class="procedAdeudo"><label for="AdedTS">Tienda Sindical</label>$<input type="text" id="AdedTS" name="AdedTS" value="0" onchange="obtenAdeudoT()" autofocus></div>-->
                            <div id="AdeudoFondPension" class="procedAdeudo"><label for="AdedFondPension">Fondo Prensionario</label>$<input type="text" id="AdedFondPension" name="AdedFondPension" value="0"></div>
                            <div id="AdeudoTurismo" class="procedAdeudo"><label for="AdedTurismo">Turismo</label>$<input type="text" id="AdedTurismo" name="AdedTurismo" value="0"></div>
                        </div>
                    </div>
                </div>
                <div id="tipModRetiro">
                    <div id="DivModRetiro">Tipo de retiro: &nbsp
                        <div id="DivOpcModRetiro">
                            <select name="ModoRetiro" id="ModoRetiro" disabled>
                                <option selected="true" disabled="disabled">Seleccione Modalidad</option>
                                <option value="C">COMPLETO</option>
                                <option value="D">DIFERIDO</option>
                            </select>
                        </div>
                        <div id="DivTpoDiferido">
                            <fieldset>
                                <!--<legend id="legenModDiferid"></legend>-->
                                <input type="radio" id="ModRetDiferid50" name="ModRetDiferid" value="50" checked><label for="ModRetDiferid50">50</label>
                                <input type="radio" id="ModRetDiferid100" name="ModRetDiferid" value="100"><label for="ModRetDiferid100">100</label>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="aportProm" name="aportProm">
                <div id="divCalcRetiro">
                    <div class="montsRet" id="montRetiro">Monto total del seguro de retiro: &nbsp $<input type="text" id="montRet" name="montRet" disabled></div></br>
                    <div class="montsRet">Monto de retiro: &nbsp $<input type="text" id="monRetEntr" name="monRetEntr" disabled></div>
                    <div class="montsRet" id="montRetFondFall">&nbsp RETIRO PARA EL FONDO DE FALLECIMIENTO &nbsp
                        <div id="divmontsrets">
                            <div class="divsmonts">Monto para ahorro: &nbsp $<input type="text" id="montRetFF" name="montRetFF" disabled></div>
                            <div class="divsmonts">Monto (dia de salario): &nbsp <input type="text" id="montSalMin" name="montSalMin" disabled></div>
                        </div>
                    </div>
                </div>
                <div id="divAgregaTram">
                    <button id="agregaTramite" name="agregaTramite" class="" >AGREGAR</button>
                    <button id="imprimeAcuerdo" name="imprimeAcuerdo" class="" >IMPRIMIR</button>
                </div>
            </section>
            </div>
        </section>
    </form>
</section>

<?php require_once("/var/www/html/sistge/views/home/editaNombreMae.php"); ?>
<?php require_once("/var/www/html/sistge/views/home/editarPSGS.php"); ?>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript" src="../../asset/js/tramiteNuevo.js"></script>


<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }

?>

<section class="contenidoGral">        
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_captTramite" enctype="multipart/form-data">
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
            <div id="causaRetiro">Causa Retiro: &nbsp
                        <select name="OpcCauRetiro" id="OpcCauRetiro" placeholder="MOTIVO RET" onchange="">
                            <option selected="true" disabled="disabled">Seleccione Motivo</option>
                            <option value="FA">FALLECIMIENTO ACT</option>
                            <option value="FJ">FALLECIMIENTO JUB</option>
                        </select>
            </div>
            <div class="CapturaJub">
            <section id="SecDatProrroga">
                <div class="DatosCaptura">  
                    <div id="titleDatsTramPend"><p>DATOS PARA REGISTRO DE TRAMITE PENDIENTE POR JUICIO</p></div> 
                              
                    <div id="DivCveServPub">
                        <div>C.S.P: &nbsp<input type="text" id="cspMaeBusq" class="CSPMae" name="cspMaeBusq" placeholder="999999999" change="" minlength="9" maxlength="9" disabled required></div>
                        <div>&nbsp &nbsp &nbsp Clave de ISSEMyM: &nbsp<input type="text" id="cveIMaeBusq" class="cveissemym" name="cveIMaeBusq" minlength="3" maxlength="6" placeholder="000000" disabled></div>
                        <div id="EstatLaboral">Estatus: &nbsp<input type="text" id="estLaboral" name="estLaboral" placeholder="Estatus laboral" disabled></div>
                    </div>
                    <!--<div id="DivInforTram"><h1 id="infoTramite"></h1></div>  -->
                    <div id="DivNomMae">Nombre del maestro: 
                        <div id="DivDatsNomMae">
                            <input type="text" class="DatsNomMae" id="apePatMae" name="apePatMae" disabled>
                            <input type="text" class="DatsNomMae" id="apeMatMae" name="apeMatMae" disabled>
                            <input type="text" class="DatsNomMae" id="nombreMae" name="nombreMae" disabled>
                            <button id="EditaNombre" class="EditaNombre" disabled><img src="../../img/lapiz.png" alt="Editar nombre" title="Editar nombre" height="20" width="20" disabled></button>
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
                        <div id="DivTelsMae">
                            <div id="DivTelPartic">Tel particular: <input type="text" class="TelsMae" id="TelPartiMae" name="TelPartiMae" placeholder="7229999999" maxlength="10" disabled></div>
                            <div id="DivTelCel">Tel celular: <input type="text" class="TelsMae" id="TelCelMae" name="TelCelMae" placeholder="7229999999" maxlength="10" disabled></div>
                        </div>
                        <div id="DivDatSolicTramPend">
                            <div class="divNomSolic">Solicitante: <input type="text" id="nomSolic" name="nomSolic" onblur="convierteMayusc(this)" disabled></div>
                            <div class="divFechSolic">Fecha de recibido: <input type="date" id="fechRecibido" name="fechRecibido" min="2000-01-01" max="2050-12-31" pattern="\d{4}-\d{2}-\d{2}" required disabled></div>
                        </div>
                        <div id="DivFechsBajIniJ">
                            <div id="DivInputFechBaja">Fecha de fallecimiento:&nbsp;<input type="date" id="fechBajaMae" name="fechBajaMae" min="2000-01-01" max="2050-12-31"  pattern="\d{4}-\d{2}-\d{2}" disabled required></div>
                            <div id="DivFechInicioJuicio">Inicio del Juicio:&nbsp;<input type="date" id="fechIniJuicio" name="fechCTJuicio" min="2022-01-01" max="2050-12-31" pattern="\d{4}-\d{2}-\d{2}" disabled required></div>
                        </div>
                    </div>
                </div>
            </section>
            <div id="divAgregaTram">
                <button type="submit" id="agregaTrampPend" name="agregaTrampPend" >AGREGAR</button>
            </div>
        </section>
        </div>
        </section>
    </form>
</section>

<?php require_once("/var/www/html/sistge/views/home/editaNombreMae.php"); ?>

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

<script type="text/javascript" src="../../asset/js/trampendNuevo.js"></script>


<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
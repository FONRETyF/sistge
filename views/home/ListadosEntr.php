<?php
require_once "/var/www/html/sistge/views/head/header.php";
?>
    <!--<h1 class="text-center mt-4">Bienvenido </h1>-->

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_listados">
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar"  id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivBtnInicio">
                    <button type="button" class="BtnsNav" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php date_default_timezone_set('America/Mexico_City'); echo("Toluca, México a  " .date("d-m-y, h:m:s"));?>
                </div>
            </div>
        </section>
        <section id="sectionListados">PROCESOS PARA LA ENTREGA
            <input type="text" id="InputIdentrega" value="<?php echo $_GET["identr"];?>">
            <!--<div id="divOpcionLists">
                <div id="divSelectList">
                    <select name="opcionListados" id="opcionListados">
                        <option selected="true" disabled="disabled">Seleccione opcion</option>
                        <option value="ListActas">Actas</option>
                        <option value="ListAded">Adeudos</option>
                        <option value="ListGral">General</option>
                        <option value="ListContad">Archivo</option>
                        
                    </select>
                </div>
            </div>-->
        </section>
        <section id="SecRadBtnsListOfics">
            <div id="DivRadBtnsListOfics">
                <div id="DivRadBtnsOptnsOfics">
                    <div id="DivTituloOfics">
                        <p>OFICIOS</p> 
                    </div>
                    <div id="DivRadBtnsOfics">
                        <div class="divsRadBtns"><input type="radio" id="RadBtnTraspaso" class="RadBtnsOptions" name="RdBtnsOfics" value="Traspaso" checked><label for="RadBtnTraspaso">Trapaso</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnSolicCheqFinan" class="RadBtnsOptions" name="RdBtnsOfics" value="Finanzas"><label for="RadBtnSolicCheqFinan">Solic. cheques</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnImpCheqInform" class="RadBtnsOptions" name="RdBtnsOfics" value="Informatica"><label for="RadBtnImpCheqInform">Impr. cheques</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnArchContad" class="RadBtnsOptions" name="RdBtnsOfics" value="Archivo"><label for="RadBtnArchContad">Archivo</label></div>
                    </div>
                    <div id="DivBtnImpOfics">
                        <button type="button" id="BtnImprimeOficio" name="BtnImprimeOficio">Imprime Oficio</button>
                    </div>
                </div>
                <div id="DivRadBtnsOptnsLists">
                    <div id="DivTituloLists">
                        <p>LISTADOS</p> 
                    </div>
                    <div id="DivRadBtnsLists">
                        <div class="divsRadBtns"><input type="radio" id="RadBtnArchivo" class="RadBtnsOptions" name="RdBtnsLists" value="Contabilidad" checked><label for="RadBtnArchivo">Contabilidad</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnAdeudos" class="RadBtnsOptions" name="RdBtnsLists" value="Adeudos"><label for="RadBtnAdeudos">Adeudos</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnActas" class="RadBtnsOptions" name="RdBtnsLists" value="Actas"><label for="RadBtnActas">Actas</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnSobres" class="RadBtnsOptions" name="RdBtnsLists" value="Sobres"><label for="RadBtnSobres">Sobres</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnInformatica" class="RadBtnsOptions" name="RdBtnsLists" value="Cheques"><label for="RadBtnInformatica">Informatica</label></div>
                        <div class="divsRadBtns"><input type="radio" id="RadBtnLlamadas" class="RadBtnsOptions" name="RdBtnsLists" value="Llamadas"><label for="RadBtnLlamadas">Llamadas</label></div>
                    </div>
                    <div id="DivBtnImpLists">
                        <button type="button" id="BtnGenerateList" name="BtnGenerateList">Genera Listado</button>
                    </div>
                </div>
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

<script type="text/javascript" src="../../asset/js/listadosEntr.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
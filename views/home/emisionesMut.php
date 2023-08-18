<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_emisionesMut" ></form>
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar" id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivFechaActual">
                    <?php date_default_timezone_set('America/Mexico_City'); echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>
        <section id="secEmisionesMut">Relacion de emisiones de afiliacion al programa de Mutualidad
            <div id="ConsultEmisiones">
                <div id="AgregaEmision">
                    <a id="emisNueva" class="newEmision" href="#"><img src="../../img/add.png" alt="Nueva" title="Nueva Emision" height="35" width="35"></a>                
                    <a id="addAports" class="addAports" href="edoCtaIndMut.php"><img src="../../img/add_aports.png" alt="Añadir aportacion" title="Añadir aportacion" height="35" width="35"></a>                
                </div>
            </div>
            <div id="ResultConsult">
                <table id ="emisiones_data" class="table display responsive nowrap">
                    <thead class="tab_emisiones">
                        <tr>
                            <th class="wd-5p"> Num </th>
                            <th class="wd-5p"> Año </th>
                            <th class="wd-30p"> Nombre emision </th>
                            <th class="wd-10p"> Num afiliaciones </th>
                            <th class="wd-10p"> Estatus </th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                        </tr>
                    </thead>
                    <tbody id="tbodEmisiones" class="tbodEmisiones">
                    </tbody>
                </table>
            </div>
        </section>
</section>

<section class="modalEmision">
    <div class="modal_contenedor">
        <h2 class="modal_title"></h2>
        <div class="divseparador"></div>
        <section class="DatsEmisionDetalle">
            <div class="DatsEmision">
                <div id="DvNumEmision">Num. Emision:
                    <input type="text" name="numemision" id="numemision">
                </div>
                <div id="DvAnioEmision">Año: 
                    <input type="text" name="Anioemision" id="Anioemision">
                </div>
                <input type="hidden" id="idemision" name="idemision">
            </div>
            <div class="DatsEmisionDesc">
                <div id="DvDescEmision">Descripcion:
                    <input type="text" id="descemision" name="descemision">
                </div>
            </div>
            <div class="divFechaEmision"> 
                <div class="fechInicio">Fecha de inicio de recepcion:
                    <input type="date" id="fechIniRecep" name="fechIniRecep" pattern="\d{4}-\d{2}-\d{2}">
                </div>
            </div>
            <div class="obsrEmision">Observaciones:
                <input type="text" id="observemision" name="observemision">
            </div> 
        </section>
        <section class="divaddEmision">
            <a href="#" class="addEmision">Agregar</a>
            <a href="#" id="updateEmision" class="updateEmision">Guardar</a>
        </section>
        <section class="closeWindowEmis">
            <a href="#" class="modal_close">Cerrar</a>
        </section>
    </div>
</section>

<!--<div class="modal_container">
    <div class = "modalEC modal-close"></div>
        <h2 class="modal_title_Edocta"></h2>
        <div class="divseparador"></div>
        <section class="DatsEdoCtaDetalle">

            <div class="DatsEdoCta">
                <div id="DvcveMae">Cve issemym:
                    <input type="text" name="cveIssemym" id="cveIssemym">
                    <input type="button" name="searchMae" id="searchMae">
                </div>
                <div id="DvNomMae">Nombre del maestro: 
                    <input type="text" name="nomcomMae" id="nomcomMae">
                </div>
                <input type="hidden" id="cveissemym" name="cveissemym">
            </div>

            <div class="DatsEdoctaAct">
                <div id="DvEdoctaAct">Estado de cuenta actual:
                    <div>Num. Aportaciones:
                        <input type="text" id="numaports" name="numaports">
                    </div>    
                    <div>Año Ult Aport:
                        <input type="text" id="anioultaport" name="anioultaport">
                    </div> 
                </div>
            </div>

            <div class="divEdoCtaNew"> Actualizacion de Estado de Cuenta:
                <div class="DvDtasNuewEdoCta">
                    <div>Num. Aportaciones:
                        <input type="text" id="numaportsNew" name="numaportsNew">
                    </div>    
                    <div>Año Ult Aport:
                        <input type="text" id="anioultaportNew" name="anioultaportNew">
                    </div>
                </div>
            </div>
        </section>

        <section class="divaddAports">
            <a href="#" id="updateEdoCtaMut" class="updateEdoCtaMut">Guardar</a>
        </section>
        <section class="closeWindowEmis">
            <a href="#" class="modal_close">Cerrar</a>
        </section>
    </div>
</div>-->

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

<script type="text/javascript" src="../../asset/js/emisiones.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
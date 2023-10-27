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
        <section class="secDatos">
            
            <div id="titleEdoCtaAct">ESTADO DE CUENTA INDIVIDUAL DE MUTUALIDAD</div>
            <div class="divseparador"></div>
            <section class="DatsEdoCtaDetalle">
                <div class="DatsEdoCta">
                    <div id="DvcveMae">Cve issemym:
                        <input type="text" name="cveIssemym" id="cveIssemym" class="cveIssemym" minlength="3" maxlength="6" placeholder="000000">
                        <button type="submit" id="searchMae" name="searchMae" class="searchMae">...</button>
                        
                    </div>
                    <div id="DvNomMae">Nombre del maestro: 
                        <input type="text" name="nomcomMae" id="nomcomMae">
                    </div>
                    <input type="hidden" id="cveissemym" name="cveissemym">
                </div>

                <div class="DatsEdoctaAct">
                    <div id="title-edoctaact">Estado de cuenta actual</div>
                    <div id="DvEdoctaAct">
                        <div class="divsEdoCta">Num. Aportaciones:
                            <input type="text" id="numaports" name="numaports" disabled>
                        </div>    
                        <div class="divsEdoCta">Año Ult Aport:
                            <input type="text" id="anioultaport" name="anioultaport" disabled>
                        </div> 
                    </div>
                </div>

                <div class="divEdoCtaNew"> 
                    <div id="title-edoctanew">Actualizacion de Estado de Cuenta</div>
                    <div class="DvDtasNuewEdoCta">
                        <div class="divsEdoCta">Num. Aportaciones:
                            <input type="text" id="numaportsNew" name="numaportsNew">
                        </div>    
                        <div class="divsEdoCta">Año Ult Aport:
                            <input type="text" id="anioultaportNew" name="anioultaportNew" class="anioultaportNew">
                        </div>
                    </div>
                </div>
            </section>

            <section class="divaddAports">
                <a href="#" id="updateEdoCtaMut" class="updateEdoCtaMut">Guardar</a>
            </section>
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

<script type="text/javascript" src="../../asset/js/emisiones.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
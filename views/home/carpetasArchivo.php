<?php

    require_once("/var/www/html/sistge/views/head/header.php");
        
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
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
                <div id="divNumCarpetas"><input type="text" id="inputNumCarp" name="inputNumCarp"></div>
            </div>
            <div id="titleDetalleCarps">Detalle de carpetas para archivo;</div>
            <section id="sectDetalleCarpetas">
                    <div class="folios">
                        <div class="titleFolios"># carpeta</div>
                        <div class="titleFolios">Folio inicial</div>
                        <div class="titleFolios">Folio final</div>
                    </div>
                    <div class="folios" id="inputsFols">
                        <div class="titleFolios"><input type="text" id="numcarpeta[]" name="numcarpeta[]"></div>
                        <div class="titleFolios"><input type="text" id="folinicial[]" name="folinicial[]"></div>
                        <div class="titleFolios"><input type="text" id="folfinal[]" name="folfinal[]"></div>
                    </div>
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

<script type="text/javascript" src="../../asset/js/carpetas.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
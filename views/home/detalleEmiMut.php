<?php
    session_start();
    require_once("/var/www/html/sistge/views/head/header.php");
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_solicMut">
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
        <section id="secSolicsMut"> RELACION DE SOLICITUDES DE LA EMISION: <?php echo $_SESSION['usuario'];?>
            <input type="text" id="inputIdEmis" value="<?php echo $_GET["idemi"];?>">
            <div id="ConsultSolics">
                <input type="hidden" id="estatentrega" name="estatentrega">
                <div id="operationsMut">
                    <a id="solicNew" class="btnsOperations" href="#"><img src="../../img/new-document.png" alt="Nueva solicitud" title="Nueva solicitud" height="35" width="35"></a>  
                    <a id="printLists" class="btnsOperations" href="#"><img src="../../img/imprimeListados.png" alt="Imprimir listado" title="Imprimir listado" height="35" width="35"></a>
                </div>
            </div>
            <div id="ResultConsultSolicsMut">
                <table id ="solicsmut_data" class="table display responsive nowrap despliegue_tabla">
                    <thead class="tab_solicsmut">
                        <tr>
                            <th class="wd-15p"> Id </th>
                            <th class="wd-15p"> Clave </th>
                            <th class="wd-15p"> Nombre maestro </th>
                            <th class="wd-15p"> Fecha afiliacion </th>
                            <th class="wd-15p"> Estatus </th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                        </tr>
                    </thead>
                </table>
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

<script type="text/javascript" src="../../asset/js/emisiones.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>


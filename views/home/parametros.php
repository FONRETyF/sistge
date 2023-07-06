<?php
    session_start();
    require_once("/var/www/html/sistge/views/head/header.php");
    /*if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }*/
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_parametros">
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar"  id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivBtnInicio">
                    <button type="button" class="BtnsNav" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php date_default_timezone_set('America/Mexico_City'); echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>
        <section id="secParametros">Relacion de parametros para los retiros
            <div id="ConsultParametros">
                <div id="AgregaParam">
                    <a id="paramNuevo" href="parametrosRetAct.php"><img src="../../img/add.png" alt="Nuevo" title="Parametro Nuevo" height="35" width="35"></a>    
                </div>
            </div>
            <div id="ResultConsult">
                <table id ="parametro_data" class="table display responsive nowrap">
                    <thead class="tab_parametros">
                        <tr>
                            <th class="wd-15p"> Num </th>
                            <th class="wd-15p"> Aport Prom </th>
                            <th class="wd-15p"> Ret Anual </th>
                            <th class="wd-15p"> Estatus </th>
                            <th class="wd-15p"> Entrega In </th>
                            <th class="wd-15p"> Entrega Fn </th>
                            <th class="wd-15p"> </th>
                        </tr>
                    </thead>
                    <tbody id="tbodParams" class="tbodParams">
                    </tbody>
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

<script type="text/javascript" src="../../asset/js/parametros.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
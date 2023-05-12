<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_entrega">
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
        <section id="secEntregas">Relacion de entregas de FONRETyF
            <div id="ConsultEntregas">
                <div id="TextConsulEntr">Cosulta:</div>
                <div id="DatConsltEntr">
                    <select name="anioentrega" id="anioentrega" onchange="">
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
                <div id="AgregaEntr">
                    <a id="entrNueva" href="#"><img src="../../img/add.png" alt="Nueva" title="Nueva Entrega" height="30" width="30"></a>    
                </div>
            </div>
            <div id="ResultConsult">
                <table id ="entrega_data" class="table display responsive nowrap">
                    <caption>Entregas encontradas</caption>
                    <thead class="tab_entregas">
                        <tr>
                            <th class="wd-15p"> Num </th>
                            <th class="wd-15p"> Año </th>
                            <th class="wd-15p"> Nombre Entrega </th>
                            <th class="wd-15p"> Estatus </th>
                            <th class="wd-15p"> Fecha Entrega </th>
                            <th class="wd-15p"> Num Trams </th>
                            <th class="wd-15p"> Monto </th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                            <th class="wd-15p"></th>
                        </tr>
                    </thead>
                    <tbody id="tbodEntregas" class="tbodEntregas">
                    </tbody>
                </table>
            </div>
        </section>
    </form>
</section>

<?php require_once("/var/www/html/sistge/views/home/editarEntrega.php"); ?>

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

<script type="text/javascript" src="../../asset/js/entregas.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
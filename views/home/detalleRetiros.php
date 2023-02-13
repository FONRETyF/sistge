<?php
    session_start();
    require_once("/var/www/html/sistge/views/head/header.php");
    /*if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }*/
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_retiros">
        <section id="secRetiros"> RELACION DE RETIROS DE LA ENTREGA: 
            <input type="text" id="InputIdentrega" value="<?php echo $_GET["identrega"];?>">
            <div id="ConsultRetiros">
                <!--<div id="TextConsulRets">Cosulta: &nbsp
                    <input type="text" id="cspConsultRet" name="cspConsultRet" placeholder="Escriba csp issemym">
                    <button class="btnConsultRet">BUSCAR</button>
                </div>-->
                <div id="AgregaEntr">
                    <input type="hidden" id="estatentrega" name="estatentrega">
                    <a id="tramNuevo" href="tratimeCaptura.php?identrega=<?php echo $_GET["identrega"];?>"><img src="../../img/new-document.png" alt="Nuevo tramite" title="Nuevo tramite" height="30" width="30"></a>
                    <!--<button id="tramNuevo" type="submit" onclick="tramiteNuevo()">
                        <img src="../../img/new-document.png" alt="Nuevo tramite" title="Nuevo tramite" height="30" width="30"  onclick="tramiteNuevo()"></button> -->    
                </div>
            </div>
            <div id="ResultConsultRets">
                <table id ="retiros_data" class="table display responsive nowrap">
                    <caption>Retiros de la entrega</caption>
                    <thead>
                        <tr>
                            <th class="wd-15p"> Entrega </th>
                            <th class="wd-15p"> Año </th>
                            <th class="wd-15p"> Clave </th>
                            <th class="wd-15p"> Motivo </th>
                            <th class="wd-15p"> Beneficiario </th>
                            <th class="wd-15p"> Monto </th>
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

<script type="text/javascript" src="../../asset/js/retiros.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
<?php

require_once("/var/www/html/sistge/views/head/header.php");
    
if(empty($_SESSION['usuario'])){
    header("Location:login.php");
}

?>
<section class="contenidoGral">
    <form id="consulta_EdoCta" action="" class="formConsulEdoCta" method="POST">
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
        </section id="SecConsltEdoCta">
            <div id="TxtTitConsl">Consulta de Estados de Cuenta de Mutualidad</div>
            <div id="ConsltEdoCta">
                <div>
                    <div id="TextCveIssemym">Clave de issemym o nombre: </div> 
                    <input type="text" id="CveIssemym" class="cveIssemym" name="CveIssemym" placeholder="Escriba clave o nombre del maestro">
                    <button type="submit" id="BttnBuscar" click="">BUSCAR</button>    
                </div>
                <div id="opcRadsBusqJub">
                    <div id="divsRadBtnsBusq"><input type="radio" id="RadBtnCveIssemym" class="RadBtnsOptionsBusq" name="RadBtnsCriterio" value="issemym" checked><label for="RadBtnCveIssemym">Cve Issemym</label></div>
                    <div id="divsRadBtnsBusq"><input type="radio" id="RadBtnNombreJub" class="RadBtnsOptionsBusq" name="RadBtnsCriterio" value="nomjub"><label for="RadBtnCveIssemym">Nombre</label></div>
                </div>
            </div>
        <section>
        <section id="ResultConsltRetr">
            <table id="edocta_data" class="table display responsive nowrap">
                <caption>Retiro (s) entregados</caption>
                <thead id="tabConsultRet">
                    <tr>   
                        <th style="width: 10px;"> id </th> 
                        <th style="width: 15px;"> Clave </th>
                        <th style="width: 150px;"> Nombre del maestro </th>
                        <th style="width: 10px;"> Programa </th>
                        <th style="width: 10px;"> Estatus </th>
                        <th style="width: 15px;"> Fecha Jubilacion </th>
                        <th style="width: 25px;"> Año Afiliacion </th>
                        <th style="width: 25px;"> Año Ult Aport </th>
                        <th style="width: 10px;"> Num Aport</th>
                    </tr>
                </thead>
                <tbody id="resultBusqEdoCta">

                </tbody>
            </table>

        </section>

        </section>
    
    </form>

</section>

<script src="../../libs/datatables/jquery-3.6.0.js"></script>
    <script src="../../libs/datatables/jquery-3.6.0.min.js"></script>    
    <script src="../../libs/datatables/jquery.dataTables.js"></script>
    <script src="../../libs/datatables/jquery.dataTables.min.js"></script>
    <script src="../../libs/datatables-responsive/dataTables.responsive.js"></script>

    <script src="../../libs/datatables/dataTables.buttons.min.js"></script>
    <script src="../../libs/datatables/buttons.html5.min.js"></script>
    <script src="../../libs/datatables/buttons.colVis.min.js"></script>
    <script src="../../libs/datatables/jszip.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../libs/datatables/select2.min.js"></script>

    <script type="text/javascript" src="../../asset/js/main.js"></script>
    <script type="text/javascript" src="../../asset/js/edosctamut.js"></script>

<?php
    require_once("/var/www/html/sistge/views/head/footer.php");
?>
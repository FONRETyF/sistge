<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">

        <section id="SecConsltRetr">
            <div id="TxtTitConsl">Consulta de Retiros Otorgados por FONRETYF</div>
            <div id="ConsltRetir">
                <div id="TextCSP">C.S.P o Issemym: </div> 
                <input type="text" id="CveServPub" name="CveServPub" placeholder="Escriba clave del maestro">
                <button type="submit" id="BttnBuscar">BUSCAR</button>       
            </div>
            
        </section>
        <section id="ResultConsltRetr">
                <table id="retiro_data" class="table display responsive nowrap">
                    <caption>Retiro (s) entregados</caption>
                    <thead id="tabConsultRet">
                        <tr>   
                            <th style="width: 10px;"> id </th> 
                            <th style="width: 15px;"> CSP o Issemym </th>
                            <th style="width: 8px;"> Mot </th>
                            <th style="width: 150px;"> Nombre del Beneficiario </th>
                            <th style="width: 25px;"> Monto </th>
                            <th style="width: 30px;"> Fecha de tramite </th>
                            <th style="width: 30px;"> Fecha de entrega </th>
                            <th style="width: 35px;"> Estatus </th>
                        </tr>
                        <?php foreach ($datos as $row) {?>
                        <tr>   
                            <th style="width: 10px;"> <?php echo $row['identrega']?> </th> 
                            <th style="width: 15px;"> <?php echo $row['cvemae']?> </th>
                            <th style="width: 8px;"> <?php echo $row['motvret']?> </th>
                            <th style="width: 150px;"> <?php echo $row['nomsolic']?> </th>
                            <th style="width: 25px;"> <?php echo $row['montret']?> </th>
                            <th style="width: 30px;"> <?php echo $row['fechrecib']?> </th>
                            <th style="width: 30px;"> <?php echo $row['fechentrega']?> </th>
                            <th style="width: 35px;"> <?php echo $row['estattramite']?> </th>
                        </tr>    
                            
                        <?php }?>

                    </thead>
                </table>
    </section>

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

    <script type="text/javascript" src="../../asset/js/main.js"></script>

    <script src="../../libs/datatables/select2.min.js"></script>
    
    <script type="text/javascript" src="../../asset/js/retiros.js"></script>

<?php
    require_once("/var/www/html/sistge/views/head/footer.php");
?>
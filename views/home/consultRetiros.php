<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">
    <form id="consulta_Retiro" action="../../controller/retiroController.php" class="formConsulRet" method="POST">
        <section id="SecConsltRetr">
            <div id="TxtTitConsl">Consulta de Retiros Otorgados por FONRETYF</div>
            <div id="ConsltRetir">
                <div>
                    <div id="TextCSP">C.S.P, issemym o nombre: </div> 
                    <input type="text" id="CveServPub" name="CveServPub" placeholder="Escriba clave del maestro">
                    <button type="submit" id="BttnBuscar">BUSCAR</button>    
                </div>
                <div id="opcRadsBusq">
                    <div class="divsRadBtnsBusq"><input type="radio" id="RadBtnCSP" class="RadBtnsOptionsBusq" name="RdBtnsOpcsBusq" value="CSP" checked><label for="RadBtnCSP">C S P</label></div>
                    <div class="divsRadBtnsBusq"><input type="radio" id="RadBtnIssemym" class="RadBtnsOptionsBusq" name="RdBtnsOpcsBusq" value="Issemym"><label for="RadBtnIssemym">Issemym</label></div>
                    <div class="divsRadBtnsBusq"><input type="radio" id="RadBtnNombre" class="RadBtnsOptionsBusq" name="RdBtnsOpcsBusq" value="Nombre"><label for="RadBtnNombre">Nombre</label></div>
                    <div class="divsRadBtnsBusq"><input type="radio" id="RadBtnFolio" class="RadBtnsOptionsBusq" name="RdBtnsOpcsBusq" value="Folio"><label for="RadBtnFolio">Folio cheque</label></div>
                </div>   
            </div>            
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

    <script type="text/javascript" src="../../asset/js/main.js"></script>

    <script src="../../libs/datatables/select2.min.js"></script>
    
    <script type="text/javascript" src="../../asset/js/tramites.js"></script>

<?php
    require_once("/var/www/html/sistge/views/head/footer.php");
?>
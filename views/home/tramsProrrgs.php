<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }

?>

<section class="contenidoGral">        
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_captTramite" enctype="multipart/form-data">
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar"  id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivBtnInicio">
                    <button type="button" class="BtnsNav BtnInicio" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>

        <section id="secRetiros"> RELACION DE TRAMITES CON PRORROGAS: 
            <div id="ConsultRetiros">
                <div id="operationsEntr">
                    <a id="tramNuevo" class="btnsOperations" href="capturaTramProrg.php"><img src="../../img/new-document.png" alt="Nuevo tramite" title="Nuevo tramite" height="35" width="35"></a>  
                </div>
            </div>
            <div id="ResultConsult">
				<table id ="prorrogas_data" class="table display responsive nowrap despliegue_tabla">
					<thead class="tabla_Prorrogas">
						<tr>
							<th class="wd-15p"> id </th>
							<th class="wd-15p"> Motivo </th>
							<th class="wd-15p"> Clave </th>
							<th class="wd-15p"> Nombre Maestro </th>
							<th class="wd-15p"> Fecha Recib </th>
							<th class="wd-15p"> Estatus de Prorroga </th>
							<th class="wd-15p"></th>
							<th class="wd-15p"></th>
							<th class="wd-15p"></th>
						</tr>
					</thead>
                    <tbody id="tbodProrrogas" class="tbodProrrogas">
                    </tbody>
				</table>
			</div>
        </section>


    </form>
</section>

<!--<script src="../../libs/datatables/jquery-1.12.4.js"></script>
<script src="../../libs/datatables/jquery-1.12.4.min.js"></script>-->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript" src="../../asset/js/tramProrrg.js"></script>


<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
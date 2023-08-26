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
                    <button type="button" class="BtnsNav" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>
		
		<section class="sectionsAltMaes">
			<div id="titleAltMae">Alta de maestros del SMSEM</div>
		</section>
		
		<section class="sectionsAltMaes">
			<p>D A T O S &nbsp &nbsp P E R S O N A L E S</p>                  
            <div id="DivCveServPub">
                <div>C.S.P: &nbsp <input type="text" id="cspMae" class="CSPMae" name="cspMae" placeholder="999999999" minlength="9" maxlength="9"  required></div>
                <div>&nbsp &nbsp &nbsp Clave de ISSEMyM: &nbsp <input type="text" id="cveIMaeBusq" class="cveissemym" name="cveIMaeBusq" minlength="3" maxlength="6" placeholder="000000" ></div>                        
            </div>
			<div id="DivNomMae">Nombre del maestro: 
				<div id="DivDatsAltNomMae">
					<input type="text" class="DatsNomMae" id="apePatMae" name="apePatMae" onblur="convierteMayusc(this)">
					<input type="text" class="DatsNomMae" id="apeMatMae" name="apeMatMae" onblur="convierteMayusc(this)">
					<input type="text" class="DatsNomMae" id="nombreMae" name="nombreMae" onblur="convierteMayusc(this)">
				</div>
				<div id="textnommae">
                    <div class="nomMaestro" id="TextApePat" disabled>Apellido Paterno</div>
                    <div class="nomMaestro" id="TextApeMat" disabled>Apellido Materno</div>
                    <div class="nomMaestro" id="TextNom" disabled>Nombre (s)</div>
                </div>
			</div>
			<div>
                <input type="text" class="nomComplMae" id="nomComplMae" name="nomComplMae" disabled>
            </div> 
            <div id="curpRfcMae">
                <div class="curpRfcMae">CURP: <input type="text" id="CURPMae" class="inputCURP" name="CURPMae" placeholder="000000000000000000" maxlength="18" onblur="convierteMayusc(this)" ></div>
                <div class="curpRfcMae">RFC: <input type="text" id="RFCMae" name="RFCMae" placeholder="0000000000000" maxlength="13" onblur="convierteMayusc(this)"></div>
                <div class="curpRfcMae">Region: 
					<select name="OpcRegSind" id="OpcRegSind" placeholder="Region Sind">
						<option selected="true" >Region</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
					</select>                                
                </div>
            </div>   
		</section>
		
		<section class="sectionsAltMaes">
			<div id="divAgregaMae">
                <button type="submit" id="agregaMae" name="agregaMae" >AGREGAR</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript" src="../../asset/js/maestros.js"></script>


<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
function init() {
}

$(document).ready(function () {
	document.querySelector(".divAddFlsChqs").style.display = "none";
});

var accionRegresa = document.querySelector('.Btnregresar');
accionRegresa.addEventListener("click", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

var accionBtnInicio = document.getElementById('Btnnicio');
accionBtnInicio.addEventListener("click", function (e) {
    e.preventDefault();
    location.href = 'Inicio.php';
});

var numcarpetas;
$("#inputsFols").on("click", ".delete_Carpeta", function(e){
    e.preventDefault();
    numcarpetas = parseInt(document.getElementById("inputNumCarp").value);  
    //alert($(this).parent('div').parent('div').attr('id'));  
    $(this).parent('div').parent('div').remove();
    numcarpetas--;
    document.getElementById("inputNumCarp").value = numcarpetas;
});

$("#addCarp").on("click", function (e) {
    e.preventDefault();
    numcarpetas = parseInt($("#inputNumCarp").val()) + 1;
    $('#inputsFols').append(
        '<div><div id="divdetalleCarpeta"><div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta[]" name="numcarpeta[]"></div><div class="divFolIni"><input type="text" class="inputfolini" id="folinicial[]" name="folinicial[]"></div><div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal[]" name="folfinal[]"></div><div class="divObserv"><input type="text" class="inputobserv" id="observcarp[]" name="observcarp[]"></div><div class="divIconDelete"><a href="#" class="delete_Carpeta"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></a></div></div></div>'
    );
    $("#inputNumCarp").val(numcarpetas);    
});

$("#editRang").on("click", function (e) {
    e.preventDefault();

    document.getElementById('inpRangIniCarps').disabled = false;
    document.getElementById('inpRangFinCarps').disabled = false;
});

$("#updateCarp").on("click", function (e) {
    e.preventDefault() ;
    
    document.getElementById('inpRangIniCarps').disabled = true;
    document.getElementById('inpRangFinCarps').disabled = true;

    folioini = parseInt($("#inpRangIniCarps").val());
    foliofinal = parseInt($("#inpRangFinCarps").val());

    var pre_numcarpetas = ((foliofinal - folioini) + 1) / 30;

    $(".divdetalleCarpeta").remove();
    var numcarpeta=0;
    var folIni=$("#inpRangIniCarps").val();
    var folFin=$("#inpRangFinCarps").val();

    if ((pre_numcarpetas - Math.trunc(pre_numcarpetas)) > 0) {
        numcarpetas = Math.trunc(pre_numcarpetas) + 1;
    } else {
        numcarpetas = Math.trunc(pre_numcarpetas);
    }

    var folIniCarp = 0;
    var folFinCarp = 0;

    for (let index = 0; index < numcarpetas; index++) {
        numcarpeta=index + 1;
        
        if (index == 0) {
            folIniCarp = Math.trunc(folIni);
            folFinCarp = Math.trunc(folIni) + 29;
        } else {
            if ((folFinCarp + 30) >= folFin) {
                folIniCarp = folFinCarp + 1;
                folFinCarp = Math.trunc(folFin);
            } else {
                folIniCarp = folFinCarp + 1;
                folFinCarp = folIniCarp + 29;
            }
        }
        $('#inputsFols').append(
            '<div id="divsDetallesCarpetas"><div id="divdetalleCarpeta" class="divdetalleCarpeta"><div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta['+index+']" name="numcarpeta['+index+']" value="'+numcarpeta+'"></div><div class="divFolIni"><input type="text" class="inputfolini" id="folinicial['+index+']" name="folinicial['+index+']" value="00'+folIniCarp+'"></div><div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal['+index+']" name="folfinal['+index+']" value="00'+folFinCarp+'"></div><div class="divObserv"><input type="text" class="inputobserv" id="observcarp['+index+']" name="observcarp['+index+']"></div><div class="divIconDelete"><a href="#" class="delete_Carpeta"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></a></div></div></div>'
        );       
    }
        
});


$("#validChqs").on("click",function(e){
	e.preventDefault();
	
	var folioInicial = $("#inpFolIniCarps").val();
	var folioFinal = $("#inpFolFinCarps").val();

	$("#folsInexs").val("");
	$.post("../../controller/entregas.php?op=validExistFols",{folioI: folioInicial,folioF:folioFinal},function(data){
		resultadoValid = Object.values( JSON.parse(data));
        NumregsResult = resultadoValid.length;
		existentes = Object.values(resultadoValid[0]);
		inexistentes = Object.values(resultadoValid[1]);
				
		if (inexistentes.length > 0){
			Swal.fire(
				"FOLIOS FALTANTES",
				'Algunos folios no se encuentran en la BD, ingreselos'
			);	
			document.querySelector(".divAddFlsChqs").style.display = "block";
			$("#validExistFols").val("incorrecto");
			$("#folsInexs").val(inexistentes);
		}else{
			document.querySelector(".divAddFlsChqs").style.display = "none";
			Swal.fire(
				"FOLIOS COMPLETOS",
				'Todos los folios se encuentran en la BD, proceda a generar los listados'
			);
			$("#validExistFols").val("correcto");
		}
	});
	
});

$("#generateCarps").on("click",function(e){
	e.preventDefault();
	
	var a_numCarpetas = []; 
	var a_folsIniciales = [];
	var a_folsFinales = [];
	var a_obsrvsCarpetas = [];
	var integridadDats = true;
	
	//indexA = 0;
	formulario = document.getElementById('form_CarpetasArchivo');
	for (let i = 0; i < formulario.elements.length - 1; i++){
		elemento = formulario.elements[i].className;
		
        switch (elemento) {
			case 'inputnumcarp':
				if(formulario.elements[i].value != ""){
					a_numCarpetas.push(formulario.elements[i].value);
					integridadDats = true;
				}else{
					a_numCarpetas.push(formulario.elements[i].value);
					integridadDats = false;
				}
				break;
				
			case 'inputfolini':
				if(formulario.elements[i].value != ""){
					a_folsIniciales.push(formulario.elements[i].value);
					integridadDats = true;
				}else{
					a_folsIniciales.push(formulario.elements[i].value);
					integridadDats = false;
				}
				break;
				
			case 'inputfolfin':
				if(formulario.elements[i].value != ""){
					a_folsFinales.push(formulario.elements[i].value);
					integridadDats = true;
				}else{
					a_folsFinales.push(formulario.elements[i].value);
					integridadDats = false;
				}
				break;
				
			case 'inputobserv':
				a_obsrvsCarpetas.push(formulario.elements[i].value);
				break;	
			
			default:
                break;
		}
	}
	
	if(!integridadDats){
		Swal.fire(
            "ERROR EN LOS DATOS",
            'Algunos datos no son correctos, verificarlos'
        );
	}else{
		$.post("../../controller/entregas.php?op=agregarCarps",{identrega:$("#InputIdentrega").val(),numcarpetas:a_numCarpetas,folsini:a_folsIniciales,folsfin:a_folsFinales,obsrvscarp:a_obsrvsCarpetas},function(data){
			resultadoAddCarps = Object.values( JSON.parse(data));
			var validAdCarps = 0;
			for(let i = 0; i <= resultadoAddCarps.length - 1; i++){
				//alert(resultadoAddCarps[i]);
				if(resultadoAddCarps[i] == "A"){
					validAdCarps = validAdCarps + 1;					
				}
			}
			if(validAdCarps == $("#inputNumCarp").val()){
				Swal.fire(
					"CARPETAS GENERADAS CORRECTAMENTE",
					'Las carpetas se generaron correctamente'
				);
			}else{
				Swal.fire(
					"ERROR EN LOS DATOS",
					'surgio un error consulte con el admin del sistema'
				);
			}
		});
	}
});

$("#gnrtCrpts").on("click", function(e){
	e.preventDefault();
	
	if($("#validExistFols").val() == "correcto"){
		location.href = "../../views/home/listsCarpetas.php" + "?identr=" + $("#InputIdentrega").val();
	}else if($("#validExistFols").val() == "incorrecto"){
		Swal.fire(
			"¡¡¡   A T E N C I O N   !!!",
			'para generar los listados de carpetas todos los folios deben estar en la BD'
		);
	}else if($("#validExistFols").val() == ""){
		Swal.fire(
			"¡¡¡   A T E N C I O N   !!!",
			'para generar los listados de carpetas primero debe validar que todos los folios esten en la BD'
		);
	}
});


$("#addChq").on("click", function(e){
	e.preventDefault();
	
    $.post("../../controller/entregas.php?op=addFolInex",{folCheq: $("#numFolNew").val(),
														  nombreMae: $("#nombreMaeF").val(),
														  nomBenef: $("#nomBenNew").val(),
														  montBenef: $("#montBenNew").val(),
														  observCheque: $("#observNew").val(),
														  concepCheq: $("#opcMotvChq").val(),
														  estatCheq: $("#opcEstatCheq").val()											
															},function(data){
																resultadoAddFols = Object.values( JSON.parse(data));
																switch (resultadoAddFols[0]){
																	case 'existente':
																		Swal.fire(
																			"¡¡¡   ATENCION   !!!",
																			'el folio ingresado ya existe'
																		);
																		break;
																	
																	case 'agregado':
																		folCheq: $("#numFolNew").val('');
																		nombreMae: $("#nombreMaeF").val('');
																		nomBenef: $("#nomBenNew").val('');
																		montBenef: $("#montBenNew").val('');
																		observCheque: $("#observNew").val('');
																		concepCheq: $("#opcMotvChq").val('');
																		estatCheq: $("#opcEstatCheq").val('E');
																		Swal.fire(
																			"FOLIO AGREGADO CORRECTAMENTE",
																			'El folio se agrego correctamente'
																		);
																				
																		break;
																		
																	case 'fallo':
																		Swal.fire(
																			"ERROR EN LOS DATOS",
																			'surgio un error consulte con el admin del sistema'
																		);
																		break;
																}
															});	
});

init();
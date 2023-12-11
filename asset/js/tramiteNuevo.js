var motivo = "";
var causaRet = "";
var programfallec = "";
var clavemae;
var validVig="";
var modalRetiro="";
var motret="";

function init() {
    $("#edita_NomMae").on("submit",function(e){
        actNomMae(e);
    });
}



$(document).ready(function(){
    
    var fechaRecibido = fechaActual();

    document.getElementById('numentr').value = document.getElementById('InputNumEntr').value;
    document.getElementById('AnioEntr').value = document.getElementById('InputAnioEntr').value;
    document.getElementById("motivRetiro").style.display = "none";
    document.getElementById("montRetFondFall").style.display = "none";
    document.getElementById("DivTestBenefsMae").style.display = "none";
    document.getElementById("DivExcepciones").style.display = "none";
    document.getElementById("DivTpoDiferido").style.display = "none";
    document.getElementById("DivFechInicioJuicio").style.display = "none";
    document.getElementById("numfolioTEJI").style.display = "none";
    document.getElementById("DivfolioBenef").style.display = "none";
    document.getElementById('fechRecibido').value = fechaRecibido;
    $('#tituto_BasJubBajFall').html('BASE Y BAJA');
    $('#tituto_InptBasJub').html('Base: &nbsp');
    $('#tituto_InptBajFall').html('Baja: &nbsp');   
    
    $("#cerrarEditPSGS").click(function () {
        $("#editarPSGS").modal('hide');
    });

    $("#cerrarEditBenefs").click(function () {
        $("#editarBenefs").modal('hide');
    });
});


$("#OpcCauRetiro").change(function () {
    causaRet= $("#OpcCauRetiro").val();

    switch (causaRet) {
        case "FR":
            document.getElementById("motivRetiro").style.display = "block";
            document.getElementById("cspMaeBusq").disabled =  true;
            document.getElementById("EditaNombre").disabled =  true;
            document.getElementById("cveIMaeBusq").disabled =  true;
            document.getElementById("OpcRegSind").disabled =  true;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "block";
            document.getElementById("DivTestBenefsMae").style.display = "none";
            document.getElementById("nomSolic").disabled = true;
            $('#tituto_BasJubBajFall').html('BASE Y BAJA');
            $('#tituto_InptBasJub').html('Base: &nbsp');
            $('#tituto_InptBajFall').html('Baja: &nbsp'); 
            break;

        case "FF":
            document.getElementById("motivRetiro").style.display = "none";
            document.getElementById("cspMaeBusq").disabled =  true;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "none";
            document.getElementById("DivDictamen").style.display = "none";
            document.getElementById("DivTestBenefsMae").style.display = "block";
            document.getElementById("nomSolic").disabled = false;
            $('#tituto_BasJubBajFall').html('JUBILACION Y FALLECIMIENTO');
            $('#tituto_InptBasJub').html('Jubilacion:&nbsp ');
            $('#tituto_InptBajFall').html('Fallecim.: &nbsp'); 
            motivo="FFJ";
            break;
        
        case "FM":
            document.getElementById("motivRetiro").style.display = "none";
            document.getElementById("cspMaeBusq").disabled =  true;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "none";
            document.getElementById("DivDictamen").style.display = "none";
            document.getElementById("DivTestBenefsMae").style.display = "block";
            document.getElementById("nomSolic").disabled = false;
            $('#tituto_BasJubBajFall').html('JUBILACION Y FALLECIMIENTO');
            $('#tituto_InptBasJub').html('Jubilacion:&nbsp ');
            $('#tituto_InptBajFall').html('Fallecim.: &nbsp'); 
            motivo="FMJ";
            break;

        default:
            break;
    }
});

$("#OpcMotivRetiro").change(function () {
    motRet = $("#OpcMotivRetiro").val();

    switch (motRet) {
        case "I":
            motivo="FRI";
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "block";
            document.getElementById("DivTestBenefsMae").style.display = "none";
            document.getElementById("nomSolic").disabled = true;
            $('#tituto_BasJubBajFall').html('BASE Y BAJA');
            $('#tituto_InptBasJub').html('Base: &nbsp');
            $('#tituto_InptBajFall').html('Baja: &nbsp');   
            break;
        
        case "J":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "block";
            document.getElementById("DivTestBenefsMae").style.display = "none"; 
            document.getElementById("nomSolic").disabled = true; 
            $('#tituto_BasJubBajFall').html('BASE Y BAJA'); 
            $('#tituto_InptBasJub').html('Base: &nbsp');  
            $('#tituto_InptBajFall').html('Baja: &nbsp'); 
            motivo="FRJ";    
            break;
        
        case "R":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "block";
            document.getElementById("DivTestBenefsMae").style.display = "none"; 
            document.getElementById("nomSolic").disabled = true; 
            $('#tituto_BasJubBajFall').html('BASE Y BAJA'); 
            $('#tituto_InptBasJub').html('Base: &nbsp');  
            $('#tituto_InptBajFall').html('Baja: &nbsp');  
            motivo="FRR";   
            break;
            
        case "D":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "block";
            document.getElementById("DivTestBenefsMae").style.display = "none"; 
            document.getElementById("nomSolic").disabled = true; 
            $('#tituto_BasJubBajFall').html('BASE Y BAJA'); 
            $('#tituto_InptBasJub').html('Base: &nbsp');  
            $('#tituto_InptBajFall').html('Baja: &nbsp');  
            motivo="FRD";   
            break;

        case "F":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("tipTramNE").disabled = false;
            document.getElementById("DivPsgs").style.display =  "block";
            document.getElementById("DivDictamen").style.display = "none";
            document.getElementById("DivTestBenefsMae").style.display = "block";
            document.getElementById("nomSolic").disabled = false; 
            $('#tituto_BasJubBajFall').html('JUBILACION Y FALLECIMIENTO');
            $('#tituto_InptBasJub').html('Base: &nbsp');
            $('#tituto_InptBajFall').html('Fallecim.: &nbsp'); 
            motivo="FRF";
            break;
    
        default:
            break;
    }        
});

$(".CSPMae").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$(".cveissemym").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$('#CURPMae').keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 65 || key > 90)  && (key < 97 || key > 122) && (key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$('#CURPMae').change(function () {
    document.getElementById('RFCMae').value = document.getElementById('CURPMae').value.substr(0,10).toUpperCase();
    if ($("#CURPMae").val().length < 18 ) {
        Swal.fire(
            'LA CLAVE CURP ES INCORRECTA',
            'deben ser 18 caracteres'
        );
        $("#CURPMae").focus();
        document.getElementById('CURPMae').style.border =  ".1em red solid";
    }else{
        document.getElementById('CURPMae').style.border =  ".1em black solid";
    }
});

$('#RFCMae').keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 65 || key > 90)  && (key < 97 || key > 122) && (key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$('#RFCMae').change(function () {
    if ($("#RFCMae").val().length < 10 || $("#RFCMae").val().length > 13) {
        Swal.fire(
            'LA CLAVE RFC ES INCORRECTA',
            'deben ser 10 o 13 caracteres'
        );
        $("#RFCMae").focus();
        document.getElementById('RFCMae').style.border =  ".1em red solid";
    }else{
        document.getElementById('RFCMae').style.border =  ".1em black solid";
    }
});

$(".TelsMae").keydown(function (event){
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$("#TelPartiMae").change(function () {
    teleParticular = $("#TelPartiMae").val();

    if (teleParticular.length > 10 || teleParticular.length < 10) {
        Swal.fire(
            'EL NUMERO DE TELEFONO PARTICULAR ES INCORRECTO',
            'deben ser 10 digitos'
        )
        $("#TelPartiMae").focus();
        document.getElementById('TelPartiMae').style.border =  ".1em red solid";
    }else{
        document.getElementById('TelPartiMae').style.border =  ".1em black solid";
    }
});

$("#TelCelMae").change(function () {
    teleCelular = $("#TelCelMae").val();

    if (teleCelular.length > 10 || teleCelular.length < 10) {
        Swal.fire(
            'EL NUMERO DE TELEFONO CELULAR ES INCORRECTO',
            'deben ser 10 digitos'
        )
        $("#TelCelMae").focus();
        document.getElementById('TelCelMae').style.border =  ".1em red solid";
    } else {
        document.getElementById('TelCelMae').style.border =  ".1em black solid";
    }
});

$("#cveIMaeBusq").change(function() {
    claveisemym = document.getElementById("cveIMaeBusq").value;

    if (claveisemym.length < 3 ) {
        Swal.fire(
            'LA CLAVE DE ISSEMYM ES INCORRECTA',
            'debe tener un maximo de 6 digitos y minimo 3 digitos'
        )
    }else{
        if (causaRet == "FF" || causaRet == "FM") {
            clavemae = $("#cveIMaeBusq").val();
            if (claveisemym.length <= 7 && $("#cveIMaeBusq").val() != ""){
                $.post("../../controller/maestro.php?op=buscarJub",{claveisemym:claveisemym},function(dataJ){
                    if(jQuery.isEmptyObject(dataJ)){
                        Swal.fire(
                            'LA CLAVE ES INCORRECTA',
                            'no esta afiliado al programa de MUTUALIDAD'
                        )
                        document.getElementById("cspMaeBusq").value="";
                        document.getElementById("cveIMaeBusq").value="";
                        document.getElementById("apePatMae").value="";
                        document.getElementById("apeMatMae").value="";
                        document.getElementById("nombreMae").value="";
                        document.getElementById("estLaboral").value="";
                        document.getElementById("nomComplMae").value="";
                        document.getElementById("nomSolic").value="";
                        document.getElementById("RFCMae").value="";
                        document.getElementById("TelPartiMae").value="";
                        document.getElementById("TelCelMae").value="";
                    }else{
                        dataJ = JSON.parse(dataJ);
                        var motivoC = dataJ.motivo;
                        var estatusmae = dataJ.estatusjub;
                        programfallec = dataJ.programafallec;
                        if (motivoC == "nuevo" && estatusmae == "A") {
                            var estatusMae = "JUBILADO " + programfallec;
                            $('#apePatMae').val(dataJ.apepatmae);
                            $('#apeMatMae').val(dataJ.apematmae);
                            $('#nombreMae').val(dataJ.nommae);
                            $('#estLaboral').val(estatusMae);
                            $('#nomComplMae').val(dataJ.nomcommae);
                            $('#CURPMae').val(dataJ.curpmae);
                            $('#RFCMae').val(dataJ.rfcmae);
                            $('#fechBaseMae').val(dataJ.fechbajamae);
                            $('#inputProgramFall').val(dataJ.programafallec);
        
                            document.getElementById("TelPartiMae").disabled =  false;
                            document.getElementById("TelCelMae").disabled =  false;
                            document.getElementById("fechRecibido").disabled =  false;
                            document.getElementById("fechBaseMae").disabled =  false;
                            document.getElementById("fechBajaMae").disabled =  false;
                            document.getElementById("OpcTestamento").disabled = false;
                            document.getElementById('CURPMae').disabled = false;
                            document.getElementById('RFCMae').disabled = false;
                        } else if (motivoC == "existente" || estatusmae == "F") {
                            if (dataJ.motivo == "existente") {
                                Swal.fire(
                                    'Ya se encuentra un tramite registrado con la misma clave, por motivo de Fallecimiento' + dataJ.fe>
                                    'Verifique su clave'
                                ).then((result)=>{
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                });
                            }
                        } else if (estatusmae == "P") {
                            Swal.fire(
                                'El maestro aun no esta activo en el programa de mutualidad'
                            ).then((result)=>{
                                let pagAnterior = document.referrer;
                                if (pagAnterior.indexOf(window.location.host) !== -1) {
                                    window.history.back();
                                }
                            });
                        } else if (motivoC == "nuevo" || estatusmae == "F") {
                            Swal.fire(
                                'No se puede dar de alta un tramite con esta clave que tiene estatus de Fallecido'
                            ).then((result)=>{
                                let pagAnterior = document.referrer;
                                if (pagAnterior.indexOf(window.location.host) !== -1) {
                                    window.history.back();
                                }
                            });
                        }
                    }
                });
            }
        }
    }
});

$("#cspMaeBusq").change(function () {
    clavemae = $("#cspMaeBusq").val();
    
    if (clavemae.length == 9) {
        $.post("../../controller/maestro.php?op=buscar",{clavemae:clavemae},function(data){ 
            if(jQuery.isEmptyObject(data)){
                Swal.fire(
                    'LA CLAVE ES INCORRECTA',
                    'no esta afiliado al SMSEM'
                )
                document.getElementById("cspMaeBusq").value="";
                document.getElementById("cveIMaeBusq").value="";
                document.getElementById("apePatMae").value="";
                document.getElementById("apeMatMae").value="";
                document.getElementById("nombreMae").value="";
                document.getElementById("estLaboral").value="";
                document.getElementById("nomComplMae").value="";
                document.getElementById("nomSolic").value="";
                document.getElementById("RFCMae").value="";
                document.getElementById("TelPartiMae").value="";
                document.getElementById("TelCelMae").value="";
            }else{
                data = JSON.parse(data);
                if(data.motivo == "existente" || data.estatlabmae != "A"){
                    if (data.motivo == "existente") {
                        Swal.fire(
                            'Ya se encuentra un tramite registrado con la misma clave, por motivo de: ' + data.motvret +  ' el ' + data.fechentrega,
                            'Verifique su clave'                            
                        ).then((result)=>{
                            let pagAnterior = document.referrer;
                            if (pagAnterior.indexOf(window.location.host) !== -1) {
                                window.history.back();
                            }
                        });
                    }else if (data.estatlabmae != "A" && data.motivo == "inconsistencia") {
                        Swal.fire(
                            'El maestro (a) no es activo verifique el expediente y la clave'                            
                        ).then((result)=>{
                            let pagAnterior = document.referrer;
                            if (pagAnterior.indexOf(window.location.host) !== -1) {
                                window.history.back();
                            }
                        });
                    }
                    
                }else if(data.motivo == "nuevo" && data.estatlabmae == "A"){
                    estatusMae = "ACTIVO";
                    $('#cspMaeBusq').val(data.csp);
                    $('#cveIMaeBusq').val(data.cveissemym);
                    $('#apePatMae').val(data.apepatmae);
                    $('#apeMatMae').val(data.apematmae);
                    $('#nombreMae').val(data.nommae);
                    $('#estLaboral').val(estatusMae);
                    $('#nomComplMae').val(data.nomcommae);
                    $('#nomSolic').val(data.nomcommae); 
                    $('#CURPMae').val(data.curpmae);
                    $('#RFCMae').val(data.rfcmae);

                    document.getElementById("TelPartiMae").disabled =  false;
                    document.getElementById("TelCelMae").disabled =  false;
                    document.getElementById("folioDictamen").disabled =  false;
                    document.getElementById("fechRecibido").disabled =  false;
                    document.getElementById("fechDictamen").disabled =  false;
                    document.getElementById("fechBaseMae").disabled =  false;
                    document.getElementById("fechBajaMae").disabled =  false;
                    document.getElementById("editaPSGS").disabled =  false;
                    document.getElementById("sinPSGS").disabled = false;
                    document.getElementById('CURPMae').disabled = false;
                    document.getElementById('RFCMae').disabled = false;

                    if (motivo == "FRF") {
                        document.getElementById("OpcTestamento").disabled =  false;
                        document.getElementById("fechCTJuicio").disabled =  false;
                        document.getElementById("fechIniJuicio").disabled = false;
                        
                    }
                }
            }
        });
    } else {
        Swal.fire(
            'La clave es incorrecta, debe tener 9 digitos',
            'Verifique y corrija la clave!!!'
        );
        document.getElementById("cspMaeBusq").value = "";
        document.getElementById("cveIMaeBusq").value = "";
        document.getElementById("apePatMae").value = "";
        document.getElementById("apeMatMae").value = "";
        document.getElementById("nombreMae").value = "";
        document.getElementById("estLaboral").value = "";
        document.getElementById("nomComplMae").value = "";
        document.getElementById("nomSolic").value = "";
        document.getElementById("RFCMae").value = "";
        document.getElementById("TelPartiMae").value = "";
        document.getElementById("TelCelMae").value = "";
    }
});

function validacurpBenef(inputCurpBenef){
    if (inputCurpBenef.value.length != 18 ) {
        Swal.fire(
            'DATO INVALIDO',
            'Proporcione la CURP correcta'
        );
    }
}

function validaNomBenef(inputNomBenef) {
    if (inputNomBenef.value.length == "" && inputNomBenef.value.length < 10 ) {
        Swal.fire(
            'DATO INVALIDO',
            'Proporcione un NOMBRE correcto'
        );
    }
}

const accionEditaNom = document.querySelector("#EditaNombre");
accionEditaNom.addEventListener("click", function (evento){
    evento.preventDefault();
    $('#modal-title').html('Modificando nombre');
    $.post("../../controller/maestro.php?op=mostrarNom",{clavemae:clavemae},function(data){       
        data = JSON.parse(data);
		$('#cvemae').val(data.csp);		
        $('#apepatModif').val(data.apepatmae);
        $('#apematModif').val(data.apematmae);
        $('#nommaeModif').val(data.nommae);
        $('#nomcomModif').val(data.nomcommae);
    });
    $('#editarNomMae').modal('show');
	
});

function actNomMae(e){
    e.preventDefault();
    nomComMae = $('#apepatModif').val() + " " + $('#apematModif').val() + " " + $('#nommaeModif').val();
    $('#nomcomModif').val(nomComMae);
	
	if(motivo == "FMJ" || motivo == "FFJ"){
		$.post("../../controller/maestro.php?op=actNomMae",{apepatModif:$('#apepatModif').val(),apematModif:$('#apematModif').val(),nommaeModif:$('#nommaeModif').val(),nomcomModif:$('#nomcomModif').val(),cvemae:$('#cvemae').val()},function(data){ 
			resultadoUpd = Object.values( JSON.parse(data));
			if(resultadoUpd[0] == "actualizado"){
				swal.fire(
					'Modificacion!',
					'Los Datos se actualizaron correctamente!!!',
					'success'
				);
				$('#edita_NomMae')[0].reset();
				$("#editarNomMae").modal('hide');
			}else{
				swal.fire(
					'ERROR!!!',
					'Surgio un error consultelo con el administrador dle sistema!!!',
					'success'
				);
			}
		});
		clavemae = $("#cveIMaeBusq").val();
		$.post("../../controller/maestro.php?op=buscarJub",{claveisemym:clavemae},function(data){ 
			data = JSON.parse(data);
			$('#apePatMae').val(data.apepatmae);
			$('#apeMatMae').val(data.apematmae);
			$('#nombreMae').val(data.nommae);
			$('#nomComplMae').val(data.nomcommae);
			$('#nomSolic').val(data.nomcommae); 
		});
	}else{
		var formData = new FormData($("#edita_NomMae")[0]);
		clavemae = $("#cveIMaeBusq").val();
		$.post("../../controller/maestro.php?op=actNomMae",{apepatModif:$('#apepatModif').val(),apematModif:$('#apematModif').val(),nommaeModif:$('#nommaeModif').val(),nomcomModif:$('#nomcomModif').val(),cvemae:$('#cspMaeBusq').val()},function(data){ 
			resultadoUpd = Object.values( JSON.parse(data));
			if(resultadoUpd[0] == "actualizado"){
				swal.fire(
					'Modificacion!',
					'Los Datos se actualizaron correctamente!!!',
					'success'
				);
				$('#edita_NomMae')[0].reset();
				$("#editarNomMae").modal('hide');
			}else{
				swal.fire(
					'ERROR!!!',
					'Surgio un error consultelo con el administrador dle sistema!!!',
					'success'
				);
			}
		});
		clavemae = $("#cvemae").val();
		$.post("../../controller/maestro.php?op=buscar",{clavemae:clavemae},function(data){ 
			data = JSON.parse(data);
			$('#apePatMae').val(data.apepatmae);
			$('#apeMatMae').val(data.apematmae);
			$('#nombreMae').val(data.nommae);
			$('#estLaboral').val(estatusMae);
			$('#nomComplMae').val(data.nomcommae);
			$('#nomSolic').val(data.nomcommae); 
		});	
	}
}

const accionFechBaja = document.querySelector("#fechBajaMae");
accionFechBaja.addEventListener("blur", function (evento) {
    evento.preventDefault();


    document.getElementById("fechBajaMae").style.border =  ".1em black solid";
    if (motivo == "FRF" || motivo == "FI" || motivo == "FRR" || motivo == "FRD") {
        document.getElementById("editaBefens").disabled = false;
    } 
    /*if (parseInt(document.getElementById('fechBajaMae').value.split("-")[0]) < 2022 || parseInt(document.getElementById('fechBajaMae').value.split("-")[0]) > 2024) {
        document.getElementById("fechBajaMae").style.border =  ".1em red solid";
        Swal.fire(
            'ERROR',
            'el año de la fecha no es correcto!!!'
        );
    }*/
});

const accionFechBase = document.querySelector("#fechBaseMae");
accionFechBase.addEventListener("blur", function (evento) {
    evento.preventDefault();
    if (parseInt(document.getElementById('fechBaseMae').value.split("-")[0]) < 1930 || parseInt(document.getElementById('fechBaseMae').value.split("-")[0]) > 2024) {
        document.getElementById("fechBaseMae").style.border =  ".1em red solid";
        Swal.fire(
            'ERROR',
            'el año de la fecha no es correcto!!!'
        );
    }else{
        document.getElementById("fechBaseMae").style.border =  ".1em black solid";
    }
});

var tipTramite = '0';
var checkBoxTipTram = document.getElementById('tipTramNE');
checkBoxTipTram.addEventListener("change", tramExtra, false);
function tramExtra() {
    var checkedTE = checkBoxTipTram.checked;
    if (checkedTE) {
        if (motivo == 'FFJ' || motivo == 'FMJ' || motivo == 'FRF') {
            document.getElementById("numfolioTEJI").style.display = "none";
            document.getElementById("DivfolioBenef").style.display = "block";
        } else {
            document.getElementById("numfolioTEJI").style.display = "block";
        }
        tipTramite = '1';
    } else {
        document.getElementById("numfolioTEJI").style.display = "none";
        document.getElementById("DivfolioBenef").style.display = "none";
        tipTramite = '0';
    }
}

var checkboxPSGS = document.getElementById('sinPSGS');
checkboxPSGS.addEventListener("change", validaCheckPSGS, false);
function validaCheckPSGS(){
    var checked = checkboxPSGS.checked;
    if(checked){
        document.getElementById("editaPSGS").disabled =  true;
        document.getElementById('numPsgs').value = 0;
        document.getElementById('diasPsgs').value = 0;
        document.getElementById('fechsIniPSGS').value = "{}";
        document.getElementById('fechsFinPSGS').value = "{}";
        document.getElementById("calcDiasAnios").disabled = false;
    }else{
        document.getElementById("editaPSGS").disabled =  false;
        document.getElementById("calcDiasAnios").disabled = true;
    }
}

var numAgrPSGS=0;
const psgs_max = 30;
var contPSGS=0;
const accionPSGS = document.querySelector("#editaPSGS");
accionPSGS.addEventListener("click", function (evento){
    evento.preventDefault();
    if (numAgrPSGS == 0) {
        $('#tituto_mod_psgs').html('Agregar P.S.G.S');
        $('#edita_PSGS')[0].reset();
        document.getElementById('numsPSGS').value = contPSGS;
        $('#editarPSGS').modal('show');
        document.getElementById("calcDiasAnios").disabled = false;
    } else {
        $('#tituto_mod_psgs').html('Agregar P.S.G.S');
        $('#edita_PSGS')[0].reset();
        document.getElementById('numsPSGS').value = contPSGS;
        document.getElementById("calcDiasAnios").disabled = false;
        $('#editarPSGS').modal('show');
        var fechaIn = document.getElementById('fechsIniPSGS').value;
        var fechaFn = document.getElementById('fechsFinPSGS').value;
        var fechasInicio = JSON.parse(fechaIn);
        var fechasFinal = JSON.parse(fechaFn);
        var numerofechas = Object.keys(fechasInicio).length;

        for (i = 0; i < numerofechas; i++) {
            document.getElementById('fechaIni[' + i + ']').value = fechasInicio[i];
            document.getElementById('fechaFin[' + i + ']').value = fechasFinal[i];
        }
    }
});

$("#edita_PSGS").on("submit",function(evento){
    evento.preventDefault();
    var diasActivo=0;
    var formDataPSGS = new FormData($("#edita_PSGS")[0]);
    $.ajax({
        url: '../../controller/tramites.php?op=diasPSGS',
        type: "POST",
        data: formDataPSGS,
        contentType: false,
        processData: false,
        success: function(datos){
            numAgrPSGS++;
            $('#edita_PSGS')[0].reset();
            $("#editarPSGS").modal('hide');
            if (datos == 0 && document.getElementById('DiasServOriginal').value > 0) {
                diasActivo = parseInt(document.getElementById('diasServMae').value) + parseInt(document.getElementById('diasPsgs').value);
                document.getElementById('numPsgs').value = 0;
                document.getElementById('diasPsgs').value = 0;
                document.getElementById('diasServMae').value = diasActivo;
                document.getElementById('aniosServMae').value = Math.trunc(diasActivo/365);  
                document.getElementById('fechsIniPSGS').value = "{}";
                document.getElementById('fechsFinPSGS').value = "{}";
            } else {
                data = JSON.parse(datos);
                $('#numPsgs').val(data.numPSGS);
                if (document.getElementById('diasServMae').value > 0) {
                    if (document.getElementById('diasServMae').value > 0) {
                        diasActivo = document.getElementById('DiasServOriginal').value - data.diasPSGS;
                    } else {
                        diasActivo = document.getElementById('diasServMae').value - data.diasPSGS;
                    }
                }
                document.getElementById('diasServMae').value = diasActivo;
                document.getElementById('diasPsgs').value = data.diasPSGS;
                document.getElementById('aniosServMae').value = Math.trunc(diasActivo/365);  
                document.getElementById('fechsIniPSGS').value = JSON.stringify(data.fechIni);
                document.getElementById('fechsFinPSGS').value = JSON.stringify(data.fechFin);                
            }
        }
    });   
});

$("#addPSGS").click(function (e) {
    e.preventDefault();
    if (contPSGS < psgs_max) {
        $('#DivFechsPSGSIni').append(
            '<div><input type="date" name="fechaIni['+ contPSGS +']" id="fechaIni['+ contPSGS +']"><a href="#" class="delete_fechaI"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar fecha" height="15" width="20"></a></input></div>'
        );
        $('#DivFechsPSGSFin').append(
            '<div><input type="date" name="fechaFin['+ contPSGS +']"  id="fechaFin['+ contPSGS +']"><a href="#" class="delete_fechaF"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar fecha" height="15" width="20"></a></input></div>'
        );
        contPSGS++
    }
    document.getElementById('numsPSGS').value = contPSGS;
});

$('#DivFechsPSGS').on("click",".delete_fechaI",function(e){
    e.preventDefault();
    $(this).parent('div').remove();
    contPSGS = contPSGS - 0.5;
    document.getElementById('numsPSGS').value = contPSGS;
});

$('#DivFechsPSGS').on("click",".delete_fechaF",function(e){
    e.preventDefault();
    $(this).parent('div').remove();
    contPSGS = contPSGS - 0.5;
    document.getElementById('numsPSGS').value = contPSGS;
});

const accionCalculadora = document.querySelector('#calcDiasAnios');
accionCalculadora.addEventListener("click", function (evento) {
    evento.preventDefault();
    var valorValid = 0;

    var a_fechs = [
        {fecha:"Recibido", nomvar:"fechRecibido", valorF:document.getElementById('fechRecibido').value},
        {fecha:"Base", nomvar:"fechBaseMae", valorF:document.getElementById('fechBaseMae').value},
        {fecha:"Baja", nomvar:"fechBajaMae", valorF:document.getElementById('fechBajaMae').value}
    ]
      
    a_fechs.forEach(element => {
        if (isNaN(Date.parse(element["valorF"])) || element["valorF"] == "") {
            a_fechs.push({validF:true,descerror:"La fecha de " + element["fecha"] + " no es valida"});
            document.getElementById(element["nomvar"]).style.border =  ".1em red solid";
            valorValid = 0;
            Swal.fire(
                "La fecha de " + element["fecha"] + " no es valida",
                'por favor verifiquela'
            );
        } else {
            a_fechs.push({validF:true,descerror:""});
            if (parseInt(element["valorF"].split("-")[0]) > 1930 && parseInt(element["valorF"].split("-")[0]) < 2024) {
                a_fechs.push({validA:true});
                document.getElementById(element["nomvar"]).style.border =  ".1em black solid";
                valorValid = valorValid + 1;
            }else{
                a_fechs.push({validA:false});
                document.getElementById(element["nomvar"]).style.border =  ".1em red solid";
                valorValid = 0;
                Swal.fire(
                    "El año de la fecha " + element["fecha"] + " no es valido",
                    'por favor verifiquela'
                );
            }
        }
    });
    validaFechas(valorValid, a_fechs);
    $("#ModoRetiro").val("C");
    modalRetiro = "C";
});

function validaFechas(valorValid, a_fechs) {
    NumPersgs = $("#numPsgs").val();
    diasInacPsgs = $("#diasPsgs").val();
    if (causaRet=="FR") {
        switch (motivo) {
            case "FRI":
            case "FRJ":
            case "FRR":
            case "FRD":
                $.post("../../controller/tramites.php?op=validaFechs",{clavemae:clavemae,motret:motivo,diasInacPsgs:diasInacPsgs,NumPersgs:NumPersgs,fechRecibido:a_fechs[0]["valorF"],fechBaseMae:a_fechs[1]["valorF"],fechBajaMae:a_fechs[2]["valorF"]},function(data){
                    data = JSON.parse(data);
                    resultValid = data.descResult;
                    switch (resultValid) {
                        case 'vigenciaVal':
                            diasServicio = data.diasServ;
                            aniosServicio = Math.trunc(diasServicio/365);
                            if (document.getElementById('numPsgs').value > 0 && document.getElementById('diasPsgs').value !== 0){
                                document.getElementById('DiasServOriginal').value = diasServicio;
                                document.getElementById('diasServMae').value = diasServicio;
                                document.getElementById('aniosServMae').value = aniosServicio;
                                document.getElementById("ModoRetiro").disabled =  false;
                            }else{
                                document.getElementById('numPsgs').value = 0;
                                document.getElementById('diasPsgs').value = 0;
                                document.getElementById('DiasServOriginal').value = diasServicio;
                                document.getElementById('diasServMae').value = diasServicio;
                                document.getElementById('aniosServMae').value = aniosServicio;
                                document.getElementById("ModoRetiro").disabled =  false;
                            }   
                            var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
                            $.post("../../controller/tramites.php?op=obtenRetiro",{aniosserv:aniosserv},function(data){       
                                data = JSON.parse(data);
                                var montInteger = parseInt(data.montret);
                                var montRetString = data.montret.toString();
                                var indxPoint = montRetString.indexOf(".");
                            
                                $('#montRet').val(montInteger + "." + montRetString.slice((indxPoint + 1), (indxPoint + 3)));
                                document.getElementById("agregaTramite").disabled =  false;
                                montoRetiro = parseFloat(document.getElementById('montRet').value);
                                document.getElementById("monRetEntr").value = montoRetiro;
                            });                 
                            break;
                        
                        case 'noprocede':
                            swal.fire({
                                title:'TRAMITE NO PROCEDENTE',
                                text:"La fecha del tramite excede la vigencia del retiro no solicito prorroga",
                                showCancelButton: true,
                                confirmButtonText:'OK',
                                timer:15000
                            }).then((result) => {
                                if (result.isConfirmed){
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                }
                            }); 
                            break;    
                        
                        case 'errorFecha':
                            notifError = data.diasServ;    
                            Swal.fire(
                                notifError,
                                'por favor verifique las fechas'
                            );
                            break;
            
                        default:
                            break;
                    }
                });
                break;
            
            case "FRF":
                var fechIniJuic = 0;
                if ($("#fechIniJuicio").is(':visible')) {
                    fechIniJuic = 1;
                } 
                $.post("../../controller/tramites.php?op=validaFechsFA",{clavemae:clavemae,motret:motivo,diasInacPsgs:diasInacPsgs,NumPersgs:NumPersgs,fechRecibido:a_fechs[0]["valorF"],fechBaseMae:a_fechs[1]["valorF"],fechBajaMae:a_fechs[2]["valorF"],fecInicioJuic:fechIniJuic,fechaIniJuic:document.getElementById('fechIniJuicio').value,tiptest:document.getElementById('OpcTestamento').value,fechJuiCTL:document.getElementById('fechCTJuicio').value},function(data){
                    data = JSON.parse(data);
                    resultValid = data.descResult;
                    switch (resultValid) {
                        case 'vigenciaVal':
                            diasServicio = data.diasServ;
                            aniosServicio = Math.trunc(diasServicio/365);
                            if (document.getElementById('numPsgs').value > 0 && document.getElementById('diasPsgs').value !== 0){
                                document.getElementById('DiasServOriginal').value = diasServicio;
                                document.getElementById('diasServMae').value = diasServicio;
                                document.getElementById('aniosServMae').value = aniosServicio;
                            }else{
                                document.getElementById('numPsgs').value = 0;
                                document.getElementById('diasPsgs').value = 0;
                                document.getElementById('DiasServOriginal').value = diasServicio;
                                document.getElementById('diasServMae').value = diasServicio;
                                document.getElementById('aniosServMae').value = aniosServicio;
                            }   
                            var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
                            $.post("../../controller/tramites.php?op=obtenRetiro",{aniosserv:aniosserv},function(data){       
                                data = JSON.parse(data);
                                var montInteger = parseInt(data.montret);
                                //var montFloat = parseInt((data.montret % 1)*100);
                                var montRetString = data.montret.toString();
                                var indxPoint = montRetString.indexOf(".");
    
                                $('#montRet').val(montInteger + "." + montRetString.slice((indxPoint + 1), (indxPoint + 3)));

                                document.getElementById("agregaTramite").disabled =  false;
                                document.getElementById("monRetEntr").value =  $('#montRet').val();
                                if (motivo == "FRF" || motivo == "FFJ" || motivo == "FMJ") {
                                    document.getElementById("ModoRetiro").disabled =  true;
                                    document.getElementById("ModoRetiro").value = "C";
                                    modalRetiro = "C";
                                    montoRetiro = parseFloat(document.getElementById('montRet').value);
                                    document.getElementById('monRetEntr').value = montoRetiro;
                                }
                            });       
                            break;
                        
                        case 'vigenciaCad':
                            diasServicio = data.diasServ
                            aniosServicio = Math.trunc(diasServicio/365);
                            
                            swal.fire({
                                title:'TRAMITE NO PROCEDENTE',
                                text:"La fecha del tramite excede la vigencia del retiro. Tiene oficio o tarjeta de soporte de autorizacion",
                                showCancelButton: true,
                                confirmButtonText:'Si',
                                cancelButtonText:'No',
                                timer:15000
                            }).then((result) => {
                                if (result.isConfirmed){
                                    var divOfTr = document.getElementById("DivExcepciones");
                                    divOfTr.style.display = "block";
                                    document.getElementById("ModoRetiro").disabled =  false;
                                    document.getElementById("calcDiasAnios").disabled = true;
        
                                    document.getElementById('DiasServOriginal').value = diasServicio;
                                    document.getElementById('diasServMae').value = diasServicio;
                                    document.getElementById('aniosServMae').value = aniosServicio;
        
                                    var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
                                    $.post("../../controller/tramites.php?op=obtenRetiro",{aniosserv:aniosserv},function(data){       
                                        data = JSON.parse(data);
                                        var montInteger = parseInt(data.montret);
                                        //var montFloat = parseInt((data.montret % 1)*100);

                                        var montRetString = data.montret.toString();
                                        var indxPoint = montRetString.indexOf(".");
    
                                        $('#montRet').val(montInteger + "." + montRetString.slice((indxPoint + 1), (indxPoint + 3)));

                                        document.getElementById("agregaTramite").disabled =  false;
                                        document.getElementById("monRetEntr").value =  $('#montRet').val();
                                        if (motret == "FRF" || motret == "FFJ" || motret == "FMJ") {
                                            document.getElementById("ModoRetiro").disabled =  true;
                                            document.getElementById("ModoRetiro").value = "C";
                                            modalRetiro = "C";
                                            montoRetiro = parseFloat(document.getElementById('montRet').value);
                                            document.getElementById('monRetEntr').value = montoRetiro;
                                        }
                                    });  
                                }else{
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                }
                            });
                            break;
        
                        case 'errorFecha':
                            notifError = data.diasServ;    
                            Swal.fire(
                                notifError,
                                'por favor verifique las fechas'
                            );
                            break;
        
                        default:
                            break;
                    }
                });
                break;
            
            default:
                break;
        }
    } else if (causaRet=="FF" || causaRet=="FM"){
        $.post("../../controller/tramites.php?op=validaFechsFJ",{clavemae:clavemae,motret:motivo,fechRecibido:a_fechs[0]["valorF"],fechBaseMae:a_fechs[1]["valorF"],fechBajaMae:a_fechs[2]["valorF"],opTest:$("#OpcTestamento").val(),fechCTJuic:$("#fechCTJuicio").val(),fechIniJuic:document.getElementById("fechIniJuicio").value},function(data){
            data = JSON.parse(data);
            resultValid = data.descResult;
            switch (resultValid) {
                case 'vigenciaVal':
                    diasServicio = data.diasJub;
                    aniosServicio = data.aniosJub;
                    document.getElementById('DiasServOriginal').value = diasServicio;
                    document.getElementById('diasServMae').value = diasServicio;
                    document.getElementById('aniosServMae').value = aniosServicio;
                    
                    var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
                    var programaF = $('#inputProgramFall').val();
                    if (programaF==="M") {
                        $.post("../../controller/tramites.php?op=obtenRetiroJub",{aniosserv:aniosserv,programF:"M"},function(data){       
                            data = JSON.parse(data);
                            var montInteger = parseInt(data.montret);
                            var montFloat = parseInt((data.montret % 1)*100);
                            document.getElementById("agregaTramite").disabled =  false;
                            $('#montRet').val(montInteger + "." + montFloat);
                            document.getElementById("monRetEntr").value =  $('#montRet').val();
                            if (motret == "FRF" || motret == "FFJ" || motret == "FMJ") {
                                document.getElementById("ModoRetiro").disabled =  true;
                                document.getElementById("ModoRetiro").value = "C";
                                modalRetiro = "C";
                                montoRetiro = parseFloat(document.getElementById('montRet').value);
                                document.getElementById('monRetEntr').value = montoRetiro;
                            }
                        });  
                    } else if (programaF==="FF") {
                        $.post("../../controller/tramites.php?op=obtenRetiroJub",{aniosserv:aniosserv,programF:"FF"},function(data){       
                            data = JSON.parse(data);
                            var montInteger = parseInt(data.montret);
                            var montFloat = parseInt((data.montret % 1)*100);
                            document.getElementById("agregaTramite").disabled =  false;
                            $('#montRet').val(montInteger + "." + montFloat);
                            document.getElementById("monRetEntr").value =  $('#montRet').val();
                            if (motret == "FRF" || motret == "FFJ" || motret == "FMJ") {
                                document.getElementById("ModoRetiro").disabled =  true;
                                document.getElementById("ModoRetiro").value = "C";
                                modalRetiro = "C";
                                montoRetiro = parseFloat(document.getElementById('montRet').value);
                                document.getElementById('monRetEntr').value = montoRetiro;
                            }
                        });  
                    }
                         
                    break;
                
                case 'vigenciaCad':
                    diasServicio = data.diasJub
                    aniosServicio = Math.trunc(diasServicio/365);
                    
                    swal.fire({
                        title:'TRAMITE NO PROCEDENTE',
                        text:"La fecha del tramite excede la vigencia del retiro. Tiene oficio o tarjeta de soporte de autorizacion",
                        showCancelButton: true,
                        confirmButtonText:'Si',
                        cancelButtonText:'No',
                        timer:15000
                    }).then((result) => {
                        if (result.isConfirmed){
                            var divOfTr = document.getElementById("DivExcepciones");
                            divOfTr.style.display = "block";
                            document.getElementById("ModoRetiro").disabled =  false;
                            document.getElementById("calcDiasAnios").disabled = true;

                            document.getElementById('DiasServOriginal').value = diasServicio;
                            document.getElementById('diasServMae').value = diasServicio;
                            document.getElementById('aniosServMae').value = aniosServicio;

                            var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
                            var programaF = $('#inputProgramFall').val();
                            if (programaF==="M") {
                                $.post("../../controller/tramites.php?op=obtenRetiroJub",{aniosserv:aniosserv,programF:"M"},function(data){       
                                    data = JSON.parse(data);
                                    var montInteger = parseInt(data.montret);
                                    var montFloat = parseInt((data.montret % 1)*100);
                                    document.getElementById("agregaTramite").disabled =  false;
                                    $('#montRet').val(montInteger + "." + montFloat);
                                    document.getElementById("monRetEntr").value =  $('#montRet').val();
                                    if (motret == "FRF" || motret == "FFJ" || motret == "FMJ") {
                                        document.getElementById("ModoRetiro").disabled =  true;
                                        document.getElementById("ModoRetiro").value = "C";
                                        modalRetiro = "C";
                                        montoRetiro = parseFloat(document.getElementById('montRet').value);
                                        document.getElementById('monRetEntr').value = montoRetiro;
                                    }
                                });  
                            } else if (programaF==="FF") {
                                $.post("../../controller/tramites.php?op=obtenRetiroJubFF",{aniosserv:aniosserv,programF:"FF"},function(data){       
                                    data = JSON.parse(data);
                                    var montInteger = parseInt(data.montret);
                                    var montFloat = parseInt((data.montret % 1)*100);
                                    document.getElementById("agregaTramite").disabled =  false;
                                    $('#montRet').val(montInteger + "." + montFloat);
                                    document.getElementById("monRetEntr").value =  $('#montRet').val();
                                    if (motret == "FRF" || motret == "FFJ" || motret == "FMJ") {
                                        document.getElementById("ModoRetiro").disabled =  true;
                                        document.getElementById("ModoRetiro").value = "C";
                                        modalRetiro = "C";
                                        montoRetiro = parseFloat(document.getElementById('montRet').value);
                                        document.getElementById('monRetEntr').value = montoRetiro;
                                    }
                                });  
                            }
                        }else{
                            let pagAnterior = document.referrer;
                            if (pagAnterior.indexOf(window.location.host) !== -1) {
                                window.history.back();
                            }
                        }
                    });
                    break;

                case 'errorFecha':
                    notifError = data.diasServ;    
                    Swal.fire(
                        notifError,
                        'por favor verifique las fechas'
                    );
                    break;

                default:
                    break;
            }
        });      
    }
}

var imageExcepcion = '';
$("#imageOficTarj").change(function (evento) {
    evento.preventDefault();

    var files = $('#imageOficTarj')[0].files[0];

    var formData = new FormData($("#form_captTramite")[0]);
    var imagensend = document.querySelector("#imageOficTarj");
    $.ajax({
        type: 'POST',
        url: '../../controller/tramites.php?op=subirImgSoporte',
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            data = JSON.parse(datos);
            if (data["imagen"].length === 0) {
                swal.fire(
                    'La imagen de soporte no se cargo correctamente!!'
                );
            } else {
                imageExcepcion = data["imagen"];
                document.getElementById("ModoRetiro").disabled = false;
            }
        }
    });
});

const accionOpcTestamento = document.querySelector('#OpcTestamento');
accionOpcTestamento.addEventListener("change", function (evento) {
    evento.preventDefault();
    var tipoTestamnt = document.getElementById("OpcTestamento").value;

    if (tipoTestamnt == "SL") {
        document.getElementById("fechCTJuicio").disabled = true;
        document.getElementById("fechCTJuicio").value = fechaActual();
        document.getElementById("DivFechInicioJuicio").style.display = "none";
        document.getElementById('editaBefens').disabled = false;
    } else {
        document.getElementById("fechCTJuicio").value = "";
        document.getElementById("fechCTJuicio").disabled = false;
        document.getElementById("DivFechInicioJuicio").style.display = "none";
        document.getElementById('editaBefens').disabled = true;
    }
});

const accioFechTEstmnt = document.querySelector("#fechCTJuicio");
accioFechTEstmnt.addEventListener("blur", function (evento) {
    evento.preventDefault();

    var tipTestamento = document.getElementById('OpcTestamento').value;
    var validAnioFechCTJuic = false;

    if (parseInt(document.getElementById('fechCTJuicio').value.split("-")[0]) > 1930 && parseInt(document.getElementById('fechCTJuicio').value.split("-")[0]) < 2024) {
        document.getElementById("fechCTJuicio").style.border =  ".1em black solid";
        validAnioFechCTJuic = true;

        switch (tipTestamento) {
            case 'CT':
                if (isNaN(Date.parse(document.getElementById('fechCTJuicio').value)) && document.getElementById('fechCTJuicio').value == "") {
                    document.getElementById("calcDiasAnios").disabled = true;
                    document.getElementById("editaBefens").disabled = true;
                    Swal.fire(
                        'ERROR',
                        'La fecha de la carta testamentaria no es correcta, verifiquela!!!'
                    );
                    document.getElementById('calcDiasAnios').disabled = true;
                    validAnioFechCTJuic = false;
                } else {
                    $.post("../../controller/tramites.php?op=validFechaCTJuic",{tipoTestamento:tipTestamento,FBase:document.getElementById('fechBaseMae').value,FBaja:document.getElementById('fechBajaMae').value,FCTJuicio:document.getElementById('fechCTJuicio').value,FRecibido:document.getElementById('fechRecibido').value},function(data){
                        data = JSON.parse(data);
                        var resultValidVig = data.resultValid;
                        
                        switch (resultValidVig) {
                            case 'correcto':
                                validAnioFechCTJuic = true;
                                break;
                            
                            case 'errorFecha':
                                notifError = data.descValid;    
                                Swal.fire(
                                    notifError,
                                    'por favor verifique la(s) fecha(s)'
                                );
                                document.getElementById('calcDiasAnios').disabled = true;
                                validAnioFechCTJuic = false;
                                break;

                            default:
                                break;
                        }
                    });
                }

                if (!validAnioFechCTJuic) {
                    Swal.fire(
                        'ERROR',
                        'por favor verifique los datos ingresados'
                    );
                    document.getElementById('calcDiasAnios').disabled = true;
                } else {
                    if (motivo) {
                        
                    }
                
                    $.post("../../controller/tramites.php?op=validVigTramFA",{tipoTestamento:tipTestamento,ClaveMae:clavemae,FBase:document.getElementById('fechBaseMae').value,FBaja:document.getElementById('fechBajaMae').value,FCTJuicio:document.getElementById('fechCTJuicio').value,FRecibido:document.getElementById('fechRecibido').value},function(data){
                        data = JSON.parse(data);
                        
                        var resultValidVig = data.resulValidVig;
                        if (resultValidVig == 'vigenciaVal') {
                            document.getElementById("calcDiasAnios").disabled = false;
                            document.getElementById("editaBefens").disabled = false;
                        }else if (resultValidVig == 'vigenciaCad') {
                            document.getElementById("calcDiasAnios").disabled = true;
                            document.getElementById("editaBefens").disabled = true;

                            swal.fire({
                                title:'TRAMITE NO PROCEDENTE',
                                text:"La fecha ddel tramite excede el limite de su vigencia, tiene numero de oficio de autorizacion",
                                showCancelButton: true,
                                confirmButtonText:'Si',
                                cancelButtonText:'No',
                                timer:15000
                            }).then((result) => {
                                if (result.isConfirmed){
                                    var divOfTr = document.getElementById("DivExcepciones");
                                    divOfTr.style.display = "block";
                                    document.getElementById('editaBefens').disabled = false;
                                }else{
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                }
                            });
                        }
                    });
                }
                break;
            
            case 'SL':
                document.getElementById("calcDiasAnios").disabled = false;
                document.getElementById("editaBefens").disabled = false;
                document.getElementById("fechCTJuicio").value = "";
                document.getElementById("fechCTJuicio").disabled = true;
                validAnioFechCTJuic = true;

                if (!validAnioFechCTJuic) {
                    Swal.fire(
                        'ERROR',
                        'por favor verifique los datos ingresados'
                    );
                    document.getElementById('calcDiasAnios').disabled = true;
                } else {
                    $.post("../../controller/tramites.php?op=validVigTramFA",{tipoTestamento:tipTestamento,ClaveMae:clavemae,FBase:document.getElementById('fechBaseMae').value,FBaja:document.getElementById('fechBajaMae').value,FCTJuicio:document.getElementById('fechCTJuicio').value,FRecibido:document.getElementById('fechRecibido').value},function(data){
                        data = JSON.parse(data);
                        var resultValidVig = data.resulValidVig;

                        if (resultValidVig == 'vigenciaVal') {
                            document.getElementById("calcDiasAnios").disabled = false;
                            document.getElementById("editaBefens").disabled = false;
                        }else if (resultValidVig == 'vigenciaCad') {
                            document.getElementById("calcDiasAnios").disabled = true;
                            document.getElementById("editaBefens").disabled = true;

                            swal.fire({
                                title:'TRAMITE NO PROCEDENTE',
                                text:"La fecha ddel tramite excede el limite de su vigencia, tiene numero de oficio de autorizacion",
                                showCancelButton: true,
                                confirmButtonText:'Si',
                                cancelButtonText:'No',
                                timer:15000
                            }).then((result) => {
                                if (result.isConfirmed){
                                    var divOfTr = document.getElementById("DivExcepciones");
                                    divOfTr.style.display = "block";
                                    document.getElementById('editaBefens').disabled = false;
                                }else{
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                }
                            });
                        }
                    });
                }
                break;
            
            case 'J':
                if (isNaN(Date.parse(document.getElementById('fechCTJuicio').value)) && document.getElementById('fechCTJuicio').value == "") {
                    document.getElementById("calcDiasAnios").disabled = true;
                    document.getElementById("editaBefens").disabled = true;
                    Swal.fire(
                        'ERROR',
                        'La fecha del JUICIO no es correcta, verifiquela!!!'
                    );
                    validAnioFechCTJuic = false;
                    document.getElementById('calcDiasAnios').disabled = true;
                } else {
                    $.post("../../controller/tramites.php?op=validFechaCTJuic",{tipoTestamento:tipTestamento,FBase:document.getElementById('fechBaseMae').value,FBaja:document.getElementById('fechBajaMae').value,FCTJuicio:document.getElementById('fechCTJuicio').value,FRecibido:document.getElementById('fechRecibido').value},function(data){
                        data = JSON.parse(data);
                        var resultValidVig = data.resultValid;
                            switch (resultValidVig) {
                                case 'correcto':
                                    document.getElementById("calcDiasAnios").disabled = false;
                                    document.getElementById("editaBefens").disabled = false;
                                    validAnioFechCTJuic = true;
                                    break;
                                
                                case 'errorFecha':
                                    notifError = data.descValid;    
                                    Swal.fire(
                                        notifError,
                                        'por favor verifique la(s) fecha(s)'
                                    );
                                    validAnioFechCTJuic = false;
                                    document.getElementById('calcDiasAnios').disabled = true;
                                    break;

                                default:
                                    break;
                            }
                    });
                }

                if (!validAnioFechCTJuic) {
                    Swal.fire(
                        'ERROR',
                        'por favor verifique los datos ingresados'
                    );
                    document.getElementById('calcDiasAnios').disabled = true;
                } else {
                    $.post("../../controller/tramites.php?op=validVigTramFA",{tipoTestamento:tipTestamento,ClaveMae:clavemae,FBase:document.getElementById('fechBaseMae').value,FBaja:document.getElementById('fechBajaMae').value,FCTJuicio:document.getElementById('fechCTJuicio').value,FRecibido:document.getElementById('fechRecibido').value},function(data){
                        data = JSON.parse(data);
                        var resultValidVig = data.resulValidVig;

                        if (resultValidVig == 'vigenciaVal') {
                            document.getElementById("calcDiasAnios").disabled = false;
                            document.getElementById("editaBefens").disabled = false;
                        }else if (resultValidVig == 'vigenciaCad') {
                            document.getElementById("calcDiasAnios").disabled = true;
                            document.getElementById("editaBefens").disabled = true;

                            swal.fire({
                                title:'TRAMITE NO PROCEDENTE',
                                text:"La fecha del tramite excede el limite de su vigencia, tiene numero de oficio de autorizacion",
                                showCancelButton: true,
                                confirmButtonText:'Si',
                                cancelButtonText:'No',
                                timer:15000
                            }).then((result) => {
                                if (result.isConfirmed){
                                    var divOfTr = document.getElementById("DivExcepciones");
                                    divOfTr.style.display = "bloxk";
                                    document.getElementById('editaBefens').disabled = false;
                                }else{
                                    let pagAnterior = document.referrer;
                                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                                        window.history.back();
                                    }
                                }
                            });
                        }else if (resultValidVig == 'fechaIni') {
                            document.getElementById("DivFechInicioJuicio").style.display = "block";
                            document.getElementById("editaBefens").disabled = true;
                            document.getElementById("calcDiasAnios").disabled = true;
                            document.getElementById("fechCTJuicio").disabled = true;
                            Swal.fire(
                                "VIGENCIA DEL TRAMITE CADUCO",
                                'proporcione la fecha de inicio del juicio!!'
                            );
                        } else if (resultValidVig == 'noProcede') {
                            Swal.fire(
                                "TRAMITE NO PROCEDENTE",
                                'Tramite fuera del limite de vigencia para su validez'
                            );
                            
                            let pagAnterior = document.referrer;
                                if (pagAnterior.indexOf(window.location.host) !== -1) {
                                    window.history.back();
                                }
                        } 
                        
                    });
                }
                break;

            default:
                break;
        }
    }else{
        document.getElementById("fechCTJuicio").style.border =  ".1em red solid";
        Swal.fire(
            "El año de la fecha de CT o Juicio no es valido",
            'por favor corrija la fecha'
        );
        validAnioFechCTJuic = false;
        document.getElementById("calcDiasAnios").disabled = true;
        document.getElementById("editaBefens").disabled = true;
    }
});


const accioFechIniJuic = document.querySelector("#fechIniJuicio");
accioFechIniJuic.addEventListener("blur", function (evento) {
    evento.preventDefault();

    if (parseInt(document.getElementById('fechIniJuicio').value.slice(0,4)) > 2019 && parseInt(document.getElementById('fechIniJuicio').value.slice(0,4)) < 2024) {
        document.getElementById("fechCTJuicio").style.border =  ".1em black solid";
        $.post("../../controller/tramites.php?op=validaVigFechas",{fechRecibido:document.getElementById('fechRecibido').value,fechBaja:document.getElementById('fechBajaMae').value,fechIniJuic:document.getElementById('fechIniJuicio').value,fechCTJuic:document.getElementById('fechCTJuicio').value},function(data){
            data = JSON.parse(data);
            resultValidFI = data.descResult;
            
            switch (resultValidFI) {
                case 'validVal':
                    document.getElementById("calcDiasAnios").disabled = false;
                    document.getElementById("editaBefens").disabled = false;
                    break;
            
                case 'errorFecha':
                    notifError = data.descValid;    
                    Swal.fire(
                        notifError,
                        'por favor verifique la fecha'
                    );
                    document.getElementById("calcDiasAnios").disabled = true;
                    document.getElementById("editaBefens").disabled = true;
                    break;
                
                case 'noProcede':
                    document.getElementById("calcDiasAnios").disabled = true;
                    document.getElementById("editaBefens").disabled = true;
                    Swal.fire(
                        "TRAMITE NO PROCEDENTE",
                        'Tramite fuera del limite de vigencia para su validez'
                    );
                    
                    let pagAnterior = document.referrer;
                        if (pagAnterior.indexOf(window.location.host) !== -1) {
                            window.history.back();
                        }
                    break;
                
                default:
                    break;
            }
        });
    }else{
        document.getElementById("fechIniJuicio").style.border =  ".1em red solid";
        Swal.fire(
            "El año de la fecha de inicio del Juicio no es valido",
            'por favor corrija la fecha'
        );
        document.getElementById("calcDiasAnios").disabled = true;
        document.getElementById("editaBefens").disabled = true;
    }
    
});

var numBenefs=0;
const benefs_max = 20;
var contBenefs=1;
const accionBenefs = document.querySelector("#editaBefens");
accionBenefs.addEventListener("click", function (evento){
    evento.preventDefault();

    if (numBenefs == 0) {
        $('#edita_Benefs')[0].reset();
        $('#editarBenefs').modal('show');
        document.getElementById('numsBenefs').value = contBenefs;
        document.getElementById("calcDiasAnios").disabled = false;
    } else {
        $('#edita_Benefs')[0].reset();
        document.getElementById('numsBenefs').value = contBenefs;
        document.getElementById("calcDiasAnios").disabled = true;
        $('#editarBenefs').modal('show');
        var aB_nombres = document.getElementById('nomsbenefs').value.split(",");
        var aB_curps = document.getElementById('curpsbenefs').value.split(",");
        var aB_parents = document.getElementById('parentsbenefs').value.split(",");
        var aB_porcents = document.getElementById('porcentsbenefs').value.split(",");
        var aB_edades = document.getElementById('edadesbenefs').value.split(",");
        var aB_vida = document.getElementById('vidasbenefs').value.split(",");
		var aB_folios = document.getElementById('foliosbenefs').value.split(",");

        indexA = 0;
        formulario = document.getElementById('edita_Benefs');
            for (let i = 0; i < formulario.elements.length - 1; i++) {
                elemento = formulario.elements[i].name;
                switch (elemento) {
                    case 'nombenef[]':
                        formulario.elements[i].value = aB_nombres[indexA];
                        break;
                
                    case 'curpbenef[]':
                        formulario.elements[i].value = aB_curps[indexA];
                        break;
                    
                    case 'parentBenef[]':
                        formulario.elements[i].value = aB_parents[indexA];
                        break;
        
                    case 'porcentBenef[]':
                        formulario.elements[i].value = aB_porcents[indexA];
                        break;
        
                    case 'opcEdoEdadBenef[]':
                        formulario.elements[i].value = aB_edades[indexA];
                        break;
        
                    case 'opcEdoVidBenef[]':
                        formulario.elements[i].value = aB_vida[indexA];
                        break;
						
					case 'numcheqBenef[]':
						formulario.elements[i].value = aB_folios[indexA];
                        indexA++;
						break;
                    default:
                        break;
                }
            }
    }
});

$("#addBenef").click(function (e) {
    e.preventDefault();

    var elemntBenef = document.getElementById("DivBeneficiarios");
    var clonElement = elemntBenef.cloneNode(true);
    document.getElementById("DivDatsBenef").appendChild(clonElement);
    contBenefs++;
    document.getElementById('numsBenefs').value = contBenefs;
});

$('#DivDatsBenef').on("click", ".delete_Benef", function (e) {
    e.preventDefault();

    if (contBenefs >1) {
        $(this).parent('div').parent('div').remove();
        contBenefs--;
    }
    document.getElementById('numsBenefs').value = contBenefs;
});

$("#editarBenefs").on("submit",function(evento){
    evento.preventDefault();

    var a_nombres = [];
    var a_curps = [];
    var a_parentescos = [];
    var a_porcentajes = [];
    var a_edades = [];
    var a_vida = [];
    var a_folios = [];
    var porcentajeBenefs = 0;
    var integridadDats = true;
    var curpvacia=false;
    

    formulario = document.getElementById('edita_Benefs');
    for (let index = 0; index < formulario.elements.length - 1; index++) {
        elemento = formulario.elements[index].name;
        switch (elemento) {
            case 'nombenef[]':
                if (formulario.elements[index].value != ""){
                    a_nombres.push(formulario.elements[index].value);
                    integridadDats = true;
                }else{
                    a_nombres.push(formulario.elements[index].value);
                    integridadDats = false;
                }
                break;
        
            case 'curpbenef[]':
                if (formulario.elements[index].value != ""){
                    curpvacia=false;
                    a_curps.push(formulario.elements[index].value);
                    integridadDats = true;
                }else{
                    curpvacia=true;
                    a_curps.push(formulario.elements[index].value);
                    integridadDats = false;
                }
                break;
            
            case 'parentBenef[]':
                a_parentescos.push(formulario.elements[index].value);
                break;

            case 'porcentBenef[]':
                if (parseFloat(formulario.elements[index].value) > 0 && parseFloat(formulario.elements[index].value) <= 100) {
                    a_porcentajes.push(parseFloat(formulario.elements[index].value));
                    porcentajeBenefs = porcentajeBenefs + parseFloat(formulario.elements[index].value);
                } else {
                    a_porcentajes.push(parseFloat(formulario.elements[index].value));
                    porcentajeBenefs = porcentajeBenefs + parseFloat(formulario.elements[index].value);
                    Swal.fire(
                        "EL porcentaje proporcionado no es correcto",
                        'verifique sus datos'
                    );
                }
                break;

            case 'opcEdoEdadBenef[]':
                a_edades.push(formulario.elements[index].value);
                break;

            case 'opcEdoVidBenef[]':  
                if (formulario.elements[index].value == "F" && curpvacia == true) {
                    a_vida.push(formulario.elements[index].value);
                    integridadDats = true;
                } else if (formulario.elements[index].value == "V" && curpvacia == true) {
                    a_vida.push(formulario.elements[index].value);
                    integridadDats = false;
                } else  {
                    a_vida.push(formulario.elements[index].value);
                }
                break;
            
            case 'numcheqBenef[]':
                a_folios.push(formulario.elements[index].value);
                break;

            default:
                break;
        }
    }

    if (porcentajeBenefs != 100 || !integridadDats) {
        if (porcentajeBenefs != 100) {
            Swal.fire(
                "ERROR EN LOS PROCENTAJES",
                'deben sumar un total de 100%, verifiquelos'
            );
        }else if (!integridadDats) {
            Swal.fire(
                "ERROR EN LOS DATOS",
                'Algunos datos no son correctos, verificarlos'
            );
        }         
    } else {
        document.getElementById('nomsbenefs').value = a_nombres;
        document.getElementById('curpsbenefs').value = a_curps;
        document.getElementById('parentsbenefs').value = a_parentescos;
        document.getElementById('porcentsbenefs').value = a_porcentajes;
        document.getElementById('edadesbenefs').value = a_edades;
        document.getElementById('vidasbenefs').value = a_vida;
        document.getElementById('foliosbenefs').value = a_folios;
        document.getElementById('numBenefs'). value = document.getElementById('numsBenefs').value;
        numBenefs = document.getElementById('numsBenefs').value;
        $('#edita_Benefs')[0].reset();
        $("#editarBenefs").modal('hide');
        document.getElementById("calcDiasAnios").disabled = false;
    }
});


var montoRetiro = 0;
var accionOpciModRet = document.getElementById('ModoRetiro');
accionOpciModRet.addEventListener("change", calculaRetiro, false);
function calculaRetiro() {
    
    var modalidad = document.getElementById('ModoRetiro').value;
    montoRetiro = document.getElementById('montRet').value;
    if (modalidad == "C") {
        document.getElementById('monRetEntr').value = montoRetiro;
        document.getElementById("montRetFondFall").style.display = "none";
        document.getElementById("DivTpoDiferido").style.display = "none";
        document.getElementById('ModalRetiro').value = "C";
        modalRetiro ="C";
    } else if (modalidad == "D") {
        document.getElementById("DivTpoDiferido").style.display = "block";
        document.getElementById("montRetFondFall").style.display = "block";
        document.getElementById("montSalMin").disabled =  false;
        if (document.getElementById('ModRetDiferid50').checked){
            document.getElementById('monRetEntr').value = (montoRetiro / 2).toFixed(2);
            document.getElementById('montRetFF').value = (montoRetiro / 2).toFixed(2);
            document.getElementById('ModalRetiro').value = "D50";
            modalRetiro ="D50";

        }else if(document.getElementById('ModRetDiferid100').checked){
            document.getElementById('monRetEntr').value = "0";
            document.getElementById('montRetFF').value = montoRetiro;
            document.getElementById('ModalRetiro').value = "D100";
            modalRetiro ="D100";
        }
    }
}

var radModDife100 = document.getElementById('ModRetDiferid100');
radModDife100.addEventListener("change", calcMretDif100porc, false);
function calcMretDif100porc() {
    var opcDife = radModDife100.checked;
    if(opcDife){
        document.getElementById('monRetEntr').value = "0";
        document.getElementById('montRetFF').value = montoRetiro;
        document.getElementById('ModalRetiro').value = "D100";
        modalRetiro ="D100";
    }
}

var radModDife50 = document.getElementById('ModRetDiferid50');
radModDife50.addEventListener("change", calcMretDif50porc, false);
function calcMretDif50porc() {
    var opcDife = radModDife50.checked;
    if(opcDife){
        document.getElementById('monRetEntr').value = (montoRetiro / 2).toFixed(2);
        document.getElementById('montRetFF').value = (montoRetiro / 2).toFixed(2);
        document.getElementById('ModalRetiro').value = "D50";
        modalRetiro ="D50";
    }
}

$("#montSalMin").change(function () {
    var SalarioDiaHaber = $("#montSalMin").val()
    document.getElementById('montSalMin').value = (new Intl.NumberFormat('es-MX').format(SalarioDiaHaber));
});

var accionAgregar = document.getElementById('agregaTramite');
accionAgregar.addEventListener("click", function (event) {
    event.preventDefault();

    switch (motivo) {
        case 'FRI':
        case 'FRJ':
        case 'FRR':
        case 'FRD':
            agregajubilado();
            break;

        case 'FRF':
            agregaRetFallecimiento();
            break;

        case 'FFJ':
            break;

        case 'FMJ':
            agregaRetFallecimientoJ();
            break;

        default:
            break;
    }
});

function agregajubilado() {
    $.post("../../controller/tramites.php?op=agregar",{Ianioentr:$("#AnioEntr").val(),
                                                        Inumentr:$("#numentr").val(),
                                                        Iidentr:$("#IdEntrega").val(),
                                                        Icvemae:$("#cspMaeBusq").val(),
                                                        Icveissemym:document.getElementById('cveIMaeBusq').value,
                                                        Iestatusmae:$("#estLaboral").val(),
                                                        Imotret:motivo,
                                                        Iapepat:$("#apePatMae").val(),
                                                        Iapemat:$("#apeMatMae").val(),
                                                        Inombre:$("#nombreMae").val(),
                                                        Inomcom:$("#nomComplMae").val(),
                                                        IRegMae:$("#OpcRegSind").val(),
                                                        IfechDictam:$("#fechDictamen").val(),
                                                        InumDictam:$("#folioDictamen").val(),
                                                        Ifechbaj:$("#fechBajaMae").val(),
                                                        InomSolic:$("#nomSolic").val(),
                                                        INumCel:$("#TelCelMae").val(),
                                                        InumPart:$("#TelPartiMae").val(),
                                                        Ifechbase:$("#fechBaseMae").val(),
                                                        IfechInipsgs:document.getElementById('fechsIniPSGS').value,
                                                        IfechFinpsgs:document.getElementById('fechsFinPSGS').value,
                                                        Inumpsgs:$("#numPsgs").val(),
                                                        Idiaspsgs:$("#diasPsgs").val(),
                                                        IdiasServ:$("#diasServMae").val(),
                                                        IaniosServ:$('#aniosServMae').val(),
                                                        ImodRet:modalRetiro,
                                                        Imonttotret:document.getElementById('montRet').value,
                                                        ImontretEntr:$("#monRetEntr").val(),
                                                        ImontRetFF:document.getElementById('montRetFF').value,
                                                        IfechRecibido:$("#fechRecibido").val(),
                                                        InumOficTarj:$("#numOficTarj").val(),
                                                        IfechOficAut:$("#fechOficAut").val(),
                                                        IimageOficTarj:imageExcepcion,
                                                        Inumbenefs:$("#numbenef").val(),
                                                        IdiaHaber: $("#montSalMin").val().replace(",",""),
                                                        Icurpmae:$("#CURPMae").val(),
                                                        Irfcmae: $("#RFCMae").val(),
                                                        IObserv: $("#observTramite").val(),
                                                        ItipTram: tipTramite,
                                                        IFolCheq: $("#numfolcheqTEJI").val()
                                                        },function (data) {
                                                            resultadoAdd = Object.values( JSON.parse(data));
                                                            NumregsResult = resultadoAdd.length;
                                                            switch (NumregsResult) {
                                                                case 1:
                                                                    Swal.fire(
                                                                        "EL TRAMITE YA FUE INGRESADO CON ANTERIORIDAD",
                                                                        'por favor ingrese un nuevo tramite'
                                                                    );
                                                                    javascript:history.go(-1);
                                                                    break;

                                                                case 3:
                                                                    if (resultadoAdd[0] == "Agregado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Agregado") {
                                                                        Swal.fire(
                                                                            "TRAMITE INGRESADO CORRECTAMENTE"
                                                                        );
                                                                        javascript:history.go(-1);
                                                                    } else {
                                                                        Swal.fire(
                                                                            "ALGO SALIO MAL",
                                                                            'por favor verifique los datos'
                                                                        );
                                                                    }
                                                                    break;

                                                                case 4:
                                                                    if (resultadoAdd[0] == "Agregado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Agregado" && resultadoAdd[3] == "Agregado") {
                                                                        Swal.fire(
                                                                            "TRAMITE INGRESADO CORRECTAMENTE"
                                                                        );
                                                                        javascript:history.go(-1);
                                                                    } else {
                                                                        Swal.fire(
                                                                            "ALGO SALIO MAL",
                                                                            'por favor verifique los datos'
                                                                        );
                                                                    }
                                                                    break;

                                                                case 5:
                                                                    if (resultadoAdd[0] == "Agregado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Agregado" && resultadoAdd[3] == "Agregado" && resultadoAdd[4] == "Agregado") {
                                                                        Swal.fire(
                                                                            "TRAMITE INGRESADO CORRECTAMENTE"
                                                                        );
                                                                        javascript:history.go(-1);
                                                                    } else {
                                                                        Swal.fire(
                                                                            "ALGO SALIO MAL",
                                                                            'por favor verifique los datos'
                                                                        );
                                                                    }
                                                                    break;

                                                                default:
                                                                    break;
                                                            }
                                                        });
}

function agregaRetFallecimiento() {
    $.post("../../controller/tramites.php?op=agregarF",{Ianioentr:$("#AnioEntr").val(),
                                                        Inumentr:$("#numentr").val(),
                                                        Iidentr:$("#IdEntrega").val(),
                                                        Icvemae:$("#cspMaeBusq").val(),
                                                        Icveissemym:document.getElementById('cveIMaeBusq').value,
                                                        Iestatusmae:$("#estLaboral").val(),
                                                        Imotret:motivo,
                                                        IRegMae:$("#OpcRegSind").val(),
                                                        Ifechbaj:$("#fechBajaMae").val(),
                                                        InomSolic:$("#nomSolic").val(),
                                                        INumCel:$("#TelCelMae").val(),
                                                        InumPart:$("#TelPartiMae").val(),
                                                        Ifechbase:$("#fechBaseMae").val(),
                                                        IfechInipsgs:document.getElementById('fechsIniPSGS').value,
                                                        IfechFinpsgs:document.getElementById('fechsFinPSGS').value,
                                                        Inumpsgs:$("#numPsgs").val(),
                                                        Idiaspsgs:$("#diasPsgs").val(),
                                                        IdiasServ:$("#diasServMae").val(),
                                                        IaniosServ:$('#aniosServMae').val(),
                                                        ImodRet:modalRetiro,
                                                        Imonttotret:document.getElementById('montRet').value,
                                                        ImontretEntr:$("#monRetEntr").val(),
                                                        IfechRecibido:$("#fechRecibido").val(),
                                                        InumOficTarj:$("#numOficTarj").val(),
                                                        IfechOficAut:$("#fechOficAut").val(),
                                                        IimageOficTarj:imageExcepcion,
                                                        Inumbenefs:$("#numBenefs").val(),
                                                        Idoctestamnt:$("#OpcTestamento").val(),
                                                        Inomsbenefs:document.getElementById('nomsbenefs').value.split(","),
                                                        Icurpsbenefs:document.getElementById('curpsbenefs').value.split(","),
                                                        Iparentsbenefs:document.getElementById('parentsbenefs').value.split(","),
                                                        Iporcnsbenefs:document.getElementById('porcentsbenefs').value.split(","),
                                                        Iedadesbenefs:document.getElementById('edadesbenefs').value.split(","),
                                                        Ividabenefs:document.getElementById('vidasbenefs').value.split(","),
                                                        Ifechtestamnt:$("#fechCTJuicio").val(),
                                                        Icurpmae:$("#CURPMae").val(),
                                                        Irfcmae: $("#RFCMae").val(),
                                                        IObserv: $("#observTramite").val(),
                                                        ItipTram: tipTramite,
                                                        IfolBenefs: $("#foliosbenefs").val().split(",")
                                                        },function (data) {
                                                            resultadoAdd = Object.values( JSON.parse(data));
                                                            NumregsResult = resultadoAdd.length;
                                                            switch (NumregsResult) {
                                                                case 1:
                                                                    Swal.fire(
                                                                        "EL TRAMITE YA FUE INGRESADO CON ANTERIORIDAD",
                                                                        'por favor ingrese un nuevo tramite'
                                                                    );
                                                                    javascript:history.go(-1);
                                                                    break;

                                                                case 4:
                                                                    if (resultadoAdd[0] == "Agregado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Agregado" && resultadoAdd[3] == "Agregado") {
                                                                        Swal.fire(
                                                                            "TRAMITE INGRESADO CORRECTAMENTE"
                                                                        );
                                                                        javascript:history.go(-1);
                                                                    } else {
                                                                        Swal.fire(
                                                                            "ALGO SALIO MAL",
                                                                            'por favor verifique los datos'
                                                                        );
                                                                    }
                                                                    break;

                                                                default:
                                                                    break;
                                                            }
                                                        });
}

function agregaRetFallecimientoJ() {
    $.post("../../controller/tramites.php?op=agregarFJ",{Ianioentr:$("#AnioEntr").val(),
                                                        Inumentr:$("#numentr").val(),
                                                        Iidentr:$("#IdEntrega").val(),
                                                        Icveissemym:document.getElementById('cveIMaeBusq').value,
                                                        Iestatusmae:$("#estLaboral").val(),
                                                        Imotret:motivo,
                                                        IRegMae:$("#OpcRegSind").val(),
                                                        Ifechbaj:$("#fechBajaMae").val(),
                                                        InomSolic:$("#nomSolic").val(),
                                                        INumCel:$("#TelCelMae").val(),
                                                        InumPart:$("#TelPartiMae").val(),
                                                        Ifechbase:$("#fechBaseMae").val(),
                                                        IdiasServ:$("#diasServMae").val(),
                                                        IaniosServ:$('#aniosServMae').val(),
                                                        ImodRet:modalRetiro,
                                                        Imonttotret:document.getElementById('montRet').value,
                                                        ImontretEntr:$("#monRetEntr").val(),
                                                        IfechRecibido:$("#fechRecibido").val(),
                                                        InumOficTarj:$("#numOficTarj").val(),
                                                        IfechOficAut:$("#fechOficAut").val(),
                                                        IimageOficTarj:imageExcepcion,
                                                        Inumbenefs:$("#numBenefs").val(),
                                                        Idoctestamnt:$("#OpcTestamento").val(),
                                                        Inomsbenefs:document.getElementById('nomsbenefs').value.split(","),
                                                        Icurpsbenefs:document.getElementById('curpsbenefs').value.split(","),
                                                        Iparentsbenefs:document.getElementById('parentsbenefs').value.split(","),
                                                        Iporcnsbenefs:document.getElementById('porcentsbenefs').value.split(","),
                                                        Iedadesbenefs:document.getElementById('edadesbenefs').value.split(","),
                                                        Ividabenefs:document.getElementById('vidasbenefs').value.split(","),
                                                        Iprogramfallec: $("#inputProgramFall").val(),
                                                        Icurpmae:document.getElementById('CURPMae').value,
                                                        Irfcmae:document.getElementById('RFCMae').value,
                                                        Ifechtestamnt:$("#fechCTJuicio").val(),
                                                        IObserv: $("#observTramite").val(),
														ItipTram: tipTramite,
                                                        IfolBenefs: $("#foliosbenefs").val().split(",")
                                                        },function (data) {
                                                            resultadoAdd = Object.values( JSON.parse(data));
                                                            NumregsResult = resultadoAdd.length;
                                                            switch (NumregsResult) {
                                                                case 1:
                                                                    Swal.fire(
                                                                        "EL TRAMITE YA FUE INGRESADO CON ANTERIORIDAD",
                                                                        'por favor ingrese un nuevo tramite'
                                                                    );
                                                                    javascript:history.go(-1);
                                                                    break;

                                                                case 4:
                                                                    if (resultadoAdd[0] == "Agregado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Agregado" && resultadoAdd[3] == "Agregado") {
                                                                        Swal.fire(
                                                                            "TRAMITE INGRESADO CORRECTAMENTE"
                                                                        );
                                                                        javascript:history.go(-1);
                                                                    } else {
                                                                        Swal.fire(
                                                                            "ALGO SALIO MAL",
                                                                            'por favor verifique los datos'
                                                                        );
                                                                    }
                                                                    break;

                                                                default:
                                                                    break;
                                                            }
                                                        });
}

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

init();
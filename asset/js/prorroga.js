var motivo = "";
var clavemae = "";
var docsoport = "0";


function init() {
    $("#edita_NomMae").on("submit",function(e){
        actNomMae(e);
    });
}

$(document).ready(function () {
	var fechaRecibido = fechaActual();
	
	document.getElementById('fechRecibido').value = fechaRecibido;
	document.getElementById('DivDocsAutTExc').style.display = "none";
	document.getElementById('divcveissemym').style.display = "none";
	
});


$("#OpcCauRetiroTExc").change(function () {
    motivo= $("#OpcCauRetiroTExc").val();

    switch (motivo) {
        case "FRI":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
			document.getElementById('divcsp').style.display = "block";
			document.getElementById('divcveissemym').style.display = "none";
            break;

        case "FRJ":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;  
			document.getElementById('divcsp').style.display = "block";			
			document.getElementById('divcveissemym').style.display = "none";
            break;

        case "FRR":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;  
			document.getElementById('divcsp').style.display = "block";			
			document.getElementById('divcveissemym').style.display = "none";
            break;

        case "FRD":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;  
			document.getElementById('divcsp').style.display = "block";			
			document.getElementById('divcveissemym').style.display = "none";
            break;

        case "FRF":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
			document.getElementById('divcsp').style.display = "block";
			document.getElementById('divcveissemym').style.display = "none";
            break;

        case "FMJ":
            document.getElementById("cspMaeBusq").disabled =  true;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false; 
			document.getElementById('divcsp').style.display = "none";
			document.getElementById('divcveissemym').style.display = "block";
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
    }
    
    if (motivo == "FMJ") {
        clavemae = $("#cveIMaeBusq").val();
        if (claveisemym.length <= 6 && $("#cveIMaeBusq").val() != ""){
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
                    document.getElementById("TelPartiMae").value="";
                    document.getElementById("TelCelMae").value="";
                }else{
                    dataJ = JSON.parse(dataJ);
                    var motivo = dataJ.motivo;
                    var estatusmae = dataJ.estatusjub;
                    programfallec = dataJ.programafallec;
                    if (motivo == "nuevo" && estatusmae == "A") {
                        estatusMae = "JUBILADO " + programfallec;
                        $('#apePatMae').val(dataJ.apepatmae);
                        $('#apeMatMae').val(dataJ.apematmae);
                        $('#nombreMae').val(dataJ.nommae);
                        $('#estLaboral').val(estatusMae);
                        $('#nomComplMae').val(dataJ.nomcommae);
    
                        document.getElementById("TelPartiMae").disabled =  false;
                        document.getElementById("TelCelMae").disabled =  false;
                        document.getElementById("fechRecibido").disabled =  false;
                        document.getElementById("fechBajaMae").disabled =  false;
                        document.getElementById("nomSolic").disabled =  false;
                    } else if (motivo == "existente" || estatusmae == "F") {
                        if (dataJ.motivo == "existente") {
                            Swal.fire(
                                'Ya se encuentra un tramite registrado con la misma clave, por motivo de Fallecimiento en la entrega ' + dataJ.identrega,
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
                    } else if (motivo == "nuevo" || estatusmae == "F") {
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

                    document.getElementById("TelPartiMae").disabled =  false;
                    document.getElementById("TelCelMae").disabled =  false;
                    document.getElementById("fechRecibido").disabled =  false;
                    document.getElementById("fechBajaMae").disabled =  false;
					document.getElementById("docSoport").disabled =  false;

                    if (motivo == "FRF") {
						document.getElementById("nomSolic").disabled =  false;
                        $("#nomSolic").val();
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
        document.getElementById("TelPartiMae").value = "";
        document.getElementById("TelCelMae").value = "";
    }
});

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
	
	if($('#OpcCauRetiro').val() == "FMJ"){
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
			//$('#estLaboral').val(estatusMae);
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



var checkboxDocSoprt = document.getElementById('docSoport');
checkboxDocSoprt.addEventListener("change", validaCheckDocSoprt, false);
function validaCheckDocSoprt(){
    var checkedSoprt = checkboxDocSoprt.checked;
    if(checkedSoprt){
        docsoport = "1";
    }else{
        docsoport = "0";
    }
}

$(".DivBotnsNav").on("click",".Btnregresar", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

$(".DivBotnsNav").on("click",".BtnInicio", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});


var accionAgregar = document.getElementById('agregaTExc');
accionAgregar.addEventListener("click", function (event) {
    event.preventDefault();
	
    $.post("../../controller/tramites.php?op=agregarProrg",{
                                                        Icvemae:clavemae,
                                                        Imotret:$("#OpcCauRetiroTExc").val(),
                                                        Ifechbaj:$("#fechBajaMae").val(),
											            InomSolic:$("#nomSolic").val(),
                                                        INumCel:$("#TelCelMae").val(),
                                                        InumPart:$("#TelPartiMae").val(),
                                                        IfechRecibido:$("#fechRecibido").val(),
                                                        Idescmotprorg: $("#observTramite").val(),
                                                        Isoporte: docsoport
                                                        },function (data) {
                                                            resultadoAdd = Object.values( JSON.parse(data));
															
                                                            if(resultadoAdd[0] == "Agregado"){
																Swal.fire(
                                                                        "LA PRORROGA YA FUE INGRESADA CORRECTAMENTE"
                                                                    );
                                                                    javascript:history.go(-1);
                                                                    $('#prorrogas_data').DataTable().ajax.reload();
															}else if(resultadoAdd[0] == "Fallo"){
																swal.fire(
																	'ERROR!!!',
																	'Surgio un error consultelo con el administrador del sistema!!!',
																	'success'
																);  
															}
                                                        });
});


function deleteProrg(idProrg) {
    swal.fire({
        title:'ELIMINACIÓN DE PRORROGA DE TRAMITE',
        text:"Eliminara la prorroga seleccionada???",
        //icon: 'danger',
        showCancelButton: true,
        confirmButtonText:'Si',
        cancelButtonText:'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed){
            $.post("../../controller/tramites.php?op=deleteProrg",{idprorg:idProrg},function(data){
            });
            tabla = $('#entrega_data').DataTable();
            tabla.ajax.reload();
            swal.fire(
                'Eliminado!',
                'La entrega se elimino correctamente!!!',
                'success'
            )
        }
    })
}


init();

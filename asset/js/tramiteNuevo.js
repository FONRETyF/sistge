function init() {
    $("#edita_NomMae").on("submit",function(e){
        actNomMae(e);
    });
}
$(document).ready(function(){
    document.getElementById("montRetFondFall").style.display = "none";
    document.getElementById("editaBefens").style.display = "none";
    document.getElementById("DivDatsAdeudos").style.display = "none";
    document.getElementById("DivExcepciones").style.display = "none";
    /*var divOfTr = document.getElementById("DivExcepciones");
    divOfTr.style.display = "none"; */
    document.getElementById('fechRecibido').value = fechaActual();
    
});

$("#OpcCauRetiro").change(function () {
    motivo= $("#OpcCauRetiro").val();
    switch (motivo) {
        case "I":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("CURPMae").disabled =  false;
            document.getElementById("RFCMae").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("folioDictamen").disabled =  false;
            document.getElementById("fechRecibido").disabled =  false;
            document.getElementById("fechDictamen").disabled =  false;
            document.getElementById("fechBaseMae").disabled =  false;
            document.getElementById("fechBajaMae").disabled =  false;
            document.getElementById("editaPSGS").disabled =  false;
            document.getElementById("sinPSGS").disabled =  false;
            document.getElementById("calcDiasAnios").disabled =  false;
            document.getElementById("nomSolic").disabled =  true;
            document.getElementById("editaBefens").style.display = "none";
            break;

        case "J":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("CURPMae").disabled =  false;
            document.getElementById("RFCMae").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("folioDictamen").disabled =  false;
            document.getElementById("fechRecibido").disabled =  false;
            document.getElementById("fechDictamen").disabled =  false;
            document.getElementById("fechBaseMae").disabled =  false;
            document.getElementById("fechBajaMae").disabled =  false;
            document.getElementById("editaPSGS").disabled =  false;
            document.getElementById("sinPSGS").disabled =  false;
            document.getElementById("calcDiasAnios").disabled =  false;
            document.getElementById("nomSolic").disabled =  true;
            document.getElementById("editaBefens").style.display = "none";            
            break;

        case "FA":
            document.getElementById("cspMaeBusq").disabled =  false;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("CURPMae").disabled =  false;
            document.getElementById("RFCMae").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("folioDictamen").disabled =  false;
            document.getElementById("fechRecibido").disabled =  false;
            document.getElementById("fechDictamen").disabled =  false;
            document.getElementById("fechBaseMae").disabled =  false;
            document.getElementById("fechBajaMae").disabled =  false;
            document.getElementById("editaPSGS").disabled =  false;
            document.getElementById("sinPSGS").disabled =  false;
            document.getElementById("calcDiasAnios").disabled =  false;
            document.getElementById("nomSolic").disabled =  false;
            document.getElementById("editaBefens").style.display = "block";
            break;

        case "FJ":
            document.getElementById("cspMaeBusq").disabled =  true;
            document.getElementById("EditaNombre").disabled =  false;
            document.getElementById("cveIMaeBusq").disabled =  false;
            document.getElementById("CURPMae").disabled =  false;
            document.getElementById("RFCMae").disabled =  false;
            document.getElementById("OpcRegSind").disabled =  false;
            document.getElementById("folioDictamen").disabled =  false;
            document.getElementById("fechRecibido").disabled =  false;
            document.getElementById("fechDictamen").disabled =  false;
            document.getElementById("fechBaseMae").disabled =  false;
            document.getElementById("fechBajaMae").disabled =  false;
            document.getElementById("editaPSGS").disabled =  false;
            document.getElementById("sinPSGS").disabled =  false;
            document.getElementById("calcDiasAnios").disabled =  false;
            document.getElementById("nomSolic").disabled =  false;
            document.getElementById("editaBefens").style.display = "block";
            break;

        default:
            break;
    }
});

$(".CSPMae").keydown(function (event) {
    if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==37  && event.keyCode !==39 && event.keyCode !==8 && event.keyCode !==9 && event.keyCode !==46){
        return false;
    }
});

$(".cveissemym").keydown(function (event) {
    if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==37  && event.keyCode !==39 && event.keyCode !==8 && event.keyCode !==9 && event.keyCode !==46){
        return false;
    }
});

$("#cspMaeBusq").change(function () {
    clavemae = $("#cspMaeBusq").val();
    if (clavemae.length == 9) {
        $.post("../../controller/maestro.php?op=buscar",{clavemae:clavemae},function(data){ 
            if(jQuery.isEmptyObject(data)){
                Swal.fire(
                    'LA CLAVE ES INCORRECTA',
                    'verifique y corrija la clave!!!'
                )
                $('#cspMaeBusq').val("");
                $('#cveIMaeBusq').val("");
                $('#apePatMae').val("");
                $('#apeMatMae').val("");
                $('#nombreMae').val("");
                $('#estLaboral').val("");
                $('#nomComplMae').val("");
                $('#nomSolic').val(""); 
                $('#RFCMae').Val("");
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
                }
            }
        });
    } else {
        Swal.fire(
            'La clave es incorrecta, debe tener 9 digitos',
            'Verifique y corrija la clave!!!'
        );
        $('#cspMaeBusq').val("");
        $('#cveIMaeBusq').val("");
        $('#apePatMae').val("");
        $('#apeMatMae').val("");
        $('#nombreMae').val("");
        $('#estLaboral').val("");
        $('#nomComplMae').val("");
        $('#nomSolic').val(""); 
        /*$('#RFCMae').Val("");*/
    }
});

function fechaActual() {
    var date = new Date();/*.toLocaleDateString();*/
    year = date.getFullYear();
    month =date.getMonth() + 1;
    day = date.getDate();
    if (month < 10) {
        fechActRecib = year + "-0" + month + "-" + day;
    }else{
        fechActRecib = year + "-" + month + "-" + day;
    }
    return fechActRecib;    
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
    
    var formData = new FormData($("#edita_NomMae")[0]);
    
    $.ajax({
        url: '../../controller/maestro.php?op=actNomMae',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            $('#edita_NomMae')[0].reset();
            $("#editarNomMae").modal('hide');
            swal.fire(
                'Modificacion!',
                'Los Datos se actualizaron correctamente!!!',
                'success'
            );
        }
    });

    clavemae = $("#cspMaeBusq").val();
    $.post("../../controller/maestro.php?op=buscar",{clavemae:clavemae},function(data){ 
        data = JSON.parse(data);
        $('#cspMaeBusq').val(data.csp);
        $('#cveIMaeBusq').val(data.cveissemym);
        $('#apePatMae').val(data.apepatmae);
        $('#apeMatMae').val(data.apematmae);
        $('#nombreMae').val(data.nommae);
        $('#estLaboral').val(estatusMae);
        $('#nomComplMae').val(data.nomcommae);
        $('#nomSolic').val(data.nomcommae); 
    });
}

const accionCalculadora = document.querySelector('#calcDiasAnios');
accionCalculadora.addEventListener("click", function (evento) {
    evento.preventDefault();
    var valorValid = 0;
    var a_fechs = [
        {fecha:"Recibido", nomvar:"fechRecibido", valorF:document.getElementById('fechRecibido').value},
        {fecha:"Dictamen", nomvar:"fechDictamen", valorF:document.getElementById('fechDictamen').value},
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
            if (parseInt(element["valorF"].slice(0,4)) > 1930 && parseInt(element["valorF"].slice(0,4)) < 2024) {
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

    alert(valorValid);
    validaFechas(valorValid, a_fechs);
});

function validaFechas(valorValid,a_fechs) {
    if (valorValid == 4) {
        motret = $("#OpcCauRetiro").val();
        $.post("../../controller/tramites.php?op=validaFechs",{clavemae:clavemae,motret:motret,fechRecibido:a_fechs[0]["valorF"],fechDictamen:a_fechs[1]["valorF"],fechBaseMae:a_fechs[2]["valorF"],fechBajaMae:a_fechs[3]["valorF"]},function(data){
            data = JSON.parse(data);
            resultValid = data.descResult;
            switch (resultValid) {
                case 'vigenciaVal':
                    diasServicio = data.diasServ;
                    aniosServicio = Math.trunc(diasServicio/365);
                    document.getElementById('numPsgs').value = 0;
                    document.getElementById('tiempoPsgs').value = diasServicio;
                    document.getElementById('aniosServMae').value = aniosServicio;
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
                        }else{
                            let pagAnterior = document.referrer;
                            if (pagAnterior.indexOf(window.location.host) !== -1) {
                                window.history.back();
                            }
                        }
                    });
                    break;
                
                case 'vigenciaCadD':
                    diasServicio = data.diasServ
                    aniosServicio = Math.trunc(diasServicio/365);
                    if (data.prorroga == "SI") {
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
                            }else{
                                let pagAnterior = document.referrer;
                                if (pagAnterior.indexOf(window.location.host) !== -1) {
                                    window.history.back();
                                }
                            }
                        });
                    } else {
                        Swal.fire(
                            'TRAMITE NO PROCEDENTE',
                            'La fecha del tramite excede la vigencia del retiro y NO solicito prorroga'
                        );
                        let pagAnterior = document.referrer;
                                if (pagAnterior.indexOf(window.location.host) !== -1) {
                                    window.history.back();
                                }
                    }
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
        alert("todo bien");
    } else {
        Swal.fire(
            "Las Fechas ingresadas no son correctas",
            'No puede haber fechas mayores a el año en curso o menores a 1900, verifique las que estan marcadas en color rojo'
        );
    }
}

var checkbox = document.getElementById('sinPSGS');
checkbox.addEventListener("change", validaCheckbox, false);
function validaCheckbox(){
    var checked = checkbox.checked;
    if(checked){
        document.getElementById("calcDiasAnios").disabled = true;
        document.getElementById("editaPSGS").disabled =  true;
        calculaAñosServicio();
    }else{
        document.getElementById("calcDiasAnios").disabled = false;
        document.getElementById("editaPSGS").disabled =  false;
    }
    document.getElementById("ModoRetiro").disabled =  false;
}

var checkboxAdeudo = document.getElementById('CheckAdeudos');
checkboxAdeudo.addEventListener("change", validaCheckAdeudos, false);
function validaCheckAdeudos() {
    var checked =checkboxAdeudo.checked;
    if(checked){
        document.getElementById("DivDatsAdeudos").style.display = "block";
    }
}
init();
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
    document.getElementById("DivTpoDiferido").style.display = "none";
    /*var divOfTr = document.getElementById("DivExcepciones");
    divOfTr.style.display = "none"; */
    document.getElementById('fechRecibido').value = fechaActual();
    
    $("#cerrarEditPSGS").click(function () {
        $("#editarPSGS").modal('hide');
    });
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
                    document.getElementById('DiasServOriginal').value = diasServicio;
                    document.getElementById('tiempoPsgs').value = diasServicio;
                    document.getElementById('aniosServMae').value = aniosServicio;
                    document.getElementById("ModoRetiro").disabled =  false;
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
    } else {
        Swal.fire(
            "Las Fechas ingresadas no son correctas",
            'No puede haber fechas mayores a el año en curso o menores a 1900, verifique las que estan marcadas en color rojo'
        );
    }
}

var PSGS = 0;
var checkboxPSGS = document.getElementById('sinPSGS');
checkboxPSGS.addEventListener("change", validaCheckPSGS, false);
function validaCheckPSGS(){
    var checked = checkboxPSGS.checked;
    if(checked){
        document.getElementById("editaPSGS").disabled =  true;
        PSGS = 0;
    }else{
        document.getElementById("editaPSGS").disabled =  false;
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
    } else {
        $('#tituto_mod_psgs').html('Agregar P.S.G.S');
        $('#edita_PSGS')[0].reset();
        document.getElementById('numsPSGS').value = contPSGS;

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

/*var fechasI = new Array();
var fechasF = new Array();*/
$("#edita_PSGS").on("submit",function(evento){
    evento.preventDefault();
            
    var formDataPSGS = new FormData($("#edita_PSGS")[0]);
    $.ajax({
        url: '../../controller/tramites.php?op=diasPSGS',
        type: "POST",
        data: formDataPSGS,
        contentType: false,
        processData: false,
        success: function(datos){
            //alert(datos);
            numAgrPSGS++;
            $('#edita_PSGS')[0].reset();
            $("#editarPSGS").modal('hide');
            if (datos == 0 && document.getElementById('DiasServOriginal').value > 0) {
                diasActivo = document.getElementById('DiasServOriginal').value;
                document.getElementById('numPsgs').value = 0;
                document.getElementById('tiempoPsgs').value = diasActivo;
                document.getElementById('aniosServMae').value = Math.trunc(diasActivo/365);  
                document.getElementById('fechsIniPSGS').value = "";
                document.getElementById('fechsFinPSGS').value = "";
            } else {
                data = JSON.parse(datos);
                $('#numPsgs').val(data.numPSGS);
                if (document.getElementById('tiempoPsgs').value > 0) {
                    diasActivo = document.getElementById('DiasServOriginal').value - data.diasPSGS;
                } else {
                    diasActivo = document.getElementById('tiempoPsgs').value - data.diasPSGS;
                }
                document.getElementById('tiempoPsgs').value = diasActivo;
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

var checkboxAdeudo = document.getElementById('CheckAdeudos');
checkboxAdeudo.addEventListener("change", validaCheckAdeudos, false);
function validaCheckAdeudos(){
    var checked =checkboxAdeudo.checked;
    if(checked){
        document.getElementById("DivDatsAdeudos").style.display = "block";
    }else{
        document.getElementById("DivDatsAdeudos").style.display = "none";
    }
}



var adeudosMae = 0;
var montoRetiro = 0;

var accionOpciModRet = document.getElementById('ModoRetiro');
accionOpciModRet.addEventListener("change", calculaRetiro, false);
function calculaRetiro() {
    var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
    var modalidad = document.getElementById('ModoRetiro').value;

    //var adeudosMae = parseFloat(AdeudoFAJAM) + parseFloat(AdeudoTS) + parseFloat(AdeudoFondPension) + parseFloat(AdeudoTurismo);
    $.post("../../controller/tramites.php?op=obtenRetiro",{aniosserv:aniosserv,modalidad:modalidad},function(data){       
        data = JSON.parse(data);
        $('#montRet').val(data.montret.toFixed(2));
        montoRetiro = parseFloat(document.getElementById('montRet').value); //- adeudosMae).toFixed(2);
    });

    if (modalidad == "C") {
        alert(montoRetiro);
        document.getElementById('monRetEntr').value = montoRetiro;
        document.getElementById("montRetFondFall").style.display = "none";
        document.getElementById("DivTpoDiferido").style.display = "none";
    } else if (modalidad == "D") {
        document.getElementById("DivTpoDiferido").style.display = "block";
        document.getElementById("montRetFondFall").style.display = "block";
        document.getElementById("montSalMin").disabled =  false;
        if (document.getElementById('ModRetDiferid50').checked){
            document.getElementById('monRetEntr').value = (montoRetiro / 2).toFixed(2);
            document.getElementById('montRetFF').value = (montoRetiro / 2).toFixed(2);
        }else if(document.getElementById('ModRetDiferid100').checked){
            document.getElementById('monRetEntr').value = "0";
            document.getElementById('montRetFF').value = montoRetiro;
        }
    }
}
/*$("#ModoRetiro").change(function () {
    
});*/
$('#AdedFajam').on('blur', function (e) {
    alert("perdio el focus fajam");
});
$('#AdedTS').on('blur', function (e) {
    alert("perdio el focus ts");
});
$('#AdedFondPension').on('blur', function (e) {
    alert("perdio el focus pension");
});
$('#AdedTurismo').on('blur', function (e) {
    alert("perdio el focus  turismo");
});

/*$('#AdedFajam').on('keydown', function (e) {
    if (e.key=='Enter' || e.key=='Tab') {
        alert("escribio en fajam fajam");
    }
});
$('#AdedTS').on('keydown', function (e) {
    if (e.key=='Enter' || e.key=='Tab') {
        alert("escribio en fajam TS");
    }
});
$('#AdedFondPension').on('keydown', function (e) {
    if (e.key=='Enter' || e.key=='Tab') {
        alert("escribio en fajam pnsion");
    }
});
$('#AdedTurismo').on('keydown', function (e) {
    if (e.key=='Enter' || e.key=='Tab') {
        alert("escribio en fajam turismo");
    }
});*/


function obtenAdeudoF() {
    alert("modificar adeudo fajam");
    $('#AdedFondPension').blur();
}

function obtenAdeudoT(){
    alert("modificar adeudo TS");
}

var radModDife100 = document.getElementById('ModRetDiferid100');
radModDife100.addEventListener("change", calcMretDif100porc, false);
function calcMretDif100porc() {
    var opcDife = radModDife100.checked;
    if(opcDife){
        document.getElementById('monRetEntr').value = "0";
        document.getElementById('montRetFF').value = montoRetiro;
    }
}

var radModDife50 = document.getElementById('ModRetDiferid50');
radModDife50.addEventListener("change", calcMretDif50porc, false);
function calcMretDif50porc() {
    var opcDife = radModDife50.checked;
    if(opcDife){
        document.getElementById('monRetEntr').value = (montoRetiro / 2).toFixed(2);
        document.getElementById('montRetFF').value = (montoRetiro / 2).toFixed(2);
    }
}

$("#montSalMin").change(function () {
    var SalarioDiaHaber = $("#montSalMin").val()
    document.getElementById('montSalMin').value = (new Intl.NumberFormat('es-MX').format(SalarioDiaHaber));
});

init();
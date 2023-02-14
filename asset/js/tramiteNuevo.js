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
        $('#RFCMae').Val("");
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
    var fechRecibido = document.getElementById('fechRecibido').value;
    var fechDictamen = document.getElementById('fechDictamen').value;
    var fechBaseMae = document.getElementById('fechBaseMae').value;
    var fechBajaMae = document.getElementById('fechBajaMae').value;
    $.post("../../controller/tramites.php?op=validaFechs",{fechRecibido:fechRecibido,fechDictamen:fechDictamen,fechBaseMae:fechBaseMae,fechBajaMae:fechBajaMae},function(data){

    });
})

/*function validaAniofechas() {
    var fechRecibido = new Date(document.getElementById('fechRecibido').value).toUTCString();
    var fechDictamen = new Date(document.getElementById('fechDictamen').value).toUTCString();
    var fechBaseMae = new Date(document.getElementById('fechBaseMae').value).toUTCString();
    var fechBajaMae = new Date(document.getElementById('fechBajaMae').value).toUTCString();
    var validsFechas = new Array();
    
    alert(fechBaseMae);
    alert(fechBajaMae);
    if (fechDictamen < fechRecibido) {
        alert("11111111");
        validsFechas["fecha"] = "fechDictamen"; 
        validsFechas["Valid"] = "True"; 
        validsFechas["errorF"] = ""; 
    } else {
        alert("2222222222");
        validsFechas["fecha"] = "fechDictamen"; 
        validsFechas["Valid"] = "False";
        validsFechas["errorF"] = "La fecha del DICTAMEN no puede ser mayor a la fecha de recibido";  
    }

    if (fechBaseMae < fechBajaMae){
        alert("333333333333333333333");
    }

    if (fechBaseMae < fechBajaMae) {
        alert("aaaaaaaaaaaaaaaaaaaaaaa");
        /*if (fechBaseMae < fechRecibido) {
            validsFechas["fecha"] = "fechBaseMae"; 
            validsFechas["Valid"] = "True"; 
            validsFechas["errorF"] = ""; 
        } else {
            validsFechas["fecha"] = "fechBaseMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BASE no puede ser mayor a la fecha de recibido";
        }
    }else{
        
        if (fechBaseMae < fechRecibido){
            validsFechas["fecha"] = "fechBaseMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BASE no puede ser mayor a la fecha de baja";
        }else{
            validsFechas["fecha"] = "fechBaseMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BASE no puede ser mayor a la fecha de baja y de recibido";
        }
    }

    if (fechBajaMae > fechBaseMae) {
        if (fechBajaMae < fechRecibido) {
            validsFechas["fecha"] = "fechBajaMae"; 
            validsFechas["Valid"] = "True"; 
            validsFechas["errorF"] = ""; 
        } else {
            validsFechas["fecha"] = "fechBajaMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BAJA no puede ser mayor a la fecha de recibido"; 
        }
    } else {
        if (fechBajaMae < fechRecibido) {
            validsFechas["fecha"] = "fechBajaMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BAJA no puede ser menor a la fecha de base";
        } else {
            validsFechas["fecha"] = "fechBajaMae"; 
            validsFechas["Valid"] = "False"; 
            validsFechas["errorF"] = "La fecha de BAJA no puede ser mayor a la fecha de recibido y menor a la fecha de base";
        }
    }
    
    return validsFechas;
}*/



init();
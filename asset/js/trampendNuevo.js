var motivo = "";
var clavemae = "";

function init(){
    $("#edita_NomMae").on("submit",function(e){
        actNomMae(e);
    });
}

$(document).ready(function () {
    var fechaRecibido = fechaActual();

    $("#OpcCauRetiro").change(function () {
        motivo= $("#OpcCauRetiro").val();

        switch (motivo) {
            case 'FRF':
                document.getElementById("cspMaeBusq").disabled =  false;
                document.getElementById("EditaNombre").disabled =  false;
                document.getElementById("cveIMaeBusq").disabled =  false;
                document.getElementById("nomSolic").disabled = false;
                document.getElementById('fechRecibido').value = fechaRecibido;
                break;
            case 'FMJ':
                document.getElementById("cspMaeBusq").disabled =  true;
                document.getElementById("EditaNombre").disabled =  false;
                document.getElementById("cveIMaeBusq").disabled =  false;
                document.getElementById("nomSolic").disabled = false;
                document.getElementById('fechRecibido').value = fechaRecibido;
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
    
                        if (motivo == "FRF") {
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
            document.getElementById("TelPartiMae").value = "";
            document.getElementById("TelCelMae").value = "";
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
                            document.getElementById("fechIniJuicio").disabled =  false;

                        } else if (motivo == "existente" || estatusmae == "F") {
                            if (dataJ.motivo == "existente") {
                                Swal.fire(
                                    'Ya se encuentra un tramite registrado con la misma clave, por motivo de Fallecimiento' + dataJ.fechentrega,
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

    $(".DivDatsNomMae").on("click",".EditaNombre", function (evento) {
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

    const accionBtnAgregar = document.querySelector("#agregaTrampPend");
    accionBtnAgregar.addEventListener("click", function (evento) {
        evento.preventDefault();
        $.post("../../controller/tramites.php?op=validaFechsTramPend",{FchFallec:document.getElementById("fechBajaMae").value,FchIniJuic:document.getElementById("fechIniJuicio").value},function (data) {
            data = JSON.parse(data);
            if (!data) {
                Swal.fire(
                    'TRAMITE NO PROCEDENTE'                            
                ).then((result)=>{
                    let pagAnterior = document.referrer;
                    if (pagAnterior.indexOf(window.location.host) !== -1) {
                        window.history.back();
                    }
                });
            } else {
                agregaTramPend();
            }
        });
    })

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
        $('#apePatMae').val(data.apepatmae);
        $('#apeMatMae').val(data.apematmae);
        $('#nombreMae').val(data.nommae);
        $('#estLaboral').val(estatusMae);
        $('#nomComplMae').val(data.nomcommae);
        $('#nomSolic').val(data.nomcommae); 
    });
}

$(".DivBotnsNav").on("click",".Btnregresar", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

$(".DivBotnsNav").on("click",".BtnInicio", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

function agregaTramPend(){
    $.post("../../controller/tramites.php?op=agregaTramPend",{Icvemae:clavemae,
                                                            Iestatusmae:$("#estLaboral").val(),
                                                            Imotret:$("#OpcCauRetiro").val(),
                                                            Inomcommae:$("#nomComplMae").val(),
                                                            InomSolic:$("#nomSolic").val(),
                                                            INumCel:$("#TelCelMae").val(),
                                                            InumPart:$("#TelPartiMae").val(),
                                                            IfechRecibido:$("#fechRecibido").val(),
                                                            Ifechbaj:$("#fechBajaMae").val(),
                                                            Ifechinijuic:$("#fechIniJuicio").val()
                                                            },function (data) {
                                                            resultadoAdd = Object.values( JSON.parse(data));
                                                            NumregsResult = resultadoAdd.length;
                                                            if (resultadoAdd[0] == "Agregado") {
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

                                                            });
}

init();
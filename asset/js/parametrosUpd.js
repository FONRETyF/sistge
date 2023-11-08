var estatusParam = "";

function init() {
    
}

$(document).ready(function () {
    var dirurl = window.location.search;
    var paramBusq = new URLSearchParams(dirurl);
    paramid = paramBusq.get('id');

    $.post("../../controller/entregas.php?op=getParam",{idParam:paramid},function(data){  
        datParam = Object.values(JSON.parse(data));
        var infoParam = Object.values(datParam[0]);

        $("#sueldBaseSuperv").val(infoParam[1].replace('$','').replace(',',''));
        $("#sueldBaseProfrTit").val(infoParam[2].replace('$','').replace(',',''));
        $("#promSueldos").val(infoParam[3].replace('$','').replace(',',''));
        $("#montRetAn").val(infoParam[4].replace('$','').replace(',',''));
        $("#NumEntApliIni").val(parseInt(infoParam[7].substr(4,2)));
        $("#AnioEntApliIni").val(infoParam[7].substr(0,4));
        if (infoParam[8] != "") {
            $("#NumEntApliFin").val(parseInt(infoParam[8].substr(4,2)));
        } 
        $("#AnioEntApliFin").val(infoParam[8].substr(0,4));

        if (infoParam[9] == "ACTIVO") {
            estatusParam = "ACTIVO";
            $("#checkStatParam").bootstrapToggle('on');
            $("#checkStatParam").bootstrapToggle('enabled');
            
        } else {
            $("#checkStatParam").bootstrapToggle('off');
            $("#checkStatParam").bootstrapToggle('disabled');
        }
    });
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

$("#sueldBaseSuperv").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});

$("#sueldBaseProfrTit").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});

$("#NumEntApliIni").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});
$("#AnioEntApliIni").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});

$("#NumEntApliFin").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});
$("#AnioEntApliFin").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key!==110 && key!==190){
        return false;
    }
});

var promedio = 0;
$("#BttnCalcProm").on("click", function(evento) {
    evento.preventDefault();
    
    if ($("#sueldBaseSuperv").val() != "" && $("#sueldBaseProfrTit").val() != "") {
        $.post("../../controller/entregas.php?op=calcPromSsBs",{SBSup:$("#sueldBaseSuperv").val(),SBTit:$("#sueldBaseProfrTit").val()},function (data) {
            data = JSON.parse(data);
            promedio = data.promedio;
            $("#promSueldos").val(promedio);
            $("#montRetAn").val(data.retiroAnual);
            document.getElementById('agregaParam').disabled = false;
        });
    } else {
        Swal.fire(
            'FALTAN DATOS',
            'Proporcione los dos sueldos base!!!'
        );
    }
});


$("#updtParam").on("click", function (event) {
    event.preventDefault();

    if (!document.getElementById("checkStatParam").checked) {
        if ($("#AnioEntApliIni").val() !== "" && $("#AnioEntApliFin").val() !== "") {
            estatusParam="CERRADO";
            document.getElementById('updtParam').disabled = false;
            $.post("../../controller/entregas.php?op=updtPramRet",{SBSup:$("#sueldBaseSuperv").val(),
                                                              SBTit:$("#sueldBaseProfrTit").val(),
                                                              PromSB:$("#promSueldos").val(),
                                                              MontRetA:$("#montRetAn").val(),
                                                              numEntIni:$("#NumEntApliIni").val(),
                                                              anioEntIni:$("#AnioEntApliIni").val(),
                                                              numEntFin:$("#NumEntApliFin").val(),
                                                              anioEntFin:$("#AnioEntApliFin").val(),
                                                              estatParam:estatusParam,
                                                              idparam: paramid                                              
                                                              },function (data) {
                                                              data = JSON.parse(data);
                                                              switch (data.actualizacion) {
                                                                case 'actualizado':
                                                                    swal.fire({
                                                                        title: 'ACTUALIZADO!',
                                                                        text: 'El parametro se actualizo correctamente!!!',
                                                                        icon: 'success',
                                                                        timer: 1000000
                                                                    });                                                                 
                                                                    javascript:history.go(-1);
                                                                    $('#parametro_data').DataTable().ajax.reload();
                                                                    break;

                                                                case 'fallo':
                                                                    swal.fire({
                                                                        title: 'SURGIO ERROR!',
                                                                        text: 'Error al actualizar el parametro, consulte con el admin. del sistema',
                                                                        icon: 'error',
                                                                        timer: 1000000
                                                                    });
                                                                    break;

                                                                default:
                                                                    break;
                                                              }

            });
        }else{
            document.getElementById('updtParam').disabled = true;
            swal.fire({
                title: 'FALTAN DATOS!!!',
                text: 'Para poder poder cerrar el parametro necesita proporcionar entrega de cierre',
                icon: 'warning'
            });
        }
    } else {
        document.getElementById('updtParam').disabled = false;
        estatusParam="ACTIVO";
        $.post("../../controller/entregas.php?op=updtPramRet",{SBSup:$("#sueldBaseSuperv").val(),
                                                            SBTit:$("#sueldBaseProfrTit").val(),
                                                            PromSB:$("#promSueldos").val(),
                                                            MontRetA:$("#montRetAn").val(),
                                                            numEntIni:$("#NumEntApliIni").val(),
                                                            anioEntIni:$("#AnioEntApliIni").val(),
                                                            numEntFin:$("#NumEntApliFin").val(),
                                                            anioEntFin:$("#AnioEntApliFin").val(),
                                                            estatParam:estatusParam,
                                                            idparam: paramid                                              
                                                            },function (data) {
                                                            data = JSON.parse(data);
                                                            switch (data.actualizacion) {
                                                            case 'actualizado':
                                                                swal.fire({
                                                                    title: 'Registrado!',
                                                                    text: 'El parametro se actualizo correctamente!!!',
                                                                    icon: 'success',
                                                                    timer: 500000
                                                                });                                                                 
                                                                javascript:history.go(-1);
                                                                $('#parametro_data').DataTable().ajax.reload();
                                                                break;

                                                            case 'fallo':
                                                                swal.fire({
                                                                    title: 'SURGIO ERROR!',
                                                                    text: 'Error al actualizar el parametro, consulte con el admin. del sistema',
                                                                    icon: 'error',
                                                                    timer: 500000
                                                                });
                                                                break;

                                                            default:
                                                                break;
                                                            }
        });
    }

})

init();
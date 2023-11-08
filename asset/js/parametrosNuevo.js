function init() {
    
}

$(document).ready(function () {
    document.getElementById('agregaParam').disabled = true;
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

function editar(idparam) {
    location.href = "../../views/home/paramUpdate.php" + "?id=" + idparam;
}

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

$("#agregaParam").on("click", function (evento) {
    evento.preventDefault();

    if ($("#NumEntApliIni").val() != "" && $("#AnioEntApliIni").val() != "") {
        $.post("../../controller/entregas.php?op=addPramRet",{SBSup:$("#sueldBaseSuperv").val(),
                                                              SBTit:$("#sueldBaseProfrTit").val(),
                                                              PromSB:$("#promSueldos").val(),
                                                              MontRetA:$("#montRetAn").val(),
                                                              numEntIni:$("#NumEntApliIni").val(),
                                                              anioEntIni:$("#AnioEntApliIni").val()                                                
                                                              },function (data) {
                                                              data = JSON.parse(data);
                                                              switch (data.insercion) {
                                                                case 'agregado':
                                                                    swal.fire({
                                                                        title: 'Registrado!',
                                                                        text: 'El parametro se ingreso correctamente!!!',
                                                                        icon: 'success',
                                                                        timer: 500000
                                                                    });                                                                 
                                                                    javascript:history.go(-1);
                                                                    $('#parametro_data').DataTable().ajax.reload();
                                                                    break;

                                                                case 'existente':
                                                                    swal.fire({
                                                                        title: 'REGISTRO EXISTENTE!',
                                                                        text: 'Existe un poblema con el registro del parametro, el numeor de entrega ya se encuentra en otro parametro',
                                                                        icon: 'error',
                                                                        timer: 500000
                                                                    });                                                                    
                                                                    break;

                                                                case 'fallo':
                                                                    swal.fire({
                                                                        title: 'SURGIO ERROR!',
                                                                        text: 'Error al ingresar el parametro, consulte con el admin. del sistema',
                                                                        icon: 'error',
                                                                        timer: 500000
                                                                    });
                                                                    break;

                                                                case 'inconsistente':
                                                                    swal.fire({
                                                                        title: 'NO SE PUEDE DAR DE ALTA EL PARAMETRO!!!',
                                                                        text: 'Para poder dar de alta el parametro primero cierre el que se encuentra activo',
                                                                        icon: 'error',
                                                                        timer: 500000
                                                                    });                                                                 
                                                                    javascript:history.go(-1);
                                                                    $('#parametro_data').DataTable().ajax.reload();
                                                                    break;

                                                                default:
                                                                    break;
                                                              }

        });
    } else {
        Swal.fire(
            'FALTAN DATOS',
            'Proporcione numero y año de la entrega inicial!!!'
        );
    }

});


init();

function init() {
}

$(document).ready(function () {

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

/*$("#sueldBaseSuperv").keydown(function (event) {
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
});*/

var promedio = 0;
var aportSup = 0;
var aportTit = 0;

$("#sueldBaseSuperv").on("blur", function(evento) {
    evento.preventDefault();
    
    elemento = document.getElementById("sueldBaseSuperv");        
    aportSup = document.getElementById("sueldBaseSuperv").value;

    promedio = (parseFloat(aportSup) + parseFloat(aportTit)) / 2;

    $("#promSueldos").val(promedio);

    elemento.onblur; 
});

$("#sueldBaseProfrTit").on("blur", function(evento) {
    evento.preventDefault();

    elemento = document.getElementById("sueldBaseProfrTit");
    aportTit = document.getElementById("sueldBaseProfrTit").value;
    
    promedio = (parseFloat(aportSup) + parseFloat(aportTit)) / 2;

    $("#promSueldos").val(promedio);

    elemento.onblur; 
});

var statusParam = "";
function cambiastatus() {
    if (document.getElementById("checkStatParam").checked) {
        statusParam = "ACTIVO"; 
    }else{
        statusParam = "CERRADO"; 
    }           
}


init();

function init() {
}

$(document).ready(function () {
    
    var eleetosform = document.getElementById("form_CarpetasArchivo");
    for (let index = 0; index < eleetosform.elements.length; index++) {
        element = eleetosform.elements[index].name;
        
        
    }
    
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
$(".divIconDelete").on("click", ".delete_carpeta", function(e){
    e.preventDefault();

    numcarpetas = parseInt(document.getElementById("inputNumCarp").value);

    
    $(this).parent('div').parent('div').remove();
    numcarpetas--;

    document.getElementById("inputNumCarp").value = numcarpetas;
    
});

$("#addCarp").on("click", function (e) {
    e.preventDefault();

    $("#inputsFols").append(
        '<div id="divdetalleCarpeta"><div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta" name="numcarpeta"></div><div class="divFolIni"><input type="text" class="inputfolini" id="folinicial" name="folinicial"></div><div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal" name="folfinal"></div><div class="divEstat"><select class="opcestat" name="estatcomplet" id="estatcomplet"><option value="COMPLETA">COMPLETA</option><option value="INCOMPLETA">INCOMPLETA</option></select></div><div class="divObserv"><input type="text" class="inputobserv" id="observcarp" name="observcarp"></div><div class="divIconDelete"><a href="#" class="delete_carpeta"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></a></div></div>'
    );
    
    numcarpetas = parseInt($("#inputNumCarp").val()) + 1;

    $("#inputNumCarp").val(numcarpetas);
});

init();
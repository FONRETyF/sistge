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

var numcarpetas;
$(".divIconDelete").on("click", ".delete_carpeta", function(e){
    e.preventDefault();
    alert("eliminara objetos");

    numcarpetas = parseInt(document.getElementById("inputNumCarp").value);

    
    $(this).parent('div').parent('div').remove();
    numcarpetas--;

    document.getElementById("inputNumCarp").value = numcarpetas;
    
});

$("#addCarp").on("click", function (e) {
    e.preventDefault();

    numcarpetas = parseInt($("#inputNumCarp").val()) + 1;

    $('#inputsFols').append(
        '<div><div id="divdetalleCarpeta"><div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta['+numcarpetas+']" name="numcarpeta['+numcarpetas+']"></div><div class="divFolIni"><input type="text" class="inputfolini" id="folinicial['+numcarpetas+']" name="folinicial['+numcarpetas+']"></div><div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal['+numcarpetas+']" name="folfinal['+numcarpetas+']"></div><div class="divEstat"><select class="opcestat" name="estatcomplet['+numcarpetas+']" id="estatcomplet['+numcarpetas+']"><option value="COMPLETA">COMPLETA</option><option value="INCOMPLETA">INCOMPLETA</option></select></div><div class="divObserv"><input type="text" class="inputobserv" id="observcarp['+numcarpetas+']" name="observcarp['+numcarpetas+']"></div><div class="divIconDelete"><button type="button" class="delete_carpeta" id="eliminaCarp"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></button></div></div></div>'
    );
    
    $("#inputNumCarp").val(numcarpetas);

    var eleetosform = document.getElementById("form_CarpetasArchivo");
    for (let index = 0; index < eleetosform.elements.length; index++) {
        element = eleetosform.elements[index].name;
        alert(element);
    }
});

var accionEliminaCarp = document.getElementById("")

init();
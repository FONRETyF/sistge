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
$("#inputsFols").on("click", ".delete_Carpeta", function(e){
    e.preventDefault();
    numcarpetas = parseInt(document.getElementById("inputNumCarp").value);    
    $(this).parent('div').parent('div').remove();
    numcarpetas--;
    document.getElementById("inputNumCarp").value = numcarpetas;
});

$("#addCarp").on("click", function (e) {
    e.preventDefault();
    numcarpetas = parseInt($("#inputNumCarp").val()) + 1;
    $('#inputsFols').append(
        '<div><div id="divdetalleCarpeta"><div class="divNumCarp"><input type="text" class="inputnumcarp" id="numcarpeta[]" name="numcarpeta[]"></div><div class="divFolIni"><input type="text" class="inputfolini" id="folinicial[]" name="folinicial[]"></div><div class="divFolFin"><input type="text" class="inputfolfin" id="folfinal[]" name="folfinal[]"></div><div class="divEstat"><select class="opcestat" name="estatcomplet[]" id="estatcomplet[]"><option value="COMPLETA">COMPLETA</option><option value="INCOMPLETA">INCOMPLETA</option></select></div><div class="divObserv"><input type="text" class="inputobserv" id="observcarp[]" name="observcarp[]"></div><div class="divIconDelete"><a href="#" class="delete_Carpeta"><img src="../../img/delete.png" alt="Eliminar" title="Eliminar carpeta" height="15" width="20"></a></div></div></div>'
    );
    $("#inputNumCarp").val(numcarpetas);    
});

$("#editRang").on("click", function (e) {
    e.preventDefault();

    document.getElementById('inpRangIniCarps').disabled = false;
    document.getElementById('inpRangFinCarps').disabled = false;
});

$("#updateCarp").on("click", function (e) {
    e.preventDefault() ;
    
    document.getElementById('inpRangIniCarps').disabled = true;
    document.getElementById('inpRangFinCarps').disabled = true;

    folioini = parseInt($("#inpRangIniCarps").val());
    foliofinal = parseInt($("#inpRangFinCarps").val());

    numcarpetas = ((foliofinal - folioini) + 1) / 30;
    alert(folioini + "---" + foliofinal + "---" + numcarpetas);
    
    if (parseInt($("#inputNumCarp").val()) < numcarpetas) {
        
    } else if(parseInt($("#inputNumCarp").val()) > numcarpetas){
        
    } else if(parseInt($("#inputNumCarp").val()) == numcarpetas){

    }
   
});

init();
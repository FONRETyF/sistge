const identrega =  $("#InputIdentrega").val();

function init() {
    
}

$(document).ready(
    function () {
        
    }
);

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

var accionBtnImpOfic = document.getElementById('BtnImprimeOficio');
accionBtnImpOfic.addEventListener("click", function (e) {
    e.preventDefault();

    if (document.getElementById("RadBtnTraspaso").checked) {
        location.href = "../../views/home/oficioTraspaso.php" + "?identr=" + identrega;
    }else if(document.getElementById("RadBtnSolicCheqFinan").checked){
        location.href = "../../views/home/oficioSolicChqs.php" + "?identr=" + identrega;
    }else if (document.getElementById("RadBtnImpCheqInform").checked) {
        location.href = "../../views/home/oficioImpChqs.php" + "?identr=" + identrega;
    } else if(document.getElementById("RadBtnArchContad").checked){
        
    }
    
});

var accionBtnGeneraList = document.getElementById('BtnGenerateList');
accionBtnGeneraList.addEventListener("click", function (e) {
    e.preventDefault();
    
    if (document.getElementById("RadBtnArchivo").checked) {
        location.href = "../../views/home/fileContab.php" + "?identr=" + identrega;
    } else if (document.getElementById("RadBtnAdeudos").checked) {
        location.href = "../../views/home/fileAdeudos.php" + "?identr=" + identrega;
    } else if (document.getElementById("RadBtnActas").checked) {
        location.href = "../../views/home/actaEntrega.php" + "?identr=" + identrega;
    } else if (document.getElementById("RadBtnSobres").checked) {
        location.href = "../../views/home/sobres.php" + "?identr=" + identrega;
    } else if (document.getElementById("RadBtnInformatica").checked) {
        location.href = "../../views/home/fileInformatic.php" + "?identr=" + identrega;
    } else if(document.getElementById("RadBtnLlamadas").checked) {
        
    }
});


init();

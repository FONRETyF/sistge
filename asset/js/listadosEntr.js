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
        alert("entro 1111");
    }else if(document.getElementById("RadBtnSolicCheqFinan").checked){
        alert("entro 222");
    }else if (document.getElementById("RadBtnImpCheqInform").checked) {
        alert("entro 333");
    } else if(document.getElementById("RadBtnArchContad").checked){
        alert("entro 444");
    }
    
});

var accionBtnGeneraList = document.getElementById('BtnGenerateList');
accionBtnGeneraList.addEventListener("click", function (e) {
    e.preventDefault();
    
    if (document.getElementById("RadBtnArchivo").checked) {
        alert("lis 1");
    } else if (document.getElementById("RadBtnAdeudos").checked) {
        alert("lis 2");
    } else if (document.getElementById("RadBtnActas").checked) {
        location.href = "../../views/home/actaEntrega.php" + "?identr=" + identrega;
    } else if (document.getElementById("RadBtnSobres").checked) {
        alert("lis 4");
    } else if (document.getElementById("RadBtnInformatica").checked) {
        location.href = "../../views/home/fileInformatic.php" + "?identr=" + identrega;
    } else if(document.getElementById("RadBtnLlamadas").checked) {
        alert("lis 6");
    }
});


init();

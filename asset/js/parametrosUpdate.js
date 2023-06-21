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

init();

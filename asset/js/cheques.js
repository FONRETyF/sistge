function init() {
    
}

$(document).ready(function () {
    
});

var accionCancelCheq = document.getElementById("cancelCheque")
accionCancelCheq.addEventListener("click", function (e) {
    e.preventDefault();
    var folcheque = '';

    folcheque = $("#numCheqCalcel").val();
    alert(folcheque);
    $.post("../../controller/retiros.php?op=cancelCheque",{numcheque:folcheque,motvcanc:document.getElementById("motivCancel").value,observ:$("#observCancelCheque").val()},function (data) {
        data = Object.values(JSON.parse(data));
        alert(data[0]);
        if (data[0] == "cancelado") {
            Swal.fire(
                "CHEQUE CANCELADO CORRECTAMENTE"
            );
            location.href = 'cancelCheqs.php';
        } else {
            Swal.fire(
                "ALGO SALIO MAL!!!",
                'verifiquelo con el administrador del sistema'
            );
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

init();
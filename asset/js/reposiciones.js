function init(){

}

$(document).ready(function () {
    
});

$("#BttnBuscarCheq").on("click",function (e) {
    e.preventDefault();

    $.post("../../controller/cheque.php?opcion=buscaCheqC",{folio:$("#numCheqCancel").val()},function (data) {
        data = Object.values(JSON.parse(data));
        if (data.length === 0) {
            Swal.fire(
                "NO SE PUEDE REPONER EL CHEQUE " + $("#numCheqCancel").val(),
                'solo se puede reponer un cheque que fue CANCELADO'
            );
        } else {
            resultBusqCheqC = Object.values(data[0]);
            $("#resultBusqCheqs").html("");
            for (let index = 0; index < resultBusqCheqC.length; index++) {
                datosCheq = Object.values(resultBusqCheqC[index]);
                var tr = `<tr>
                        <td>`+datosCheq[4]+ `</td> 
                        <td>`+datosCheq[8]+ `</td> 
                        <td>`+datosCheq[19]+ `</td> 
                        <td>`+datosCheq[30]+ `</td> 
                        <td>`+datosCheq[20]+ `</td> 
                    </tr>`;
                $("#resultBusqCheqs").append(tr);
            }
        }
    });
});

$("#reposCheque").on("click", function (e) {
    e.preventDefault();
    
    $.post("../../controller/cheque.php?opcion=reponerCheque",{folioAnt:$("#numCheqCancel").val(),folioNuevo:$("#numCheqRepos").val(),fechRepos:$("#fechRepos").val(),observRepos:$("#observRepos").val()},function (data) {
        data = Object.values(JSON.parse(data));   
        resultPre = data.length;
        switch (resultPre) {
            case 1:
                if (data[0] == "Fallo") {
                    Swal.fire(
                        "ALGO SALIO MAL",
                        'por favor comuniquese con el administrador del sistema'
                    );
                }
                break;
        
            case 2:
                if (data[0] == "Agregado" && data[1] == "Actualizado") {
                    Swal.fire(
                        "REPOSICION REALIZADA CORRECTAMENTE"
                    );
                    location.href = "../../views/home/oficioReposicion.php" + "?folio=" + $("#numCheqCancel").val();                    
                }
                if (data[0] == "Agregado" && data[1] == "Fallo") {
                    Swal.fire(
                        "ALGO SALIO MAL",
                        'por favor comuniquese con el administrador del sistema'
                    );
                }

                break;

            default:
                break;
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
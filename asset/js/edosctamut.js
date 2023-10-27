function init(){
    $("#consulta_EdoCta").on("submit", function(e){
        consultaEdoCta(e);
    });
}

$(document).ready(function () {
    
});

$(".cveIssemym").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    
    if((key < 96 || key > 122) && (key < 48 || key > 57) && (key < 65 || key > 90) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46 && key !== 32&& key !== 32 && key !== 192){
        return false;
    }
});

$(".cveIssemym").change(function () {
    document.getElementById('CveIssemym').value = document.getElementById('CveIssemym').value.toUpperCase();
});

function consultaEdoCta(e) {
    e.preventDefault();

    var criterio= "";
    var validaCri = false;
    var statCri = false;

    if (document.getElementById("RadBtnCveIssemym").checked) {
        criterio ="cveissemym";
        statCri = true;
    }
    if (document.getElementById("RadBtnNombreJub").checked) {
        criterio ="nomcomjub";
        statCri = true;
    }

    if (!statCri) {
        Swal.fire(
            'SELECCIONE UN CRITERIO DE BUSQUEDA PARA EFECTUAR LA CONSULTA'
        );
    } else {
        validaCri= validacriterio(criterio, document.getElementById("CveIssemym").value);
        if (!validaCri) {
            Swal.fire(
                'EL DATO PROPORCIONADO ES INCORRECTO VERIFIQUE LA CLAVE O NOMBRE'
            );
        } else {
            $.post("../../controller/maestro.php?op=buscaEdoCta",{criterioBusq:criterio,valCriBusq:$("#CveIssemym").val()},function (data) {
                data = JSON.parse(data);
                dataResultBusqEdoscta = Object.values(data);
                $("#resultBusqEdoCta").html("");
                for (var index = 0; index < dataResultBusqEdoscta.length; index++) {
                    datosEdoCta = Object.values(dataResultBusqEdoscta[index]);
                    var tr = `<tr>
                            <td>`+(index + 1)+ `</td> 
                            <td>`+datosEdoCta[0]+ `</td> 
                            <td>`+datosEdoCta[1]+ `</td> 
                            <td>`+datosEdoCta[2]+ `</td> 
                            <td>`+datosEdoCta[3]+ `</td> 
                            <td>`+datosEdoCta[4]+ `</td> 
                            <td>`+datosEdoCta[5]+ `</td>
                            <td>`+datosEdoCta[6]+ `</td> 
                            <td>`+datosEdoCta[7]+ `</td> 
                        </tr>`;
                    $("#resultBusqEdoCta").append(tr);
                }    
            });
            
        }
    }
}

function validacriterio(criterio,valorCri){
    switch (criterio) {
        case "cveissemym":
            if (valorCri.length > 2 && valorCri.length < 8) {
                return true;
            } else {
                return false;
            } 
            break;
        case 'nomcomjub':
            if (valorCri.length > 4 && valorCri != "") {
                return true;
            } else {
                return false;
            } 
            break;
        default:
            break;
    }
}

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
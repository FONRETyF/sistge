var psgs_max = 20 ;
    
function init() {
    
    
    $("#edita_PSGS").on("submit",function(e){
        e.preventDefault();
        var formDataPSGS = new FormData($("#edita_PSGS")[0]);
        $.ajax({
            url: '../../controller/maestro.php?op=aniosPSGS',
            type: "POST",
            data: formDataPSGS,
            contentType: false,
            processData: false,
            success: function(datos){
                //alert(datos);
                $('#edita_PSGS')[0].reset();
                $("#editarPSGS").modal('hide');
                data = JSON.parse(datos);
                $('#numPsgs').val(data.numPSGS);
                diasActivo = document.getElementById('tiempoPsgs').value - data.diasPSGS;
                document.getElementById('tiempoPsgs').value = diasActivo;
                document.getElementById('aniosServMae').value = Math.trunc(diasActivo/365);    
                document.getElementById('fechsIniPSGS').value =  data.fechIni;
                document.getElementById('fechsfinPSGS').value = data.fechFin;          
            }
        });   
    });
}


$("#ModoRetiro").change(function () {
    var aniosserv = Math.floor(document.getElementById('aniosServMae').value);
    var modalidad = document.getElementById('ModoRetiro').value;
    $.post("../../controller/maestro.php?op=obtenRetiro",{aniosserv:aniosserv,modalidad:modalidad},function(data){       
        data = JSON.parse(data);
        $('#montRet').val(data.montret.toFixed(2));
        montoRetiro = document.getElementById('montRet').value;
    });
    if (modalidad=="C") {
        ocultaDivFondFall(modalidad);
    } else if (modalidad=="P" || modalidad=="FF") {
        ocultaDivFondFall(modalidad);
        document.getElementById("montSalMin").disabled =  false;
        if (modalidad=="P") {
            document.getElementById('montRetFF').value = (montoRetiro / 2).toFixed(2);
        }else if (modalidad=="FF"){
            document.getElementById('montRetFF').value = montoRetiro;
        }
    }
});

$(function(){
    
    $(".inputCURP").keydown(function(event){
        if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && (event.keyCode < 65 || event.keyCode > 90) && (event.keyCode < 97 || event.keyCode > 122) && event.keyCode !==37  && event.keyCode !==39 && event.keyCode !==8 && event.keyCode !==9 && event.keyCode !==46){
            return false;
        }else if (event.keyCode > 96 || event.keyCode < 123){
            this.value = this.value.toUpperCase();
        }
    });
});



/*$("#cveIMaeBusq").change(function () {
    
});

$('#CURPMae').change(function () {
    document.getElementById('RFCMae').value = document.getElementById('CURPMae').value.substr(0,10);
})

$("#fechBaseMae").change(function () {
    fechBase = $("#fechBaseMae").val();
});

$("#fechBajaMae").change(function () {
    fechBaja = $("#fechBajaMae").val();
});*/

function validaVigenciaTram(){
    
    var fechaRecibido = new Date(document.getElementById('fechRecibido').value);
    var fechaBaja = new Date(document.getElementById('fechBajaMae').value);
    alert("Entro en la funcion valida tramite");
    if((isNaN(Date.parse(fechaBaja))) || (isNaN(Date.parse(fechaRecibido))) ){
        Swal.fire(
            'La fecha de recibido o de baja no ha sido ingresada',
            'por favor verifique sus datos!'
        );
    }else if (fechaBaja != null && fechaBaja < fechaRecibido) {
        vigenciaTram = ((fechaRecibido.getTime() - fechaBaja.getTime()) / (1000 * 60 * 60 * 24))/365;
        alert("esta en esta funcion");
        if(vigenciaTram > 1){
            

        }
    }
}

/*function ocultaDivFondFall(modalidad){
    var x = document.getElementById("montRetFondFall");
    if (modalidad == "P" || modalidad=="FF") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}*/

function modifNomMae(){
    $('#modal-title').html('Modificar Nombre');
    $.post("../../controller/maestro.php?op=mostrarNom",{clavemae:clavemae},function(data){       
        data = JSON.parse(data);
        $('#cvemae').val(data.csp);
        $('#apepatModif').val(data.apepatmae);
        $('#apematModif').val(data.apematmae);
        $('#nommaeModif').val(data.nommae);
        $('#nomcomModif').val(data.nomcommae);
    });
    $('#editarNomMae').modal('show');
}



/*function calculaAñosServicio(){
    var fechaBase = new Date(document.getElementById('fechBaseMae').value);
    var fechaBaja = new Date(document.getElementById('fechBajaMae').value);
    
    if (isNaN(Date.parse(fechaBase)) || isNaN(Date.parse(fechaBaja))) {
        Swal.fire(
            'Falta una fecha para el calculo',
            'por favor verifique sus datos!'
        );
    } else {
        if (fechaBaja > fechaBase){
            var difDias = fechaBaja.getTime() - fechaBase.getTime();
            document.getElementById('numPsgs').value = 0;
            document.getElementById('tiempoPsgs').value = (difDias / (1000 * 60 * 60 * 24));
            document.getElementById('aniosServMae').value = Math.trunc((difDias / (1000 * 60 * 60 * 24))/365);
            document.getElementById("ModoRetiro").disabled =  false;
        }else if (fechaBaja != null && fechaBaja < fechaBase) {
            Swal.fire(
                'La fecha de baja debe ser posterior a la fecha de base',
                'por favor verifique sus datos!'
            );
            document.getElementById('tiempoPsgs').value = 0;
        }
    }  
}*/


function agregaPSGS(){        
    $('#tituto_mod_psgs').html('Agregar P.S.G.S');
    $('#edita_PSGS')[0].reset();
    $('#editarPSGS').modal('show');
}

var contPSGS = 0;
$("#addPSGS").click(function (e) {
    e.preventDefault();
    document.getElementById('numsPSGS').value = contPSGS + 1;
    if (contPSGS < psgs_max) {
        $('#DivFechsPSGSIni').append(
            '<div><input type="date" name="fechaIni[]" id="fechaIni"' + contPSGS + '><a href="#" class="delete_fecha"><img src="../../img/delete.png" alt="Nueva" title="Nueva Entrega" height="15" width="20"></a></input></div>'
        );
        $('#DivFechsPSGSFin').append(
            '<div><input type="date" name="fechaFin[]"  id="fechaFin"' + contPSGS + '><a href="#" class="delete_fecha"><img src="../../img/delete.png" alt="Nueva" title="Nueva Entrega" height="15" width="20"></a></input></div>'
        );
    contPSGS++
    }
    document.getElementById('numsPSGS').value = contPSGS + 1;
});

$('#DivFechsPSGS').on("click",".delete_fecha",function(e){
    e.preventDefault();
    $(this).parent('div').remove();
    contPSGS--;
    document.getElementById('numsPSGS').value = contPSGS;
});




init();
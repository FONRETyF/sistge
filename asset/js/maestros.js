function init() {

}


$(document).ready(

);

$(".CSPMae").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});


$(".cveissemym").keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$('#CURPMae').keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 65 || key > 90)  && (key < 97 || key > 122) && (key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});


$('#CURPMae').change(function () {
    document.getElementById('RFCMae').value = document.getElementById('CURPMae').value.substr(0,10).toUpperCase();
    if ($("#CURPMae").val().length < 18 ) {
        Swal.fire(
            'LA CLAVE CURP ES INCORRECTA',
            'deben ser 18 caracteres'
        );
        $("#CURPMae").focus();
        document.getElementById('CURPMae').style.border =  ".1em red solid";
    }else{
        document.getElementById('CURPMae').style.border =  ".1em black solid";
    }
});

$('#RFCMae').keydown(function (event) {
    var key = window.event ? event.which : event.keyCode;
    if((key < 65 || key > 90)  && (key < 97 || key > 122) && (key < 48 || key > 57) && (key < 96 || key > 105) && key !== 37 && key !==39 && key !==8 && key!==9 && key !==46){
        return false;
    }
});

$('#RFCMae').change(function () {
    if ($("#RFCMae").val().length < 10 || $("#RFCMae").val().length > 13) {
        Swal.fire(
            'LA CLAVE RFC ES INCORRECTA',
            'deben ser 10 o 13 caracteres'
        );
        $("#RFCMae").focus();
        document.getElementById('RFCMae').style.border =  ".1em red solid";
    }else{
        document.getElementById('RFCMae').style.border =  ".1em black solid";
    }
});



init();
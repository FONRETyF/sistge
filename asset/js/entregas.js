var tabla;

function init(){
    $("#edita_Entrega").on("submit",function(e){
        guardaryeditar(e);
    });
}

$("#editentrega").click(function () {
    $("#editarEntrega").modal('hide');
})

$(document).ready(function(){
    $('#entrega_data').dataTable({
        "aProcessing": true, //procesamiento dle datatable
        "aServerSide": true, //paginacion y filtrado por el servidor
        dom: 'Bfrtip', //definicion de los elementos del control de la tabla
        buttons: [		          
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax":{
            url: '../../controller/entregasController.php?op=listar',
            type : "post",
            dataType : "json",						
            error: function(e){
                console.log(e.responseText);	
            }
        },
        "ordering": false,
        'rowsGroup': [0,1],
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 5,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {          
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
    }).DataTable();
});

function guardaryeditar(e){
    e.preventDefault();

    var actionFechEntr = document.getElementById('CheckAsigFech');
    var checked = actionFechEntr.checked;

    var fechaentrega = $("#fechentrega").val();
    var numentrega = $("#numentrega").val();

    if ($("#numentrega").val() < 10) {
        numentrega = 0 + $("#numentrega").val();
    }

    var identrega = $("#Anioentrega").val() + numentrega;
    document.getElementById("identrega").value = identrega; 
    if (checked) {
        $.post("../../controller/entregasController.php?op=updateFech",{identrega:identrega,fechEntrega:fechaentrega},function(data){
            resultadoAdd = Object.values(JSON.parse(data));
            //NumregsResult = resultadoAdd.length;
            //alert(NumregsResult );
            if (resultadoAdd[0] == "Actualizado" && resultadoAdd[1] == "Actualizado" && resultadoAdd[2] == "Actualizado") {
                Swal.fire(
                    "LA FECHA SE MODIFICO CORRECTAMENTE"
                );
                $('#editarEntrega').modal('hide');
            } else {
                Swal.fire(
                    "ALGO SALIO MAL",
                    'por favor verifique los datos'
                );
            }
        });
    } else {
        var formData = new FormData($("#edita_Entrega")[0]);
        $.ajax({
            url: '../../controller/entregasController.php?op=guardaryeditar',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos){
                $('#edita_Entrega')[0].reset();
                $("#editarEntrega").modal('hide');
                $('#entrega_data').DataTable().ajax.reload();

                swal.fire(
                    'Registro!',
                    'La entrega se ingreso correctamente!!!',
                    'success'
                )
            }
        });
    }
}

function editar(identrega){
    $('#modal-title').html('Modificar entrega');
    $.post("../../controller/entregasController.php?op=mostrar",{identrega:identrega},function(data){       
        data = JSON.parse(data);
        $('#identrega').val(data.identrega);
        $('#numentrega').val(data.numentrega);
        $('#Anioentrega').val(data.anioentrega);
        $('#descentrega').val(data.descentrega);
        $('#fechentrega').val(data.fechentrega);
        $('#observaciones').val(data.observaciones);
    });
    $('#editarEntrega').modal('show');
    document.getElementById('DivAsignaFecha').style.display = "block";
}

function eliminar(identrega){
    swal.fire({
        title:'ELIMINACIÓN DE ENTREGA',
        text:"Eliminara la entrega " + identrega +"?",
        //icon: 'danger',
        showCancelButton: true,
        confirmButtonText:'Si',
        cancelButtonText:'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed){
            $.post("../../controller/entregasController.php?op=eliminar",{identrega:identrega},function(data){
            });
            tabla = $('#entrega_data').DataTable();
            tabla.ajax.reload();
            swal.fire(
                'Eliminado!',
                'La entrega se elimino correctamente!!!',
                'success'
            )
        }
    })
}

function detalleRetiros(identrega){
    location.href = "../../views/home/detalleRetiros.php" + "?identrega=" + identrega;
}

$(document).on("click","#entrNueva",function() {
    $('#modal-title').html('Nueva entrega');
    $('#edita_Entrega')[0].reset();
    $('#editarEntrega').modal('show');
    document.getElementById('DivAsignaFecha').style.display = "none";
});

var accionRegresa = document.querySelector('.Btnregresar');
accionRegresa.addEventListener("click", function (e) {
    e.preventDefault;

    let pagAnterior = document.referrer;
    if (pagAnterior.indexOf(window.location.host) !== -1) {
        window.history.back();
    }
});


init();

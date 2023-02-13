function init() {
    
}

const identrega =  document.getElementById("InputIdentrega").value;


$(document).ready(
    function () {
        /*alert("entro aqui");
        urlajax='../../controller/retiros.php?op=listar&identrega='+ identrega;
        alert(urlajax);*/
    $('#retiros_data').dataTable({
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
            url: '../../controller/retiros.php?op=listar&identrega='+ identrega,
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
        "iDisplayLength": 50,
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
    
    obtenEstatEntr(identrega);
});

function obtenEstatEntr(identrega) {
    $.post("../../controller/retiros.php?op=buscar",{identrega:identrega},function(data){       
    });
    
}

init();
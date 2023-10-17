function init() {
    
}

$(document).ready(function () {

    $('#tramsPend_data').dataTable({
        "aProcessing": true, //procesamiento dle datatable
        "aServerSide": true, //paginacion y filtrado por el servidor
        scrollY: '500px',
        scrollCollapse: true,
        paging: true,
        pagingType: 'full_numbers',
        dom: 'Bfrtip', //definicion de los elementos del control de la tabla
        buttons: [		          
            'copyHtml5',
            'excelHtml5',
            'csvHtml5'
        ],
        "ajax":{
            url: '../../controller/retiros.php?op=listarPendts',
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
        columnDefs:[
            {width: "5px",targets:0},
            {width: "5px",targets:1},
            {width: "5px",targets:2},
            {width: "5px",targets:3},
            {width: "5px",targets:4},
            {width: "5px",targets:5},
            {width: "5px",targets:6},
            {width: "5px",targets:7},
			{width: "5px",targets:8}
        ]
    }).DataTable();

    /*$('#tramsProrrg_data').dataTable({
        "aProcessing": true, //procesamiento dle datatable
        "aServerSide": true, //paginacion y filtrado por el servidor
        scrollY: '500px',
        scrollCollapse: true,
        paging: true,
        pagingType: 'full_numbers',
        dom: 'Bfrtip', //definicion de los elementos del control de la tabla
        buttons: [		          
            'copyHtml5',
            'excelHtml5',
            'csvHtml5'
        ],
        "ajax":{
            url: '../../controller/retiros.php?op=listarPendts',
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
        columnDefs:[
            {width: "5px",targets:0},
            {width: "5px",targets:1},
            {width: "5px",targets:2},
            {width: "5px",targets:3},
            {width: "5px",targets:4},
            {width: "5px",targets:5},
            {width: "5px",targets:6},
            {width: "5px",targets:7},
            {width: "5px",targets:8}
        ]
    }).DataTable();*/
});

/*var accionRegresa = document.querySelector('.Btnregresar');
accionRegresa.addEventListener("click", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});*/

$(".DivBotnsNav").on("click",".Btnregresar", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

/*var accionBtnInicio = document.getElementById('Btnnicio');
accionBtnInicio.addEventListener("click", function (e) {
    e.preventDefault();
    location.href = 'Inicio.php';
});*/

$(".DivBotnsNav").on("click",".BtnInicio", function (e) {
    e.preventDefault();
    javascript:history.go(-1);
});

function imprimeProga(cvemae) {
    location.href = "../../views/home/acuerdoProrroga.php" + "?cvemae=" + cvemae;
}

init();
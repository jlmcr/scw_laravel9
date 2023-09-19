$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel", "pdf"],
        "language": {
            "zeroRecords": "Nada encontrado - disculpa",
            "info": "Mostrando pagina _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            'search': 'Buscar:',
            'paginate': {
                'next': '>>',
                'previous': '<<'
            }
        }

    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});

$(function() {
    $(document).DataTable({
        "language": {
            // "lengthMenu": "Mostrar _MENU_ registros por pagina",
            /*                 "lengthMenu": "Mostrar " +
                                                `<select class='form-control input-sm'>
                                                <option value='5'>5</option>
                                                <option value='10'>10</option>
                                                <option value='25'>25</option>
                                                <option value='50'>50</option>
                                                <option value='100'>100</option>
                                                <option value='-1'>Todos</option>
                                                </select>`+
                                            "registros por pagina", */
            "zeroRecords": "Nada encontrado - disculpa",
            "info": "Mostrando pagina _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            'search': 'Buscar:',
            'paginate': {
                'next': '>>',
                'previous': '<<'
            }
        }
    });
});

function mododark() {
    document.get getElementsByClassName("").classList.toggle("");
}
document.getElementById("boton").onclick = function() {
    mododark();
}

let te = "fdsfs";
te.slice;

var ef = document.getElementsByName('ejercicioFiscal');
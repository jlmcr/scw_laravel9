function funcionDuplicados() {
    var arregloVentas = new Array();

    $('body tr').each(function() {

        /* arregloVentas[$(this).attr('id')] = $(this).find('.nit').html() +
            $(this).find('.autorizacion').html() +
            $(this).find('.numerofac').html() +
            $(this).find('.fecha').html(); */

        //quitamos la clase de alerta por si lo tiene antes, esto por la eliminacion multiple, ya que no se recarga
        $(this).removeClass('bg-warning');

        arregloVentas.push($(this).attr('id_validador'));

    })


    var duplicados = new Array();
    const temporal = arregloVentas.sort(); //ordenamos

    //console.log(temporal);

    for (var i = 0; i < temporal.length; i++) {

        if (temporal[i + 1] == temporal[i]) {

            duplicados.push(temporal[i]);
        }
    }

    //console.log(duplicados[0]);

    for (var i = 0; i < duplicados.length; i++) {
        $('.' + duplicados[i]).addClass("bg-warning");
    }
}


$(document).ready(function() {
    funcionDuplicados();
})
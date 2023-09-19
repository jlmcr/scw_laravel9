function funcionDuplicados() {
    var arregloCompras = new Array();

    $('body tr').each(function() {

        /* arregloCompras[$(this).attr('id')] = $(this).find('.nit').html() +
            $(this).find('.autorizacion').html() +
            $(this).find('.numerofac').html() +
            $(this).find('.fecha').html(); */


        //quitamos la clase de alerta por si lo tiene antes, esto por la eliminacion multiple, ya que no se recarga
        $(this).removeClass('bg-warning');

        arregloCompras.push($(this).attr('id_validador'));

    })


    //YA NO USAMOS UNA MATRIZ, SI NO UN CAMPO UNICO COMO VALIDADOR Y UNA CLASE
    var duplicados = new Array();
    const temporal = arregloCompras.sort(); //ordenamos
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
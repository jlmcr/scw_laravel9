/* calculos automaticos para subtotal y Base CF y CF*/
function calculo_DF() {
    /* toFixed() redondea el número hacia arriba, es decir, si tenemos, por ejemplo 11.123,
    el valor resultante es 11.12, ya si tenemos 20.555, el valor resultante 20.56. Otro aspecto
    importante es que su retorno será una string representando el número */

    debitoFiscal.value = (baseParaDF.value * 0.13).toFixed(2); //redondeamos
    var debitoFiscalAuxiliar = debitoFiscal.value;

    debitoFiscal.value = numeral(debitoFiscalAuxiliar).format('0.00');
}

function calculo_baseParaDF() {

    baseParaDF.value = subtotal.value - descuentos.value - gifCard.value;
    var baseParaDFauxiliar = baseParaDF.value;
    baseParaDF.value = numeral(baseParaDFauxiliar).format('0.00');
}

function calculo_SubtotalVenta() {

    subtotal.value = importeTotal.value - ice.value - iehd.value - ipj.value - tasas.value - otrosNoSujetosaIva.value - exportacionesyExentos.value - tasaCero.value;

    var subtotalAuxiliar = subtotal.value;

    subtotal.value = numeral(subtotalAuxiliar).format('0.00');

    calculo_baseParaDF();
    calculo_DF();
}

/* cambios en total venta  */
importeTotal.oninput = calculo_SubtotalVenta; //! al cambiar algo dentro del input

importeTotal.onchange = function() {
    importeTotal.value = numeral(importeTotal.value).format('0.00');

    calculo_SubtotalVenta(); //se debe calcular nuevamente el subtotal despues de salir del imput

    // *var input = document.getElementById(nombre);
    // *input.value = numeral(input.value).format('0.00');
}; //! al cambiar de input

/* cambios en ice  */
ice.oninput = calculo_SubtotalVenta;
ice.onchange = function() {
    ice.value = numeral(ice.value).format('0.00');
};

/* cambios en iehd  */
iehd.oninput = calculo_SubtotalVenta;
iehd.onchange = function() {
    iehd.value = numeral(iehd.value).format('0.00');
};

/* cambios en ipj  */
ipj.oninput = calculo_SubtotalVenta;
ipj.onchange = function() {
    ipj.value = numeral(ipj.value).format('0.00');
};

/* cambios en tasas  */
tasas.oninput = calculo_SubtotalVenta;
tasas.onchange = function() {
    tasas.value = numeral(tasas.value).format('0.00');
};

/* cambios en otrosNoSujetosaIva  */
otrosNoSujetosaIva.oninput = calculo_SubtotalVenta;
otrosNoSujetosaIva.onchange = function() {
    otrosNoSujetosaIva.value = numeral(otrosNoSujetosaIva.value).format('0.00');
};

/* cambios en exportacionesyExentos  */
exportacionesyExentos.oninput = calculo_SubtotalVenta;
exportacionesyExentos.onchange = function() {
    exportacionesyExentos.value = numeral(exportacionesyExentos.value).format('0.00');
};

/* cambios en tasaCero  */
tasaCero.oninput = calculo_SubtotalVenta;
tasaCero.onchange = function() {
    tasaCero.value = numeral(tasaCero.value).format('0.00');
};

/* cambios en descuentos  */
descuentos.oninput = calculo_SubtotalVenta;
descuentos.onchange = function() {
    descuentos.value = numeral(descuentos.value).format('0.00');
};

/* cambios en gifCard  */
gifCard.oninput = calculo_SubtotalVenta;
gifCard.onchange = function() {
    gifCard.value = numeral(gifCard.value).format('0.00');
};

/* formato del codigoControl */
function formato_codigoControl() {
    let cadena = codigoControl.value;
    let texto = cadena.split('');
    let nuevotexto = '';

    if (texto != "") {
        if (texto.length % 3 == 0 && texto[texto.length - 1] != '-') {

            for (let i = 0; i < texto.length - 1; i++) {

                nuevotexto = nuevotexto + texto[i];

            }

            nuevotexto = nuevotexto + '-' + texto[texto.length - 1];
            codigoControl.value = nuevotexto;
        }
    }
}
//oninput //! al cambiar algo dentro del input
//onchange //! al cambiar de input
codigoControl.oninput = formato_codigoControl;

// ===========================================================
// ===========================================================
// ===========================================================

//PERMITIR SOLO NUMEROS EN FECHA, NUMERO Y CI

$("#fechaDia, #numeroFactura, #ciNitCliente").on('input', function(evt) {
    $(this).val($(this).val().replace(/[^0-9]/g, '')); //reemplazamos digitos que no sean del solo de 0 al 9
});

$("#fechaDia_editar, #numeroFactura_editar, #ciNitCliente_editar").on('input', function(evt) {
    $(this).val($(this).val().replace(/[^0-9]/g, '')); //reemplazamos digitos que no sean del solo de 0 al 9
});


//CODIGO DE CONTROL
$("#codigoControl").on('input', function() {
    // https://www.w3schools.com/jsref/jsref_regexp_not_0-9.asp
    // https://www.w3schools.com/jsref/jsref_obj_regexp.asp
    // https://www.w3schools.com/jsref/jsref_obj_regexp.asp#:~:text=Find%20any%20of%20the%20alternatives%20specified

    $(this).val($(this).val().replace(/[^A-Fa-f0-9|-]/g, '')); //reemplazamos digitos que no sean lo que esta entre corchetes - | es una alternativa - 3er enlace
});

$("#codigoControl_editar").on('input', function() {
    $(this).val($(this).val().replace(/[^A-Fa-f0-9|-]/g, '')); //reemplazamos digitos que no sean lo que esta entre corchetes - | es una alternativa - 3er enlace
});
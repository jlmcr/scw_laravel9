/* calculos automaticos para subtotal y Base CF y DF*/
function calculo_DF_editar() {
    /* toFixed() redondea el número hacia arriba, es decir, si tenemos, por ejemplo 11.123,
    el valor resultante es 11.12, ya si tenemos 20.555, el valor resultante 20.56. Otro aspecto
    importante es que su retorno será una string representando el número */

    debitoFiscal_editar.value = (baseParaDF_editar.value * 0.13).toFixed(2); //redondeamos
    var debitoFiscalAuxiliar = debitoFiscal_editar.value;

    debitoFiscal_editar.value = numeral(debitoFiscalAuxiliar).format('0.00');
}

function calculo_baseParaDF_editar() {
    baseParaDF_editar.value = subtotal_editar.value - descuentos_editar.value - gifCard_editar.value;

    var baseParaDFauxiliar = baseParaDF_editar.value;

    baseParaDF_editar.value = numeral(baseParaDFauxiliar).format('0.00');
}

function calculo_SubtotalVenta_editar() {

    subtotal_editar.value = importeTotal_editar.value - ice_editar.value - iehd_editar.value - ipj_editar.value - tasas_editar.value - otrosNoSujetosaIva_editar.value - exportacionesyExentos_editar.value - tasaCero_editar.value;

    var subtotalAuxiliar = subtotal_editar.value;

    subtotal_editar.value = numeral(subtotalAuxiliar).format('0.00');

    calculo_baseParaDF_editar();
    calculo_DF_editar();
}

/* cambios en total venta  */
importeTotal_editar.oninput = calculo_SubtotalVenta_editar; //! al cambiar algo dentro del input
importeTotal_editar.onchange = function() {
    importeTotal_editar.value = numeral(importeTotal_editar.value).format('0.00');

    calculo_SubtotalVenta_editar(); //se debe calcular nuevamente el subtotal despues de salir del imput

    // *var input = document.getElementById(nombre);
    // *input.value = numeral(input.value).format('0.00');
}; //! al cambiar de input

/* cambios en ice  */
ice_editar.oninput = calculo_SubtotalVenta_editar;
ice_editar.onchange = function() {
    ice_editar.value = numeral(ice_editar.value).format('0.00');
};

/* cambios en iehd  */
iehd_editar.oninput = calculo_SubtotalVenta_editar;
iehd_editar.onchange = function() {
    iehd_editar.value = numeral(iehd_editar.value).format('0.00');
};

/* cambios en ipj  */
ipj_editar.oninput = calculo_SubtotalVenta_editar;
ipj_editar.onchange = function() {
    ipj_editar.value = numeral(ipj_editar.value).format('0.00');
};

/* cambios en tasas  */
tasas_editar.oninput = calculo_SubtotalVenta_editar;
tasas_editar.onchange = function() {
    tasas_editar.value = numeral(tasas_editar.value).format('0.00');
};

/* cambios en otrosNoSujetosaCF  */
otrosNoSujetosaIva_editar.oninput = calculo_SubtotalVenta_editar;
otrosNoSujetosaIva_editar.onchange = function() {
    otrosNoSujetosaIva_editar.value = numeral(otrosNoSujetosaIva_editar.value).format('0.00');
};

/* cambios en excentos  */
exportacionesyExentos_editar.oninput = calculo_SubtotalVenta_editar;
exportacionesyExentos_editar.onchange = function() {
    exportacionesyExentos_editar.value = numeral(exportacionesyExentos_editar.value).format('0.00');
};

/* cambios en tasaCero  */
tasaCero_editar.oninput = calculo_SubtotalVenta_editar;
tasaCero_editar.onchange = function() {
    tasaCero_editar.value = numeral(tasaCero_editar.value).format('0.00');
};

/* cambios en descuentos  */
descuentos_editar.oninput = calculo_SubtotalVenta_editar;
descuentos_editar.onchange = function() {
    descuentos_editar.value = numeral(descuentos_editar.value).format('0.00');
};

/* cambios en gifCard  */
gifCard_editar.oninput = calculo_SubtotalVenta_editar;
gifCard_editar.onchange = function() {
    gifCard_editar.value = numeral(gifCard_editar.value).format('0.00');
};

/* formato del codigoControl */
function formato_codigoControl_editar() {
    let cadena = codigoControl_editar.value;
    let texto = cadena.split('');
    let nuevotexto = '';

    if (texto != "") {
        if (texto.length % 3 == 0 && texto[texto.length - 1] != '-') {

            for (let i = 0; i < texto.length - 1; i++) {

                nuevotexto = nuevotexto + texto[i];

            }

            nuevotexto = nuevotexto + '-' + texto[texto.length - 1];
            codigoControl_editar.value = nuevotexto;
        }
    }

    if (texto.length == 3 && texto[2] != '-') //* abc (3) ->   ab-c
    {
        codigoControl.value = texto[0] + texto[1] + '-' + texto[2];
    }

    if (texto.length == 6 && texto[5] != '-') //* ab-cde ()  ->  ab-cd-e
    {
        codigoControl.value = texto[0] + texto[1] + texto[2] + texto[3] + texto[4] + '-' + texto[5];
    }

    if (texto.length == 9 && texto[8] != '-') //* ab-cd-efg (9)  ->  ab-cd-ef-g
    {
        codigoControl.value = texto[0] + texto[1] + texto[2] + texto[3] + texto[4] + texto[5] + texto[6] + texto[7] + '-' + texto[8];
    }

    if (texto.length == 12 && texto[11] != '-') //* ab-cd-ef-ghi (12)  ->  ab-cd-ef-gh-i
    {
        codigoControl.value = texto[0] + texto[1] + texto[2] + texto[3] + texto[4] + texto[5] + texto[6] + texto[7] + texto[8] + texto[9] + texto[10] + '-' + texto[11];
    }

    if (texto.length == 15 && texto[14] != '-') //* ab-cd-ef-gh-ijk (15)  ->  ab-cd-ef-gh-ij-k
    {
        codigoControl.value = texto[0] + texto[1] + texto[2] + texto[3] + texto[4] + texto[5] + texto[6] + texto[7] + texto[8] + texto[9] + texto[10] + texto[11] + texto[12] + texto[13] + '-' + texto[14];
    }
}

//oninput //! al cambiar algo dentro del input
//onchange //! al cambiar de input
codigoControl_editar.oninput = formato_codigoControl_editar;
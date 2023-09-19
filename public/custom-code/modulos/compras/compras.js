/* var subtotal = document.getElementById("subtotal");
var total = document.getElementById("total");
 */
// ********************************************************

/* UTILIZAMOS LA LIBRERIA NUMERAL */

/* document.getElementById("btnAgregarCompra").click = function() {
    var elem = document.getElementById("razonSocialProveedor");
    setTimeout(() => {
        elem.focus();
    }, 1);
}
 */


/* calculos automaticos para subtotal y Base CF y CF*/
function calculo_CF() {
    /* toFixed() redondea el número hacia arriba, es decir, si tenemos, por ejemplo 11.123,
    el valor resultante es 11.12, ya si tenemos 20.555, el valor resultante 20.56. Otro aspecto
    importante es que su retorno será una string representando el número */

    creditoFiscal.value = (baseParaCF.value * 0.13).toFixed(2); //redondeamos
    var creditoFiscalAuxiliar = creditoFiscal.value;

    creditoFiscal.value = numeral(creditoFiscalAuxiliar).format('0.00');
}

function calculo_baseParaCF() {
    baseParaCF.value = subtotal.value - descuentos.value - gifCard.value;

    var baseParaCFauxiliar = baseParaCF.value;

    baseParaCF.value = numeral(baseParaCFauxiliar).format('0.00');
}

function validarCombustibleCheckBox() {
    //! calculo de 30% de combustible
    var checkboxCombustible = document.getElementById("checkboxCombustible");

    if (checkboxCombustible.checked == true) {
        otrosNoSujetosaCF.value = (importeTotal.value * 0.30).toFixed(2); //redondeamos
    } else {
        otrosNoSujetosaCF.value = 0;
    }
    otrosNoSujetosaCF.value = numeral(otrosNoSujetosaCF.value).format('0.00');
}

function calculo_SubtotalCompra() {

    subtotal.value = importeTotal.value - ice.value - iehd.value - ipj.value - tasas.value - otrosNoSujetosaCF.value - exentos.value - tasaCero.value;

    var subtotalAuxiliar = subtotal.value;

    subtotal.value = numeral(subtotalAuxiliar).format('0.00');

    calculo_baseParaCF();
    calculo_CF();
}

/* cambios en total compra  */
importeTotal.oninput = calculo_SubtotalCompra; //! al cambiar algo dentro del input
importeTotal.onchange = function() {
    importeTotal.value = numeral(importeTotal.value).format('0.00');
    validarCombustibleCheckBox(); //se calcula otros no sujetos a Cf
    calculo_SubtotalCompra(); //se debe calcular nuevamente el subtotal despues de salir del imput

    // *var input = document.getElementById(nombre);
    // *input.value = numeral(input.value).format('0.00');
}; //! al cambiar de input

/* cambios en ice  */
ice.oninput = calculo_SubtotalCompra;
ice.onchange = function() {
    ice.value = numeral(ice.value).format('0.00');
};

/* cambios en iehd  */
iehd.oninput = calculo_SubtotalCompra;
iehd.onchange = function() {
    iehd.value = numeral(iehd.value).format('0.00');
};

/* cambios en ipj  */
ipj.oninput = calculo_SubtotalCompra;
ipj.onchange = function() {
    ipj.value = numeral(ipj.value).format('0.00');
};

/* cambios en tasas  */
tasas.oninput = calculo_SubtotalCompra;
tasas.onchange = function() {
    tasas.value = numeral(tasas.value).format('0.00');
};

/* cambios en otrosNoSujetosaCF  */
otrosNoSujetosaCF.oninput = calculo_SubtotalCompra;
otrosNoSujetosaCF.onchange = function() {
    otrosNoSujetosaCF.value = numeral(otrosNoSujetosaCF.value).format('0.00');
};

/* cambios en exentos  */
exentos.oninput = calculo_SubtotalCompra;
exentos.onchange = function() {
    exentos.value = numeral(exentos.value).format('0.00');
};

/* cambios en tasaCero  */
tasaCero.oninput = calculo_SubtotalCompra;
tasaCero.onchange = function() {
    tasaCero.value = numeral(tasaCero.value).format('0.00');
};

/* cambios en descuentos  */
descuentos.oninput = calculo_SubtotalCompra;
descuentos.onchange = function() {
    descuentos.value = numeral(descuentos.value).format('0.00');
};

/* cambios en gifCard  */
gifCard.oninput = calculo_SubtotalCompra;
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

    /*
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
        } */
}
//oninput //! al cambiar algo dentro del input
//onchange //! al cambiar de input
codigoControl.oninput = formato_codigoControl;

//CHECKBOX
function checkClick() {
    validarCombustibleCheckBox(); //se calcula otros no sujetos a Cf
    calculo_SubtotalCompra(); //se debe calcular nuevamente el subtotal despues de salir del imput
}

//checkboxCombustible.addEventListener('click', checkClick);

document.getElementById("checkboxCombustible").onclick = checkClick;

// ===========================================================
// ===========================================================
// ===========================================================

//oninput //! al cambiar algo dentro del input
//onchange //! al cambiar de input


//Validacion de Codigo de autorizacion - numero de factura - dui
dim.oninput = function() {
    let cadena = dim.value
    let texto = cadena.split('');

    if (texto.length >= 3) {
        numeroFactura.value = 0;
        codigoAutorizacion.value = 3;
    }
};

//si codigoAutorizacion es 3 oen 1
// ponemos la factura en 0 o en dui en 0
// por que se registrara una DUI
codigoAutorizacion.onchange = function() {
    //al salir del imput
    let cadena = codigoAutorizacion.value
    let texto = cadena.split('');

    if (texto.length == 1) {
        if (texto[0] == 3) {
            numeroFactura.value = 0;
        }
        if (texto[0] == 1) {
            dim.value = 0;
        }
    }
    if (texto.length >= 5) {
        dim.value = 0;
    }
};

// ===========================================================
// ===========================================================
// ===========================================================

//PERMITIR SOLO NUMEROS EN FECHA, NUMERO Y CI

$("#fechaDia, #numeroFactura, #nitProveedor").on('input', function() {
    $(this).val($(this).val().replace(/[^0-9]/g, '')); //reemplazamos digitos que no sean del solo de 0 al 9
});

$("#fechaDia_editar, #numeroFactura_editar, #nitProveedor_editar").on('input', function() {
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
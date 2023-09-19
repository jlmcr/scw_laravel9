function calculoNumeroComprobante() {
    //Swal.fire($('#fecha').val());

    if ($('#tipoComprobante').val() != "" && $('#fecha').val() != "") {

        let tipoComprobante = $('#tipoComprobante').val();
        let fecha = $('#fecha').val();

        $.ajax({
            //url: "{{route('numero-comprobante')}}", //no funciona en archivos externos
            //url: "<?php route('numero-comprobante') ?>",
            url: "/contabilidad/numero-comprobante",
            type: "GET",
            datatype: 'json',
            data: { tipoComprobante: tipoComprobante, fecha: fecha },
            success: function(response) {
                // console.log(response);
                // console.log(response.numero_comprobante); //esto no funciona
                // console.log(response[0].numero_comprobante);

                $("#nroComprobante").val(response[0].numero_comprobante);
                $("#correlativo").val(response[0].correlativo);
                if (response[0].fecha == "fuera de periodo") {
                    //swal.fire("La fecha del comprobante no se encuentra dentro del periodo que comprende el ejercicio contable.");
                    toastr.error('Por favor asegurese de tener una Empresa Activa en el Sistema.');
                    toastr.error('La fecha del comprobante no se encuentra dentro del periodo que comprende el ejercicio contable.');
                }
                if (response[0].fecha == "erronea") {
                    //swal.fire("La fecha es errónea.");
                    toastr.error('Por favor revise la fecha del comprobante de contabilidad.');
                }

            }
        })
    }
};
// focusout : Esta para cuando pierda el foco el input valide la fecha. 

$('#tipoComprobante').change(function() {
    calculoNumeroComprobante();
});

$('#fecha').focusout(function() {
    calculoNumeroComprobante();
});
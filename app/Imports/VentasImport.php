<?php

namespace App\Imports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\ToModel;
//fechas
use Carbon\Carbon;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
//validacion
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;


class VentasImport implements ToModel,WithBatchInserts, WithChunkReading, WithValidation
{

    //! para enviar parametros utilizamos el constuctor y atributos
    //atributos
    // private $gestionBuscada;
    // private $mesBuscado;
    private $idSucursalBuscada;

    //constructor para envio de variables
    public function  __construct($idSucursalBuscada)
    {
        // $this->gestionBuscada = $gestionBuscada;
        // $this->mesBuscado = $mesBuscado;
        $this->idSucursalBuscada = $idSucursalBuscada;
    }

    public function model(array $row)
    {
        return new Venta([
            'fecha' => Carbon::instance(Date::excelToDateTimeObject($row[2])),
            'numeroFactura' => $row[3],
            'codigoAutorizacion' => $row[4],
            'ciNitCliente' => $row[5],
            'complemento' => $row[6],
            'razonSocialCliente' => $row[7],
            'importeTotal' => $row[8],
            'ice' => $row[9],
            'iehd' => $row[10],
            'ipj' => $row[11],
            'tasas' => $row[12],
            'otrosNoSujetosaIva' => $row[13],
            'exportacionesyExentos' => $row[14],
            'tasaCero' => $row[15],
            'descuentos' => $row[17],
            'gifCard' => $row[18],
            'estado' => $row[21],
            'codigoControl' => $row[22],
            'tipoVenta' => $row[23],
            'sucursal_id' => $this->idSucursalBuscada,
        ]);
    }

    //!Row Validation
    // https://es.stackoverflow.com/questions/515774/no-puedo-traer-los-errores-del-importador-de-usuarios-de-excel-laravel
    // https://docs.laravel-excel.com/3.1/imports/validation.html#gathering-all-failures-at-the-end

    public function rules(): array
    {
        return [
             //?se usa asi para cuando se usa batchs *.1
            //fecha
            '*.2' => [
                'required',
                'numeric'
            ],
            //numero factura
            '*.3' => [
                'numeric',
            ],
            //ci nit
            '*.5' => [
                'required',
                'max:15'
            ],
            //complemento
            '*.6' => [
                'max:5'
            ],
            //razon social
            '*.7' => [
                'required',
                'max:150'
            ],
            //estado
            '*.21' => [
                'required',
                'max:1'
            ],
            //el codigo de control - cero u otro
            //para que en la base de datos, todos tengan cero
            '*.22' => [
                'required',
                'max:20'
            ],
            //tipo venta
            '*.23' => [
                'required',
                'max:1'
            ],

            //IMPORTES
            '*.8' => [
                'required',
                'numeric'
            ],
            '*.9' => [
                'required',
                'numeric'
            ],
            '*.10' => [
                'required',
                'numeric'
            ],
            '*.11' => [
                'required',
                'numeric'
            ],
            '*.12' => [
                'required',
                'numeric'
            ],
            '*.13' => [
                'required',
                'numeric'
            ],
            '*.14' => [
                'required',
                'numeric'
            ],
            '*.15' => [
                'required',
                'numeric'
            ],

            '*.17' => [
                'required',
                'numeric'
            ],
            '*.18' => [
                'required',
                'numeric'
            ],


        ];
    }

/*     public function customValidationMessages()
    {
        return [
            //nit
            '2.in' => 'El NIT es requerido y debe tener máximo 15 carácteres',
                //razon
            '3.in' => 'La razon social es requerido y debe tener máximo 150 carácteres',
                //autorizacion
            '4.in' => 'El código de autorizacion puede tener máximo 100 carácteres',
                //fecha
            '7.in' => 'La fecha es requerida',
                //tipo compra
            '21.in' => 'El tipo de comrpa es requerido',
        ];
    } */


    //! ESTOS DOS METODOS ES PARA CARGAR MUCHOS DATOS
    // WithBatchInserts, WithChunkReading
    // https://www.youtube.com/watch?v=peB01zLSfHY
    //? DIVIDE EL PARTES LOS DATOS
    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}

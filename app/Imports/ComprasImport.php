<?php

namespace App\Imports;

use App\Models\Compra;
use Maatwebsite\Excel\Concerns\ToModel;
//fechas
use Carbon\Carbon;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
//validacion
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ComprasImport implements ToModel,WithBatchInserts, WithChunkReading, WithValidation
{

    //! para enviar parametros utilizamos el constuctor y atributos
    //atributos
    //! private $gestionBuscada;
    //! private $mesBuscado;
    private $idSucursalBuscada;

    //constructor para envio de variables
    public function  __construct($idSucursalBuscada)
    {
        //! $this->gestionBuscada = $gestionBuscada;
        //! $this->mesBuscado = $mesBuscado;
        $this->idSucursalBuscada = $idSucursalBuscada;
    }

    //importamos
    public function model(array $row)
    {
        /*Saltar filas En caso de que desee omitir una fila, puede devolver nulo. */
        // if ($row[0]) {
        //     return null;
        // }

        return new Compra([
            'nitProveedor' => $row[2],
            'razonSocialProveedor'=> $row[3],
            'codigoAutorizacion' => $row[4],
            'numeroFactura' => $row[5],
            'dim' => $row[6],
            'fecha' => Carbon::instance(Date::excelToDateTimeObject($row[7])),
            'importeTotal' => $row[8],
            'ice' => $row[9],
            'iehd' => $row[10],
            'ipj' => $row[11],
            'tasas' => $row[12],
            'otrosNoSujetosaCF' => $row[13],
            'exentos' => $row[14],
            'tasaCero' => $row[15],
            'descuentos' => $row[17],
            'gifCard' => $row[18],
            'tipoCompra' => $row[21],
            'codigoControl' => $row[22],
            'combustible' => 0,
            'ultimoCodigoAutorizacion'=>$row[4],
            'sucursal_id'=>$this->idSucursalBuscada
        ]);
    }

    //!Row Validation
    // https://es.stackoverflow.com/questions/515774/no-puedo-traer-los-errores-del-importador-de-usuarios-de-excel-laravel
    // https://docs.laravel-excel.com/3.1/imports/validation.html#gathering-all-failures-at-the-end

    public function rules(): array
    {
        return [
             //?se usa asi para cuando se usa batchs *.1
                //nit
            '*.2' => [
                'required',
                'max:15'
            ],
                //razon
            '*.3' =>[
                'required',
                'max:150'
            ],
                //autorizacion
            '*.4' => [
                'max:100'
            ],
                //fecha
            '*.7' => [
                'required',
                'numeric'
            ],
                //tipo compra
            '*.21' => [
                'required',
                'max:1'
            ],
            //el codigo de control - cero u otro
            //para cuando ninguna factura tenga codigo de control
            '*.22' => [
                'required',
                'max:20'
            ],
            //VALIDACION DE IMPORTES
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

    public function customValidationMessages()
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
    }

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

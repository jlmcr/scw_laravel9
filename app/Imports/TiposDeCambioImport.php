<?php

namespace App\Imports;

use App\Models\TipoDeCambio;
use Maatwebsite\Excel\Concerns\ToModel;
//fechas
use Carbon\Carbon;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
//validacion
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
//encabezados
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TiposDeCambioImport implements ToModel, WithValidation, WithHeadingRow
{

    public function model(array $row)
    {
        return new TipoDeCambio([
            'fecha' => Carbon::instance(Date::excelToDateTimeObject($row['fecha'])),
            'ufv' => $row['ufv'],
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
            'fecha' => [
                'required',
                'numeric'
            ],

            //ufv
            'ufv' => [
                'required',
                'numeric',
                'max:7'
            ],
        ];
    }

}

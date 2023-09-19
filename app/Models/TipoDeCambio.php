<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeCambio extends Model
{
    use HasFactory;
    protected $table = "tipos_de_cambio";
    protected $fillable =[
        'fecha',
        'ufv'
    ];
}

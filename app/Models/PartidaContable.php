<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidaContable extends Model
{
    use HasFactory;

    protected $primaryKey = 'codigo'; //cuando el id es tiene otro nombre de "id"
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table ="pc_partida_contable";
}

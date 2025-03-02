<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculos extends Model
{
    protected $table = 'veiculo';

    protected $fillable = ['id','placa','id_proprietario','cor','marca','ano_fabricacao','ano_modelo', 'ativado'];
}

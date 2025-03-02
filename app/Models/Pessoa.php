<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = 'cliente';

    protected $fillable = ['id','nome','cpf_cnpj','data_nascimento','sexo', 'ativado'];
}

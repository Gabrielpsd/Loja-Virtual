<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    protected $table = 'servicos';

    protected $fillable = ['id','descricao', 'preco', 'ativado'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    protected $table = 'ordem_servico';

    protected $fillable = ['id','id_cliente', 'id_veiculo', 'ativado', 'data'];
}

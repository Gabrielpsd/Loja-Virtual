<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function vendasPorPeriodo(Request $request)
    {
        $select = <<< VENDASPORPERIODO
            select 
                sum(valor
        VENDASPORPERIODO;
    }

    public function vendasPorSexo(Request $request)
    {
        $select = <<< VENDASPORSEXO
            select 
                count(*) as quantidade,
                case sexo 
                    when 'M' then 'Masculino'
                    when 'F' then 'Feminino'
                    else 'Não informado'
                end as sexo
            from 
                ordem_servico
            inner join cliente on
                cliente.id = ordem_servico.id_cliente
            group by
                sexo
        VENDASPORSEXO;

        $query = DB::select($select);

        $response[0] = ['value' => $query[0]->quantidade, 
                        'name' => $query[0]->sexo];
        $response[1] = ['value' => $query[1]->quantidade, 
                        'name' => $query[1]->sexo];

        return response()->json($response);
    }
}

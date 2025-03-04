<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function quantidadeVendasPorPeriodo(Request $request)
    {
        $select = <<< VENDASPORPERIODO
            select  
                coalesce(sum(quantidade * preco),0) as value,
                TO_CHAR(ordem_servico.data, 'DD/MM/YYYY') AS name
            from 
                ordem_servico 
            left join ordem_servico_servicos on 
                ordem_servico_servicos.id_ordem_servico = ordem_servico.id
            where 
                ordem_servico.data between ? and ?
            group by 
                ordem_servico.data
        VENDASPORPERIODO;

        $query = DB::select($select,[$request['data_inicial'],$request['data_final']]);

        return response()->json($query);
    }

    public function totalPorPeriodo(Request $request)
    {
        $select = <<< TOTALPORPERIODO
            select  
                coalesce(sum(quantidade * preco),0) as value
            from 
                ordem_servico 
            left join ordem_servico_servicos on 
                ordem_servico_servicos.id_ordem_servico = ordem_servico.id
            where 
                ordem_servico.data between ? and ?
        TOTALPORPERIODO;

        $query = DB::select($select,[$request['data_inicial'],$request['data_final']]);

        return response()->json($query);
    }

    public function totalIdadePorSexo(Request $request)
    {
        $select = <<< TOTALIDADEPORSEXO
            select 
            case sexo 
                when 'M' then 'Masculino'
                when 'F' then 'Feminino'
                else 'Não informado'
            end as sexo, 
            coalesce(EXTRACT(YEAR FROM AGE(data_nascimento)),0) AS idade,
            count(*) as quantidade
            from 
                cliente
            group by 
                sexo,
                idade
            order by 
            idade
        TOTALIDADEPORSEXO;

        $query = DB::select($select);

        return response()->json($query);
    
    }
    public function totalValorPorMarca(Request $request)
    {
        $select = <<< TOTALVALORPORMARCA
            select 
                coalesce(sum(quantidade * preco),0) as value,
                marcas.descricao as name
            from 
                ordem_servico
            left join veiculo on  
                ordem_servico.id_veiculo = veiculo.id
            left join ordem_servico_servicos on 
                    ordem_servico_servicos.id_ordem_servico = ordem_servico.id
            join marcas on 
                marcas.id = veiculo.marca
            where 
                ordem_servico.data between ? and ?
            group by 
                marcas.descricao

        TOTALVALORPORMARCA;

        $query = DB::select($select,[ $request['data_inicial'],$request['data_final']]);

        return response()->json($query);
    }

    public function totalServicosPorPeriodo(Request $request)
    {
        $select = <<< TOTALSERVICOSPORPERIODO
            select 
                coalesce(sum(ordem_servico_servicos.quantidade * ordem_servico_servicos.preco),0) as value,
                servicos.descricao as name,
                count(ordem_servico_servicos.quantidade) as quantidade
            from 
                ordem_servico
            left join ordem_servico_servicos on 
                    ordem_servico_servicos.id_ordem_servico = ordem_servico.id
            left join servicos on  
                ordem_servico_servicos.id_servico = servicos.id
            where 
                ordem_servico.data between ? and ?
            group by 
                servicos.descricao
        TOTALSERVICOSPORPERIODO;

        $query = DB::select($select,[ $request['data_inicial'],$request['data_final']]);

        return response()->json($query);
    }

    public function vendasPorPeriodo(Request $request)
    {
        $select = <<< VENDASPORPERIODO
            select 
                ordem_servico.id,
                cliente.nome,
                TO_CHAR(ordem_servico.data, 'DD/MM/YYYY') AS data,
                marcas.descricao || ' | ' || veiculo.placa  as descricao_veiculo,
                count(ordem_servico_servicos.*) as quantidade_servicos,
                coalesce(sum(quantidade * preco),0) as valor_total
            from 
                ordem_servico 
            join cliente on 
                cliente.id = ordem_servico.id_cliente
            join veiculo on 
                veiculo.id = ordem_servico.id_veiculo
            left join ordem_servico_servicos on 
                ordem_servico_servicos.id_ordem_servico = ordem_servico.id
            join marcas on 
                veiculo.marca = marcas.id
            where 
                ordem_servico.data between ? and ? and ordem_servico.ativado = true
            group by 
                ordem_servico.id,
                ordem_servico.data,
                cliente.nome,
                veiculo.placa,
                marcas.descricao
        VENDASPORPERIODO;

        $query = DB::select($select,[ $request['data_inicial'],$request['data_final']]);

        return response()->json($query);
    }

    public function vendasPorSexo(Request $request)
    {
        $select = <<< QUANTIDADEVENDASPORSEXO
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
            where 
                ordem_servico.data between ? and ?
            group by
                sexo
            order by 
                sexo desc
        QUANTIDADEVENDASPORSEXO;

        $query = DB::select($select,[$request['data_inicial'],$request['data_final']]);

                
        $response = [];

        // Check if $query[0] exists and has valid data
        if (isset($query[0]) && !empty($query[0])) {
            $response[0] = [
                'value' => $query[0]->quantidade,
                'name' => $query[0]->sexo
            ];
        } else {
            // Handle the case where $query[0] is missing or invalid
            $response[0] = [
                'value' => 0, // Default value
                'name' => 'Nenhum' // Default name
            ];
        }

        // Check if $query[1] exists and has valid data
        if (isset($query[1]) && !empty($query[1])) {
            $response[1] = [
                'value' => $query[1]->quantidade,
                'name' => $query[1]->sexo
            ];
        }

        return response()->json($response);
    }
}

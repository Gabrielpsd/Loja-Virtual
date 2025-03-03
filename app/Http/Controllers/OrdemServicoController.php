<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrdemServicoController extends Controller
{
    public function index(){
        $sql = <<< TODASORDEMSERVICOS
                select 
                ordem_servico.id,
                ordem_servico.id_cliente,
                cliente.nome,
                ordem_servico.data,
                ordem_servico.id_veiculo,
                marcas.descricao || ' | ' || cores.descricao || ' | ' || veiculo.placa  as descricao_veiculo,
                ordem_servico.ativado,
                ordem_servico.obs,
                (select count(*) from ordem_servico_servicos where ordem_servico_servicos.id_ordem_servico = ordem_servico.id) as quantidade_servicos,
                CASE 
                    WHEN COUNT(ordem_servico_servicos.id_servico) > 0 THEN
                        JSON_ARRAYAGG(
                            JSON_OBJECT(
                                'descricao': servicos.descricao,
                                'id': ordem_servico_servicos.id_servico,
                                'quantidade': ordem_servico_servicos.quantidade,
                                'preco': ordem_servico_servicos.preco
                            )
                        )
                    ELSE
                        NULL
                END AS servicos
                from 
                    ordem_servico 
                join cliente on 
                    cliente.id = ordem_servico.id_cliente
                join veiculo on 
                    veiculo.id = ordem_servico.id_veiculo
                left join ordem_servico_servicos on 
                    ordem_servico_servicos.id_ordem_servico = ordem_servico.id
                join cores on 
                    cores.id = veiculo.cor
                join marcas on 
                    veiculo.marca = marcas.id
                left join servicos on
                    servicos.id = ordem_servico_servicos.id_servico
                group by 
                    ordem_servico.id,
                    ordem_servico.id_cliente,
                    ordem_servico.data,
                    cliente.nome,
                    ordem_servico.id_veiculo,
                    veiculo.placa,
                    marcas.descricao,
                    cores.descricao,
                    ordem_servico.ativado;
        TODASORDEMSERVICOS;

        $response = DB::select($sql);

        $sql = <<< TODOSCLIENTES
                select 
                    id,
                    nome as descricao
                from 
                    cliente
                where 
                    ativado = true
        TODOSCLIENTES;


        $pessoas = DB::select($sql);

        $sql = <<< TODOSVEICULOS
                 select 
                    veiculo.id,
                    veiculo.id_proprietario,
                    marcas.descricao || ' | ' || cores.descricao || ' | ' || veiculo.placa  as descricao
                from 
                    veiculo
                join cores on 
                    cores.id = veiculo.cor
                join marcas on 
                    veiculo.marca = marcas.id
                where 
                    ativado = true
        TODOSVEICULOS;


        $veiculos = DB::select($sql);

        $sql = <<< TODOSERVICOS
                select 
                    id,
                    descricao,
                    preco,
                    ativado
                from 
                    servicos
                where 
                    ativado = true
        TODOSERVICOS;
        
        $servicos = DB::select($sql);

        return Inertia::render('OrdemServico/lista',
        ['OrdemServicos' => $response,
        'Clientes' => $pessoas,
        'Veiculos' => $veiculos,
        'Servicos' => $servicos]);
    }

    public function inserirOrdemServico(Request $request)
    {
        $var = $request->all();
        $var = OrdemServico::create(
            [
                'id_cliente'=> $var['id_cliente'],
                'id_veiculo'=>$var['id_veiculo'],
                'data'=>$request['data'],
                'ativado'=> true,
                'obs'=>$request['obs']
            ]);
        
            $sql = <<< TODASORDEMSERVICOS
                    select 
                    ordem_servico.id,
                    ordem_servico.id_cliente,
                    cliente.nome,
                    ordem_servico.data,
                    ordem_servico.id_veiculo,
                    marcas.descricao || ' | ' || cores.descricao || ' | ' || veiculo.placa  as descricao_veiculo,
                    ordem_servico.ativado,
                    ordem_servico.obs,
                    (select count(*) from ordem_servico_servicos where ordem_servico_servicos.id_ordem_servico = ordem_servico.id) as quantidade_servicos,
                    CASE 
                    WHEN COUNT(ordem_servico_servicos.id_servico) > 0 THEN
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'descricao': servicos.descricao,
                                    'id': ordem_servico_servicos.id_servico,
                                    'quantidade': ordem_servico_servicos.quantidade,
                                    'preco': ordem_servico_servicos.preco
                                )
                            )
                        ELSE
                            NULL
                    END AS servicos
                    from 
                        ordem_servico 
                    join cliente on 
                        cliente.id = ordem_servico.id_cliente
                    join veiculo on 
                        veiculo.id = ordem_servico.id_veiculo
                    left join ordem_servico_servicos on 
                        ordem_servico_servicos.id_ordem_servico = ordem_servico.id
                    join cores on 
                        cores.id = veiculo.cor
                    join marcas on 
                        veiculo.marca = marcas.id
                    left join servicos on
                        servicos.id = ordem_servico_servicos.id_servico
                    where 
                        ordem_servico.id = ?
                    group by 
                        ordem_servico.id,
                        ordem_servico.id_cliente,
                        ordem_servico.data,
                        cliente.nome,
                        ordem_servico.id_veiculo,
                        veiculo.placa,
                        marcas.descricao,
                        cores.descricao,
                        ordem_servico.ativado;
            TODASORDEMSERVICOS;

            $response = DB::select($sql,[$var->id]);
        
            return response()->json($response[0]);;
    }

    public function editarOrdemServico(Request $request, int $id)
    {
        $sql = <<< UPDATEORDEMSERVICO
                update 
                    ordem_servico
                set 
                    id_cliente = ?,
                    id_veiculo = ?,
                    data = ?,
                    obs = ?
                where 
                    id = ?
        UPDATEORDEMSERVICO;

        DB::update($sql,[$request['id_cliente'],$request['id_veiculo'],$request['data'],$request['obs'],$id]);

        $sql = <<< LIMPARSERVICOS
                delete from 
                    ordem_servico_servicos
                where 
                    id_ordem_servico = ?
        LIMPARSERVICOS;

        DB::delete($sql,[$id]);

        $sql = <<< INSERIRSERVICOS
                insert into 
                    ordem_servico_servicos
                    (id_ordem_servico,id_servico,quantidade,preco)
                values
                    (?,?,?,?)
        INSERIRSERVICOS;    

        $servicos = Json::decode($request['servicos']);
        
        foreach($servicos as $servico)
        {
            DB::insert($sql,[$id,$servico['id'],$servico['quantidade'],$servico['preco']]);
        }
        
        $sql = <<< TODASORDEMSERVICOS
                    select 
                    ordem_servico.id,
                    ordem_servico.id_cliente,
                    cliente.nome,
                    ordem_servico.data,
                    ordem_servico.id_veiculo,
                    marcas.descricao || ' | ' || cores.descricao || ' | ' || veiculo.placa  as descricao_veiculo,
                    ordem_servico.ativado,
                    ordem_servico.obs,
                    (select count(*) from ordem_servico_servicos where ordem_servico_servicos.id_ordem_servico = ordem_servico.id) as quantidade_servicos,
                    CASE 
                    WHEN COUNT(ordem_servico_servicos.id_servico) > 0 THEN
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'descricao': servicos.descricao,
                                    'id': ordem_servico_servicos.id_servico,
                                    'quantidade': ordem_servico_servicos.quantidade,
                                    'preco': ordem_servico_servicos.preco
                                )
                            )
                        ELSE
                            NULL
                    END AS servicos
                    from 
                        ordem_servico 
                    join cliente on 
                        cliente.id = ordem_servico.id_cliente
                    join veiculo on 
                        veiculo.id = ordem_servico.id_veiculo
                    left join ordem_servico_servicos on 
                        ordem_servico_servicos.id_ordem_servico = ordem_servico.id
                    join cores on 
                        cores.id = veiculo.cor
                    join marcas on 
                        veiculo.marca = marcas.id
                    left join servicos on
                        servicos.id = ordem_servico_servicos.id_servico
                    where 
                        ordem_servico.id = ?
                    group by 
                        ordem_servico.id,
                        ordem_servico.id_cliente,
                        ordem_servico.data,
                        cliente.nome,
                        ordem_servico.id_veiculo,
                        veiculo.placa,
                        marcas.descricao,
                        cores.descricao,
                        ordem_servico.ativado;
            TODASORDEMSERVICOS;

            $response = DB::select($sql,[$id]);
        return response()->json($response[0]);
    }
}

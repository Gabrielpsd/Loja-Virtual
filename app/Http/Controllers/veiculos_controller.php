<?php

namespace App\Http\Controllers;

use App\Models\Veiculos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class veiculos_controller extends Controller
{
    public function index(Request $request){

        $sql = <<< TODOSVEICULOS
                select 
                veiculo.id,
                veiculo.placa, 
                veiculo.id_proprietario,
                cliente.nome,
                veiculo.cor as id_cor,
                cores.descricao as cor,
                veiculo.marca as id_marca,
                marcas.descricao as marca,
                veiculo.ano_fabricacao,
                veiculo.ano_modelo
            from 
                veiculo
            inner join cliente on 
                cliente.id = veiculo.id_proprietario
            inner join cores on 
                cores.id = veiculo.cor
            inner join marcas on 
                marcas.id = veiculo.marca
        TODOSVEICULOS;

        $response = DB::select($sql);
        
        if($request->query('JSON') == true)
        {   
            return response()->json($response);
        }

        $sql = <<< PROPRIETARIOS
                select 
                id,
                nome as descricao
            from 
                cliente
        PROPRIETARIOS;

        $clientes = DB::select($sql);

        $sql = <<< CORES
                select 
                id,
                descricao as descricao
            from 
                cores
        CORES;

        $cores = DB::select($sql);

        $sql = <<< MARCAS
                select 
                id,
                descricao as descricao
            from 
                marcas
        MARCAS;

        $marcas = DB::select($sql);


        return Inertia::render('Veiculos/lista',
        ['Veiculos' => $response,
        'Clientes'=> $clientes,
        'Cores'=>$cores,
        'Marcas'=>$marcas]);
    }

    public function inserirVeiculo(Request $request)
    {
        $var = $request->all();
        $var = Veiculos::create(
            [
                'placa'=> $var['placa'],
                'cor'=>$request['cor'],
                'marca'=>$request['marca'],
                'id_proprietario'=>$request['id_proprietario'],
                'ano_modelo'=>$request['ano_modelo'],
                'ano_fabricacao'=>$request['ano_fabricacao'],
                'ativado'=> true
            ]);
        
            $sql = <<< TODOSVEICULOS
                select 
                veiculo.id,
                veiculo.placa, 
                veiculo.id_proprietario,
                cliente.nome,
                veiculo.cor as id_cor,
                cores.descricao as cor,
                veiculo.marca as id_marca,
                marcas.descricao as marca,
                veiculo.ano_fabricacao,
                veiculo.ano_modelo
            from 
                veiculo
            inner join cliente on 
                cliente.id = veiculo.id_proprietario
            inner join cores on 
                cores.id = veiculo.cor
            inner join marcas on 
                marcas.id = veiculo.marca
            Where 
                veiculo.id = ?
        TODOSVEICULOS;

        $response = DB::select($sql,[$var->id]);
        
        return response()->json($response[0]);
    }
    
    public function editarVeiculo(Request $request , string $id)
    {
        $pessoa = Veiculos::findOrFail($id);
        $pessoa->update($request->all());
        
        $sql = <<< TODOSVEICULOS
                select 
                veiculo.id,
                veiculo.placa, 
                veiculo.id_proprietario,
                cliente.nome,
                veiculo.cor as id_cor,
                cores.descricao as cor,
                veiculo.marca as id_marca,
                marcas.descricao as marca,
                veiculo.ano_fabricacao,
                veiculo.ano_modelo
            from 
                veiculo
            inner join cliente on 
                cliente.id = veiculo.id_proprietario
            inner join cores on 
                cores.id = veiculo.cor
            inner join marcas on 
                marcas.id = veiculo.marca
            Where 
                veiculo.id = ?
        TODOSVEICULOS;

        $response = DB::select($sql,[$id]);
        
        return response()->json($response[0]);
    }
}

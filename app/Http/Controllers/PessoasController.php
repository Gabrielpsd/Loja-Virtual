<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PessoasController extends Controller
{
    public function index(){

        $sql = <<< TODASPESSOAS
                select 
                    id,
                    nome,
                    cpf_cnpj,
                    sexo,
                    data_nascimento,
                    coalesce(EXTRACT(YEAR FROM AGE(data_nascimento)),0) AS idade,
                    ativado
                from 
                    cliente
        TODASPESSOAS;

        $clientes = DB::select($sql);
        
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

        $cars = DB::select($sql);

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

        $sql = <<< TODOSERVICOS
                select 
                    id,
                    descricao,
                    preco
                from 
                    servicos
                where 
                    ativado = true
        TODOSERVICOS;

        $servicos = DB::select($sql);

        return Inertia::render('Clientes/lista',
        [
            'Clientes' => $clientes,
            'Veiculos' => $cars,
            'Cores'=>$cores,
            'Marcas'=>$marcas,
            'Servicos'=>$servicos
        ]);
    }

    public function inserirPessoa(Request $request)
    {
        $var = $request->all();
        $var = response()->json(Pessoa::create(
            [
                'nome'=> $var['nome'],
                'cpf_cnpj'=>$var['cpf_cnpj'],
                'data_nascimento'=>$request['data_nascimento'],
                'sexo'=>$request['sexo'],
                'ativado'=> true
            ]));

        return $var;
    }
    
    public function editarPessoa(Request $request , string $id)
    {
        $pessoa = Pessoa::findOrFail($id);
        $pessoa->update($request->all());
    
        return response()->json($pessoa);
    }
}

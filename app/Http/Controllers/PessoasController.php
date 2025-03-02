<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PessoasController extends Controller
{
    public function index(Request $request){

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

        $response = DB::select($sql);

        if($request->query('JSON') == true)
        {   
            return response()->json($response);
        }

        return Inertia::render('Clientes/lista',
        ['Cliente' => $response]);
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

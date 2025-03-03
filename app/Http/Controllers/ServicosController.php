<?php

namespace App\Http\Controllers;

use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ServicosController extends Controller
{
    public function index(Request $request){

        $sql = <<< TODOSERVICOS
                select 
                    id,
                    descricao,
                    preco,
                    ativado
                from 
                    servicos
        TODOSERVICOS;
        
        $response = DB::select($sql);

        if($request->query('JSON') == true)
        {   
            return response()->json($response);
        }

        return Inertia::render('Servicos/lista',
        ['Servicos' => $response]);
    }

    public function inserirServico(Request $request)
    {
        $var = $request->all();
        $var = response()->json(Servicos::create(
            [
                'descricao'=> $var['descricao'],
                'preco'=>$var['preco'],
                'ativado'=> true
            ]));

        return $var;
    }
    
    public function editarServico(Request $request , string $id)
    {
        $servico = Servicos::findOrFail($id);
        $servico->update($request->all());
    
        return response()->json($servico);
    }
}

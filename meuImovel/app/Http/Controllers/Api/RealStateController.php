<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
        $this->middleware('auth.basic',
            ['except'=>
                [
                    'index',
                    'show'
                ]
        ]);
    }

    public function index(){
        $realState = $this->realState->paginate(10);
        return response()->json($realState,200);
    }

    public function show(RealState $real_state){
        if(!$real_state)
            return response()->json(["Error, state not found"],404);
        return response()->json($real_state,200);
    }

    public function store(Request $request){
        $data = $request->all();
        try{
            if(!$data){
                throw new Exception("Error: Dados inválidos!");
            }
            return response()->json(
                [   'msg'=>'Novo registro de imóvel inserido com sucesso!',
                    'data'=>Auth::user()->real_state()->create($data)
                ],201);
        }catch(Exception $error) {
            return response()->json(['Error' => $error->getMessage()], 401);
        }
    }

    public function destroy($id){
        try{
            $real_state = $this->realState->findOrfail($id);
            $real_state->delete();
            return response()->json(
                [   'msg'=>'Registro excluído com sucesso!',
                    'data'=>$real_state
                ],201);
        }catch(Exception $error){
            return response()->json(['Error'=>$error->getMessage()],400);
        }
    }

//    public function update(RealState $real_state, Request $request){
    public function update($id, Request $request){
        $data = $request->all();
        try{
            if(!$data)
                throw new Exception("Error: Dados inválidos!");
            $real_state = $this->realState->findOrfail($id);
            $real_state->update($data);
            return response()->json(
                [   'msg'=>'Registro atualizado com sucesso!',
                    'data'=>$real_state
                ],201);
        }catch(Exception $error){
            return response()->json(['Error'=>$error->getMessage()],400);
        }
    }
}